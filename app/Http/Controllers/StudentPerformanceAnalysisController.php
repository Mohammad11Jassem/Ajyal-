<?php

namespace App\Http\Controllers;

use App\Services\StudentPerformanceAnalysisService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class StudentPerformanceAnalysisController extends Controller
{
    protected $service;
    use HttpResponse;
    public function __construct(StudentPerformanceAnalysisService $service)
    {
        $this->service = $service;
    }

    public function claculateMeanForQuiz($curriculumId)
    {
        $studentID=auth()->user()->user_data['role_data']['id'];
        return $this->success('المتوسط الحسابي للاختبارات الإلكترونية لهذه المادة',$this->service->claculateMeanForQuiz($curriculumId,$studentID));
    }

    public function calculateStandardDeviationForQuiz($curriculumId)
    {
        $studentID=auth()->user()->user_data['role_data']['id'];
        return $this->success('الانحراف المعياري للاختبارات الإلكترونية لهذه المادة',$this->service->calculateStandardDeviationForQuiz($curriculumId,$studentID));
    }

    public function claculateMeanForPaperExam($curriculumId)
    {
        $studentID=auth()->user()->user_data['role_data']['id'];
        return $this->success('المتوسط الحسابي للاختبارات الكتابية لهذه المادة',$this->service->claculateMeanForPaperExam($curriculumId,$studentID));
    }

    public function calculateStandardDeviationForPaperExam($curriculumId)
    {
        $studentID=auth()->user()->user_data['role_data']['id'];
        return $this->success('الانحراف المعياري للاختبارات الكتابية لهذه المادة',$this->service->calculateStandardDeviationForPaperExam($curriculumId,$studentID));
    }

    public function calculateCombinedMean($curriculumId)
    {
        $studentID=auth()->user()->user_data['role_data']['id'];
        return $this->success('المتوسط الحسابي العام للمادة',$this->service->calculateCombinedMean($curriculumId,$studentID));
    }

    public function calculateCombinedStandardDeviation($curriculumId)
    {
        $studentID=auth()->user()->user_data['role_data']['id'];
        return $this->success('الانحراف المعياري العام للمادة',$this->service->calculateCombinedStandardDeviation($curriculumId,$studentID));
    }

    public function quizzes($curriculumId)
    {
        $studentID=auth()->user()->user_data['role_data']['id'];
        return $this->success('الامتحانات الكتابية والالترونية',$this->service->quizzes($curriculumId,$studentID));
    }

    public function calculateTotalMean($courseId)
    {
        $studentID=auth()->user()->user_data['role_data']['id'];
        return $this->success('المتوسط العام لكل الكورس ',$this->service->calculateTotalMean($courseId,$studentID));

    }
    public function calculateMean($curriculumId)
    {
        $studentID=auth()->user()->user_data['role_data']['id'];
        return $this->success('المتوسط للمادة ',$this->service->calculateMean($curriculumId,$studentID));

    }
    public function calculateStddev($curriculumId)
    {
         $studentID=auth()->user()->user_data['role_data']['id'];
        return $this->success('الانحراف المعياري للمادة',$this->service->calculateStddev($curriculumId,$studentID));

    }
    public function quizzesType($studentId,$curriculumId)
    {
        return $this->success('الامتحانات الكتابية والالترونية',$this->service->quizzesType($curriculumId,$studentId));

    }
    public function SubjectsMean($studentId,$courseId)
    {
        return $this->success('معدلات المواد',$this->service->SubjectsMean($studentId,$courseId));

    }

    public function calculateTotalMeanForParent($studentId,$courseId)
    {
        return $this->success('المتوسط العام لكل الكورس ',$this->service->calculateTotalMean($courseId,$studentId));

    }
}
