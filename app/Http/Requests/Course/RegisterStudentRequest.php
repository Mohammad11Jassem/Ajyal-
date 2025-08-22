<?php

namespace App\Http\Requests\Course;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['Manager', 'Secretariat',]);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'student_id'=>['required','exists:students,id'],
            'course_id'=>['required','exists:courses,id',
                Rule::unique('registrations')->where(function ($query) {
                return $query->where('student_id', $this->student_id);
            }),],
            'invoice'=>['array'],
            'invoice.*' => [ 'numeric','exists:invoices,id',
                function ($attribute, $value, $fail) {
                    $invoice = Invoice::find($value);
                    if (!$invoice || $invoice->course_id != $this->course_id) {
                        $fail('The selected invoice does not belong to the specified course.');
                    }
                }
        ],
        ];
    }
    public function messages()
    {
        return [
            // 'course_id.unique' => 'This student is already registered in this course.',
            'course_id.unique' => 'هذا الطالب مسجل بالفعل في هذه الدورة.',
        ];
    }

}
