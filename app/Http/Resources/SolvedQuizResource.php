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
        $realMaxDegree=$this->markedQuestions->count();
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
            // 'max_degree' => $this->markedQuestions->count() ?: 1,
            'max_degree' => round(100 * ($realMaxDegree / $realMaxDegree), 2),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'student_quiz' => [
                'student_id' => $this->pivot->student_id ?? null,
                'quiz_id' => $this->pivot->quiz_id ?? null,
                // 'result' => $this->pivot->result ?? null,
                'result' => round(100 * ($this->pivot->result / $realMaxDegree), 2),
                'is_submit' => $this->pivot->is_submit ?? null,
            ],
        ];
    }
}
