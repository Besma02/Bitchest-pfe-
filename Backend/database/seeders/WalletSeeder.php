<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    public function run()
    {
        // Récupère tous les utilisateurs
        $users = User::all();

        // Pour chaque utilisateur, crée un wallet
        $users->each(function ($user) {
            Wallet::create([
                'idUser' => $user->id,
                'publicAdress' => '0x' . bin2hex(random_bytes(16)), // Adresse publique aléatoire
                'privateAdress' => '0x' . bin2hex(random_bytes(16)), // Adresse privée aléatoire
            ]);
        });
    }
}
