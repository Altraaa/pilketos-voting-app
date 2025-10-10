<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin OSIS',
            'unique_code' => 'ADM-' . strtoupper(Str::random(6)),
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'has_voted' => false,
        ]);
    }
}
