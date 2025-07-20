<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizRequest extends FormRequest
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
            'quiz_id'       => 'required|exists:quizzes,id',
            'curriculum_id' => 'exists:curricula,id',
            'topic_id'      => 'exists:topics,id',
            'name'          => 'string|max:255',
            'type'          => 'in:Timed,worksheet',
            'available'     => 'boolean',
            'start_time'    => 'nullable|date|required_if:type,Timed',
            // 'end_time'      => 'required|date|after_or_equal:start_time',
            'duration'      => 'nullable|numeric|min:0|required_if:type,Timed',
        ];
    }
}
