<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mockery\Matcher\Subset;

class Curriculum extends Model
{
    protected $fillable=[
        'subject_id',
        'course_id'
    ];

    protected $hidden=[
        'updated_at',
        'created_at',
        ];
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'curriculum_teachers');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function files()
    {
        return $this->hasMany(CurriculumFile::class);
    }

    public function paperExams()
    {
        return $this->hasMany(PaperExam::class);
    }
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
