<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function register(array $data)
    {
        return User::create($data);
    }

    public function findById(int $id)
    {
        return User::findOrFail($id);
    }

    public function markAsVoted(int $id)
    {
        $user = User::findOrFail($id);
        $user->update(['has_voted' => true]);
        return $user;
    }

    public function getAllUsers()
    {
        return User::all();
    }

    public function deleteUser(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return ['message' => 'User deleted successfully'];
    }

       public function generateUsers(int $count)
    {
        $users = [];
        
        for ($i = 0; $i < $count; $i++) {
            $uniqueCode = 'USR-' . strtoupper(Str::random(8));
            $password = $this->generatePassword();
            
            $user = User::create([
                'name' => 'User ' . strtoupper(Str::random(6)),
                'unique_code' => $uniqueCode,
                'password' => $password,
                'role' => 'user',
                'has_voted' => false,
            ]);

            $users[] = $user;
        }

        return $users;
    }

    private function generatePassword(int $length = 8)
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $password;
    }

    public function deleteMultipleUsers(array $ids)
    {
        User::whereIn('id', $ids)->delete();
        return ['message' => count($ids) . ' users deleted successfully'];
    }
}