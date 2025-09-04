<?php

namespace App\Services;

use App\Http\Resources\IssueResource;
use App\Http\Resources\StudentIssueRescource;
use App\Http\Resources\TeacherIssueRescource;
use App\Models\Curriculum;
use App\Models\Issue;
use App\Models\Student;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class IssueService
{

    public function saveIssueImage(UploadedFile $imageFile, $relatedModel, string $folder = 'questions')
    {
        // Create a temporary image record
        $image = $relatedModel->image()->create([
            'path' => '' // Temporary, updated after file move
        ]);

        $imageName = time() . $image->id . '.' . $imageFile->getClientOriginalExtension();
        $imageFile->move(public_path($folder), $imageName);

        $imagePath = $folder . '/' . $imageName;
        $image->path = $imagePath;
        $image->save();

    }
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
             $curriculum=Curriculum::where('id',$data['curriculum_id'])->first();
             $communityId=$curriculum->community['id'];
             $issue=$userData->issues()->create([
                     'community_id' => $communityId,
                     'body' => $data['body'],
                     'is_fqa' => $data['is_fqa']??0,
                 ]);
            if (isset($data['image'])) {
                $this->saveIssueImage($data['image'],$issue,'issues');
            }
             return $issue->load('image');
         });
    }


    private function getIssue($curriculumId,$isFqa)
    {
         return DB::transaction(function () use ($curriculumId,$isFqa) {
            $curriculum=Curriculum::where('id',$curriculumId)->first();
            $communityId=$curriculum->community['id'];

            if(auth()->user()->hasRole('Student')){

                $issues=Issue::where('is_fqa', $isFqa)
                            ->where('community_id', $communityId)
                            ->where('author_id', '!=', auth()->user()->user_data['role_data']['id'])
                            // ->with(['image','author:id,user_id,first_name,last_name'])
                            ->get();
                return IssueResource::collection($issues);
             }
            $issues= Issue::where('is_fqa', $isFqa)
                        ->where('community_id', $communityId)
                        ->with('image')
                        ->get();
            return IssueResource::collection($issues);
         });
    }
    public function getNormalIssue($curriculumId)
    {
         return DB::transaction(function () use ($curriculumId) {

             return $this->getIssue($curriculumId,0);
         });
    }
    public function getIsFqaIssue($curriculumId)
    {
            return DB::transaction(function () use ($curriculumId) {

                // return $this->getIssue($curriculumId,1);
                $issues= Issue::where('is_fqa', 1)
                            ->where('community_id', $curriculumId)
                            // ->where('author_id', '!=', auth()->user()->user_data['role_data']['id'])
                            // ->where('author_type',Teacher::class)
                            // ->with(['image','author'])
                            ->get();
                return IssueResource::collection($issues);
            });
    }
    public function getMyIssue($curriculumId)
    {
         return DB::transaction(function () use ($curriculumId) {
            $curriculum=Curriculum::where('id',$curriculumId)->first();
            $communityId=$curriculum->community['id'];
             $issues =Issue::where('is_fqa', 0)
                            ->where('community_id', $communityId)
                            ->where('author_id', auth()->user()->user_data['role_data']['id'])
                            ->where('author_type',Student::class)
                            ->with('image')
                            ->get();
            return StudentIssueRescource::collection($issues);
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
    public function destroy($issueId)
    {
         return DB::transaction(function () use ($issueId) {
            // $curriculum=Curriculum::where('id',$curriculumId)->first();
            // $communityId=$curriculum->community['id'];

             $userId = auth()->user()->user_data['role_data']['id'];
             $userData=null;
            $issue=Issue::findorFail($issueId);
             if(auth()->user()->hasRole('Teacher')){
                 $userData=Teacher::find($userId);
             }
             if(auth()->user()->hasRole('Student')){
                 $userData=Student::find($userId);
                  $userIssue=$issue->author;
                 if ($userData != $userIssue){
                     return throw new Exception('لا يمكنك مسح السؤال');
                 }
             }


            // $userIssue=$issue->author;
            // if ($userData == $userIssue){
                if ($issue->image) {
                    File::delete($issue->image->path);
                    $issue->image()->delete();
                }
                return $issue->delete();

            // }
            // else
            //      return throw new Exception('لا يمكنك مسح السؤال');
         });
    }


}
