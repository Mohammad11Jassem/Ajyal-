<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRegisterRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255','exists:teachers,email'],
            'password' => ['required', 'string', 'min:6','confirmed'],
            'verifyCode' => ['required', 'string', 'min:8'],
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
            'email.unique' => 'This email is already registered as a teacher.',
            'email.exists' => 'No teacher account found with this email.',
            'password.confirmed' => 'The password does not match with confirm.',
        ];
    }
}
