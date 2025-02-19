<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idCryptoWallet')->constrained('cypto_wallets');
            $table->float('quantity');
            $table->decimal('unitPrice', 18, 3);
            $table->decimal('totalPrice', 18, 3);
            $table->dateTime('date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
