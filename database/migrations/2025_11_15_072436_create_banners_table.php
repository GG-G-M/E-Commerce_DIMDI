<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_banners_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('target_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banners');
    }
}