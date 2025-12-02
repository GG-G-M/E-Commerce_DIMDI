<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('delivery_personnel_id')->constrained('deliveries')->onDelete('cascade'); // Changed to 'deliveries'
            $table->string('status')->default('assigned'); // assigned, picked_up, in_transit, delivered, failed
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->text('customer_signature')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('assigned_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_deliveries');
    }
};