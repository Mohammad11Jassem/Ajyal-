<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperExamStudent extends Model
{
    protected $fillable = [
        'student_id',
        'paper_exam_id',
        'mark',
    ];
}
