<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quiz\StoreQuizRequest as QuizStoreQuizRequest;
use App\Http\Requests\Quiz\SubmitQuizRequest;
use App\Http\Requests\Quiz\UpdateQuizRequest;
use App\Services\QuizService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $data=$request->validated();
        // $data['teacher_id']=auth()->user()->user_data['role_data']['id'];
        $result = $this->quizService->create($data);

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
            'data' => $result['data'],
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

    public function mySolvedQuizDetails($id)
    {
       $result=$this->quizService->mySolvedQuizDetails($id);
       return $this->success('تفاصيل الاختبار المحلول',$result);
    }

    public function update(UpdateQuizRequest $updateQuizRequest)
    {
        $data=$updateQuizRequest->validated();
        $result=$this->quizService->update($data);
        return $this->success('تم تعديل الاختبار بنجاح',$result);
    }
    public function delete(Request $request){
        Validator::make($request->all(), [
            'quiz_id'=>'required|exists:quizzes,id'
        ]);
        $result=$this->quizService->delete($request->quiz_id);
        if (!$result['success']) {
            return $this->badRequest('فشل حذف الاختبار');
        }
        return $this->success('تم حذف الاختبار بنجاح');
    }
    public function changeState(Request $request){
        Validator::make($request->all(), [
            'quiz_id'=>'required|exists:quizzes,id'
        ]);
        $result=$this->quizService->changeState($request->quiz_id);
        if (!$result['success']) {
            return $this->badRequest('فشل تغيير حالة الاختبار');
        }
        return $this->success('تم تغيير حالة الاختبار',$result['data']);
    }
}
