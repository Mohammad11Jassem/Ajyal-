<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quiz\StoreQuizRequest as QuizStoreQuizRequest;
use App\Services\QuizService;

class QuizController extends Controller
{
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
}
