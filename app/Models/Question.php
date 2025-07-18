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
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
