<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $student = optional($this->notifiable->registration->student);

        return [
            'id'    => $this->id,
            'title' => $this->title,
            'body'  => $this->body,
            'created_at' => $this->created_at->toDateTimeString(),

            // الطالب مباشرة
            'student' => $student ? [
                'id' => $student->id,
                'full_name' => $student->full_name,
                'class_level' => $student->class_level,
                'student_Id_number' => $student->student_Id_number,
            ] : null,
        ];
    }
}
