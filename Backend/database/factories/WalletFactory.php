<?php

namespace Database\Factories;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition()
    {
        return [
            'idUser' => \App\Models\User::factory(),
            'publicAdress' => '0x' . $this->faker->sha256(), // Adresse publique aléatoire
            'privateAdress' => '0x' . $this->faker->sha256(), // Adresse privée aléatoire
        ];
    }
}