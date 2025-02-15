<?php

namespace Database\Factories;

use App\Models\CryptoWallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class CryptoWalletFactory extends Factory
{
    protected $model = CryptoWallet::class;

    public function definition()
    {
        return [
            'idCrypto' => $this->faker->numberBetween(1, 10), // Supposons 10 cryptos existantes
            'idWallet' => $this->faker->numberBetween(1, 20), // Référence à un wallet existant
            'quantity' => $this->faker->randomFloat(8, 0.001, 10), // Quantité aléatoire entre 0.001 et 10
            'status' => $this->faker->randomElement(['owned', 'to_buy']), // Statut aléatoire
        ];
    }
}
