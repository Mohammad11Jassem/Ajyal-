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

        if(auth()->user()->hasRole('Teacher')){
            return [
                'success' => true,
                'message' => 'All Question at Quiz',
                'data' =>Quiz::with(['questions.parent.image','questions.choices',

                            'questions.image',
                            ])->findOrFail($quizID)
            ];
        }
        return [
                'success' => true,
                'message' => 'All Question at Quiz',
                'data' =>Quiz::available()->with(['questions.parent.image','questions.choices',

                            'questions.image',
                            ])->findOrFail($quizID)
        ];
    }

    public function getAllQuizzesForSubject($id){

        if(auth()->user()->hasRole('Teacher')){
            return Quiz::where('curriculum_id',$id)->get();
        }
        return Quiz::available()->where('curriculum_id',$id)->get();
    }
}
