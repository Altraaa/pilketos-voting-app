<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $credentials)
    {
        $user = User::where('unique_code', $credentials['unique_code'])->first();

        if (!$user || $credentials['password'] !== $user->password) {
            throw ValidationException::withMessages([
                'unique_code' => ['Kode unik atau password salah.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'message' => 'Login berhasil',
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout($user)
    {
        $user->tokens()->delete();
        return ['message' => 'Logout berhasil'];
    }
}