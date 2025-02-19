<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Cryptocurrency;
use App\Models\CryptoWallet;
use App\Models\Transaction;
use App\Models\PriceHistory;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create(); // Initialisation de Faker

        // Récupère toutes les cryptos existantes
        $allCryptos = Cryptocurrency::all();

        // Crée 20 utilisateurs
        User::factory(20)->create()->each(function ($user) use ($allCryptos, $faker) {
            // Crée un wallet pour chaque utilisateur
            $wallet = Wallet::factory()->create(['idUser' => $user->id]);

            // Sélectionne un nombre aléatoire de cryptos (1 à 5) pour cet utilisateur
            $userCryptos = $allCryptos->random(rand(1, 5));

            // Pour chaque crypto sélectionnée, simuler plusieurs achats
            $userCryptos->each(function ($crypto) use ($wallet, $faker) {
                // Nombre d'achats aléatoire (1 à 3 fois)
                $purchaseCount = rand(1, 3);

                for ($i = 0; $i < $purchaseCount; $i++) {
                    // Quantité achetée
                    $quantity = $faker->randomFloat(6, 0.1, 10);

                    // Récupère un prix historique aléatoire
                    $priceHistory = PriceHistory::where('cryptocurrency_id', $crypto->id)
                        ->inRandomOrder()
                        ->first();

                    if (!$priceHistory) {
                        continue;
                    }

                    // Vérifie si cette crypto existe déjà dans le wallet
                    $cryptoWallet = CryptoWallet::where('idCrypto', $crypto->id)
                        ->where('idWallet', $wallet->id)
                        ->first();

                    if ($cryptoWallet) {
                        // Met à jour la quantité existante
                        $cryptoWallet->quantity += $quantity;
                        $cryptoWallet->save();
                    } else {
                        // Crée une nouvelle entrée dans crypto-wallets
                        $cryptoWallet = CryptoWallet::create([
                            'idCrypto' => $crypto->id,
                            'idWallet' => $wallet->id,
                            'quantity' => $quantity,
                        ]);
                    }

                    // Crée une nouvelle transaction pour cet achat
                    Transaction::create([
                        'idCryptoWallet' => $cryptoWallet->id,
                        'quantity' => $quantity,
                        'unitPrice' => $priceHistory->value,
                        'totalPrice' => $quantity * $priceHistory->value,
                        'date' => $priceHistory->date,
                    ]);
                }
            });
        });
    }
}
