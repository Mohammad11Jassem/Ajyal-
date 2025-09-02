<?php

namespace App\Http\Requests\Issue;

use Illuminate\Foundation\Http\FormRequest;

class AddIssueRequest extends FormRequest
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
            'curriculum_id' => 'required|exists:curricula,id',
            'body' => 'required|string',
            'image'=>'nullable|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'is_fqa' => 'boolean'
        ];
    }
}
