<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Client;

class UserSeeder extends Seeder
{
    public function run()
    {
        Client::factory(10)->create();  // Crée 10 clients avec la balance initiale
    }
}
