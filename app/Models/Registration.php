<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registration extends Model
{
        protected $fillable=[
        'course_id',
        'student_id',
        'registered_at',
    ];

    public function classroom_courses():BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'sort_students', 'registration_id', 'classroom_course_id');
    }

    public function Student(){
        return $this->belongsTo(Student::class);
    }

}
