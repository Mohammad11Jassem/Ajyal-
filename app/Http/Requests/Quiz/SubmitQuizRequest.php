<?php

namespace App\Http\Requests\Quiz;

use App\Models\Choice;
use Illuminate\Foundation\Http\FormRequest;

class SubmitQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['Student']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'quiz_id' => 'required|exists:quizzes,id',
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.choice_id' => 'nullable|exists:choices,id',
        ];
    }
    public function messages(): array
    {
        return [
            'quiz_id.required' => 'Quiz ID is required.',
            'quiz_id.exists' => 'The selected quiz does not exist.',
            'answers.required' => 'You must provide at least one answer.',
            'answers.array' => 'Answers must be in array format.',
            'answers.*.question_id.required' => 'Each answer must have a question ID.',
            'answers.*.question_id.exists' => 'A question does not exist.',
            'answers.*.choice_id.required' => 'Each answer must have a choice ID.',
            'answers.*.choice_id.exists' => 'A choice does not exist.',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $answers = $this->input('answers', []);

            foreach ($answers as $index => $answer) {
                $questionId = $answer['question_id'] ?? null;
                $choiceId = $answer['choice_id'] ?? null;

                if ($questionId && $choiceId) {
                    $isValid = Choice::where('id', $choiceId)
                        ->where('question_id', $questionId)
                        ->exists();

                    if (! $isValid) {
                        $validator->errors()->add("answers.$index.choice_id", "The selected choice does not belong to the given question.");
                    }
                }
            }
        });
    }
}
