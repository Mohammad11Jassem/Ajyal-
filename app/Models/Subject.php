<?php

namespace App\Models;

use App\Enum\SubjectType;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
   protected $fillable = [
        'name',
        'subject_code',
        'type',
        'description',
        'archived',
    ];
    protected $casts = [
        'type' => SubjectType::class,
    ];

    public function scopeNonArchived($query)
    {
        return $query->where('archived', false);
    }
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class,'teacher_subjects');
    }
}
