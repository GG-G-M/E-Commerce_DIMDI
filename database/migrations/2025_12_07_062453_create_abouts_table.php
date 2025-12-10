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
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            
            // Main section
            $table->string('title');
            $table->text('description_1');
            $table->text('description_2')->nullable();

            // Feature 1
            $table->string('feature_1_title');
            $table->text('feature_1_description');

            // Feature 2
            $table->string('feature_2_title');
            $table->text('feature_2_description');

            // Image URL or file path
            $table->string('image')->nullable();

            // Archive support
            $table->boolean('is_archived')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
