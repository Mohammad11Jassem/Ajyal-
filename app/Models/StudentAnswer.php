<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAnswer extends Model
{
    protected $fillable = [
        'student_quiz_id',
        'question_id',
        'selected_choice_id',
        'answered_at'
    ];
    public function studentQuiz(): BelongsTo
    {
        return $this->belongsTo(StudentQuiz::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedChoice(): BelongsTo
    {
        return $this->belongsTo(Choice::class, 'selected_choice_id');
    }

}
