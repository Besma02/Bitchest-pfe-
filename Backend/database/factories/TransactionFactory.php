<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'idCryptoBankStore' => $this->faker->numberBetween(1, 20), // Référence à une crypto_bank_store existante
            'idRecipient' => $this->faker->numberBetween(1, 20), // Référence à un utilisateur existant
            'fee' => $this->faker->randomFloat(2, 0.1, 10), // Frais aléatoires entre 0.1 et 10
            'date' => $this->faker->dateTimeThisYear(), // Date aléatoire dans l'année en cours
        ];
    }
}
