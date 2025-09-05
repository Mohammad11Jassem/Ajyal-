<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Registration;
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
                            'data'=>$invoice
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

}
