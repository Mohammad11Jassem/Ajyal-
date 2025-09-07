<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use HttpResponse;
    protected PaymentService $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    public function getStudentPayments($studentId, $courseId)
    {
        $result=$this->paymentService->getStudentPayments($studentId,$courseId);
        // return $this->success( $result['message'],$result);
        return $this->success( 'تم ',$result);
    }
    public function getCoursePayments($courseId)
    {
        $result=$this->paymentService->getCoursePayments($courseId);
        return $this->success( $result['message'],$result);
    }
    public function getInvoicePaymentStatus($invoiceId)
    {
        $result=$this->paymentService->getInvoicePaymentStatus($invoiceId);
        return $this->success( $result['message'],$result);
    }
    public function getCoursesPayments($studentId)
    {
        $result=$this->paymentService->getCoursesPayments($studentId);
        return $this->success( $result['message'],$result['data']);
    }
}
