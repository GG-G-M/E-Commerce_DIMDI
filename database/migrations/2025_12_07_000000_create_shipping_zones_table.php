<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create pivot/warehouse locations table
        Schema::create('shipping_pivot_points', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "Main Warehouse", "Branch A"
            $table->decimal('latitude', 10, 8); // GPS latitude
            $table->decimal('longitude', 11, 8); // GPS longitude
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create shipping zones table (distance-based)
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pivot_point_id')->constrained('shipping_pivot_points')->onDelete('cascade');
            $table->string('zone_name'); // e.g., "Local", "Metro", "Provincial"
            $table->decimal('min_distance', 8, 2); // Minimum distance in km
            $table->decimal('max_distance', 8, 2); // Maximum distance in km
            $table->decimal('shipping_fee', 10, 2); // Shipping cost in PHP
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Composite unique constraint to prevent overlapping zones per pivot
            $table->unique(['pivot_point_id', 'zone_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_zones');
        Schema::dropIfExists('shipping_pivot_points');
    }
};
