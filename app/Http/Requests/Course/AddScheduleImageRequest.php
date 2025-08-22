<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class AddScheduleImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['Manager', 'Secretariat']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'schedule' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'classroom_course_id' => 'required|exists:classroom_courses,id',
        ];
    }
}
