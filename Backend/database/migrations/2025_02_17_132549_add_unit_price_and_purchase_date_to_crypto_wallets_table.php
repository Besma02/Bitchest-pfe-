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
        Schema::table('crypto_wallets', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->nullable(); // Prix unitaire de la crypto
            $table->date('purchase_date')->nullable(); // Date d'achat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crypto_wallets', function (Blueprint $table) {
            $table->dropColumn('unit_price');
            $table->dropColumn('purchase_date');
        });
    }
};
