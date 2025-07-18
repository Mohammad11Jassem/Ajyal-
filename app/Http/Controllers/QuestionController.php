<?php

namespace App\Http\Controllers;

use App\Http\Requests\Question\StoreQuestionRequest as QuestionStoreQuestionRequest;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Requests\StoreQuestionRequest;
use App\Services\QuestionService;
class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected QuestionService $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionStoreQuestionRequest $request)
    {
        // return $request->validated();
        $result = $this->questionService->create($request->validated());

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

    /**
     * Display the specified resource.
     */
    public function show($questionID)
    {
        $result = $this->questionService->showQuestion($questionID);

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        //
    }
}
