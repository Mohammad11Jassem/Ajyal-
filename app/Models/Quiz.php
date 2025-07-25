<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Quiz extends Model
{
    protected $fillable = [
    // 'curriculum_id',
    'curriculum_teacher_id',
    'topic_id',
    'name',
    'type',
    'column_name',
    'available',
    'start_time',
    // 'end_time',
    'duration',
    ];

    protected $appends=['curriculum_id'];

    protected $hidden=['assignment'];
    public function getCurriculumIdAttribute()
    {
        return $this->assignment?->curriculum_id;
    }
    public function scopeAvailable($query)
    {
        return $query->where('available', 1);
    }
    // public function curriculum()
    // {
    //     return $this->belongsTo(Curriculum::class);
    // }
    public function assignment()
    {
        return $this->belongsTo(CurriculumTeacher::class,'curriculum_teacher_id');

    }
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function questions()
    {
        return $this->hasMany(Question::class)->whereNull('parent_question_id');
    }
    public function markedQuestions()
    {
        return $this->hasMany(Question::class)->whereHas('choices');
    }
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_quizzes', 'quiz_id', 'student_id');
    }
    public function student()
    {
        return $this->hasOne(StudentQuiz::class)
            ->where('student_id',auth()->user()->user_data['role_data']['id']);
            // ->where('student_id',1);
    }
    public function studentQuizzes(): HasMany
    {
        return $this->hasMany(StudentQuiz::class);
    }

    public function curriculumTeacher()
    {
        return $this->belongsTo(CurriculumTeacher::class,'curriculum_teacher_id');
    }


}
