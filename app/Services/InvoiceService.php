<?php

namespace App\Services;

use App\Jobs\SendNotificationJob;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Student;
use Exception;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function addInvoice(array $data)
    {
        try{
            $course = Course::findOrFail($data['course_id']);
            $invoices = $course->invoices()->createMany($data['invoices']);
        return [
            'success'=>true,
            'message'=>'تم إضافة الدفعات المالية لهذا الكورس',
            // 'data'=>$invoices

        ];
    }catch(Exception $e){
        return [
            'success'=>false,
            'message'=>'فشلت العملية',
            'error'=>$e->getMessage()
        ];
    }
    }
    public function allInvoices($courseID)
    {
        try{
            return [
            'success'=>true,
            'message'=>'جميع فواتير هذا الكورس',
            'data'=>Course::find($courseID)->invoices()->get(),
        ];

        }catch(Exception $e){
        return [
            'success'=>false,
            'message'=>'فشل جلب الفواتير',
            'error'=>$e->getMessage()
        ];
        }
    }

        public function payInvoices(array $data){
        try{
                return DB::transaction(function () use ($data) {
                    $invoice = Invoice::findOrFail($data['invoice_id']);
                    $registration=Registration::where('student_id',$data['student_id'])->where('course_id',$invoice->course->id)->first();
                    // here you can add payment logic (e.g., mark as paid, call payment gateway)
                    $payment = $invoice->payments()->create(['registration_id'=>$registration->id]);

                    return [
                            'success'=>true,
                            'message' => 'تم دفع الفاتورة بنجاح',
                            // 'data'=>$invoice
                            'data'=>$payment
                        ];
                });
        }catch(Exception $e){
            return [
                'success'=>false,
                'message'=>'فشلت عملية الدفع',
                'error'=>$e->getMessage()
            ];
        }
    }

    public function notifyStudent($data)
    {
        try{
                  // جلب الطلاب بناءً على IDs
            $students = Student::whereIn('id', $data['student_ids'])->get();
            $invoice=Invoice::where('id',$data['invoice_id'])->first();
            // جمع الـ users المرتبطين بالطلاب
            // $users = $students->map(function ($student) {
            //     return $student->user; // يفترض أن كل طالب له علاقة user()
            // })->filter(); // إزالة القيم null إذا لم يوجد user

            $users = $students->flatMap(function ($student) {
                    // parents علاقة Many-to-Many
                    return $student->parents->map(function ($parent) {
                        return $parent->user; // يفترض أن كل والد له علاقة user()
                    });
                })->filter();

        $message = [
                'title' => 'تذكير بدفع الفاتورة',
                'body'  => 'مرحبًا! لم يتم دفع فاتورتك بعد. يرجى تسديد المبلغ المستحق لتجنب أي تأخير أو مشاكل في التسجيل.'
            ];

            SendNotificationJob::dispatch($message, $users, $invoice);

            return [
                'success' => true,
                'message' => 'تم إرسال الإشعارات بنجاح'
            ];
        }catch(Exception $ex){
            return [
                'success' => true,
                'message' => $ex->getMessage()
            ];
        }


    }

}
