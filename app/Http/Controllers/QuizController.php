<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quiz\StoreQuizRequest as QuizStoreQuizRequest;
use App\Http\Requests\Quiz\SubmitQuizRequest;
use App\Services\QuizService;
use App\Traits\HttpResponse;

class QuizController extends Controller
{
    use HttpResponse;
    protected QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function store(QuizStoreQuizRequest $request)
    {
        $result = $this->quizService->create($request->validated());

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Quiz created successfully',
        //     'data'    => $quiz,
        // ], 201);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error']
            ], 422);
        }
        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], 201);
    }
    public function getAllQuestions($quizID){

        $result = $this->quizService->allQuestions($quizID);
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error']
            ], 422);
        }
        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], 201);
    }

    public function getAllQuizzesForSubject($id){
        $quzzies=$this->quizService->getAllQuizzesForSubject($id);
        return $this->success('امتحانات هذه المادة',$quzzies);
    }
    public function enterQuiz($quizID){
        $result=$this->quizService->StartQuiz($quizID);
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error']
            ], 422);
        }
        return response()->json([
            'message' => $result['message'],
            // 'data' => $result['data']
        ], 201);

    }
    public function submitAnswers(SubmitQuizRequest $request){

        $result=$this->quizService->submitQuiz($request->validated());
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                // 'error' => $result['error']
            ], 422);
        }
        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], 201);

    }
    public function mySolvedQuizzes(){

        $result=$this->quizService->allMySolvedQuiz();
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                // 'error' => $result['error']
            ], 422);
        }
        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], 201);
    }
}
