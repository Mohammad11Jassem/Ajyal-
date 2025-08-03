<?php

use App\Enum\SubjectType;
use App\Http\Controllers\StripeController;
use App\Http\Resources\PrevQuizeResource;
use App\Mail\TestMail;
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



Route::get('ser/{id}',[StudentPerformanceAnalysisService::class,'calculateTotalMean']);

Route::get('/stripe/session', [StripeController::class, 'session'])->name('stripe.session');
Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');

