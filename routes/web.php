<?php

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

Route::get('test',function(){
    $quizId = 1; // the quiz you want
    $studentId = 1; // the student you're checking

    $paperExam=Student::PaperExamForSubject(1)->find(1);
    return $paperExam;
});

Route::get('ser/{id}',[StudentPerformanceAnalysisService::class,'quizzes']);
