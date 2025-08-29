<?php

namespace App\Services;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizWithoutQustionsResource;
use App\Http\Resources\SolvedQuizResource;
use App\Models\Choice;
use App\Models\Course;
use App\Models\CurriculumTeacher;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\StudentQuiz;
use App\Models\Teacher;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
class QuizService
{
    public function create(array $data)
    {
        try{
            return DB::transaction(function () use ($data) {
                $curriculmTeacher=CurriculumTeacher::where('curriculum_id',$data['curriculum_id'])
                                                    ->where('teacher_id',auth()->user()->user_data['role_data']['id'])
                                                    ->first();
                $data['curriculum_teacher_id']=$curriculmTeacher['id'];
                $quiz=Quiz::create($data);
                return [
                    'success' => true,
                    'message' => 'Quiz created successfully',
                    'data' =>new QuizWithoutQustionsResource($quiz),
                ];
            });
        //     return [
        //         'success' => true,
        //         'message' => 'Quiz created successfully',
        //         'data' =>DB::transaction(function () use ($data) {
        //         return Quiz::create($data);
        //     })
        // ];

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
                'data' =>Quiz::with(['questions.image','questions.choices','questions.children.image','questions.children.choices',


                            ])->findOrFail($quizID)
                // 'data' =>Quiz::with(['questions.parent.image','questions.choices',

                //             'questions.image',
                //             ])->findOrFail($quizID)
            ];
        }
        return [
                'success' => true,
                'message' => 'All Question at Quiz',
                'data' =>Quiz::with(['questions.image','questions.choices',
                                    'questions.children.image','questions.children.choices',
                            ])->findOrFail($quizID)
                // 'data' =>Quiz::available()->with(['questions.parent.image','questions.choices',

                //             'questions.image',
                //             ])->findOrFail($quizID)
        ];
    }

    public function getAllQuizzesForSubject($id){

        if(auth()->user()->hasRole('Teacher')){
            // return Quiz::where('curriculum_id',$id)->get();
            return Quiz::whereHas('assignment',function($query)use($id){
                $query->where('teacher_id',auth()->user()->user_data['role_data']['id'])
                      ->where('curriculum_id',$id);
            })->get();
        }
        return Quiz::available()->whereDoesntHave('student')->whereHas('assignment',function($query)use($id){
                $query->where('curriculum_id',$id);
            })->get();
    }

    public function StartQuiz($quizID){
        try{
            $user=auth()->user();
            if($user && $user->hasRole('Student')){
                $user->student->studentQuizzes()->create(['quiz_id'=>$quizID,
                'is_submit'=>1,
            ]);
                return [
                    'success'=>true,
                    'message'=>'نتمنى لكم امتحاناً موفقاً',
                    'data'=>$this->allQuestions($quizID),
                ];
            }
        }
        catch(Exception $e){
                return [
                    'success'=>false,
                    'message'=>'فشل الدخول إلى الامتحان',
                    'error'=>$e->getMessage()
                ];

        }

    }


    public function submitQuiz(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = auth()->user();

            if (!$user || !$user->hasRole('Student')) {
                throw new \Exception('Unauthorized');
            }

            $student = $user->student;

            if (!$student) {
                throw new \Exception('Student record not found');
            }

            $studentQuiz = $student->studentQuizzes()
                ->where('quiz_id', $data['quiz_id'])
                ->first();

            if (!$studentQuiz) {
                throw new Exception('Student quiz not started');
            }

            if ($studentQuiz->is_submit && $studentQuiz->result) {
                throw new Exception('Quiz already submitted');
            }

            $totalScore = 0;

            foreach ($data['answers'] as $answer) {
                $question = Question::find($answer['question_id']);
                $choice = Choice::find($answer['choice_id']??null);

                // Throw exception if question or choice not found
                // if (!$question || !$choice) {
                //     throw new ModelNotFoundException('Invalid question or choice');
                // }

                // Check if choice belongs to the question
                // if ($choice?->question_id !== $question->id) {
                //     throw new \Exception('Choice does not belong to question');
                // }

                // Save answer
                $studentQuiz->answers()->create([
                    'question_id' => $question->id,
                    'selected_choice_id' => $choice?->id??null,
                    'answered_at'=>Carbon::now(),
                ]);

                if ($choice?->is_correct) {
                    $totalScore += $question->mark;
                }
            }
            // try & catch
            $studentQuiz->update([
                'result' => $totalScore,
                'is_submit' => true,
            ]);

            return [
                'success' => true,
                'message' => 'Quiz submitted successfully',
                'data' => $totalScore,
            ];
        });
    }

    public function allMySolvedQuiz($curriculumId){
            return DB::transaction(function ()use ($curriculumId)  {
                $user = auth()->user();
                if (!$user || !$user->hasRole('Student')) {
                    throw new \Exception('Unauthorized');
                }

            $student = $user->student;

            if (!$student) {
                throw new \Exception('Student record not found');
            }


            // $quizzes = $student->quizzes()
            //         ->with('assignment')
            //         ->withPivot(['result', 'is_submit'])
            //         ->wherePivot('is_submit', true)
            //         ->get()
            //         ->filter(function ($quiz) use ($curriculumId) {
            //             return $quiz->curriculum_id == $curriculumId;
            //         });

            $quizzes = $student->quizzes()
                            ->with('assignment')
                            ->withCount('markedQuestions as max_degree') // نجيب العلامة الكاملة
                            ->withPivot(['result', 'is_submit'])
                            ->wherePivot('is_submit', true)
                            ->get()
                            ->filter(function ($quiz) use ($curriculumId) {
                                return $quiz->curriculum_id == $curriculumId;
                            });
                       

            return [
                'success' => true,
                'message' => 'اختباراتي السابقة',
                // 'data' => $quizzes
                'data' => SolvedQuizResource::collection($quizzes),
                // 'data' => $student->quizzes->student
            ];
        });

    }

    public function mySolvedQuizDetails($id)
    {
        $quiz=Quiz::where('id',$id)->first();
        // return $quiz;
        if(!$quiz->student){
            throw new ModelNotFoundException();
        }
       return DB::transaction(function() use($id){
             $quiz=Quiz::with([
                'student',
                'questions.image',
                'questions.choices',
                'questions.correctChoice',
                'questions.studentAnswer.selectedChoice',
                'questions.children.image',
                'questions.children.choices',
                'questions.children.correctChoice',
                'questions.children.studentAnswer.selectedChoice'
            ])->findOrFail($id);

            //   return $quiz;
            return new QuizResource($quiz);
       });
    }


    public function update($data)
    {
        // return $data;
        return DB::transaction(function () use ($data) {

            $quiz=Quiz::findOrFail($data['quiz_id']);
            //get the teacher assignment
            $curriculmTeacher=CurriculumTeacher::where('curriculum_id',$data['curriculum_id'])
                                                    ->where('teacher_id',auth()->user()->user_data['role_data']['id'])
                                                    ->first();
            $data['curriculum_teacher_id']=$curriculmTeacher['id'];

            $quiz->update([
                'curriculum_teacher_id'=>$data['curriculum_teacher_id']??$quiz['curriculum_teacher_id'],
                'topic_id'=>$data['topic_id']??null,
                'name'=>$data['name']??$quiz['name'],
                'type'=>$data['type']??$quiz['type'],
                'available'=>$data['available']??$quiz['available'],
                'start_time'=>$data['start_time']??null,
                'duration'=>$data['duration']??null,
            ]);

            return $quiz;
        });


    }
    public function delete($quizId){
        $quiz=Quiz::findOrFail($quizId);
        $userID=auth()->user()->user_data['role_data']['id'];
        $OwnerID=CurriculumTeacher::findOrFail($quiz['curriculum_teacher_id'])->teacher_id;
        if($userID===$OwnerID ){
            $quiz->delete();
            return[
                'success'=>true,
                'message'=>'Quiz has been deleted'
            ];
        }
        return [
            'success'=>false,
            'message'=>'َQuiz deletion failed'
        ];
    }
    public function changeState($quizId){
        $quiz=Quiz::findOrFail($quizId);
        $userID=auth()->user()->user_data['role_data']['id'];
        $OwnerID=CurriculumTeacher::findOrFail($quiz['curriculum_teacher_id'])->teacher_id;
        if($userID===$OwnerID ){
            $quiz->available=!$quiz->available;
            $quiz->save();
            return [
                'success'=>true,
                'message'=>'Quiz status changed',
                'data'=>$quiz
            ];
        }
    }

    public function getAllCourseQuiz($courseId)
    {
       $quizzes= Quiz::whereHas('curriculumTeacher.curriculum.course', function($q)use($courseId) {
                    $q->where('id', $courseId);
                })->with('studentQuizzes')
                ->withCount('markedQuestions')
                ->get();

        $withResults = [];
        $withoutResults = [];
        foreach ($quizzes as $quiz) {
            $mean = 0;
            $studentQuizzesCount=$quiz->studentQuizzes->count();
            if ($studentQuizzesCount > 0) {
                // $mean = $quiz->studentQuizzes->avg('result');
                $meanSum=0;
                foreach ($quiz->studentQuizzes as $studentQuiz) {
                    $meanSum+=$this->getTheMarkByPercentage($quiz['marked_questions_count'],$studentQuiz['result']);
                }
                $withResults[] = [
                    'id' => $quiz->id,
                    'curriculum_teacher_id'=>$quiz->curriculum_teacher_id,
                    'topic_id'=>$quiz->topic_id,
                    'curriculum_id'=>$quiz->curriculum_id,
                    'name' => $quiz->name,
                    'type' => $quiz->type,
                    'available' => $quiz->available,
                    'start_time' => $quiz->start_time,
                    'duration' => $quiz->duration,
                    'mean_result' => round($meanSum/$studentQuizzesCount, 2),
                ];
            } else {
                $withoutResults[] = [
                    'id' => $quiz->id,
                    'curriculum_teacher_id'=>$quiz->curriculum_teacher_id,
                    'topic_id'=>$quiz->topic_id,
                    'curriculum_id'=>$quiz->curriculum_id,
                    'name' => $quiz->name,
                    'type' => $quiz->type,
                    'available' => $quiz->available,
                    'start_time' => $quiz->start_time,
                    'duration' => $quiz->duration,
                    'mean_result' => 0,
                ];
            }
        }

       return [
            'with_results' => $withResults,
            'without_results' => $withoutResults,
       ];

    }

    private function getTheMarkByPercentage($maxMark,$studentMark)
    {
        // if($questionNumber==0)$questionNumber=1;
        return 100*($studentMark/$maxMark);
    }

    public function getQuizResult($quizId)
    {
        $quiz=Quiz::with('studentQuizzes.student:id,user_id,first_name,last_name,father_name')
                    ->withCount( 'markedQuestions as max_degree')->findOrFail($quizId);

        $realMaxDegree = $quiz->max_degree ?: 1;


        $quiz->max_degree = 100;

        // تحويل نتائج الطلاب إلى نسبة مئوية
        $quiz->studentQuizzes->transform(function ($studentQuiz) use ($realMaxDegree) {
            $studentMark = (float) $studentQuiz->result;
            // $studentQuiz->result = round(100 * ($studentMark / $realMaxDegree), 2);
            $studentQuiz->result =round($this->getTheMarkByPercentage($realMaxDegree,$studentMark),2);

            return $studentQuiz;
        });
        return $quiz;
    }
}
