<?php

namespace App\Models;

use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SortStudent extends Model
{
        protected $fillable=[
        'registration_id',
        'classroom_course_id',
    ];
    public function classroomCourse()
    {
        return $this->belongsTo(ClassroomCourse::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
