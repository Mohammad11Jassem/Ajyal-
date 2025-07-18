<?php

namespace App\Services;

use App\Models\Classroom;

class ClassroomService
{
    public function getClasses(){
        return Classroom::get();
    }
}
