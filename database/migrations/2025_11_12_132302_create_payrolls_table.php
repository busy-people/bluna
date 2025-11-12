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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('period'); // YYYY-MM
            $table->decimal('total_revenue', 15, 2); // Total omzet
            $table->decimal('operational_cost', 15, 2); // 35% untuk modal & operasional
            $table->decimal('net_salary', 15, 2); // 65% untuk dibagi
            $table->integer('total_points'); // Total point semua member
            $table->decimal('point_value', 15, 2); // Nilai per point (net_salary / total_points)
            $table->string('status')->default('draft'); // draft, finalized, paid
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
