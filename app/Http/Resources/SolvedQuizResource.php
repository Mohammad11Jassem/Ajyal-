<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolvedQuizResource extends JsonResource
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
            'curriculum_teacher_id' => $this->curriculum_teacher_id,
            'curriculum_id' => $this->curriculum_id,
            'topic_id' => $this->topic_id,
            'name' => $this->name,
            'type' => $this->type,
            'available' => $this->available,
            'start_time' => $this->start_time,
            'duration' => $this->duration,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'student_quiz' => [
                'student_id' => $this->pivot->student_id ?? null,
                'quiz_id' => $this->pivot->quiz_id ?? null,
                'result' => $this->pivot->result ?? null,
                'is_submit' => $this->pivot->is_submit ?? null,
            ],
        ];
    }
}
