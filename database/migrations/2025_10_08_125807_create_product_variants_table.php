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
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->timestamps();
            
            $table->unique(['product_id', 'size']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};