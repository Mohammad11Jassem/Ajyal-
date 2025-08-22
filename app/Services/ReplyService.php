<?php

namespace App\Services;

use App\Models\Issue;
use App\Models\Student;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\DB;

class ReplyService
{
    public function addReply(array $data){

        return DB::transaction(function () use ($data) {
            $issue=Issue::findorFail($data['issue_id']);
            $userId = auth()->user()->user_data['role_data']['id'];
            $userData=null;
            if(auth()->user()->hasRole('Teacher')){
                $userData=Teacher::find($userId);

            }
            if(auth()->user()->hasRole('Student')){
                $userData=Student::find($userId);
                if ($userData != $issue->author){
                     return throw new Exception('لا يمكنك إضافة إجابة ');
                }

            }

           $reply=$userData->replies()->create([
                    'issue_id'=>$data['issue_id'],
                    'body'=>$data['body'],
            ]);
            return $reply;
         });
    }
}
