<?php

namespace App\Services;

use App\Http\Resources\PaymentNotificationResource;
use App\Models\Absence;
use App\Models\Complaint;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\Note;
use App\Models\Notification;

class NotificationService
{
    public function store(array $data)
    {
        $Model=$data['model']??null;
        $notification=Notification::create([
            'user_id'=>$data['user_id'],
            'title'=>$data['title'],
            'body'=>$data['body'],
            'notifiable_type' => $Model ? get_class($Model) : null,
            'notifiable_id'=>$Model?->id??null
        ]);
        return [
            'success'=>true,
            'message'=>'نم إضافة الإشعار',
            'data'=>$notification
        ];
    }
    public function getInvoicesNotifications(){
        $notifications=Notification::where('user_id',auth()->id())->where('notifiable_type',Invoice::class)->get();
        return [
            'success'=>true,
            'message'=>'كل إشعارات الفواتير ',
            'data'=>$notifications
        ];
    }
    public function getAbscencesNotifications(){
        $notifications=Notification::where('user_id',auth()->id())->where('notifiable_type',Absence::class)->get();
        return [
            'success'=>true,
            'message'=>'كل إشعارات الغياب ',
            'data'=>$notifications
        ];
    }
    public function getNotesNotifications(){
        $notifications=Notification::where('user_id',auth()->id())->where('notifiable_type',Note::class)->get();
        return [
            'success'=>true,
            'message'=>'كل إشعارات الملاحظات ',
            'data'=>$notifications
        ];
    }
    public function getNotifications(){
        $notifications=Notification::where('user_id',auth()->id())->get();
        return [
            'success'=>true,
            'message'=>'كل الإشعارات  ',
            'data'=>$notifications
        ];
    }
    public function getComplaintsNotifications(){
        $notifications=Notification::where('user_id',auth()->id())->where('notifiable_type',Complaint::class)->get();
        return [
            'success'=>true,
            'message'=>'كل إشعارات الشكاوي  ',
            'data'=>$notifications
            ];
    }
    public function getPaymentNotifications(){

        $noti=Notification::where('user_id',auth()->id())
                            ->with('notifiable.registration.Student')
                            ->where('notifiable_type','App\Models\Payment')->get();

        return [
            'success'=>true,
            'message'=>'كل إشعارات الدفع  ',
            'data'=>PaymentNotificationResource::collection($noti)
        ];
    }
}




