<?php

namespace App\Http\Resources;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->author_type === Teacher::class) {
            return (new TeacherReplyRescource($this))->toArray($request);
        }

        if ($this->author_type === Student::class) {
            return (new StudentReplyRescource($this))->toArray($request);
        }

        return parent::toArray($request); // fallback
    }
}
