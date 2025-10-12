<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('variant_name'); // e.g., "Pro Model", "Standard Edition"
            $table->text('variant_description')->nullable();
            $table->string('image')->nullable(); // Variant-specific image
            $table->string('sku')->nullable(); // Unique SKU for each variant
            $table->integer('stock_quantity')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->timestamps();
            
            $table->unique(['product_id', 'variant_name']); // Unique by product and variant name
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};