<?php

namespace App\Services;
use App\Models\Quiz;
use Exception;
use Illuminate\Support\Facades\DB;
class QuizService
{
    public function create(array $data)
    {
        try{
            return [
                'success' => true,
                'message' => 'Quiz created successfully',
                'data' =>DB::transaction(function () use ($data) {
                return Quiz::create($data);
            })
        ];

        }catch(Exception $e){
            return[
                'success' => false,
                'message' => 'Failed to create Quiz',
                'error' => $e->getMessage()
        ];
        }
    }
    public function allQuestions($quizID){
        return [
                'success' => true,
                'message' => 'All Question at Quiz',
                'data' =>Quiz::with(['questions.choices','questions.children.choices','questions.image','questions.children.image'])->findOrFail($quizID)
        ];
    }
}
