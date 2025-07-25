<?php

namespace App\Http\Requests\Subject;

use App\Enum\SubjectType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSubjectRequest extends FormRequest
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
            'name' => 'required|string',
            // 'subject_code' => ['required', 'string', 'max:100'],
            'subjects_type' => ['required', 'string', Rule::enum(SubjectType::class)],
            'description' => ['nullable', 'string'],
            'topics' => ['nullable', 'array'],
            'topics.*' => ['string', 'max:255'],
        ];
    }
}
