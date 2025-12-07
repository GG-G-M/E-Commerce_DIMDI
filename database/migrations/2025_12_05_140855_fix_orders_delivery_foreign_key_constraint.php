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
        $foreignKeyInfo = DB::select("
            SELECT CONSTRAINT_NAME, REFERENCED_TABLE_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'orders'
            AND COLUMN_NAME = 'delivery_id'
        ");
        
        if (!empty($foreignKeyInfo)) {
            $constraintName = $foreignKeyInfo[0]->CONSTRAINT_NAME;
            $referencedTable = $foreignKeyInfo[0]->REFERENCED_TABLE_NAME;
            
            // If it's pointing to deliveries, we need to change it
            if ($referencedTable === 'deliveries') {
                // Drop the existing foreign key
                DB::statement("ALTER TABLE orders DROP FOREIGN KEY `{$constraintName}`");
                
                // Clear delivery_id values that don't exist in users table
                // First, get all valid user IDs from deliveries
                $validUserIds = DB::table('deliveries')
                    ->whereNotNull('user_id')
                    ->pluck('user_id')
                    ->toArray();
                
                if (!empty($validUserIds)) {
                    // Update orders to use user_id instead of delivery.id
                    DB::statement("
                        UPDATE orders o
                        INNER JOIN deliveries d ON o.delivery_id = d.id
                        SET o.delivery_id = d.user_id
                        WHERE o.delivery_id IS NOT NULL 
                        AND d.user_id IS NOT NULL
                        AND d.user_id IN (" . implode(',', $validUserIds) . ")
                    ");
                }
                
                // Set remaining invalid delivery_ids to NULL
                DB::statement("
                    UPDATE orders o
                    LEFT JOIN deliveries d ON o.delivery_id = d.id
                    SET o.delivery_id = NULL
                    WHERE o.delivery_id IS NOT NULL AND d.id IS NULL
                ");
                
                // Add foreign key to users table
                Schema::table('orders', function (Blueprint $table) {
                    $table->foreign('delivery_id')
                          ->references('id')
                          ->on('users')
                          ->onDelete('set null');
                });
            }
        } else {
            // No foreign key exists, create one pointing to users
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('delivery_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            });
        }
    }

    public function down()
    {
        // Drop the foreign key
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_id']);
        });
        
        // Note: Can't easily revert data changes
    }
};