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

    protected $hidden=[
        'pivot',
    ];

    public function scopeNonArchived($query)
    {
        return $query->where('archived', false);
    }
    public function scopeArchived($query)
    {
        return $query->where('archived', true);
    }
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class,'teacher_subjects');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'curricula');
    }
}
