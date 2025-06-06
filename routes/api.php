<?php

use App\Http\Controllers\ManagerController;
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
