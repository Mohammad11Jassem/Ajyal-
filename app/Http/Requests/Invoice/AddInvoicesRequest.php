<?php

namespace App\Http\Requests\Invoice;

use App\Models\Course;
use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class AddInvoicesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['Manager']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'invoices' => ['required', 'array','min:1'],
            'invoices.*.value' => ['required', 'numeric', 'min:0'],
            'invoices.*.due_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }
    public function messages(): array
    {
        return [
            'invoices.*.value.numeric' => 'خطأ في قيمة الفاتورة',
            'invoices.*.due_date.after_or_equal'=>'خطأ في التاريخ'
        ];
    }

    public function withValidator($validator)
    {
        if($validator->fails())
            return ;
        $validator->after(function ($validator) {
            $courseId = $this->input('course_id');
            $course = Course::find($courseId);
            $sumOfOldInvoice=$course->invoices()->sum('value');

            if (!$course) {
                $validator->errors()->add('course_id', 'Course not found');
                return;
            }


            $sum = collect($this->input('invoices'))
                ->pluck('value')
                ->sum();


            $courseCost = $course->cost;

            // Compare with floating point precision
            $tolerance = 0.001; // Adjust based on your precision needs

            // cofirm about old invoices
            if($sumOfOldInvoice===$courseCost){
                $validator->errors()->add('invoices', 'لا يمكن إضافة فواتير لهذا الكورس , لقد أضفت الدفعات المطلوبة سابقاً');
                return;
            }

            if (abs($sum - $courseCost) > $tolerance) {
                $errorKey = 'invoices'; // More logical error location
                $errorMessage = ($sum > $courseCost)
                    ? 'مجموع قيمة الفواتير أكبر من مبلغ الكورس'
                    : 'مجموع قيمة الفواتير أصغر من مبلغ الكورس';

                $validator->errors()->add($errorKey, $errorMessage);
            }
        });
    }




}
