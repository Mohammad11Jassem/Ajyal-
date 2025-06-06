<?php

use App\Http\Controllers\ParentModelController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/teacher', function () {
        return response()->json([
            'data'=>auth()->user()->user_data
        ]);
    });
});
Route::post('/login', [TeacherController::class, 'login']);
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
