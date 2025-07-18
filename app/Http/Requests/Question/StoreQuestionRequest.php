<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['Teacher']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // return [
        //     'quiz_id' => ['required', 'exists:quizzes,id'],
        //     'questions' => ['required', 'array'],
        //     // 'questions.*.quiz_id' => ['required', 'exists:quizzes,id'],
        //     'questions.*.question_text' => ['required', 'string'],
        //     'questions.*.mark' => ['nullable', 'numeric'],
        //     'questions.*.hint' => ['nullable', 'string'],
        //     'questions.*.image' => ['nullable','image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'],

        //     'questions.*.children' => ['nullable', 'array'],
        //     'questions.*.children.*.question_text' => ['required_with:questions.*.children', 'string'],
        //     'questions.*.children.*.mark' => ['nullable', 'numeric'],
        //     'questions.*.children.*.hint' => ['nullable', 'string'],
        //     'questions.*.children.*.image' => ['nullable','image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'],

        //     'questions.*.children.*.choices' => 'nullable|array|min:2',
        //     'questions.*.children.*.choices.*.choice_text' => 'nullable|string',
        //     'questions.*.children.*.choices.*.is_correct' => 'nullable|boolean',

        //     //'questions.*.children.*.children' => ['nullable','array'], // Recursive support

        //     'questions.*.choices' => ['nullable','array'],
        //     'questions.*.choices.*.choice_text' => ['nullable','string'],
        //     'questions.*.choices.*.is_correct' => ['nullable','boolean'],
        // ];


        return [
            'quiz_id' => ['required', 'exists:quizzes,id'],
            'question_text' => ['required', 'string'],
            'mark' => ['nullable', 'numeric'],
            'hint' => ['nullable', 'string'],
            'image' => ['nullable','image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'],

            'children' => ['nullable', 'array'],
            'children.*.question_text' => ['required_with:questions.*.children', 'string'],
            'children.*.mark' => ['nullable', 'numeric'],
            'children.*.hint' => ['nullable', 'string'],
            'children.*.image' => ['nullable','image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'],

            'children.*.choices' => 'nullable|array|min:2',
            'children.*.choices.*.choice_text' => 'nullable|string',
            'children.*.choices.*.is_correct' => 'nullable|boolean',

            'choices' => ['nullable','array'],
            'choices.*.choice_text' => ['nullable','string'],
            'choices.*.is_correct' => ['nullable','boolean'],
        ];
    }
    public function messages(): array
    {
        return [
            'quiz_id.required' => 'Quiz ID is required for each question.',
            'quiz_id.exists' => 'The specified quiz ID does not exist.',
            'question_text.required' => 'Each question must have a text.',
            // 'choices.*.text.required' => 'Each choice must have text.',
        ];
    }
}
