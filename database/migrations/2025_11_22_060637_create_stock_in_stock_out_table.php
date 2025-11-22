<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_in_stock_out', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stock_in_id')
                ->constrained('stock_ins')
                ->cascadeOnDelete();

            $table->foreignId('stock_out_id')
                ->constrained('stock_outs')
                ->cascadeOnDelete();

            // Quantity deducted from this specific stock-in batch
            $table->integer('deducted_quantity');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_in_stock_out');
    }
};
