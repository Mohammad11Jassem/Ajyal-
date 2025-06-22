<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeacherRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:teachers,email'],
            'date_of_contract' => ['required','date'],
            'phone_number' => ['required','regex:/^[0-9]{10}$/','unique:teachers'],
            'avatar' => [ 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'],
            'bio' => ['string','nullable'],
            'subjects'=>['required','array'],
            'subjects.*' => ['required','integer','distinct','exists:subjects,id'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered for another teacher.',
            'subjects.*.exists' =>'One or more selected subjects are invalid.',
            'subject_ids.*.distinct' => 'Duplicate subject IDs are not allowed.',
            'phone_number.unique' => 'This phone number is already registered for another teacher.',
        ];
    }
}
