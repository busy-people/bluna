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
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type', ['income', 'expense']); // income = uang masuk, expense = uang keluar
            $table->string('category'); // beli_bahan, alat, operasional, lainnya, penjualan
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->string('receipt_photo')->nullable(); // foto nota/bukti
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};
