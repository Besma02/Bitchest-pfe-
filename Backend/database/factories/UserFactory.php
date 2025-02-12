<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role' => $this->faker->randomElement(['client', 'admin']),
            'balance' => $this->faker->randomFloat(3, 0, 10000), // or 2 if you prefer
            'remember_token' => null,
        ];
    }
}
