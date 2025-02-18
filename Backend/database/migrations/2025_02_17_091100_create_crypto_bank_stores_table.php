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
        Schema::create('crypto_bank_stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idCryptoWallet')->constrained('crypto_wallets');
            $table->decimal('quantity', 18, 8);
            $table->decimal('unitPrice', 18, 8);
            $table->decimal('totalPrice', 18, 8);
            $table->string('operationStatus')->default('pending');
            $table->dateTime('operationDate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_bank_stores');
    }
};
