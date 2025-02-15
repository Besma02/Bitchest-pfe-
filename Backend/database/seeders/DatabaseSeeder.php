<?php

namespace Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Crée 20 utilisateurs
        \App\Models\User::factory(20)->create();

        // Crée un wallet pour chaque utilisateur
        $this->call(WalletSeeder::class);

        // Crée 20 crypto_wallets
        \App\Models\CryptoWallet::factory(20)->create();

        // Crée 20 crypto_bank_stores
        \App\Models\CryptoBankStore::factory(20)->create();

        // Crée 20 transactions
        \App\Models\Transaction::factory(20)->create();
    }
}
