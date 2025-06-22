<?php

namespace App\Http\Requests\Course;

use App\Enum\SubjectType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'type' =>['required', 'string', Rule::enum(SubjectType::class)],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            // 'code' => 'required|string|max:255|unique:courses,code',
            'capacity' => 'required|integer|min:10',

            'classrooms' => 'required|array|min:1',
            'classrooms.*' => [
                'required',
                'integer',
                'exists:classrooms,id'
            ],


            'subjects' => 'required|array|min:1',
            'subjects.*.subject_id' => [
                'required',
                'integer',
                'exists:subjects,id'
            ],
            'subjects.*.teachers' => 'required|array|min:1',
            'subjects.*.teachers.*' => [
                'required',
                'integer',
                'exists:teachers,id'
            ]
        ];
    }

    public function messages()
    {
        return [
            'sections.*.exists' => 'إحدى الشعب المحددة غير موجودة',
            'subjects.*.subject_id.exists' => 'المادة المحددة غير موجودة',
            'subjects.*.teachers.*.exists' => 'الأستاذ المحدد غير موجود'
        ];
    }
}
