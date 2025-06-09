<?php

namespace App\Http\Controllers;

use App\Http\Requests\Parent\LoginParentRequest;
use App\Http\Requests\Parent\RegisterParentRequest;
use App\Services\ParentService;
use App\Traits\HttpResponse;
use Exception;
use Illuminate\Http\Request;

class ParentModelController extends Controller
{
    use HttpResponse;
    protected $parentService;

    public function __construct(ParentService $parentService)
    {
        $this->parentService = $parentService;
    }

    public function registerParent(RegisterParentRequest $request)
    {
        try{
        $data = $request->validated();
        $parentData=$this->parentService->registerParent($data);
        if(!$parentData['success']){
            return $this->badRequest('تم ربط الطالب سابقاً');
        }
        return $this->success('Parent registered and linked to student',$parentData);
        }catch(Exception $e){
            $this->error('Registration failed',$e->getMessage());
        }

    }

      public function loginParent(LoginParentRequest $loginParentRequest)
      {
        $data=$loginParentRequest->validated();
        $parent=$this->parentService->loginParent($data);
        if(!$parent){
            return $this->badRequest('Invalid phone number or password');
        }
        return $this->success('Login successfully',$parent); // edit the response
      }

      public function profile(){
        return auth()->user()->user_data;
      }
}
