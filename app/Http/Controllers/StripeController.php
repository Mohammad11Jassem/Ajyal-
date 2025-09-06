<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\CoursePaymentRequest;
use App\Http\Requests\Payment\PayRequest;
use App\Interfaces\PayableContract;
use App\Jobs\SendNotificationJob;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\User;
use App\Payments\CoursePayment;
use App\Payments\InvoicePayment;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

class StripeController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }
    public function checkout()
    {
        return view('stripe.checkout'); // Create this Blade view
    }

    public function session(PayRequest $payRequest)
    {
        $data =$payRequest->validated();
        return DB::transaction(function() use($data){
            $invoice=Invoice::findOrFail($data['invoice_id']);
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            $response=$stripe->checkout->sessions->create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'invoice price',
                        ],
                        'unit_amount' => (int) $invoice['value'], // = $10.00
                        // 'unit_amount' => $invoice, // = $10.00
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
                // 'metadata' => [
                //     'order_id' => 'order_id',
                //     'user_id' =>'user_id' ,
                // ],
                'metadata' => [
                    'invoice_id' => $invoice['id'],
                    'student_id'=>auth()->user()->user_data['role_data']['id'],
                    'user_id'=>auth()->id(),
                ],
            ]);

            // return redirect($response->url);
            return response()->json([
                'checkout_url' => $response->url,
            ]);
        });
    }

    public function success(Request $request)
    {

        return DB::transaction(function() use($request){
            // return 'Payment success';
            $sessionId = $request->get('session_id');

            if (!$sessionId) {
                return response()->json(['error' => 'No session ID'], 400);
            }

            $stripe = new StripeClient(env('STRIPE_SECRET'));
            $session = $stripe->checkout->sessions->retrieve($sessionId);


           $in= $this->invoiceService->payInvoices([

                    'invoice_id'=>$session->metadata->invoice_id,
                    'student_id'=>$session->metadata->student_id,
            ]);

            $invoice=Invoice::find($session->metadata->invoice_id);
             //send notification
            $users = User::where('id',$session->metadata->user_id)->get();

            $message = [
                'title' => 'تسديد فاتورة',
                'body'  => 'تم تسديد فاتورةالكترونياً بنجاح'
            ];

            SendNotificationJob::dispatch($message, $users,$invoice);
            //send notification
            $managers = User::role('Manager', 'api')->get();

             $message = [
                'title' => 'تسديد فاتورة',
                'body'  => 'تم تسديد فاتورةالكترونياً بنجاح'
            ];

            SendNotificationJob::dispatch($message, $managers,$invoice);
            // return [
            //     'status'=>$session->payment_status,
            //      'orderId' => $session->metadata->order_id,
            //      'userId' => $session->metadata->user_id,
            // ];

            return view('successPayment');
        });

    }

    public function cancel()
    {
        return view('failPayment');
    }


    public function createCheckoutSession(PayableContract $payable)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        return $stripe->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $payable->getTitle(),
                    ],
                    'unit_amount' => $payable->getAmount(),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('stripe.cancel'),
            'metadata'    => $payable->getMetadata(),
        ]);
    }

    public function sessionForInvoice(PayRequest $request)
    {
        $data = $request->validated();
        $invoice = Invoice::findOrFail($data['invoice_id']);

        $payable = new InvoicePayment($invoice, auth()->user()->user_data['role_data']['id'], auth()->id());

        $session = $this->createCheckoutSession($payable);

        return response()->json(['checkout_url' => $session->url]);
    }

    public function sessionForCourse(CoursePaymentRequest $coursePayment)
    {
        $data=$coursePayment->validated();
        $course = Course::findOrFail($data['course_id']);

        $payable = new CoursePayment($course, auth()->user()->user_data['role_data']['id'], auth()->id());

        $session = $this->createCheckoutSession($payable);

        return response()->json(['checkout_url' => $session->url]);
    }
}
