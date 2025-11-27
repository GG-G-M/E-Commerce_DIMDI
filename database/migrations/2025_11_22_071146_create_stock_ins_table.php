<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();
            
            // Nullable for either product or variant
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('cascade');
            
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');

            // New relationships
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('stock_checker_id')->nullable()->constrained('stock_checkers')->onDelete('set null');

            $table->integer('quantity')->default(0);
            $table->integer('remaining_quantity')->default(0); // New column for remaining stock
            $table->string('reason')->nullable();
            
            $table->boolean('is_archived')->default(false); // Optional: archive flag
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_ins');
    }
};
