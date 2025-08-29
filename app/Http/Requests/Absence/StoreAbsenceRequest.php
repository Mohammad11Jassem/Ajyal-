<?php

namespace App\Http\Requests\Absence;

use Illuminate\Foundation\Http\FormRequest;

class StoreAbsenceRequest extends FormRequest
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
            'absence_date' => 'required|date',
            'classroom_course_id' => 'required|exists:classroom_courses,id',
            'registration_ids' => 'array',
            'registration_ids.*' => 'exists:registrations,id',
        ];
    }
}
