<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
     protected $fillable = [
        'class_number',
        'capacity',
    ];

    public function classRooms()
    {
        return $this->belongsToMany(Course::class, 'classroom_courses');
    }
}
