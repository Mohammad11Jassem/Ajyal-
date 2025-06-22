<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{

    protected $table="teacher_subjects";
    protected $fillable=["teacher_id","subject_id"];

}
