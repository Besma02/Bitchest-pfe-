<?php

namespace Database\Factories;

use App\Models\CryptoBankStore;
use Illuminate\Database\Eloquent\Factories\Factory;

class CryptoBankStoreFactory extends Factory
{
    protected $model = CryptoBankStore::class;

    public function definition()
    {
        return [
            'idCryptoWallet' => $this->faker->numberBetween(1, 20), // Référence à une crypto_wallet existante
            'quantity' => $this->faker->randomFloat(8, 0.001, 5), // Quantité aléatoire entre 0.001 et 5
            'unitPrice' => $this->faker->randomFloat(2, 100, 10000), // Prix unitaire aléatoire entre 100 et 10 000
            'totalPrice' => $this->faker->randomFloat(2, 100, 50000), // Prix total aléatoire entre 100 et 50 000
            'operationStatus' => $this->faker->randomElement(['en_attente', 'vendu']), // Statut aléatoire
            'operationDate' => $this->faker->dateTimeThisYear(), // Date aléatoire dans l'année en cours
        ];
    }
}
