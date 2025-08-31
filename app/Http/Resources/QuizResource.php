<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
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
            'curriculum_id' => $this->curriculum_id,
            'curriculum_teacher_id' => $this->curriculum_teacher_id,
            'topic_id' => $this->topic_id,
            'name' => $this->name,
            'type' => $this->type,
            'available' => $this->available,
            'start_time' => $this->start_time,
            'duration' => $this->duration,
            'max_degree' => round(100 * ($realMaxDegree / $realMaxDegree), 2),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'student_quiz'=>$this->student,
            // 'max_degree' => $this->markedQuestions->count() ?: 1,
             'student_quiz' => [
                'id'=>$this->student->id??null,
                'student_id' => $this->student->student_id ?? null,
                'quiz_id' => $this->student->quiz_id ?? null,
                // 'result' => $this->student->result ?? null,
                'result' => round(100 * ($this->student->result / $realMaxDegree), 2),
                'is_submit' => $this->student->is_submit ?? null,
            ],

            'questions' => QuestionResource::collection($this->questions),
        ];
    }
}
