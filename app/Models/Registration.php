<?php

namespace App\Models;

use Illuminate\Contracts\Cache\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registration extends Model
{
        protected $fillable=[
        'course_id',
        'student_id',
        'registered_at',
    ];

    public function classroom_courses():BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'sort_students', 'registration_id', 'classroom_course_id');
    }

    public function Student(){
        return $this->belongsTo(Student::class);
    }
    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function sort(){
        return $this->hasOne(SortStudent::class);
    }

    public function absenceDays()
    {
        return $this->hasManyThrough(
            AbsenceDate::class, // final model
            Absence::class,     // intermediate
            'registration_id',  // Foreign key on Absences table
            'id',               // Foreign key on AbsenceDates table
            'id',               // Local key on Registrations
            'absence_date_id'   // Local key on Absences
        );
    }
}
