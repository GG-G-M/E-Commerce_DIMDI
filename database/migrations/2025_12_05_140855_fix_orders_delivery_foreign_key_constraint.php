<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Remove old foreign key if exists (PostgreSQL safe)
        $table = 'orders';
        $column = 'delivery_id';

        $foreignKeys = DB::select("
            SELECT constraint_name
            FROM information_schema.table_constraints
            WHERE table_name = ?
            AND constraint_type = 'FOREIGN KEY'
        ", [$table]);

        foreach ($foreignKeys as $fk) {
            if (str_contains($fk->constraint_name, $column)) {
                DB::statement("
                    ALTER TABLE {$table}
                    DROP CONSTRAINT IF EXISTS {$fk->constraint_name}
                ");
            }
        }

        // Data cleanup — keep only valid user IDs
        if (Schema::hasTable('deliveries')) {

            DB::statement("
                UPDATE orders
                SET delivery_id = NULL
                WHERE delivery_id IS NOT NULL
                AND delivery_id NOT IN (
                    SELECT user_id
                    FROM deliveries
                    WHERE user_id IS NOT NULL
                )
            ");

            DB::statement("
                UPDATE orders o
                SET delivery_id = d.user_id
                FROM deliveries d
                WHERE o.delivery_id = d.id
                AND d.user_id IS NOT NULL
            ");
        }

        // Create FK to users table
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('delivery_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_id']);
        });
    }
};