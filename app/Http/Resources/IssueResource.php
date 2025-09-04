<?php

namespace App\Http\Resources;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IssueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         if ($this->author_type === Teacher::class) {
            return (new TeacherIssueRescource($this))->toArray($request);
        }

        if ($this->author_type === Student::class) {
            return (new StudentIssueRescource($this))->toArray($request);
        }

        return parent::toArray($request); // fallback
    }
}
