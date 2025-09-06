<?php

namespace App\Services;

use App\Jobs\SendNotificationJob;
use App\Models\Note;
use App\Models\Student;
use App\Models\User;
use Exception;

class NoteService
{
    public function storeNote(array $data){
        try{
        $note=Note::create($data);
        $student = Student::with(['parents.user'])->findOrFail(1);

        // جلب المستخدمين المرتبطين بأولياء الأمور فقط
        $users = collect();

        if ($student->parents) {
            $student->parents->each(function ($parent) use ($users) {
                if ($parent->user) {
                    $users->push($parent->user);
                }
            });
        }

        // إزالة أي تكرار
        $users = $users->unique('id');

        // رسالة الإشعار
        $message = [
            'title' => 'تمت إضافة ملاحظة تخص الطالب ' . $student->full_name,
            'body'  => $note['content'],
        ];

        // إرسال الإشعار
        SendNotificationJob::dispatch($message, $users,$note);
        return [
            'success'=>true,
            'message'=>'تم إرسال الملاحظة'
        ];
        }catch(Exception $e){

            return [
                'success'=>false,
                'message'=>'فشل إرسال الملاحظة'
            ];
        }

    }

    public function studentNotes($studentId){
        $notes=Note::where('student_id',$studentId)->orderByDesc('noted_at')->get();
        return [
            'success'=>true,
            'message'=>'كل ملاحظات هذا الطالب',
            'data'=>$notes
        ];
    }

    public function allNotes(){
        $notes=Note::orderByDesc('noted_at')->get();
        return [
            'success'=>true,
            'message'=>'كل الملاحظات',
            'data'=>$notes
        ];
    }
    public function show($id){
        $notes=Note::findOrFail($id);
        return [
            'success'=>true,
            'message'=>'تفاصيل الملاحظة',
            'data'=>$notes
        ];
    }


}
