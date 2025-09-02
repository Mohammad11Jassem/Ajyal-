<?php

namespace App\Services;

use App\Http\Resources\AbsenceResource;
use App\Models\Absence;
use App\Models\AbsenceDate;
use App\Models\ClassroomCourse;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AbsenceService
{
    public function store($data)
    {
       return DB::transaction(function() use($data){
            $absenceDate=AbsenceDate::create([
                'classroom_course_id'=>$data['classroom_course_id'],
                'absence_date'=>$data['absence_date'],
            ]);
            $absences = [];

            if(isset($data['registration_ids'])){

                foreach ($data['registration_ids'] as $registrationId) {
                    $absences[] = Absence::create([
                        'absence_date_id' => $absenceDate->id,
                        'registration_id' => $registrationId,
                    ]);
                }
            }

            return $absences;
        });
    }

    public function todayAbsence($courseId)
    {
        return DB::transaction(function() use($courseId){

            // $today = Carbon::now()->toDateTimeString();// with time
            $today = Carbon::now()->toDateString();

            //   return [
            //     'today'=>$today,
            //     'classrooms_with_absence'=>"bla",
            //     'classrooms_without_absence'=>"bla"
            // ];
            $classroomsWithAbsence = ClassroomCourse::where('course_id', $courseId)
                    ->whereHas('absenceDates', function($query) use ($today) {
                        $query->where('absence_date', $today);
                    })
                    ->with('classRoom')
                    ->get();

            // Classrooms that did NOT take absence today
            $classroomsWithoutAbsence = ClassroomCourse::where('course_id', $courseId)
                ->whereDoesntHave('absenceDates', function($query) use ($today) {
                    $query->where('absence_date', $today);
                })->with('classRoom')
                ->get();

            return [
                'classrooms_with_absence'=>$classroomsWithAbsence,
                'classrooms_without_absence'=>$classroomsWithoutAbsence
            ];
        });

    }

    public function getAbsence($studentId)
    {
        $registration = Registration::with(['course', 'absenceDays'])
                    ->where('student_id', $studentId)
                    ->first();
          return  new AbsenceResource($registration);
    }
}
