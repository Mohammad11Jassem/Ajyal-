<?php

namespace App\Http\Controllers;

use App\Http\Requests\Issue\AddIssueRequest;
use App\Services\IssueService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    use HttpResponse;
    protected $issueService;

    public function __construct(IssueService $issueService)
    {
        $this->issueService = $issueService;
    }
    public function addIssue(AddIssueRequest $addIssueRequest)
    {
        $data=$addIssueRequest->validated();
        $issue=$this->issueService->addIssue($data);
        return $this->success('تم إضافة السؤال',$issue);
    }

    public function getNormalIssue($curriculumId)
    {
        return $this->success('الأسئلة العادية',$this->issueService->getNormalIssue($curriculumId));
    }
    public function getIsFqaIssue($curriculumId)
    {
        return $this->success('الأسئلة المكررة',$this->issueService->getIsFqaIssue($curriculumId));
    }

    public function changeIssueStatus($issueId)
    {
         return $this->success('تم تغيير حالة السؤال',$this->issueService->changeIssueStatus($issueId));
    }
    public function destroy($issueId)
    {
         return $this->success('تم حذف السؤال',$this->issueService->destroy($issueId));
    }
    public function getMyIssue($curriculumId)
    {
         return $this->success('أسئلتي',$this->issueService->getMyIssue($curriculumId));
    }
}
