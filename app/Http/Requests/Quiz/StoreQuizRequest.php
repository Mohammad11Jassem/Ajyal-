<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
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
        return [
            'curriculum_id' => 'required|exists:curricula,id',
            'topic_id'      => 'nullable|exists:topics,id',
            'name'          => 'required|string|max:255',
            'type'   => 'required|in:Timed,worksheet',
            'available'     => 'boolean',
            'start_time'    => 'required|date',
            // 'end_time'      => 'required|date|after_or_equal:start_time',
            'duration'      => 'required|numeric|min:0',
        ];
    }
}
