<?php

namespace App\Services;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizWithoutQustionsResource;
use App\Models\Choice;
use App\Models\CurriculumTeacher;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\StudentQuiz;
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
        return Quiz::available()->whereHas('assignment',function($query)use($id){
                $query->where('curriculum_id',$id);
            })->get();
    }

    public function StartQuiz($quizID){
        try{
            $user=auth()->user();
            if($user && $user->hasRole('Student')){
                $user->student->studentQuizzes()->create(['quiz_id'=>$quizID,
                'is_submit'=>0,
                'result'=>0,
            ]);
                return [
                    'success'=>true,
                    'message'=>'نتمنى لكم امتحاناً موفقاً',
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
                throw new \Exception('Student quiz not started');
            }

            if ($studentQuiz->is_submit) {
                throw new \Exception('Quiz already submitted');
            }

            $totalScore = 0;

            foreach ($data['answers'] as $answer) {
                $question = Question::find($answer['question_id']);
                $choice = Choice::find($answer['choice_id']);

                // Throw exception if question or choice not found
                if (!$question || !$choice) {
                    throw new ModelNotFoundException('Invalid question or choice');
                }

                // Check if choice belongs to the question
                if ($choice->question_id !== $question->id) {
                    throw new \Exception('Choice does not belong to question');
                }

                // Save answer
                $studentQuiz->answers()->create([
                    'question_id' => $question->id,
                    'selected_choice_id' => $choice->id,
                    'answered_at'=>Carbon::now(),
                ]);

                if ($choice->is_correct) {
                    $totalScore += $question->mark;
                }
            }

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

    public function allMySolvedQuiz(){
            return DB::transaction(function ()  {
                $user = auth()->user();
                if (!$user || !$user->hasRole('Student')) {
                    throw new \Exception('Unauthorized');
                }

            $student = $user->student;

            if (!$student) {
                throw new \Exception('Student record not found');
            }

            return [
                'success' => true,
                'message' => 'اختباراتي السابقة',
                'data' => $student->quizzes
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

}
