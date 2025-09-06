<?php

use App\Enum\SubjectType;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StripeController;
use App\Http\Requests\CreateAndRegisterStudentRequest;
use App\Http\Resources\AbsenceResource;
use App\Http\Resources\PaymentNotificationResource;
use App\Http\Resources\PrevQuizeResource;
use App\Jobs\SendNotificationJob;
use App\Mail\TestMail;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\CurriculumTeacher;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Quiz;
use App\Models\Registration;
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
    // $users = Quiz::eligibleStudents(1);
        // $inv=Invoice::find(1);
        // return get_class($inv);
        // $users=User::where('id',6)->get();
        //             // إعداد الرسالة مع اسم الاختبار
        //             $message = [
        //                 'title' => 'تسجيل فاتورة',
        //                 'body'  => "تم تسجيل فاتورة بنجاح"
        //             ];

        //             // إرسال الإشعار
        //             SendNotificationJob::dispatch($message, $users,$inv);

        $noti=Notification::with('notifiable.registration.Student')->where('notifiable_type','App\Models\Payment')->get();
        return PaymentNotificationResource::collection($noti);
});
Route::post('/createAndRegister', function (CreateAndRegisterStudentRequest $request) {
     $validated = $request->validated();

    $studentData = $validated['student'];
    $registerData = $validated['registration'];

    return response()->json([
        'studentData'=>$studentData,
        'registerData'=>$registerData,
    ]);
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
            $fcm_token = 'cz6l7oyXS01i1gg0yAtM9Y:APA91bH1pz2fzr6BQ0tDXztooGVZQf6id2DHpaobzC7svEouQrNKoS7oDsqyKRVwzzBL0OAJGwfvkF5vl0ZZIMK1pmIDtjk4AC4DtVmKlP2GIGgqWS0eMcw';

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



