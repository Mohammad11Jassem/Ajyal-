<?php

namespace App\Repositories;

use App\Interfaces\CourseInterface;
use App\Models\Course;

class CourseRepository implements CourseInterface
{
    public function store(array $data){
        return Course::create($data);
    }

    public function show($id){
     return Course::with(['curriculums.subject', 'curriculums.teachers'])->findOrFail($id);;
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        return $course->delete();
    }

}
