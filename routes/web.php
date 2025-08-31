<?php

use App\Enum\SubjectType;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StripeController;
use App\Http\Resources\AbsenceResource;
use App\Http\Resources\PrevQuizeResource;
use App\Mail\TestMail;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\CurriculumTeacher;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\User;
use App\Services\StudentPerformanceAnalysisService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Row;



// Route::get('ser/{id}',[StudentPerformanceAnalysisService::class,'calculateTotalMean']);

Route::get('/stripe/session', [StripeController::class, 'session'])->name('stripe.session');
Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
Route::get('/view', function () {

    $studentId = 1; // example student id

    $absenceDays = \App\Models\Absence::whereHas('registration', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })
        ->with('absenceDate','registration.course') // eager load date relation
        ->get();
        // ->pluck('absenceDate.absence_date'); // only get the date

    $registration = \App\Models\Registration::where('student_id', $studentId)->first();

    // $absenceDays = $registration->absences()
    //     ->with('absenceDate')
    //     ->get();

    $registration = \App\Models\Registration::with(['course', 'absenceDays'])
                    ->where('student_id', $studentId)
                    ->first();
    return  new AbsenceResource($registration);

});


// Route::middleware('throttle:2,1')->group(function () {
//    2 requests per 1 min
//     Route::get('/', function () {
//         return "bla bla";
//     });
// });

    Route::get('/send-not', function () {
        try {
            $apiUrl = 'https://fcm.googleapis.com/v1/projects/ajyal-45f04/messages:send';

            $access_token = Cache::remember('access_token', now()->addHour(), function () {
                $credentialsFilePath = storage_path('app/fcm.json');
                $client = new \Google_Client();
                $client->setAuthConfig($credentialsFilePath);
                $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                $client->fetchAccessTokenWithAssertion();
                $token = $client->getAccessToken();
                return $token['access_token'];
            });

            // $fcm_token = 'fUq3em0xR0CY08GLllSMNd:APA91bF21K4QKiPwb_9TWLd6SCvy0nW5gmEMdRdmFx5qZ6la08kvxZ3fnZdL8luji4XKqsn_qm_iAVuRHwGk1r89atbKsCgtp__MwFi6-_C4SYkXxqwKlxU';
            $fcm_token = '';

            $message = [
                "message" => [
                    "token" => $fcm_token,
                    "notification" => [
                        "title" => "test",
                        "body" => "hi",
                    ]
                ]
            ];

            $response = Http::withToken($access_token)
                ->post($apiUrl, $message);

            dd($response); // dump API response so you can see result

        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ]);
        }
    });
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



