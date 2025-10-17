<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function view()
    {
        return view('auth.login');
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'unique_code' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $result = $this->authService->login($validated);
            return response()->json($result);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Login gagal',
                'errors' => $e->errors(),
            ], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $result = $this->authService->logout($user);
        return response()->json($result);
    }
}