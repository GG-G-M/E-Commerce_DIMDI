<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // Add only the missing columns from your friend's migration
        if (!Schema::hasColumn('users', 'vehicle_type')) {
            $table->string('vehicle_type')->nullable()->after('country');
        }
        
        if (!Schema::hasColumn('users', 'vehicle_number')) {
            $table->string('vehicle_number')->nullable()->after('vehicle_type');
        }
        
        if (!Schema::hasColumn('users', 'license_number')) {
            $table->string('license_number')->nullable()->after('vehicle_number');
        }
        
        // Add any other missing columns
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['vehicle_type', 'vehicle_number', 'license_number']);
    });
}
};
