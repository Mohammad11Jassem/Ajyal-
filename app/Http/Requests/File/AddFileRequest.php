<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class AddFileRequest extends FormRequest
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
            'curriculum_id'=>'required|exists:curricula,id',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf|max:20480',
        ];
    }
}
