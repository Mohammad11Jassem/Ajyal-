<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurriculumTeacher extends Model
{
    protected $fillable = [
        'curriculum_id',
        'teacher_id',
    ];
}
