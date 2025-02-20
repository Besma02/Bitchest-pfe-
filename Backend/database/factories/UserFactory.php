<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
<<<<<<< HEAD
=======
    protected $model = Client::class;

>>>>>>> bc8b66ebfbcf30c4d5128882abe73845ea7a2025
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
<<<<<<< HEAD
<<<<<<< HEAD
            'role' => $this->faker->randomElement(['client', 'admin']),
            'balance' => $this->faker->randomFloat(3, 0, 10000),
            'remember_token' => null,
=======
            'balance' => $this->faker->randomFloat(2, 0, 1000),  // Balance aléatoire entre 0 et 1000 avec 2 décimales
            'type' => 'client',
>>>>>>> bc8b66ebfbcf30c4d5128882abe73845ea7a2025
=======
            'balance' => $this->faker->randomFloat(3, 0, 1000),  // Balance aléatoire entre 0 et 1000 avec 2 décimales
            'role' => 'client',
>>>>>>> 60f904f3255718ad6d1e83ec566a300b62ba032c
        ];
    }
}
