<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'balance' => $this->faker->randomFloat(2, 0),
            'profile_photo' => $this->faker->imageUrl(),
            'role' => 'client',
        ];
    }
}
