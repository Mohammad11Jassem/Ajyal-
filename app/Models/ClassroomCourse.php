<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClassroomCourse extends Model
{
    protected $fillable=[
        'classroom_id',
        'course_id',
        'capacity'
    ];

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class,'classroom_id');
    }
    public function registrations():BelongsToMany
    {
        return $this->belongsToMany(Registration::class, 'sort_students', 'classroom_course_id', 'registration_id');
    }
    public function sortStudents(){
        return $this->hasMany(SortStudent::class,'classroom_course_id');
    }


    public function absenceDates()
    {
        return $this->hasMany(AbsenceDate::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');

    }
}
