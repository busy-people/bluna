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
        Schema::create('sales', function (Blueprint $table) {
             $table->id();
            $table->date('date'); // YYYY-MM-DD
            $table->enum('product_type', ['small','large']);
            $table->integer('quantity')->default(1);
            $table->integer('price'); // price per unit (in IDR)
            $table->integer('total'); // quantity * price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
