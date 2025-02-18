<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crypto_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idWallet')->constrained('wallets')->onDelete('cascade'); // Lien avec la table wallets
            $table->foreignId('idCrypto')->constrained('cryptocurrencies')->onDelete('cascade'); // Lien avec la table cryptocurrencies
            $table->decimal('quantity', 20, 8)->default(0); // Quantité de crypto
            $table->enum('status', ['active', 'inactive'])->default('active'); // Statut du portefeuille
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_wallets');
    }
};
