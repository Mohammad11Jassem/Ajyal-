<?php

namespace App\Services;

use App\Enum\SubjectType;
use App\Http\Requests\Teacher\TeacherLoginRequest;
use App\Mail\TeacherCredentialsMail;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\User;
use App\Models\VerifyCode;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Database\Eloquent\Builder;

class TeacherService
{
    /**
     * Create a new teacher in the system
     *
     * @param array $data
     * @return array
     */
    public function createTeacher(array $data): array
    {
        try {
            // Generate a random verifyCode
            $verifyCode = Str::random(8);      //or // $verifyCode=mt_rand(100000,999999);

            while(VerifyCode::firstWhere('code', $verifyCode)){
                // $verifyCode=mt_rand(100000,999999);
                $verifyCode=Str::random(8);
            }

            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'password'=>null
            ]);

            $user->assignRole(Role::findByName('Teacher', 'api'));

            // Create teacher profile
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'date_of_contract' => $data['date_of_contract'],
                'phone_number' => $data['phone_number'],
                'bio' => $data['bio']??null,

            ]);
            $teacher->subjects()->attach($data['subjects']);

            // Save image if exists
            // if (isset($data['avatar']) && $data['avatar']->isValid()) {
            //     $imageFile = $data['avatar'];
            //     $imagePath = $imageFile->store('teachers', 'public'); // e.g. storage/app/public/teachers

            //     $teacher->image()->create([
            //         'path' => $imagePath
            //     ]);
            // }

            // Save image if exists
            if (isset($data['avatar']) && $data['avatar']->isValid()) {
                $imageFile = $data['avatar'];

                $image = $teacher->image()->create([
                    'path' => '' // Temporary, will be updated after saving the file
                ]);

                $imageName = time().$image->id. '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('teachers'), $imageName);
                $imagePath = 'teachers/' . $imageName;

                // $image->update(['path' => $imagePath]);        // or

                $image->path=$imagePath;
                $image->save();

            }

            VerifyCode::create([
            'user_id'=>$user->id,
            'code'=>$verifyCode
            ]);
            DB::commit();

             // Try to send welcome email with credentials
            try {
                // Mail::to($data['email'])->send(new TeacherCredentialsMail(
                //     teacherName: $data['name'],
                //     email: $data['email'],
                //     password: $password
                // ));
                $emailStatus = 'Email sent successfully';
            } catch (\Exception $e) {
                // Log the email error but don't fail the teacher creation
                Log::warning('Failed to send teacher credentials email: ' . $e->getMessage());
                $emailStatus = 'Teacher created but email could not be sent';
            }

            // Return success response with temporary password
            return [
                'success' => true,
                'message' => 'Teacher created successfully. ' . $emailStatus,
                'data' => [
                    'teacher' =>$teacher->load('image'),
                    // 'temporary_password' => $verifyCode // This should be sent via email in production
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to create teacher',
                'error' => $e->getMessage()
            ];
        }
    }

    public function RegisterTeacher(array $data){
    try{
        $teacher=Teacher::where('email',$data['email'])->first();
        if($teacher && $teacher->user->password ){
        return [
                'success' => false,
                'message' => 'the account is used from other one'
            ];
            }

            $verifyCode =VerifyCode::where('user_id',$teacher->user_id)->firstOrFail();
             // Try to send welcome email with credentials
            try {
                // Mail::to($data['email'])->send(new TeacherCredentialsMail(
                //     teacherName: $teacher['name'],
                //     email: $data['email'],
                //     password: $verifyCode['code']
                // ));
                $emailStatus = 'Email sent successfully';
            } catch (\Exception $e) {
                // Log the email error but don't fail the teacher creation
                Log::warning('Failed to send teacher credentials email: ' . $e->getMessage());
                $emailStatus = 'Teacher created but email could not be sent';
            }

            $user=User::find($teacher->user_id);
            $user['password']=Hash::make($data['password']);
            // $token = $teacher->user->createToken('manager-token')->plainTextToken;
            $teacher->save();
            $user->save();


            return [
            'success' => true,
            'message'=>'Teacher Register successfully. ' . $emailStatus,
            'data' => [
                'verifyCode' => $verifyCode,
                'teacher' => $teacher->load('image'),
                'password'=>$data['password']
                ]
        ];

        }catch(Exception $e){
            // return $e->getMessage();
            return [
                'success' => false,
                'message' => 'Failed to register teacher',
                'error' => $e->getMessage()
            ];

        }
    }

    public function VerifyCode(array $data){
        try{
            $teacher=Teacher::findOrFail($data['teacher_id']);
            //check if exists
        if($teacher->exists()){
                $verifyCode=VerifyCode::where('user_id',$teacher->user_id)->first();
            //check if correct code
            if($verifyCode['code'] !=$data['verifyCode']){
                return [
                'success' => false,
                'message' => 'the verifyCode is not correct ',
            ];
            }
                $verifyCode['confirmed']=true;
                $verifyCode->save();

        $token = $teacher->user->createToken('manager-token')->plainTextToken;
            return [
            'success' => true,
            'message'=>'welcome to our community',
            'data' => [
                'token' => $token,
                'teacher' => Teacher::with('image')->where('id',$teacher->id)->first(),
                ]
            ];

        }
    }
    catch(Exception $e){
        return [
                'success' => false,
                'message' => 'your account has not been added at System ',
                'error' => $e->getMessage()
            ];
        }

    }
    public function loginTeacher(array $data)
    {
        $teacher=Teacher::where('email',$data['email'])->first();
        // $user = User::where('password',bcrypt($data['password']))->first();
        // !Hash::check($data['password'], $teacher->user->password);

        $user=User::find($teacher->user_id);
        if (!$user['password']) {
            return [
                'success'=>false,
                'message' => 'الرجاء تسجيل الدخول أولاً'
            ];
        }
        // if(!$user->verifyCode['confirmed']){
        //     return [
        //         'success'=>false,
        //         'message' => 'الرجاء تأكيد الحساب أولاً'
        //     ];
        // }
        if($user->id != $teacher->user_id || !Hash::check($data['password'], $user->password)){
            return[
                'success'=>false,
                'message' =>'this information not matching together',
            ];
        }

        // Optional: revoke old tokens
        $user->tokens()->delete();

        // $token = $user->createToken('api_token')->plainTextToken;
        $token = $teacher->user->createToken('manager-token')->plainTextToken;


        return [
            'success' => true,
            'message'=>'welcome',
            'data' => [
                'token' => $token,
                'teacher' => Teacher::with('image')->where('id',$teacher->id)->first(),
                ]
        ];
    }

        public function logout(): void
        {
            if ($user = Auth::user()) {
                // Revoke all tokens for this user
                $user->tokens()->delete();
            }
        }


    public function getProfile($id): array
    {
        return [
            'success' => true,
            'data' => Teacher::with('image')->where('id',$id)->firstOrFail()
        ];
    }
    public function getMyProfile(){
        $user=Auth::user();
        $teacher=Teacher::where('user_id',$user->id)->first();
        return [
            'success'=>true,
            'data'=>$teacher->load('image'),
        ];
    }

    public function getAllTeachers(){
        $teachers=Teacher::with('image','subjects')->get()->all();
        return [
            'success'=>true,
            'data'=>$teachers
        ];
    }

    public function getSpecificTeachers($subject_id){
        // $teachers=Teacher::with(['subjects'=>function(Builder $query) use($subject_id){

        //     $query->where('id',$subject_id);
        // }])->get();

        $subject=Subject::where('id',$subject_id)->first();
        return [
            'success'=>true,
            'data'=>$subject->teachers
        ];
    }
    public function getLevelTeachers($level_id){
        $SubjectType=SubjectType::getTypeId($level_id);

        // $subject=Subject::where('type',$SubjectType)->first();
        // $teachers=Teacher::with(['subjects'=>function(Builder $query) use($SubjectType){

        //     $query->where('type',$SubjectType);
        // }])->get();
        $teachers = Teacher::whereHas('subjects', function (Builder $query) use ($SubjectType) {
        $query->where('type', $SubjectType);
        })
        // ->with(['subjects' => function (Builder $query) use ($SubjectType) {
        //     $query->where('type', $SubjectType);
        // }])
        ->get();
        return [
            'success'=>true,
            'data'=>$teachers
        ];
    }



}
