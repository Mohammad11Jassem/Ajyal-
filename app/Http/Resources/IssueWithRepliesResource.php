<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IssueWithRepliesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'community_id' => $this->community_id,
            'author_type'  => $this->author_type,
            'author_id'    => $this->author_id,
            'body'         => $this->body,
            'is_fqa'       => $this->is_fqa,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'image'   => $this->image,
            'replies'      => ReplyResource::collection($this->whenLoaded('replies')),
        ];
    }
}
