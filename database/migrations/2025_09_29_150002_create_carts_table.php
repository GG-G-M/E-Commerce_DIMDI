<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('selected_size')->default('One Size');
            $table->integer('quantity')->default(1);
            $table->timestamps();
            
            $table->unique(['session_id', 'product_id', 'selected_size']);
            $table->unique(['user_id', 'product_id', 'selected_size']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};