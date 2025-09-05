<?php

namespace App\Services;

use App\Http\Resources\AbsenceResource;
use App\Jobs\SendNotificationJob;
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
                    $absence = Absence::create([
                        'absence_date_id' => $absenceDate->id,
                        'registration_id' => $registrationId,
                    ]);
                    $absences[]=$absence;
                    // send notifications
                    $registration = Registration::with('student.parents.user')->findOrFail($registrationId);
                    $studentName = $registration?->student?->first_name . ' ' . $registration?->student?->last_name;

                    $users = $registration->student->parents->map(function($parent){
                        return $parent->user;
                    });
                   $message = [
                        'title' => 'إشعار غياب',
                        'body'  => "لقد تم تسجيل غياب على الطالب {$studentName}"
                    ];
                    SendNotificationJob::dispatch($message,$users,$absence);

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

    public function getAbsence($studentId,$courseId)
    {
        $registration = Registration::with(['course', 'absenceDays'])
                    ->where('student_id', $studentId)
                    ->where('course_id', $courseId)
                    ->first();
        return  new AbsenceResource($registration);
    }
}
