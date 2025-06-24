<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassroomCourse extends Model
{
    protected $fillable=[
        'classroom_id',
        'course_id',
    ];

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class,'classroom_id');
    }
}
