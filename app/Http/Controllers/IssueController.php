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

    public function getNormalIssue($communityId)
    {
        return $this->success('الأسئلة العادية',$this->issueService->getNormalIssue($communityId));
    }
    public function getIsFqaIssue($communityId)
    {
        return $this->success('الأسئلة المكررة',$this->issueService->getIsFqaIssue($communityId));
    }

    public function changeIssueStatus($communityId)
    {
         return $this->success('تم تغيير حالة السؤال',$this->issueService->changeIssueStatus($communityId));
    }
    public function destroy($communityId)
    {
         return $this->success('تم حذف السؤال',$this->issueService->destroy($communityId));
    }
    public function getMyIssue($communityId)
    {
         return $this->success('تم حذف السؤال',$this->issueService->destroy($communityId));
    }
}
