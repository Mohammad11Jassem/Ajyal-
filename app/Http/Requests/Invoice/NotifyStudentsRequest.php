<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class NotifyStudentsRequest extends FormRequest
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
            'student_ids' => ['required', 'array'],
            'student_ids.*' => ['integer', 'exists:students,id'],
            'invoice_id' => ['required', 'integer', 'exists:invoices,id'], 
        ];
    }

    /**
     * يمكن إضافة رسالة مخصصة للأخطاء إذا رغبت
     */
    public function messages(): array
    {
        return [
            'student_ids.required' => 'يجب تحديد الطلاب المستهدفين.',
            'student_ids.array' => 'يجب أن تكون student_ids مصفوفة.',
            'student_ids.*.exists' => 'الطالب المحدد غير موجود.',
            'invoice_id.required' => 'يجب تحديد الفاتورة.',
            'invoice_id.exists' => 'الفاتورة المحددة غير موجودة.',
        ];
    }
}
