<?php

namespace App\Services\Auth;

use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManagerAuthService
{
    /**
     * Attempt to authenticate a manager
     *
     * @param array $credentials
     * @return array
     */
    public function authenticate(array $credentials): array
    {
        $manager = Manager::where('email', $credentials['email'])->first();
        $user=User::where('id',$manager['user_id'])->first();
        if (!$manager || !Hash::check($credentials['password'], $manager->user->password)) {
            return [
                'success' => false,
                'message' => 'فشل تسجيل الدخول '
            ];
        }



        // $token = $manager->createToken('manager-token')->plainTextToken;
        $token = $manager->user->createToken('manager-token')->plainTextToken;
        $user->fcm_token=$data['fcm_token']??null;
        $user->save();

        return [
            'success' => true,
            'data'=>[
                'token' => $token,
                'role' =>$manager->user->getRoleNames(),
            ]

        ];
    }

    /**
     * Logout the currently authenticated manager
     * Revokes all tokens for the current manager
     *
     * @return void
     */
    public function logout(): void
    {
        // // Get the currently authenticated manager
        // if ($manager = Auth::user()) {
        //     // Revoke all tokens for this manager
        //     $manager->tokens()->delete();
        // }
        // Get the currently authenticated user
        if ($user = Auth::user()) {
            // Revoke all tokens for this user
            $user->tokens()->delete();
        }
    }

    /**
     * Get the profile of the currently authenticated manager
     *
     * @return array
     */
    public function getProfile(): array
    {
        // $user = Auth::user();
        // $manager = Manager::where('user_id', $user->id)->first();

        // // Get roles and permissions directly from the manager model
        // return [
        //     'success' => true,
        //     'data' => [
        //         'manager' => [
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
