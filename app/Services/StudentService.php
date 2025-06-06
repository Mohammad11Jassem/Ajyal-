<?php

namespace App\Services;

use App\Interfaces\StudentInterface;
use App\Models\ParentModel;
use App\Models\ParentStudent;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentService
{
    protected $studentRepo;

    public function __construct(StudentInterface $studentRepo)
    {
        $this->studentRepo = $studentRepo;
    }

    public function createStudent(array $data)
    {
        // $user=User::create();
        // $data['user_id'] = $user->id;
        // return $this->studentRepo->store($data);

        return DB::transaction(function () use ($data) {
            $user = User::create([
            'password' => null,
             ]);
            $data['access_code']=$this->generateUniqueVerifyCode();
            $data['user_id'] = $user->id;
            //  return $data;
            // throw new Exception();
            return $this->studentRepo->store($data);
        });
    }

    function generateUniqueVerifyCode(int $length = 8): string
    {
        do {
            $code = Str::upper(Str::random($length));
        } while (Student::select('access_code')->where('access_code', $code)->exists());

        return $code;
    }
    public function getStudentByCodeAndName(array $data){

        $student=$this->studentRepo->getStudentByCodeAndName($data);
        if(!$student){
            return null;
        }
        return $student;
    }

    public function register(array $data){
         return DB::transaction(function () use ($data) {

             $student = User::where('id', $data['user_id'])
                            ->where('password',null)
                          ->whereHas('student', function ($query) use ($data) {
                              $query->where('access_code', $data['access_code']);
                          })
                          ->first();

            if(!$student){
                return null;
            }
            $student->password=$data['password'];
            $student->save();
            $token = $student->createToken('token')->plainTextToken;
            return [
                'student'=>$student->user_data['role_data'],
                'token'=>$token
            ];
         });

    }


    public function login(array $data)
    {
        try{
            $student = Student::where('access_code', $data['access_code'])->first();
            $user=User::where('id',$student['user_id'])->first();

            if (!$student || !Hash::check($data['password'], $user->password)) {
                return null;
            }

             // Optional: revoke old tokens
            // $user->tokens()->delete();
            $token = $user->createToken('token')->plainTextToken;

            return [
                'student' => $student,
                'token' => $token
            ];
        }catch(Exception $e){
            return null;
        }
    }

    private function getStudentQr()
    {
        try{

            $student = Student::where('user_id',auth()->id())->first();

            // return $student;
            $token = encrypt($student->id); // secure token
            $data = ['token' => $token];
            // Generate base64 PNG
            $qrCode = QrCode::format('svg')->size(300)->generate(json_encode($data));
            $base64 = base64_encode($qrCode);

            return[
                'student_id'=>$token,
                'svgImage'=>$base64
            ];
        }catch(Exception $e){
            return null;
        }
    }

    public function profile()
    {
        $qr=$this->getStudentQr();
        $data=[
            ...auth()->user()->user_data['role_data'],
            // 'student_id'=>$qr['token'],
            ...$qr,
        ];

        return $data;
    }

    public function linkStudent(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $studentId = decrypt($data['student_id']);
                $student = Student::findOrFail($studentId);

                // get the auth parent
                $p=ParentModel::where('user_id',$data['parent_id'])->first();

                // $parent = auth()->user()->parentModel;
                $parent = $p->user;

                if (!$parent) {
                    return response()->json(['message' => 'Only parents can link to students.'], 403);
                }

                // Avoid duplicate entries
                // $parent->students()->syncWithoutDetaching([$student->id]);
                $parentStudent= ParentStudent::create([
                    'student_id'=>$studentId,
                    'parent_model_id'=>$data['parent_id']
                ]);
                // dd($data['parent_id']);


                return $student;

            });
        } catch (\Exception $e) {
            return new Exception($e->getMessage());
        }
    }

}
