<?php

namespace App\Http\Controllers;

use App\Http\Requests\Parent\LoginParentRequest;
use App\Http\Requests\Parent\RegisterParentRequest;
use App\Services\ParentService;
use App\Traits\HttpResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

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
        // dd($parentData);
        // return $parentData;
        if(!$parentData['success']){
            return $this->badRequest('تم ربط الطالب سابقاً');
        }
        // return $parentData;
        return $this->success('تم ربط الوالد مع الطالب وتسجيله',Arr::except($parentData,['success']));
        }catch(Exception $e){
            $this->error('فشل التسجيل',$e->getMessage());
        }

    }

      public function loginParent(LoginParentRequest $loginParentRequest)
      {
        $data=$loginParentRequest->validated();
        $parent=$this->parentService->loginParent($data);
        if(!$parent){
            return $this->badRequest('رقم الهاتف أو كلمة المرور غير صالحة');
        }
        return $this->success('تم تسجيل الدخول بنجاح',$parent); // edit the response
      }

      public function profile(){
        return auth()->user()->user_data;
      }

    public function parentStudent(){
        $studnets=$this->parentService->parentStudent();
        return $this->success('طلابي',$studnets);
    }

    public function logout()
    {
         Auth::user()->tokens()->delete();

        return $this->success('تم تسجيل الخروج ');

    }
}
