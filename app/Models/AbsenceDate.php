<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenceDate extends Model
{
    protected $fillable=['absence_date','classroom_course_id'];
    public function classroomCourse()
    {
        return $this->belongsTo(ClassroomCourse::class);
    }
}
