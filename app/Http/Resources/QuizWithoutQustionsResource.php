<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizWithoutQustionsResource extends JsonResource
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
            'topic_id'             => $this->topic_id,
            'name' => $this->name,
            'type' => $this->type,
            'available' => $this->available,
            'start_time' => $this->start_time,
            // 'end_time' => $this->end_time,
            'duration' => $this->duration,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
