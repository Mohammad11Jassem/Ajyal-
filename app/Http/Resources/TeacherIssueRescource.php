<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherIssueRescource extends JsonResource
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
            'community_id' => $this->community_id,
            'author_type'=>$this->author_type,
            'author_id'=>$this->author_id,
            'body' => $this->body,
            'is_fqa' => (bool)$this->is_fqa,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'image'=>$this->image,
            'author'=>[
                'id' => $this->author->id,
                'name' => $this->author->name,
            ],
        ];
    }
}
