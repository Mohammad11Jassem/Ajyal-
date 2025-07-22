<?php

namespace App\Http\Controllers;

use App\Http\Requests\Question\StoreQuestionRequest as QuestionStoreQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Models\Question;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreQuestionRequest;
use App\Services\QuestionService;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    use HttpResponse;
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
    public function update(UpdateQuestionRequest $updateQuestionRequest)
    {
        $data=$updateQuestionRequest->validated();
        $result=$this->questionService->update($data);
        return $this->success('تم تعديل السؤال بنجاح',$result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        Validator::make($request->all(), [
            'question_id'=>'required|exists:quizzes,id'
        ]);
        $result=$this->questionService->delete($request['question_id']);
        return $this->success('تم حذف السؤال بنجاح');
    }
}
