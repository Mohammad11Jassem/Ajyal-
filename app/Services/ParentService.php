<?php

namespace App\Services;

use App\Models\ParentModel;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ParentService
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }
    public function registerParent(array $data)
    {
        try {
             return DB::transaction(function () use ($data) {

                 $user = User::create([
                     'password' => bcrypt($data['password']),
                 ]);

                 $parent = ParentModel::create([
                     'user_id' => $user->id,
                     'name' => $data['name'],
                     'phone_number' => $data['phone_number'],
                 ]);

                 // link the parent with student
                 $this->studentService->linkStudent([
                     'student_id'=>$data['student_id_qr'],
                     'parent_id'=>$parent->id,
                 ]);


                 // Optional: create token
                 $token = $user->createToken('token')->plainTextToken;

                return [
                    'parent'=>$parent,
                    'token'=>$token
                ];
             });

        } catch (\Exception $e) {
            return new Exception($e->getMessage());
        }
    }

    public function loginParent(array $data)
    {
        $user = User::whereHas('parentModel', function ($query) use ($data) {
            $query->where('phone_number', $data['phone_number']);
        })->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
           return null;
        }

        $token = $user->createToken('parent-token')->plainTextToken;

        // $parent=[
        //     ...$user->user_data['role_data'],
        //     ...$token,
        // ];
        // return $parent;
        return [
            'parent' => $user->user_data['role_data'],
            'token' => $token,
        ];
    }
}
