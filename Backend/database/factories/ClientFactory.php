<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),  // Assurez-vous de sécuriser le mot de passe
            'balance' => $this->faker->randomFloat(3, 0, 1000),  // Balance aléatoire entre 0 et 1000 avec 3 décimales
            'role' => 'client',
        ];
    }
}
