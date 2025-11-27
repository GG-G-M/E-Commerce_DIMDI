<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Option 1: If role is ENUM, change it to string
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('customer')->change();
        });

        // Or Option 2: If you want to use ENUM with all roles
        // DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'delivery', 'customer') DEFAULT 'customer'");
    }

    public function down(): void
    {
        // Revert back to original if needed
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 10)->default('customer')->change();
        });
    }
};