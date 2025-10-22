<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

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
        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,user',
        ]);

        $validated['unique_code'] = 'USR-' . strtoupper(Str::random(8));
        $validated['has_voted'] = false;

        $user = $this->userService->register($validated);
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    public function generateBulk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'count' => 'required|integer|min:1|max:500',
        ]);

        $users = $this->userService->generateUsers($validated['count']);
        
        return response()->json([
            'success' => true,
            'message' => $validated['count'] . ' users generated successfully',
            'data' => $users,
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $user = $this->userService->findById($id);
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->userService->deleteUser($id);
        return response()->json([
            'success' => true,
            'message' => $result['message']
        ]);
    }

    public function destroyMultiple(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:users,id',
        ]);

        $result = $this->userService->deleteMultipleUsers($validated['ids']);
        
        return response()->json([
            'success' => true,
            'message' => $result['message']
        ]);
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'user_credentials.xlsx');
    }
}