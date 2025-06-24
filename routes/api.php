<?php


use App\Http\Controllers\AdvertisementController;

use App\Http\Controllers\CourseController;

use App\Http\Controllers\ParentModelController;
use App\Http\Controllers\StudentController;

use App\Http\Controllers\ManagerController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TopicController;
use App\Models\Curriculum;
use App\Models\CurriculumFile;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Route;



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
        });
    });
        Route::middleware('auth:sanctum')->controller(TeacherController::class)->group(function () {
            Route::get('profile/{id}', 'profile');

        });

});

Route::prefix('parent')->controller(ParentModelController::class)->group(function () {
    Route::post('/register', 'registerParent');
    Route::post('/login', 'loginParent');
    Route::middleware(['auth:sanctum','role:Parent'])->group(function(){

        Route::get('/profile', 'profile');
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


        Route::middleware(['auth:sanctum','role:Teacher'])->group(function () {
            Route::get('myProfile', 'myProfile');
            Route::post('logout','logout');


                });
});

// middleware('auth:api')->
Route::middleware(['auth:sanctum','role:Secretariat|Manager'])->prefix('subjects')->controller(SubjectController::class)->group(function () {
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
Route::prefix('course')->controller(CourseController::class)->group(function () {
    Route::post('/create', 'store');
    Route::post('/delete/{id}', 'delete');
    // Route::post('/update/{id}', 'update');
    Route::get('/show/{id}', 'show');
    Route::post('/delete/{id}', 'destroy');
    Route::get('/all-courses', 'AllCourses');
    Route::get('/courses-filter', 'getCurrentAndIncomingCourses');
    Route::get('/classRooms-course/{courseId}', 'classRoomsCourse');


    Route::get('/all-file-for-course/{courseId}', 'AllfileForCourse');
    Route::post('/store-file', 'storeFile');
});

Route::get('fff',function(){
    //  $fullPath = "Curriculumfiles/1750680979.pdf";
    //  return response()->file($fullPath, [
    //      'Content-Type' => 'application/pdf',
    //      'Content-Disposition' => 'inline; filename="' . "1750680979" . '"',
    //     ]);
        $cur=CurriculumFile::findOrFail(7);
        return response()->json([
            'data'=>asset($cur['file_path'])
        ]);
});
