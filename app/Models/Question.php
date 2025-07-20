<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'parent_question_id',
        'mark',
        'question_text',
        'hint',
    ];
    // protected $appends = ['selected_choice'];
    // protected $hidden = ['studentAnswer'];
    public function parent()
    {
        return $this->belongsTo(Question::class, 'parent_question_id');
    }

    public function children()
    {
        return $this->hasMany(Question::class, 'parent_question_id');
    }
    public function choices()
    {
        return $this->hasMany(Choice::class);
    }
    public function correctChoice()
    {
        return $this->hasOne(Choice::class)->where('is_correct',1);
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }


    public function studentAnswer()
    {
        return $this->hasOne(StudentAnswer::class)
                ->whereHas('studentQuiz',function($query){
                    // $query->where('student_id',1);
                    $query->where('student_id',auth()->user()->user_data['role_data']['id']);

                })->with('selectedChoice');
    }

    public function getSelectedChoiceAttribute()
    {
        return $this->studentAnswer?->selectedChoice;
    }
}
