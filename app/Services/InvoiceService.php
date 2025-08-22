<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Invoice;
use Exception;

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
    
}
