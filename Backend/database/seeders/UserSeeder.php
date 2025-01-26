<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
       
        // Créer plusieurs clients
        User::factory()->count(10)->create([
            'role' => 'client', 
        ]);
    }
}
