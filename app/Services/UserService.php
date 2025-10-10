<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
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
}