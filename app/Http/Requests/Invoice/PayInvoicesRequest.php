<?php

namespace App\Http\Requests\Invoice;

use App\Models\Invoice;
use App\Models\Registration;
use Illuminate\Foundation\Http\FormRequest;

class PayInvoicesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice_id'=>'|required|exists:invoices,id',
            'student_id'=>'|required|exists:students,id'
        ];
    }



    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $invoice = Invoice::find($this->invoice_id);

            if (!$invoice) {
                return; // rules() already checks existence
            }

            $registration = Registration::where('student_id', $this->student_id)
                ->where('course_id', $invoice->course_id)
                ->first();

            if (!$registration) {
                $validator->errors()->add(
                    'student_id',
                    'هذا الطالب غير مسجل في هذا الكورس'
                );
                return;
            }

            // ✅ Check if already paid
            $alreadyPaid = $invoice->payments()
                ->where('registration_id', $registration->id)
                ->exists();

            if ($alreadyPaid) {
                $validator->errors()->add(
                    'invoice_id',
                    'الطالب دفع هذه الفاتورة بالفعل'
                );
            }
        });
    }
}
