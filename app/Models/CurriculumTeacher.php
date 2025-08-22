<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurriculumTeacher extends Model
{
    protected $fillable = [
        'curriculum_id',
        'teacher_id',
    ];


    public function availableQuizzes()
    {
        return $this->hasMany(Quiz::class,'curriculum_teacher_id')
                ->where('available',1);
    }

    public function curriculum(){
        return $this->belongsTo(Curriculum::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
