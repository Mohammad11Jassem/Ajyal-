<?php

namespace App\Services;

use App\Http\Requests\Teacher\TeacherLoginRequest;
use App\Mail\TeacherCredentialsMail;
use App\Models\Teacher;
use App\Models\User;
use App\Models\VerifyCode;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

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
            // Generate a random password
            $password = Str::random(8);

            // $password=mt_rand(100000,999999);

        while(VerifyCode::firstWhere('code', $password)){
            // $password=mt_rand(100000,999999);
            $password=Str::random(8);
        }

            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'password' =>" "
            ]);


            $user->assignRole(Role::findByName('Teacher', 'api'));

            // Create teacher profile
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'email' => $data['email'],

            ]);

            VerifyCode::create([
            'user_id'=>$user->id,
            'code'=>$password
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
                    'teacher' => $teacher,
                    'temporary_password' => $password // This should be sent via email in production
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
      if($teacher && !$teacher->user ){
        return [
                'success' => false,
                'message' => 'THE ACCOUNT IS USED FROM OTHER ONE'
            ];
            }
            // return[
            //      'success' => false,
            //     'message'=>$teacher->user->verfiyCode
            // ];
            if($teacher->user->verfiyCode['code'] !=$data['verifyCode']){
              return [
                'success' => false,
                'message' => 'the verify Code Not correct'
            ];
            }

            $user=User::find($teacher->user_id);
            $user['password']=Hash::make($data['password']);
            $token = $teacher->user->createToken('manager-token')->plainTextToken;
            $teacher->save();
            $user->save();


            return [
            'success' => true,
            'message'=>'welcome to our community',
            'data' => [
                'token' => $token,
                'teacher' => Teacher::where('id',$teacher->id)->first(),
                'password'=>$data['password']
                ]
        ];

        }
        catch(Exception $e){
            // return $e->getMessage();
            return [
                'success' => false,
                'message' => 'Failed to register teacher',
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
        if (!$user) {
            return [
                'success'=>false,
                'message' => 'please register first'
            ];
        }
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
                'teacher' => Teacher::where('id',$teacher->id)->first(),
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


    public function getProfile(): array
    {
        // $user = Auth::user();
        // $manager = Teacher::where('user_id', $user->id)->first();

        // // Get roles and permissions directly from the manager model
        // return [
        //     'success' => true,
        //     'data' => [
        //         'teacher' => [
        //             'id' => $manager->id,
        //             'email' => $manager->email,
        //             'roles' => $user->getRoleNames(),
        //             // 'permissions' => $user->getAllPermissions()->pluck('name'),
        //         ]
        //     ]
        // ];
        $user = Auth::user()->user_data;
         return [
            'success' => true,
            'data' => $user
        ];
    }




}
