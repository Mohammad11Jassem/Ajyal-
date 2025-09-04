<?php

namespace App\Services;

use App\Models\Absence;
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
            'title'=>$data['title'],
            'body'=>$data['body'],
            'notifiable_type'=>$Model??null,
            'notifiable_id'=>$Model->id??null
        ]);
        return [
            'success'=>true,
            'message'=>'نم إضافة الإشعار',
            'data'=>$notification
        ];

        // $title=null;
        // $body=null;
        // switch($data['type'])
        // {
        //     case 'note_added':
        //         $title='ملاحظة على الطالب';
        //         $body=$data['content'];
        //         break;

        //     case 'invoice_due':
        //         $title='تأخر في دفع فاتورة';
        //         $course_name=Course::findOrFail($data['course_id']);
        //         $invoice=
        //         $body='يرجى تسديد فاتورة ';
        //         break;

        //     case 'attendance_alert':
        //         $title='تغيب عن الدوام';
        //         break;
        // }
    }
    public function getInvoicesNotifications(){
        $notifications=Notification::where('notifiable_type',Invoice::class)->get();
        return [
            'success'=>true,
            'message'=>'كل إشعارات الفواتير ',
            'data'=>$notifications
        ];
    }
    public function getAbscencesNotifications(){
        $notifications=Notification::where('notifiable_type',Absence::class)->get();
        return [
            'success'=>true,
            'message'=>'كل إشعارات الغياب ',
            'data'=>$notifications
        ];
    }
    public function getNotesNotifications(){
        $notifications=Notification::where('notifiable_type',Note::class)->get();
        return [
            'success'=>true,
            'message'=>'كل إشعارات الملاحظات ',
            'data'=>$notifications
        ];
    }
    public function getNotifications(){
        $notifications=Notification::paginate(10);
        return [
            'success'=>true,
            'message'=>'كل الإشعارات  ',
            'data'=>$notifications
        ];
    }
}




