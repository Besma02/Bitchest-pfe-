<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoWalletsTable extends Migration
{
    public function up()
    {
        Schema::create('crypto_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idCrypto')->constrained('cryptocurrencies');
            $table->foreignId('idWallet')->constrained('wallets');
            $table->decimal('quantity', 18, 8);
            $table->string('status')->default('owned');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('crypto_wallets');
    }
}
