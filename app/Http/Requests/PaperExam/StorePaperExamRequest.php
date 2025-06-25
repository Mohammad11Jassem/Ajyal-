<?php

namespace App\Http\Requests\PaperExam;

use Illuminate\Foundation\Http\FormRequest;

class StorePaperExamRequest extends FormRequest
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
            'curriculum_id' => ['required', 'exists:curricula,id'],
            'title'         => ['required', 'string'],
            'description'   => ['nullable', 'string'],
            'exam_date'     => ['required', 'date'],
            'max_degree'    => ['required', 'integer', 'min:1'],
            'file'          => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:2048'], // Excel file, max 2MB
        ];
    }

    public function messages(): array
    {
        return [
            'curriculum_id.exists' => 'المنهاج غير موجود',
            'file.mimes'           => 'يجب أن يكون الملف بصيغة Excel',
            // 'exam_date.after_or_equal' => 'يجب أن يكون تاريخ الامتحان اليوم أو بعده',
        ];
    }
}
