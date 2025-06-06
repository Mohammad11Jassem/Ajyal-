<?php

<<<<<<< HEAD
use App\Http\Controllers\ParentModelController;
use App\Http\Controllers\StudentController;
=======
use App\Http\Controllers\ManagerController;
>>>>>>> 0fe9d8ddbf7a7d14cf075f0eb908f3d9ee6f89f5
use App\Http\Controllers\TeacherController;
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
<<<<<<< HEAD
Route::post('/link-student2', [StudentController::class, 'linkStudent2']);
Route::get('/qr', [StudentController::class, 'getStudentQr']);

Route::post('/check-student', [StudentController::class, 'getStudentByCodeAndName']);
Route::post('/register-student', [StudentController::class, 'register']);
Route::post('/login-student', [StudentController::class, 'login']);
Route::post('/add-student', [StudentController::class, 'store'])->middleware('auth:sanctum');
Route::get('/profile-student', [StudentController::class, 'profile'])->middleware('auth:sanctum');
Route::post('/link-student', [StudentController::class, 'linkStudent'])->middleware('auth:sanctum');


// Route::prefix('student')->controller(StudentController::class)->group(function () {
//     // Public routes
//     Route::post('/check', 'getStudentByCodeAndName');
//     Route::post('/register', 'register');
//     Route::post('/login', 'login');

//     // Authenticated routes
//     Route::middleware('auth:sanctum')->group(function () {
//         Route::post('/add', 'store');
//         Route::get('/profile', 'profile');
//         Route::post('/link', 'linkStudent');
//     });
// });

Route::post('/register-parent', [ParentModelController::class, 'registerParent']);
Route::post('/login-parent', [ParentModelController::class, 'loginParent']);
=======


// Admin/Manager Routes
Route::prefix('admin')->controller(ManagerController::class)->group(function () {

        Route::post('login','login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout','logout');
            Route::get('profile','profile');
            // Route::get('dashboard', [ManagerController::class, 'dashboard']);
               //Add teachers
            Route::post('teachers',[TeacherController::class, 'store'])->middleware('role:Manager|Secretariat');


            // Route::post('role', function () {
            //     $user = FacadesAuth::user();
            //     if (!$user) {
            //         return response()->json(['message' => 'Not authenticated'], 401);
            //     }
            //     // Get the associated User model through the manager relationship
            //     $actualUser = User::find($user->id);
            //     if (!$actualUser) {
            //         return response()->json(['message' => 'User not found'], 404);
            //     }

            //     return response()->json([
            //         'roles' => $actualUser->getRoleNames()
            //     ]);

            //     // return $user->getRoleNames();
            // });

        });
});

//Teacher Routes

Route::prefix('teacher')->controller(TeacherController::class)->group(function () {

        Route::post('teacherRegister', 'register');
        Route::post('teacherLogin','login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('profile', 'profile');
            
                });
});
>>>>>>> 0fe9d8ddbf7a7d14cf075f0eb908f3d9ee6f89f5
