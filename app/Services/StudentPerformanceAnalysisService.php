<?php

namespace App\Services;

use App\Models\Curriculum;
use App\Models\CurriculumTeacher;
use App\Models\Quiz;
use App\Models\Student;

class StudentPerformanceAnalysisService
{
    public function claculateMeanForQuiz($curriculumId)
    {
        // $curriculumId=1;
        $studentID=1;
        $student = Student::studentQuizzesForSubject($curriculumId)->find($studentID);

        $quizzes = $student->studentQuizzes;

        $totalScore = 0;
        // $totalQuizzes = 0;
        $totalQuizzes = Quiz::whereHas('curriculumTeacher',function($query) use($curriculumId){
                $query->where('curriculum_id',$curriculumId);
        })->get()->count();;

        foreach ($quizzes as $studentQuiz) {
            if($studentQuiz->result){
                // $score = $studentQuiz->result;
                $score = $this->getTheMarkByPercentage($studentQuiz->quiz->markedQuestions->count(),$studentQuiz->result);
                $totalScore += $score;
                // $totalQuizzes++;
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
                'exams' => $quizData,
        ];
    }
    public function getTheMarkByPercentage($questionNumber,$studentMark)
    {
        // if($questionNumber==0)$questionNumber=1;
        return 100*($studentMark/$questionNumber);
    }

    public function calculateStandardDeviationForQuiz($curriculumId)
    {

        $dataMean=$this->claculateMeanForQuiz($curriculumId);
        $mean=$dataMean['average_score'];


        $sumOfSquares = 0;
        $count=0;
        foreach($dataMean['exams'] as $exam){
            $sumOfSquares+=pow($exam['score']-$mean,2);
            $count++;
        }

        return round(sqrt($sumOfSquares / $count), 2);
    }


    // paper Exam
    public function claculateMeanForPaperExam($curriculumId)
    {
        // $curriculumId=1;
        $studentID=1;
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
                'exams' => $quizData,
        ];
    }

    public function calculateStandardDeviationForPaperExam($curriculumId)
    {

        $dataMean=$this->claculateMeanForPaperExam($curriculumId);
        $mean=$dataMean['average_score'];


        $sumOfSquares = 0;
        $count=0;
        foreach($dataMean['exams'] as $exam){
            $sumOfSquares+=pow($exam['score']-$mean,2);
            $count++;
        }

        return round(sqrt($sumOfSquares / $count), 2);
    }


    public function calculateCombinedMean($curriculumId)
    {
        $quizStats = $this->claculateMeanForQuiz($curriculumId);
        $paperStats = $this->claculateMeanForPaperExam($curriculumId);

        $quizCount = count($quizStats['exams']);
        $paperCount = count($paperStats['exams']);

        if ($quizCount + $paperCount === 0) {
            return 0;
        }

        $meanQuiz = $quizStats['average_score'];
        $meanPaper = $paperStats['average_score'];

        $combinedMean = (
            ($quizCount * $meanQuiz) +
            ($paperCount * $meanPaper)
        ) / ($quizCount + $paperCount);

        return round($combinedMean, 2);
    }

    public function calculateCombinedStandardDeviation($curriculumId)
    {
        $quizStats = $this->claculateMeanForQuiz($curriculumId);
        $paperStats = $this->claculateMeanForPaperExam($curriculumId);

        $quizCount = count($quizStats['exams']);
        $paperCount = count($paperStats['exams']);

        if ($quizCount + $paperCount <= 1) {
            return 0;
        }

        $meanQuiz = $quizStats['average_score'];
        $meanPaper = $paperStats['average_score'];

        $stdQuiz = $this->calculateStandardDeviationForQuiz($curriculumId);
        $stdPaper = $this->calculateStandardDeviationForPaperExam($curriculumId);

        $combinedVarianceDenominator =
            (($quizCount - 1) * pow($stdQuiz, 2)) +
            (($paperCount - 1) * pow($stdPaper, 2)) +
            ($quizCount * $paperCount * pow($meanQuiz - $meanPaper, 2)) / (($quizCount + $paperCount)*($quizCount + $paperCount - 1));

        // $combinedVarianceDenominator = $quizCount + $paperCount - 1;

        // $combinedStd = sqrt($combinedVarianceNumerator / $combinedVarianceDenominator);
        $combinedStd = sqrt($combinedVarianceDenominator);

        return round($combinedStd, 2);
    }

    public function quizzes($curriculumId)
    {
        $studentID=1;
        $studentPaper = Student::paperExamForSubject($curriculumId)->find($studentID);

        $studentQuiz = Student::studentQuizzesForSubject($curriculumId)->find($studentID);
        $quizzes = $studentQuiz->studentQuizzes->map(function ($studentQuiz) {
                return [
                    'id' => $studentQuiz->quiz->id,
                    'quiz_name' => $studentQuiz->quiz->name,
                    'result' => $studentQuiz->result,
                ];
            });
        // return $quizzes;
        // $totalQuizzes = CurriculumTeacher::where('curriculum_id',$curriculumId)
        //                     ->withCount('availableQuizzes')->get();

        // $quiz=Quiz::whereHas('curriculumTeacher',function($query) use($curriculumId){
        //         $query->where('curriculum_id',$curriculumId);
        // })->get();
        return [
            'paper_exams'=>$studentPaper->paperExams,
            'quiz'=>$quizzes,
        ];

    }


}
