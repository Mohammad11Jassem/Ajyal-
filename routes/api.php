<?php


use App\Exports\StudentsExport;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;

use App\Http\Controllers\ExcelController;

use App\Http\Controllers\IssueController;

use App\Http\Controllers\InvoiceController;

use App\Http\Controllers\ParentModelController;
use App\Http\Controllers\StudentController;

use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PaperExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;

use App\Http\Controllers\ReplyController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\StudentPerformanceAnalysisController;

use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TopicController;
use App\Models\ClassroomCourse;
use App\Models\Curriculum;
use App\Models\CurriculumFile;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/teacher', function () {
        return response()->json([
            // 'data'=>auth()->user()->user_data
        ]);
    });
});
Route::post('/login', [TeacherController::class, 'login']);

Route::post('/link-student2', [StudentController::class, 'linkStudent2']);
Route::get('/qr', [StudentController::class, 'getStudentQr']);

// Get teacher profile
Route::get('profile/{id}',[TeacherController::class, 'profile']);

// Route::post('/check-student', [StudentController::class, 'getStudentByCodeAndName']);
// Route::post('/register-student', [StudentController::class, 'register']);
// Route::post('/login-student', [StudentController::class, 'login']);
// Route::post('/add-student', [StudentController::class, 'store'])->middleware('auth:sanctum');
// Route::get('/profile-student', [StudentController::class, 'profile'])->middleware('auth:sanctum');
// Route::post('/link-student', [StudentController::class, 'linkStudent'])->middleware('auth:sanctum');


Route::prefix('student')->group(function () {

    Route::controller(StudentController::class)->group(function () {
        // Public routes
        Route::post('/check', 'getStudentByCodeAndName');
        Route::post('/register', 'register');
        Route::post('/login', 'login');

        // Authenticated routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/add', 'store')->middleware('role:Manager|Secretariat');
            Route::get('/profile', 'profile')->middleware('role:Student');
            Route::post('/logout', 'logout')->middleware('role:Student');
            Route::post('/link', 'linkStudent')->middleware('role:Parent');
            Route::get('/all', 'getAllStudent')->middleware('role:Manager|Secretariat');
        });

    });
        Route::middleware(['auth:sanctum','role:Student'])->controller(TeacherController::class)->group(function () {
            Route::get('profile/{id}', 'profile');
            // All teachers
            Route::get('allTeachers','allTeachers');
            //Specific teachers
            Route::get('specificTeachers/{subject_id}','specificTeachers');
            //level teachers
            Route::get('levelTeachers/{level_id}','levelTeachers');


        });
        Route::middleware(['auth:sanctum','role:Student'])->controller(CourseController::class)->group(function () {
            Route::get('my-courses','studentCourses');
            Route::get('my-courses-with-details','studentCoursesWithDetails');
        });



});

Route::prefix('parent')->controller(ParentModelController::class)->group(function () {
    Route::post('/register', 'registerParent');

    Route::post('/login', 'loginParent');
    Route::middleware(['auth:sanctum','role:Parent'])->group(function(){

        Route::get('/profile', 'profile');
        Route::get('/parent-students', 'parentStudent');



    });
});



// Admin/Manager Routes
Route::prefix('admin')->group(function () {


Route::middleware(['auth:sanctum','role:Manager|Secretariat'])->group(function () {

    Route::controller(ManagerController::class)->group(function () {

        Route::post('logout','logout');

    });

    Route::controller(TeacherController::class)->group(function () {

        // Add teachers
        Route::post('teachers', 'store');
        // All teachers
        Route::get('allTeachers','allTeachers');
        //teacher profile
        Route::get('profile/{id}','profile');
        //Specific teachers
        Route::get('specificTeachers/{subject_id}','specificTeachers');
        //level teachers
        Route::get('levelTeachers/{level_id}','levelTeachers');



    });

    Route::controller(AdvertisementController::class)->group(function () {

        Route::post('addAdvertisement','store');
        Route::get('showAdvertisement/{id}','show');
        Route::post('updateAdvertisement/{id}','update');
        Route::delete('deleteAdvertisement/{id}','delete');


    });


});

    Route::controller(ManagerController::class)->group(function () {

        Route::post('login','login');

    });
});
// Route::prefix('admin')->controller(ManagerController::class)->group(function () {

//         Route::post('login','login');

//         Route::middleware(['auth:sanctum','role:Manager|Secretariat'])->group(function () {
//             Route::post('logout','logout');
//             Route::get('profile','profile');
//             // Add teachers
//             Route::post('teachers',[TeacherController::class, 'store']);
//             // All teachers
//             Route::get('allTeachers',[TeacherController::class,'allTeachers']);

//             //Add advertisement
//             Route::post('addAdvertisement',[AdvertisementController::class,'store']);
//             Route::get('showAdvertisement/{id}',[AdvertisementController::class,'show']);
//             Route::post('updateAdvertisement/{id}',[AdvertisementController::class,'update']);
//             Route::delete('deleteAdvertisement/{id}',[AdvertisementController::class,'delete']);




//         });
// });

//Teacher Routes

Route::prefix('teacher')->controller(TeacherController::class)->group(function () {

        Route::post('teacherRegister', 'register');
        Route::post('teacherLogin','login');
        Route::post('teacherverifycode','VerifyCode');
        // Route::get('allTeachers','allTeachers');


        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('myProfile', 'myProfile')->middleware('role:Teacher');
            Route::post('logout','logout')->middleware('role:Teacher');

            Route::get('get-all-my-subjects-with-course','getAllMySubjectWithCourse')->middleware('role:Teacher');
            Route::get('get-all-subjects-for-teacher/{id}','getAllSubjectForTeacher');


        });
});

// middleware('auth:api')->
Route::middleware(['auth:sanctum','role:Secretariat|Manager|Teacher'])->prefix('subjects')->controller(SubjectController::class)->group(function () {
    Route::post('/', 'all');
    Route::post('/with-topics', 'allWithTopics');

    Route::get('/classes-type', 'getClasses'); // get subject Type
    Route::get('/all-subjects', 'allSubjects'); // get subject Type

    Route::get('/{id}', 'find'); // get subject by id
    Route::get('/{id}/with-topics', 'findWithTopics'); // get subject with topics
    Route::post('/create', 'create');
    Route::post('/delete/{id}', 'deleteSubject');
    Route::post('/archive/{id}', 'toggleArchive');

});
Route::prefix('subject/topic')->controller(TopicController::class)->group(function () {
    Route::post('/create', 'create');
    Route::post('/delete/{id}', 'delete');
    Route::post('/update/{id}', 'update');
});

Route::get('file',function(){
    //  $fullPath = "Curriculumfiles/1750680979.pdf";
    //  return response()->file($fullPath, [
    //      'Content-Type' => 'application/pdf',
    //      'Content-Disposition' => 'inline; filename="' . "1750680979" . '"',
    //     ]);
        $cur=CurriculumFile::findOrFail(13);
        return response()->json([
            // 'data'=>asset($cur['file_path'])
            'data'=>[
                'id'=>$cur['id'],
                'title'=>$cur['title'],
                'file_path'=>asset($cur['file_path']),
            ]
        ]);

        // $data['classroom_course_id']=1;
        // $data['course_id']=2;
        // $filename = "طلاب الشعبة (رقم {$data['classroom_course_id']}).xlsx";
        // return Excel::download(new StudentsExport($data['course_id'],$data['classroom_course_id']), $filename);


});



//general api ==========> for everyone
Route::prefix('advertisement')->group(function () {

        Route::controller(AdvertisementController::class)->group(function () {
            Route::get('teacherAdvertisements','allTeacherAdvertisements');
            Route::get('courseAdvertisements','allCourseAdvertisements');
            Route::get('generalAdvertisements','allGeneralAdvertisements');

        });
});


//course
Route::prefix('course')->controller(CourseController::class)->group(function () {
    Route::middleware(['auth:sanctum'])->group(function(){
            Route::post('registerAtCourse','registerAtCourse');
            Route::post('sortStudent','sortStudent');
            Route::get('AllStudent/{course_id}','AllStudent');
            Route::post('AllStudentAtClass','AllStudentAtClass');

            Route::post('/create', 'store')->middleware('role:Secretariat|Manager');
            Route::post('/delete/{id}', 'delete')->middleware('role:Secretariat|Manager');
            // Route::post('/update/{id}', 'update');
            Route::get('/show/{id}', 'show');
            // Route::post('/delete/{id}', 'destroy')->middleware('role:Secretariat|Manager');
            Route::get('/all-courses', 'AllCourses');
            Route::get('/courses-filter', 'getCurrentAndIncomingCourses');
            Route::get('/classRooms-course/{courseId}', 'classRoomsCourse');
            Route::get('/curricula-course/{courseId}', 'curriculumsCourse');
            Route::post('/add-schedule-to-classroom-at-course', 'addScheduleToClassroom');


            Route::get('/all-files-for-course/{courseId}', 'AllfileForCourse');
            Route::get('/get-files/{curriculumId}', 'getFiles');
            Route::get('/show-file/{fileId}', 'showFile');
            Route::post('/store-file', 'storeFile');

            Route::prefix('excel')->controller(ExcelController::class)->group(function () {
                Route::post('download-excel','downloadStudentsExcel')->middleware('role:Secretariat|Manager');
            });
            Route::post('store-paperExam',[PaperExamController::class,'store'])->middleware('role:Secretariat|Manager');
        });
    });
    Route::prefix('quiz')->controller(QuizController::class)->group(function () {

        Route::middleware(['auth:sanctum'])->group(function(){
            Route::post('/create',  'store')->middleware('role:Teacher');
            Route::post('/update',  'update')->middleware('role:Teacher');
            Route::get('/all_quizzes_for_curriculum/{id}',  'getAllQuizzesForSubject');
            Route::get('/all_questions/{quizID}',  'getAllQuestions');
            Route::post('/enter/{quizID}',  'enterQuiz')->middleware('role:Student');
            Route::post('/submit',  'submitAnswers')->middleware('role:Student');
            Route::get('/my_solved_quizzes/{id}',  'mySolvedQuizzes')->middleware('role:Student');
            Route::get('/my_solved_quiz_details/{id}',  'mySolvedQuizDetails')->middleware('role:Student');
            Route::post('/delete',  'delete')->middleware('role:Teacher');
            Route::post('/change_available',  'changeState')->middleware('role:Teacher');
            Route::get('get-all-course-quiz/{courseId}',  'getAllCourseQuiz')->middleware('role:Teacher|Manager|Secretariat');
            Route::get('/get-quiz-result/{quizId}',  'getQuizResult')->middleware('role:Teacher|Manager|Secretariat');

        });
        // Route::get('/all_quizzes_for_curriculum/{id}',  'getAllQuizzesForSubject');
    });


Route::prefix('classroom')->controller(ClassroomController::class)->group(function () {
    Route::middleware(['auth:sanctum'])->group(function(){
        Route::get('all-classrooms','getClasses');
    });

});

Route::prefix('question')->controller(QuestionController::class)->group(function () {

    Route::middleware(['auth:sanctum','role:Teacher'])->group(function(){
        Route::post('/create',  'store');
        Route::post('/update',  'update');
        Route::post('/delete',  'delete');
    });
    Route::get('/show/{questionID}',  'show');
});



//invoices
Route::prefix('invoice')->controller(InvoiceController::class)->group(function () {
    Route::middleware(['auth:sanctum'])->group(function(){
        Route::post('/addInvoice','store');
        Route::get('/allInvoices/{courseID}','show')->middleware('role:Manager');
    });

});

        Route::prefix('absence')->controller(AbsenceController::class)->group(function () {

            Route::middleware(['auth:sanctum','role:Secretariat|Manager'])->group(function(){
                Route::post('/store-absences',  'store');
                Route::get('/today-absence/{courseId}',  'todayAbsence');
            });
        });

//Image
Route::post('/image/delete', [AdvertisementController::class, 'deleteImage'])->middleware(['auth:sanctum','role:Manager|Secretariat']);


// Route::post('excel',[ExcelController::class,'downloadStudentsExcel']);
// Route::post('import-excel',[ExcelController::class,'importExcel']);

// Route::post('store-paperExam',[PaperExamController::class,'store']);


        Route::prefix('issue')->group(function () {
            Route::post('/add-issue', [IssueController::class, 'addIssue']);
            Route::get('get-is-fqa-issues/{id}', [IssueController::class, 'getIsFqaIssue']);
            Route::get('get-normal-issues/{id}', [IssueController::class, 'getNormalIssue']);
            Route::post('change-issue-status/{id}', [IssueController::class, 'changeIssueStatus']);
            Route::post('delete-issue/{id}', [IssueController::class, 'destroy'])->middleware('auth:sanctum');
        });

        Route::prefix('reply')->group(function () {
            Route::post('add-reply', [ReplyController::class, 'addReply'])->middleware('auth:sanctum');
            Route::delete('/{id}', [ReplyController::class, 'destroy']);
        });

Route::get('testapi',function(){
    // $data=User::paginate(3);
    // return response()->json([
    //     'data'=>$data
    // ]);

});


Route::post('/stripe/session', [StripeController::class, 'session'])->name('stripe.session');
