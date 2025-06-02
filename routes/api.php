<?php

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
