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
            $table->string('size');
            $table->integer('stock_quantity')->default(0);
            $table->decimal('price', 10, 2)->nullable(); // Optional: size-specific pricing
            $table->decimal('sale_price', 10, 2)->nullable(); // Optional: size-specific sale pricing
            $table->timestamps();
            
            $table->unique(['product_id', 'size']);
        });

        // Migrate existing data
        $products = \App\Models\Product::all();
        foreach ($products as $product) {
            if ($product->sizes && count($product->sizes) > 0) {
                foreach ($product->sizes as $size) {
                    \App\Models\ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'stock_quantity' => $product->stock_quantity, // Distribute total stock
                        'price' => $product->price,
                        'sale_price' => $product->sale_price,
                    ]);
                }
            } else {
                // For products without sizes, create a default variant
                \App\Models\ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => 'One Size',
                    'stock_quantity' => $product->stock_quantity,
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                ]);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};