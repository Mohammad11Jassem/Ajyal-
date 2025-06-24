<?php

namespace App\Http\Controllers;

use App\Http\Requests\Manager\ManagerLoginRequest;
use App\Services\Auth\ManagerAuthService;
use Illuminate\Http\JsonResponse;

class ManagerController extends Controller
{
    protected ManagerAuthService $authService;

    public function __construct(ManagerAuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle manager login
     *
     * @param ManagerLoginRequest $request
     * @return JsonResponse
     */
    public function login(ManagerLoginRequest $request): JsonResponse
    {
        $result = $this->authService->authenticate($request->validated());

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $result['token'],
            'role' => $result['role']
        ]);
    }

    /**
     * Handle manager logout
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get authenticated manager profile
     *
     * @return JsonResponse
     */
    public function profile(): JsonResponse
    {
        $result = $this->authService->getProfile();

        return response()->json($result['data']);
    }

    /**
     * Get manager dashboard data
     *
     * @return JsonResponse
     */
    public function dashboard(): JsonResponse
    {
        return response()->json([
            'message' => 'Dashboard data retrieved successfully',
            'data' => [
                // Add dashboard data here
            ]
        ]);
    }

    public function addTeacher(){

    }
}
