<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'balance' => $this->faker->randomFloat(3, 0, 1000),  // Balance aléatoire entre 0 et 1000 avec 2 décimales
            'role' => 'client',
        ];
    }
}
