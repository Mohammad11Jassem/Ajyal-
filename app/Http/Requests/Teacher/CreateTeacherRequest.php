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
            // 'phone' => ['required', 'string', 'unique:teachers,phone'],
            // 'subject' => ['required', 'string'],
            // 'qualification' => ['required', 'string'],
            // 'address' => ['required', 'string'],
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
            // 'phone.unique' => 'This phone number is already registered for another teacher.',
        ];
    }
}
