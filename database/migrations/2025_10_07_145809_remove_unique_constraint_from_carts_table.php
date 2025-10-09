<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            // First, drop the foreign key constraints
            $table->dropForeign(['product_id']);
            $table->dropForeign(['user_id']);
            
            // Then drop the unique constraints
            $table->dropUnique(['session_id', 'product_id']);
            $table->dropUnique(['user_id', 'product_id']);
            
            // Add new unique constraints that include selected_size
            $table->unique(['session_id', 'product_id', 'selected_size']);
            $table->unique(['user_id', 'product_id', 'selected_size']);
            
            // Re-add the foreign key constraints
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['product_id']);
            $table->dropForeign(['user_id']);
            
            // Remove the new unique constraints
            $table->dropUnique(['session_id', 'product_id', 'selected_size']);
            $table->dropUnique(['user_id', 'product_id', 'selected_size']);
            
            // Restore old unique constraints
            $table->unique(['session_id', 'product_id']);
            $table->unique(['user_id', 'product_id']);
            
            // Re-add foreign keys
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};