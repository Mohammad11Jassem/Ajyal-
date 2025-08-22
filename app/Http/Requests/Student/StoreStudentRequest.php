<?php

namespace App\Http\Requests\Student;

use App\Enum\SubjectType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'number_civial'     => 'required|numeric',
            'address'           => 'required|string',
            'birthdate'         =>'date',
            'class_level'       =>Rule::enum(SubjectType::class),
            // 'location'          => 'required|string',
            'father_name'       => 'required|string',
            'mother_name'       => 'required|string',
        ];
    }
}
