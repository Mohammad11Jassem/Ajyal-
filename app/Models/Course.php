<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    protected $fillable = [
        'name',
        'cost',
        'type',
        'start_date',
        'end_date',
        'code',
        'capacity',
    ];
    // protected $casts = [
    //     'start_date' => 'date',
    //     'end_date' => 'date',
    //     'cost' => 'decimal:2',
    // ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'curricula');
    }
    public function classRooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_courses');
    }

    public function classroomCourse(){
        return $this->hasMany(ClassroomCourse::class,'course_id');
    }
    public function curriculums(){
        return $this->hasMany(Curriculum::class,'course_id');
    }
    public function classroomCourse(){
        return $this->hasMany(ClassroomCourse::class,'course_id');
    }

    public function files()
    {
        return $this->hasManyThrough(
            CurriculumFile::class,
            Curriculum::class,
            'course_id',       // Foreign key on the Curriculum table
            'curriculum_id',   // Foreign key on the CurriculumFile table
            'id',              // Local key on the Course table
            'id'          // Local key on the Curriculum table
        );
    }
    public function students():BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'registrations', 'course_id', 'student_id');
    }

    public function registration()
    {
        return $this->hasMany(Registration::class );
    }
}
