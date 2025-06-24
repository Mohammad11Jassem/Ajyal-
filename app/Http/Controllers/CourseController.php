<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\StoreCourseRequest;
use App\Models\Course;
use App\Models\Curriculum;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    use HttpResponse;

    public function store(StoreCourseRequest $storeCourseRequest){
        $validated=$storeCourseRequest->validated();
        return DB::transaction(function() use($validated){
             $course = Course::create([
                'name' => $validated['name'],
                'cost' => $validated['cost'],
                'type' => $validated['type'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'code' => "COU123",
                'capacity' => $validated['capacity'],
            ]);
            $course->classrooms()->attach($validated['classrooms']);

            foreach ($validated['subjects'] as $subject) {
                $curriculum = Curriculum::create([
                    'course_id' => $course->id,
                    'subject_id' => $subject['subject_id'],
                ]);

                $curriculum->teachers()->attach($subject['teachers']);
            }
             return response()->json([
                'message' => 'Course created successfully.',
                'course' => $course,
            ], 201);
        });
    }

    public function register(){
      
    }
}
