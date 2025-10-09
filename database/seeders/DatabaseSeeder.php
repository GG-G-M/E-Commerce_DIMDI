<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(CommerceSeeder::class);
        $this->command->info('Datas created successfully!');
        $this->call(AdminUserSeeder::class);
        $this->call(UserSeeder::class); 

        $this->command->info('|||| Seeder Run Completed ||||');

    }
}