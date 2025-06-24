<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class SortStudentRequest extends FormRequest
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
            'class_course_id'=>['required','exists:classroom_courses,id'],
            'student_id'=>['required','array'],
            'student_id.*' =>['required','exists:student,column','distinct'],
        ];
    }
}
