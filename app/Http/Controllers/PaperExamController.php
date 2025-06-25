<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaperExam\StorePaperExamRequest;
use App\Services\PaperExamService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class PaperExamController extends Controller
{
    use HttpResponse;
    protected $paperExamService;

    public function __construct(PaperExamService $paperExamService)
    {
        $this->paperExamService = $paperExamService;
    }
    public function store(StorePaperExamRequest $storePaperExamRequest){
        $data=$storePaperExamRequest->validated();
        
        $exam= $this->paperExamService->store($data);
        return $this->success('تم تحميل الملف',$exam);
    }
}
