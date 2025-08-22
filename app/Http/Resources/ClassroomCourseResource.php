<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ClassroomCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            // 'id' => $this->id,
            // 'image' => $this->image ? [
            //     'url' => Storage::url($this->image->path),
            //     'thumbnail' => $this->image->thumbnail_path ? Storage::url($this->image->thumbnail_path) : null
            // ] : null,
            // 'classroom' => new ClassroomResource($this->classroom),
            // 'sort_students' => SortStudentResource::collection($this->sortStudents)
        ];
    }
}
