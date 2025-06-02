<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function login(Request $request)
{
    $request->validate([
        'password' => 'required|string',
    ]);

    $user = User::where('password', $request->password)->first();

    if (! $user ) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    // Optional: revoke old tokens
    $user->tokens()->delete();

    $token = $user->createToken('api_token')->plainTextToken;

    return response()->json([
        'user' => $user->user_data,
        'token' => $token,
    ]);
}
}
