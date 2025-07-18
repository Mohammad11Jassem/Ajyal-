<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
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
            'question_id'=>'required|exists:questions,id',
            'question_text' => 'nullable|string',
            'hint' => 'nullable|string',
            'choices' => 'nullable|array',
            'choices.*.id' => 'nullable|exists:choices,id',
            'choices.*.choice_text' => 'nullable|string',
            'choices.*.is_correct' => 'nullable|boolean',
            'image' => 'nullable|image|max:2048',
        ];
    }
}
