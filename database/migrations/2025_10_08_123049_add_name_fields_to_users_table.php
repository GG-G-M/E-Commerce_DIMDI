<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->after('middle_name');
            
            // Optionally remove the old name field after data migration
            // $table->dropColumn('name');
        });

        // Migrate existing data from name to new fields
        \App\Models\User::chunk(100, function ($users) {
            foreach ($users as $user) {
                $nameParts = explode(' ', $user->name, 3);
                $user->first_name = $nameParts[0] ?? '';
                $user->last_name = $nameParts[2] ?? ($nameParts[1] ?? '');
                $user->middle_name = count($nameParts) === 3 ? $nameParts[1] : null;
                $user->save();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_name', 'last_name']);
        });
    }
};