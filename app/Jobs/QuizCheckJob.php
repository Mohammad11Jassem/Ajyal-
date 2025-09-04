<?php

namespace App\Jobs;

use App\Models\Quiz;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuizCheckJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("message");
        $quizzes = Quiz::where('start_time','<',Carbon::now()->toDateString())->get();
        foreach($quizzes as $quiz){
            $courseId = $quiz->assignment->curriculum->course['id'];
            $quizId=$quiz['id'];
            // dd($courseId);
            // الطلاب في الكورس الذين لم يتقدموا للاختبار
            $students = DB::table('registrations as r')
                ->leftJoin('student_quizzes as sq', function ($join) use ($quizId) {
                    $join->on('r.student_id', '=', 'sq.student_id')
                        ->where('sq.quiz_id', $quizId);
                })
                ->where('r.course_id', $courseId)
                ->whereNull('sq.id') // لم يتقدموا
                ->select('r.student_id','r.course_id')
                ->get();

            foreach($students as $student){
                $tempStudent=Student::find($student->student_id);
                $tempStudent->studentQuizzes()->create([
                    'quiz_id'=>$quizId,
                    'result'=>0,
                    'is_submit'=>1,
                ]);
            }

        }
    }
}
