<?php

use App\Http\Resources\PrevQuizeResource;
use App\Mail\TestMail;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::get('test',function(){
    $quizId = 1; // the quiz you want
    $studentId = 1; // the student you're checking

    // $quiz = Quiz::with([
    //     'questions.correctChoice' => function ($q) {
    //         $q->select('id', 'question_id', 'choice_text', 'is_correct');
    //     },
    //     // 'questions.studentAnswers' => function ($q) use ($studentId) {
    //     //     $q->whereHas('studentQuiz', function ($sq) use ($studentId) {
    //     //         $sq->where('student_id', $studentId);
    //     //     });
    //     // },
    //     'questions.studentAnswer',
    // ])
    // ->findOrFail($quizId);

        $quiz = Quiz::with([
            'questions.choices',
            'questions.children.choices',
        ])->where('id', 2)->first();

        // $questionsWithChoices = $quiz->questions->filter(function ($question) {
        //     // Include question if it or any of its children has choices
        //     return $question->choices->isNotEmpty() || $question->children->filter(fn($child) => $child->choices->isNotEmpty())->isNotEmpty();
        // });
        $allQuestions = collect();

    // Loop through parent questions
    foreach ($quiz->questions as $question) {
        if ($question->choices->isNotEmpty()) {
            $allQuestions->push($question);
        }

        // Loop through each child of the parent
        foreach ($question->children as $child) {
            if ($child->choices->isNotEmpty()) {
                $allQuestions->push($child);
            }
        }
    }
        return $allQuestions;
});
