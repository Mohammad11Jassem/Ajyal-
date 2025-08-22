<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reply\AddReplyRequest;
use App\Services\ReplyService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    use HttpResponse;

    protected $replyService;

    public function __construct(ReplyService $replyService)
    {
        $this->replyService = $replyService;
    }
    public function addReply(AddReplyRequest $addReplyRequest){
        $data=$addReplyRequest->validated();
        return $this->success('تم إضافة الرد',$this->replyService->addReply($data));
    }
}
