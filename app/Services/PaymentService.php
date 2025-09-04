<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Invoice;
use App\Models\Registration;

class PaymentService
{
    public function getStudentPayments($studentId, $courseId)
    {
        // find registration
        $registration = Registration::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->firstOrFail();

        $course = $registration->course;

        // All invoices of this course
        $invoices = $course->invoices()->with('payments')->get();
        // $unpaid = $invoices->filter(function ($invoice) use ($registration) {
        //     return $invoice->payments->where('registration_id', $registration->id)->isEmpty();
        // });

        // Unpaid invoices (no payments list)
        $unpaid = $invoices->filter(function ($invoice) use ($registration) {
            return $invoice->payments->where('registration_id', $registration->id)->isEmpty();
        })->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'value' => $invoice->value,
                'due_date' => $invoice->due_date,
            ];
        });


        if(auth()->user()->hasRole('Student')){
            return [
                'message'=>'فواتيري غير المدفوعة في كورس معين',
                // 'course' => $course,
                'unpaid_invoices' => $unpaid->values(),
            ];
        }

        // $paid = $invoices->filter(function ($invoice) use ($registration) {
        //     return $invoice->payments->where('registration_id', $registration->id)->isNotEmpty();
        // });

        // Paid invoices (with payments list)
        $paid = $invoices->filter(function ($invoice) use ($registration) {
            return $invoice->payments->where('registration_id', $registration->id)->isNotEmpty();
        })->map(function ($invoice) use ($registration) {
            return [
                'id' => $invoice->id,
                'value' => $invoice->value,
                'due_date' => $invoice->due_date,
                'payments' => $invoice->payments
                    ->where('registration_id', $registration->id)
                    ->first(),
            ];
        });


        return [
            'message'=>'فواتير مدفوعة وغير مدفوعة لطالب في كورس معين',
            'student' => $registration->student,
            // 'course' => $course,
            'paid_invoices' => $paid->values(),
            'unpaid_invoices' => $unpaid->values(),
        ];
    }
    public function getCoursePayments($courseId){
        $course = Course::with([
        'invoices.payments.registration.student'
    ])->findOrFail($courseId);

    // Flatten payments for easy listing
    $payments = $course->invoices->flatMap(function ($invoice) {
        return $invoice->payments->map(function ($payment) use ($invoice) {
            return [
                'payment_id'   => $payment->id,
                'invoice_id'   => $invoice->id,
                'invoice_value'=> $invoice->value,
                'student_id'   => $payment->registration->student->id,
                'student_name' => $payment->registration->student->full_name,
                'payment_date' => $payment->payment_date,
            ];
        });
    });

    return [
        'message'=>'كل مدفوعات هذا الكورس',
        'course'   => $course->name ?? $course->id,
        'payments' => $payments->values()
    ];
    }


    public function getInvoicePaymentStatus($invoiceId)
    {
        $invoice = Invoice::with([
            'course.registration.student',
            'payments.registration.student'
        ])->findOrFail($invoiceId);

        $course = $invoice->course;

        // All students registered in this course
        $allStudents = $course->registration->pluck('student');

        // Students who paid this invoice
        $paidStudents = $invoice->payments->map(function ($payment) {
            return $payment->registration->student;
        })->unique('id');

        // Students who did not pay this invoice
        $unpaidStudents = $allStudents->whereNotIn('id', $paidStudents->pluck('id'));

        return [
            'message'=>'كل الطلاب الذين دفعو & لم يدفعو هذه الفاتورة',
            'course'          => $course->id,
            'invoice_id'      => $invoice->id,
            'invoice_value'   => $invoice->value,
            'paid_students'   => $paidStudents->values(),
            'unpaid_students' => $unpaidStudents->values(),
        ];
    }



    public function getCoursesPayments($studentId)
    {

    $registrations = Registration::with(['course.invoices.payments'])
        ->where('student_id', $studentId)
        ->get();

    $courses = $registrations->map(function ($registration) {
        $course = $registration->course;

        $invoices = $course->invoices;

        // Split into paid & unpaid for this student
        $paid = $invoices->filter(function ($invoice) use ($registration) {
            return $invoice->payments
                ->where('registration_id', $registration->id)
                ->isNotEmpty();
        });

        $unpaid = $invoices->filter(function ($invoice) use ($registration) {
            return $invoice->payments
                ->where('registration_id', $registration->id)
                ->isEmpty();
        });

        return [
            'course'          => $course->makeHidden(['invoices']),
            'paid_invoices'   => $paid->values(),
            'unpaid_invoices' => $unpaid->values(),
        ];
        });

        return [
            'message' => 'قائمة بكورساتي مع الفواتير المدفوعة وغير المدفوعة ',
            'data' => $courses
        ];
        }


}
