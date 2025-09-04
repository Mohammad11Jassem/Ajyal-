<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Curriculum;
use App\Models\CurriculumTeacher;
use App\Models\Quiz;
use App\Models\Student;

class StudentPerformanceAnalysisService
{
    public function claculateMeanForQuiz($curriculumId, $studentID)
    {
        // $curriculumId=1;
        // $studentID=auth()->user()->user_data['role_data']['id'];
        $student = Student::studentQuizzesForSubject($curriculumId)->find($studentID);

        $quizzes = $student->studentQuizzes;

        $totalScore = 0;
        // $totalQuizzes2 = 0;
        $totalQuizzes = Quiz::whereHas('curriculumTeacher',function($query) use($curriculumId){
                $query->where('curriculum_id',$curriculumId);
        })->get()->count();

        foreach ($quizzes as $studentQuiz) {
            if($studentQuiz->result){
                // $score = $studentQuiz->result;
                $score = $this->getTheMarkByPercentage($studentQuiz->quiz->markedQuestions->count(),$studentQuiz->result);
                $totalScore += $score;
                // $totalQuizzes2++;
            }
        }
        $average = $totalQuizzes > 0 ? $totalScore / $totalQuizzes : 0;

        $quizData = $quizzes->map(function($studentQuiz) {
                return [
                    'quiz_name' => $studentQuiz->quiz->name,
                    // 'mark'=>$studentQuiz->result,
                    // 'questuons_num' => $studentQuiz->quiz->markedQuestions->count(),
                    'score' => round($this->getTheMarkByPercentage($studentQuiz->quiz->markedQuestions->count(),$studentQuiz->result),2) ,
                    'max_score' => round($this->getTheMarkByPercentage($studentQuiz->quiz->markedQuestions->count(),$studentQuiz->quiz->markedQuestions->count()),2), // العلامة العظمى

                    // 'score' => $studentQuiz->result,
                    // 'max_score' => $studentQuiz->quiz->markedQuestions->count(), // العلامة العظمى
                ];
            });
        // return $quizData;
        return [
                // 'subject'=>Curriculum::where('id',$curriculumId)->with('subject')->first(),
                'average_score' => round($average, 2),
                'total_quizzes'=>$totalQuizzes,
                // 'totalQuizzes2'=>$totalQuizzes2,
                'exams' => $quizData,
        ];
    }
    public function getTheMarkByPercentage($maxMark,$studentMark)
    {
        // if($questionNumber==0)$questionNumber=1;
        return 100*($studentMark/$maxMark);
    }

    public function calculateStandardDeviationForQuiz($curriculumId,$studentID)
    {

        $dataMean=$this->claculateMeanForQuiz($curriculumId,$studentID);
        $mean=$dataMean['average_score'];


        $sumOfSquares = 0;
        $count=$dataMean['total_quizzes'];
        foreach($dataMean['exams'] as $exam){
            $sumOfSquares+=pow($exam['score']-$mean,2);
            // $count++;
            // here we need to add the exams that the student did not register for
        }
        $result=0;
        if($count!=0) {
           $result=round(sqrt($sumOfSquares / $count), 2);
        }
        return [
            'result'=>$result,
        ];
    }


    // paper Exam
    public function claculateMeanForPaperExam($curriculumId,$studentID)
    {
        // $curriculumId=1;
        // $studentID=auth()->user()->user_data['role_data']['id'];
        $student = Student::paperExamForSubject($curriculumId)->find($studentID);

        $quizzes = $student->paperExams;
        // return $quizzes;

        $totalScore = 0;
        $totalQuizzes = 0;

        foreach ($quizzes as $studentQuiz) {
            if($studentQuiz->pivot->mark){
                // $score = $studentQuiz->result;
                $score = $this->getTheMarkByPercentage($studentQuiz->max_degree,$studentQuiz->pivot->mark);
                $totalScore += $score;
                $totalQuizzes++;
            }
        }
        $average = $totalQuizzes > 0 ? $totalScore / $totalQuizzes : 0;

        $quizData = $quizzes->map(function($studentQuiz) {
                return [
                    'quiz_name' => $studentQuiz->title,
                    // 'mark'=>$studentQuiz->pivot->mark,
                    // 'questuons_num' => $studentQuiz->max_degree,
                    'score' => round($this->getTheMarkByPercentage($studentQuiz->max_degree,$studentQuiz->pivot->mark),2) ,
                    'max_score' => round($this->getTheMarkByPercentage($studentQuiz->max_degree,$studentQuiz->max_degree),2), // العلامة العظمى

                    // 'score' => $studentQuiz->result,
                    // 'max_score' => $studentQuiz->quiz->markedQuestions->count(), // العلامة العظمى
                ];
            });
        // return $quizData;
        return [
                // 'subject'=>Curriculum::where('id',$curriculumId)->with('subject')->first(),
                'average_score' => round($average, 2),
                'total_quizzes'=>$totalQuizzes,
                'exams' => $quizData,
        ];
    }

    public function calculateStandardDeviationForPaperExam($curriculumId,$studentID)
    {

        $dataMean=$this->claculateMeanForPaperExam($curriculumId,$studentID);
        $mean=$dataMean['average_score'];


        $sumOfSquares = 0;
        $count=$dataMean['total_quizzes'];
        foreach($dataMean['exams'] as $exam){
            $sumOfSquares+=pow($exam['score']-$mean,2);
            // $count++;
        }
        $result=0;
        if($count!=0) {
           $result=round(sqrt($sumOfSquares / $count), 2);
        }
        return [
            'result'=>$result,
        ];
    }


    public function calculateCombinedMean($curriculumId,$studentID)
    {
        $quizStats = $this->claculateMeanForQuiz($curriculumId,$studentID);
        $paperStats = $this->claculateMeanForPaperExam($curriculumId,$studentID);

        // $quizCount = count($quizStats['exams']);
        $quizCount = $quizStats['total_quizzes'];
        $paperCount = count($paperStats['exams']);

        if ($quizCount + $paperCount === 0) {
             return [
                'result'=>0,
            ];
        }

        $meanQuiz = $quizStats['average_score'];
        $meanPaper = $paperStats['average_score'];

        $combinedMean = (
            ($quizCount * $meanQuiz) +
            ($paperCount * $meanPaper)
        ) / ($quizCount + $paperCount);

        // return round($combinedMean, 2);
        // return [
        //     'meanQuiz'=>$meanQuiz,
        //     'meanPaper'=>$meanPaper
        // ];
        return [
            'result'=>round($combinedMean, 2),
        ];
    }

    public function calculateCombinedStandardDeviation($curriculumId,$studentID)
    {
        $quizStats = $this->claculateMeanForQuiz($curriculumId,$studentID);
        $paperStats = $this->claculateMeanForPaperExam($curriculumId,$studentID);

        $quizCount = count($quizStats['exams']);
        $paperCount = count($paperStats['exams']);

        if ($quizCount + $paperCount <= 1) {
            return [
                'result'=>0,
            ];
        }

        $meanQuiz = $quizStats['average_score'];
        $meanPaper = $paperStats['average_score'];

        $stdQuiz = $this->calculateStandardDeviationForQuiz($curriculumId,$studentID)['result'];
        $stdPaper = $this->calculateStandardDeviationForPaperExam($curriculumId,$studentID)['result'];

        $combinedVarianceDenominator =
            (($quizCount - 1) * pow($stdQuiz, 2)) +
            (($paperCount - 1) * pow($stdPaper, 2)) +
            ($quizCount * $paperCount * pow($meanQuiz - $meanPaper, 2)) / (($quizCount + $paperCount)*($quizCount + $paperCount - 1));

        // $combinedVarianceDenominator = $quizCount + $paperCount - 1;

        // $combinedStd = sqrt($combinedVarianceNumerator / $combinedVarianceDenominator);
        $combinedStd = sqrt($combinedVarianceDenominator);

        // return round($combinedStd, 2);
        return [
            'result'=>round($combinedStd, 2),
        ];
    }

    public function quizzes($curriculumId,$studentID)
    {

        // $studentID=auth()->user()->user_data['role_data']['id'];
        $studentPaper = Student::paperExamForSubject($curriculumId)->find($studentID);
        $studentExams=$studentPaper->paperExams->map(function ($studentExam) {
                return [
                    'id' => $studentExam->id,
                    'curriculum_id'=>$studentExam->curriculum_id,
                    'quiz_name' => $studentExam->title,
                    'exam_date'=>$studentExam->exam_date,
                    // 'result' => $studentExam->pivot->mark,
                    'result' =>round($this->getTheMarkByPercentage($studentExam->max_degree,$studentExam->pivot->mark),2),
                    'max_score'=>round($this->getTheMarkByPercentage($studentExam->max_degree,$studentExam->max_degree),2),
                    // 'max_score'=>$studentExam->max_degree,
                ];
            });
            //round($this->getTheMarkByPercentage(,),2)
        $studentQuiz = Student::studentQuizzesForSubject($curriculumId)->find($studentID);
        $quizzes = $studentQuiz->studentQuizzes->map(function ($studentQuiz) {
                return [
                    'id' => $studentQuiz->quiz->id,
                    'curriculum_id' => $studentQuiz->quiz->curriculum_id,
                    'quiz_name' => $studentQuiz->quiz->name,
                    'exam_date' => $studentQuiz->quiz->start_time,
                    // 'result' => $studentQuiz->result,
                    'result' => round($this->getTheMarkByPercentage($studentQuiz->quiz->markedQuestions->count(),$studentQuiz->result),2),
                    'max_score' => round($this->getTheMarkByPercentage($studentQuiz->quiz->markedQuestions->count(),$studentQuiz->quiz->markedQuestions->count()),2),
                ];
            });

        $mergedResults = collect($studentExams)->merge($quizzes)->sortBy('exam_date')->values();

        // return $quizzes;
        // $totalQuizzes = CurriculumTeacher::where('curriculum_id',$curriculumId)
        //                     ->withCount('availableQuizzes')->get();

        // $quiz=Quiz::whereHas('curriculumTeacher',function($query) use($curriculumId){
        //         $query->where('curriculum_id',$curriculumId);
        // })->get();
        return [
            'paper_exams'=>$studentExams,
            'quiz'=>$quizzes,
            'both'=>$mergedResults,
        ];

    }

    public function calculateTotalMean($courseId,$studentID)
    {
        $means=[];
        $subjects=Course::findOrFail($courseId)->curriculums;
        // return $subjects;
        foreach($subjects as $subject){
            $means[]=$this->calculateCombinedMean($subject->id,$studentID)['result'];
        }

        $meanSum=0;
        $subjectCount=0;
        foreach($means as $mean){
            $meanSum+=$mean;
            $subjectCount++;
        }
        return [
            'result'=>round($this->getTheMarkByPercentage(100,$meanSum/$subjectCount),2),
        ];
    }
    public function calculateMean($curriculumId,$studentID)
    {
       $quizMean=$this->claculateMeanForQuiz($curriculumId,$studentID);
       $paperMean=$this->claculateMeanForPaperExam($curriculumId,$studentID);
       $totalMean=$this->calculateCombinedMean($curriculumId,$studentID);

       return [
            // 'paper_exam'=>$paperMean,
            'paper_exam'=>[
                    'average_score' => $paperMean['average_score'],
                    'total_quizzes'=>$paperMean['total_quizzes'],
            ],
            // 'quiz'=>$quizMean,
            'quiz'=>[
                    'average_score' => $quizMean['average_score'],
                    'total_quizzes'=>$quizMean['total_quizzes'],
            ],
            'both'=>$totalMean,
       ];
    }
    public function calculateStddev($curriculumId,$studentID)
    {
       $quizStddev=$this->calculateStandardDeviationForQuiz($curriculumId,$studentID);
       $paperStddev=$this->calculateStandardDeviationForPaperExam($curriculumId,$studentID);
       $totalStddev=$this->calculateCombinedStandardDeviation($curriculumId,$studentID);

       return [
            'paper_exam'=>$paperStddev['result'],
            'quiz'=>$quizStddev['result'],
            'both'=>$totalStddev['result'],
       ];
    }

    public function SubjectsMean($courseId,$studentId)
    {
        $means=[];
        $subjects=Course::with('curriculums.subject')->findOrFail($courseId);
        // $this->calculateCombinedMean($subject->id,$studentId)['result']
        // return $subjects;
        foreach($subjects['curriculums'] as $curriculum){
            $means[]=[
                'id'=>$curriculum['id'],
                'name'=>$curriculum['subject']['name'],
                'mean'=>$this->calculateCombinedMean($curriculum->id,$studentId)['result']
            ];
        }

        return $means;

    }
    public function quizzesType($curriculumId,$studentId)
    {
        $quizzes=$this->quizzes($curriculumId,$studentId);
        return [
            'paper_exams'=>$quizzes['paper_exams'],
            'quizzes'=>$quizzes['quiz'],
        ];
    }


}
