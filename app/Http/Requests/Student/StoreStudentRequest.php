<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'number_civial'     => 'required|string',
            'address'           => 'required|string',
            // 'location'          => 'required|string',
            'father_name'       => 'required|string',
            'mother_name'       => 'required|string',
        ];
    }
}
