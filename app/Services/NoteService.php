<?php

namespace App\Services;

use App\Models\Note;
use Exception;

class NoteService
{
    public function storeNote(array $data){
        try{
        $note=Note::create($data);
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
