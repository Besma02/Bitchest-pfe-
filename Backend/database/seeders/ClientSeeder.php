<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'balance' => 10000.000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Client User',
                'email' => 'client@example.com',
                'password' => Hash::make('password123'),
                'role' => 'client',
                'balance' => 500.000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
