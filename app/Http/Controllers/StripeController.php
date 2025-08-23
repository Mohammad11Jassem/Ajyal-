<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\PayRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
      public function checkout()
    {
        return view('stripe.checkout'); // Create this Blade view
    }

    public function session(PayRequest $payRequest)
    {
        $data =$payRequest->validated();
        return DB::transaction(function() use($data){
            // $invoice=
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            $response=$stripe->checkout->sessions->create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Course price',
                        ],
                        'unit_amount' => 1000, // = $10.00
                        // 'unit_amount' => $invoice, // = $10.00
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
                'metadata' => [
                'order_id' => 'order_id',
                'user_id' =>'user_id' ,
                // 'order_id' => $order->id,
                // 'user_id' => $user->id,
                ],
                // 'metadata' => [
                //     'invoice' => $invoice,
                // ],
            ]);

            // return redirect($response->url);
            return response()->json([
                'checkout_url' => $response->url,
            ]);
        });
    }

    public function success(Request $request)
    {
        // return 'Payment success';
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return response()->json(['error' => 'No session ID'], 400);
        }

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $session = $stripe->checkout->sessions->retrieve($sessionId);

        // return [
        //     'status'=>$session->payment_status,
        //      'orderId' => $session->metadata->order_id,
        //      'userId' => $session->metadata->user_id,
        // ];

        return view('test');


    }

    public function cancel()
    {
        return 'Payment canceled!';
    }
}
