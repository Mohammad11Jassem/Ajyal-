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
        return $this->success('المتوسط الحسابي للاختبارات الإلكترونية لهذه المادة',$this->service->claculateMeanForQuiz($curriculumId));
    }

    public function calculateStandardDeviationForQuiz($curriculumId)
    {
        return $this->success('الانحراف المعياري للاختبارات الإلكترونية لهذه المادة',$this->service->calculateStandardDeviationForQuiz($curriculumId));
    }

    public function claculateMeanForPaperExam($curriculumId)
    {
        return $this->success('المتوسط الحسابي للاختبارات الكتابية لهذه المادة',$this->service->claculateMeanForPaperExam($curriculumId));
    }

    public function calculateStandardDeviationForPaperExam($curriculumId)
    {
        return $this->success('الانحراف المعياري للاختبارات الكتابية لهذه المادة',$this->service->calculateStandardDeviationForPaperExam($curriculumId));
    }

    public function calculateCombinedMean($curriculumId)
    {
        return $this->success('المتوسط الحسابي العام للمادة',$this->service->calculateCombinedMean($curriculumId));
    }

    public function calculateCombinedStandardDeviation($curriculumId)
    {
        return $this->success('الانحراف المعياري العام للمادة',$this->service->calculateCombinedStandardDeviation($curriculumId));
    }

    public function quizzes($curriculumId)
    {
        return $this->success('الامتحانات الكتابية والالترونية',$this->service->quizzes($curriculumId));
    }

    public function calculateTotalMean($courseId)
    {
        return $this->success('المتوسط العام لكل الكورس ',$this->service->calculateTotalMean($courseId));

    }
    public function calculateMean($curriculumId)
    {
        return $this->success('المتوسط للمادة ',$this->service->calculateMean($curriculumId));

    }
    public function calculateStddev($curriculumId)
    {
        return $this->success('الانحراف المعياري للمادة',$this->service->calculateStddev($curriculumId));

    }
}
