<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
    'curriculum_id',
    'topic_id',
    'name',
    'column_name',
    'available',
    'start_time',
    'end_time',
    'duration',
    ];

    public function scopeAvailable($query)
    {
        return $query->where('available', 1);
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
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
}
