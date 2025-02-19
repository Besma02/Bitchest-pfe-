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
            'idCrypto' => $this->faker->numberBetween(1, 10),
            'idWallet' => $this->faker->numberBetween(1, 10),
            'quantity' => $this->faker->randomFloat(8, 0.001, 10)
        ];
    }
}
