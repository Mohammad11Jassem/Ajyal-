<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'cost',
        'type',
        'start_date',
        'end_date',
        'code',
        'capacity',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'curricula');
    }
    public function classRooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_courses');
    }
}
