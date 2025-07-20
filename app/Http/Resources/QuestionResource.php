<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quiz_id' => $this->quiz_id,
            'parent_question_id' => $this->parent_question_id,
            'mark' => $this->mark,
            'question_text' => $this->question_text,
            'hint' => $this->hint,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relations
            'image' => $this->image,
            'choices' =>$this->choices,
            'correct_choice' => $this->correctChoice,
            'student_answer' => $this->SelectedChoice,

            // 'selected_choice' => $this->selected_choice, // your accessor

            // // children questions recursively
            'children' => QuestionResource::collection($this->children),
        ];
    }
}
