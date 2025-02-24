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
        Schema::create('crypto_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('crypto_name');  // Nom de la crypto
            $table->decimal('quantity', 16, 8);  // Quantité de crypto achetée
            $table->decimal('purchase_price', 16, 8);  // Prix d'achat
            $table->timestamp('purchase_date');  // Date d'achat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_purchases');
    }
};
