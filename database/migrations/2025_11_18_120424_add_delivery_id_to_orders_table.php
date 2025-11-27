<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_delivery_id_to_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('delivery_id')
                  ->nullable()
                  ->constrained('deliveries')
                  ->onDelete('set null')
                  ->after('user_id');
                  
            $table->timestamp('assigned_at')->nullable()->after('delivery_id');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_id']);
            $table->dropColumn(['delivery_id', 'assigned_at']);
        });
    }
};