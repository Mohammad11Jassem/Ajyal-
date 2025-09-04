<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Notification;
use App\Services\NotificationService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use HttpResponse;
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function getNotesNotifications(){
        $result=$this->notificationService->getNotesNotifications();
        return $this->success($result['message'],$result['data']);
    }
    public function getAbscencesNotifications(){
        $result=$this->notificationService->getAbscencesNotifications();
        return $this->success($result['message'],$result['data']);
    }
    public function getInvoicesNotifications(){
        $result=$this->notificationService->getInvoicesNotifications();
        return $this->success($result['message'],$result['data']);
    }
    public function getNotifications(){
        $result=$this->notificationService->getNotifications();
        return $this->success($result['message'],$result['data']);
    }
    // public function add(){
    //     $note = Note::find(1);

    //     $data =[
    //         'title' => 'note_created',
    //         'model'=>$note,
    //         'body' => 'New note created'
    //     ];

    //     $result=$this->notificationService->store($data);
    //     return $this->success($result['message'],$result['data']);

    // }
}
