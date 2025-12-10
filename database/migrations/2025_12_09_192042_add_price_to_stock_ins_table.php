<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Price column already exists, migration not needed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Price column already exists, no rollback needed
    }
};
