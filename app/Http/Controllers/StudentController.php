<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\LinkStudentRequest;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\StudentByCodeAndNameRequest;
use App\Http\Requests\Student\StudentLoginRequest;
use App\Http\Requests\Student\StudentRegisterRequest;
use App\Http\Resources\StudentResource;
use App\Models\ParentModel;
use App\Models\ParentStudent;
use App\Models\Student;
use App\Models\User;
use App\Services\StudentService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentController extends Controller
{
    use HttpResponse;
     protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;

    }
    public function getStudentQr()
    {
        $student = Student::findOrFail(1);
        // return $student;
        $token = encrypt($student->id); // secure token
        $data = ['token' => $token];
        // Generate base64 PNG
        $qrCode = QrCode::format('svg')->size(300)->generate(json_encode($token));
        $base64 = base64_encode($qrCode);

        return view('welcome',compact('base64'));
        // return response()->json([
        //     'qr_code_base64' => $qrCode,
        //     'token' => $token // (optional, for testing)
        // ]);
    }

    public function linkStudent2(Request $request)
    {
        $request->validate([ // student_id
            'token' => 'required|string',
        ]);

        try {
            $studentId = decrypt($request->token);
            $student = Student::findOrFail($studentId);

            $p=ParentModel::where('id',1)->first();

            // $parent = auth()->user()->parentModel;
            $parent = $p->user;

            if (!$parent) {
                return response()->json(['message' => 'يمكن للوالدين فقط الربط مع الطلاب'], 403);
            }

            // Avoid duplicate entries
            // $parent->students()->syncWithoutDetaching([$student->id]);
            ParentStudent::create([
                'student_id'=>$studentId,
                'parent_model_id'=>$p->id
            ]);


            return response()->json([
                'message' => 'تم الربط بنجاح ',
                'student' => $student,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();

        // return $data;

        $student = $this->studentService->createStudent($data);

        return response()->json([
            'message' => 'تم إنشاء حساب الطالب بنجاح',
            'data' => $student,
        ]);
    }

    public function getStudentByCodeAndName(StudentByCodeAndNameRequest $studentByCodeAndNameRequest){
        $data= $studentByCodeAndNameRequest->validated();
        $student=$this->studentService->getStudentByCodeAndName($data);
        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
            ], 404);
        }
        return response()->json([
            'message' => 'تم إيجاد الطالب',
            'data' => new StudentResource($student),
        ]);
    }

    public function register(StudentRegisterRequest $studentRegisterRequest)
    {
        $data=$studentRegisterRequest->validated();
        $student=$this->studentService->register($data);
        if(!$student){
            return $this->badRequest('فشل التسجيل حاول مرة أخرى');
        }

       return $this->success('تم التسجيل بنجاح',$student);
    }

    public function login(StudentLoginRequest $studentLoginRequest)
    {
        $data=$studentLoginRequest->validated();
        $student=$this->studentService->login($data);
        if(!$student){
           return $this->badRequest('فشل تسجيل الدخول');
        }
        return $this->success('تم تسجيل الدخول',$student);
    }

    public function logout()
    {
         Auth::user()->tokens()->delete();

        return $this->success('تم تسجيل الخروج ');

    }



    public function profile()
    {
        $data=$this->studentService->profile();
        if(!$data){
            return $this->badRequest('Error',$data);
        }
        return $this->success("profile data",$data);
    }

    public function linkStudent(LinkStudentRequest $linkStudentRequest)
    {

        try {
            $data=$linkStudentRequest->validated();
            $data['parent_id']=auth()->user()->user_data['role_data']['id'];
            // $data['parent_id']=1;
            $linkData=$this->studentService->linkStudent($data);
            if(!$linkData['success']){
                return $this->badRequest('تم ربط الطالب سابقاً');
            }
            return $this->success('Link Successfully',$linkData['student']);
        } catch (\Exception $e) {
            return $this->badRequest($e->getMessage());
        }
    }
    public function getAllStudent(){
        $result=$this->studentService->allStudent();
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                // 'error' => $result['error']
            ], 422);
        }
        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], 201);
    }



}
