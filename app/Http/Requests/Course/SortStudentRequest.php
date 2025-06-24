<?php

namespace App\Http\Requests\Course;

use App\Models\Classroom;
use App\Models\ClassroomCourse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
            'registration_id'=>['required','array'],
            'registration_id.*' =>['required','exists:registrations,id','distinct',],
        ];
    }

    public function withValidator($validator)
    {
        $classCourseId = $this->input('class_course_id');

        $validator->after(function ($validator) use ($classCourseId) {
            foreach ($this->input('registration_id', []) as $index => $registrationId) {
                $exists = DB::table('sort_students')
                    ->where('registration_id', $registrationId)
                    ->where('classroom_course_id', $classCourseId)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add("registration_id.$index", "تم فرز هذا الطالب بالفعل في هذه الفئة.");
                }
            }
        });
    }

}
