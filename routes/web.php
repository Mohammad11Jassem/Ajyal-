<?php

use App\Enum\SubjectType;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StripeController;
use App\Http\Resources\PrevQuizeResource;
use App\Mail\TestMail;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\CurriculumTeacher;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\User;
use App\Services\StudentPerformanceAnalysisService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Row;



// Route::get('ser/{id}',[StudentPerformanceAnalysisService::class,'calculateTotalMean']);

Route::get('/stripe/session', [StripeController::class, 'session'])->name('stripe.session');
Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
Route::get('/view', function () {
    return view('test');
});


// Route::middleware('throttle:2,1')->group(function () {
//    2 requests per 1 min
//     Route::get('/', function () {
//         return "bla bla";
//     });
// });

Route::middleware(['throttle:web'])->group(function () {
    Route::get('/{id}', function ($id) {
        //   $allQuizzes = Course::with('curriculums.currSubject.quizzes')
        //  ->find(1);
         return Quiz::whereHas('curriculumTeacher.curriculum.course', function($q)use($id) {
                    $q->where('id', $id);
                })->with('studentQuizzes')
                ->withCount('markedQuestions')
                ->get();
        $quizzes = Quiz::whereHas('curriculumTeacher.curriculum.course', function($q) use ($id) {
                $q->where('id', $id);
            })
            ->with('studentQuizzes')
            ->get();

        $withResults = [];
        $withoutResults = [];

        foreach ($quizzes as $quiz) {
            $mean = 0;

            if ($quiz->studentQuizzes->count() > 0) {
                $mean = $quiz->studentQuizzes->avg('result');
                $withResults[] = [
                    'id' => $quiz->id,
                    'curriculum_teacher_id'=>$quiz->curriculum_teacher_id,
                    'topic_id'=>$quiz->topic_id,
                    'curriculum_id'=>$quiz->curriculum_id,
                    'name' => $quiz->name,
                    'type' => $quiz->type,
                    'available' => $quiz->available,
                    'start_time' => $quiz->start_time,
                    'duration' => $quiz->duration,
                    'mean_result' => round($mean, 2),
                ];
            } else {
                $withoutResults[] = [
                    'id' => $quiz->id,
                    'name' => $quiz->name,
                    'type' => $quiz->type,
                    'available' => $quiz->available,
                    'start_time' => $quiz->start_time,
                    'duration' => $quiz->duration,
                    'mean_result' => 0,
                ];
            }
        }

        return response()->json([
            'with_results' => $withResults,
            'without_results' => $withoutResults,
        ]);

    });

    Route::get('dtd/{id}',[QuizController::class,'getAllCourseQuiz']);
//     // other web routes...
});
