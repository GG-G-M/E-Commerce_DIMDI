<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps(); // This creates created_at and updated_at
            
            // Indexes for better performance
            $table->index(['sender_id', 'receiver_id', 'created_at']);
            $table->index(['product_id', 'created_at']);
            $table->index(['receiver_id', 'is_read', 'created_at']);
            $table->index(['sender_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};