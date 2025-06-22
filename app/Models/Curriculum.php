<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    protected $fillable=[
        'subject_id',
        'course_id'
    ];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'curriculum_teachers');
    }
}
