<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Find foreign key constraint name safely (PostgreSQL version)
        $foreignKeys = DB::select("
            SELECT constraint_name
            FROM information_schema.key_column_usage
            WHERE table_name = 'orders'
            AND column_name = 'delivery_id'
            AND constraint_schema = current_schema()
        ");

        // Drop foreign key if exists
        if (!empty($foreignKeys)) {
            $constraintName = $foreignKeys[0]->constraint_name;

            DB::statement("
                ALTER TABLE orders
                DROP CONSTRAINT IF EXISTS {$constraintName}
            ");
        }

        // Data synchronization if deliveries table exists
        if (Schema::hasTable('deliveries')) {

            DB::statement("
                UPDATE orders o
                SET delivery_id = u.id
                FROM deliveries d
                JOIN users u ON d.email = u.email
                WHERE o.delivery_id = d.id
                AND o.delivery_id IS NOT NULL
            ");
        }

        // Add new foreign key constraint
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