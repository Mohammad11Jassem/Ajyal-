<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'user_id' => $this->user_id,
            'student_Id_number' => $this->student_Id_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'access_code' => $this->access_code,
            'number_civial' => $this->number_civial,
            'address' => $this->address,
            'birthdate'=>$this->birthdate,
            'class_level'=>$this->class_level,
            // 'location' => $this->location,
            'created_at' => $this->created_at,
            // 'qr'=>$this->QR,
        ];
    }
}
