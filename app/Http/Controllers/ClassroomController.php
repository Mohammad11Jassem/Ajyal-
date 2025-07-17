<?php

namespace App\Http\Controllers;

use App\Services\ClassroomService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    use HttpResponse;
     protected $classroomService;

    public function __construct(ClassroomService $classroomService)
    {
        $this->classroomService = $classroomService;
    }

    public function getClasses(){
        $classRooms=$this->classroomService->getClasses();
        return $this->success('الصفوف',$classRooms);
    }
}
