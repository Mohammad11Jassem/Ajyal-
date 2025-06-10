<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Teacher\CreateTeacherRequest;
use App\Http\Requests\Teacher\TeacherLoginRequest;
use App\Http\Requests\Teacher\TeacherRegisterRequest;
use App\Services\TeacherService;
use Illuminate\Http\JsonResponse;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;

class TeacherController extends Controller
{
    protected TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'password' => 'required|string',
    //     ]);

    //     $user = User::where('password', $request->password)->first();

    //     if (! $user ) {
    //         return response()->json([
    //             'message' => 'Invalid credentials'
    //         ], 401);
    //     }

    //     // Optional: revoke old tokens
    //     $user->tokens()->delete();

    //     $token = $user->createToken('api_token')->plainTextToken;

    //     return response()->json([
    //         'user' => $user->user_data,
    //         'token' => $token,
    //     ]);
    // }

    /**
     * Create a new teacher
     *
     * @param CreateTeacherRequest $request
     * @return JsonResponse
     */
    public function store(CreateTeacherRequest $request): JsonResponse
    {
        $result = $this->teacherService->createTeacher($request->validated());

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error']
            ], 422);
        }

        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], 201);
    }

    public function register(TeacherRegisterRequest $teacherRegisterRequest):JsonResponse{
                $result=$this->teacherService->RegisterTeacher($teacherRegisterRequest->validated());
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
    public function login(TeacherLoginRequest $teacherLoginRequest):JsonResponse{

        $result=$this->teacherService->loginTeacher($teacherLoginRequest->validated());
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
    public function logout(): JsonResponse{

        $this->teacherService->logout();
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function profile(): JsonResponse
    {
        $result = $this->teacherService->getProfile();

        return response()->json($result['data']);
    }
}
