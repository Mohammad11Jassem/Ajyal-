<?php

namespace App\Services;

use App\Models\Issue;
use App\Models\Student;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\DB;

class IssueService
{

    public function addIssue(array $data)
    {
         return DB::transaction(function () use ($data) {

             $userId = auth()->user()->user_data['role_data']['id'];
             $userData=null;
             if(auth()->user()->hasRole('Teacher')){
                 $userData=Teacher::find($userId);
             }
             if(auth()->user()->hasRole('Student')){
                 $userData=Student::find($userId);
             }
             // $data['author_id'] = $user->id;
             // $data['author_type'] = get_class($user);
             // return $user;
             $issue=$userData->issues()->create([
                     'community_id' => $data['community_id'],
                     'body' => $data['body'],
                     'is_fqa' => $data['is_fqa']??0,
                 ]);

             return $issue;
         });
    }


    private function getIssue($communityId,$isFqa)
    {
         return DB::transaction(function () use ($communityId,$isFqa) {

             return Issue::where('is_fqa',$isFqa)->where('community_id',$communityId)->get();
         });
    }
    public function getNormalIssue($communityId)
    {
         return DB::transaction(function () use ($communityId) {

             return $this->getIssue($communityId,0);
         });
    }
    public function getIsFqaIssue($communityId)
    {
         return DB::transaction(function () use ($communityId) {

             return $this->getIssue($communityId,1);
         });
    }
    public function changeIssueStatus($communityId)
    {
         return DB::transaction(function () use ($communityId) {
            $issue=Issue::findorFail($communityId);
            $issue->is_fqa=!$issue->is_fqa;
            $issue->save();
             return $issue;
         });
    }
    public function destroy($communityId)
    {
         return DB::transaction(function () use ($communityId) {

             $userId = auth()->user()->user_data['role_data']['id'];
             $userData=null;
             if(auth()->user()->hasRole('Teacher')){
                 $userData=Teacher::find($userId);
             }
             if(auth()->user()->hasRole('Student')){
                 $userData=Student::find($userId);
             }

            $issue=Issue::findorFail($communityId);
            $userIssue=$issue->author;
            if ($userData == $userIssue)
             return $issue->delete();
            else
             return throw new Exception('لا يمكنك مسح السؤال');
         });
    }


}
