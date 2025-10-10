<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,user',
        ]);

        $validated['unique_code'] = 'USR-' . strtoupper(Str::random(8));

        $user = $this->userService->register($validated);
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $user = $this->userService->findById($id);
        return response()->json($user);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->userService->deleteUser($id);
        return response()->json($result);
    }
}