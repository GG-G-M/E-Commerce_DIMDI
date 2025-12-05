<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, check what the current foreign key is
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'orders' 
            AND COLUMN_NAME = 'delivery_id'
        ");

        // Drop the existing foreign key if it exists
        if (!empty($foreignKeys)) {
            $constraintName = $foreignKeys[0]->CONSTRAINT_NAME;
            DB::statement("ALTER TABLE orders DROP FOREIGN KEY `{$constraintName}`");
        }

        // Check if deliveries table exists and has data
        if (Schema::hasTable('deliveries')) {
            // If deliveries table exists, we need to sync user IDs
            // Copy delivery_id from deliveries table to users table reference
            DB::statement("
                UPDATE orders o
                JOIN deliveries d ON o.delivery_id = d.id
                JOIN users u ON d.user_id = u.id OR d.email = u.email
                SET o.delivery_id = u.id
                WHERE o.delivery_id IS NOT NULL
            ");
        }

        // Add new foreign key to reference users table
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('delivery_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        // Drop the foreign key
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_id']);
        });
        
        // You can't easily revert the data changes, but at least remove the FK
    }
};