<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(CommerceSeeder::class);
        $this->command->info('Datas created successfully!');
        $this->call(AdminUserSeeder::class);
        $this->call(SuperAdminSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(DeliverySeeder::class);
        $this->call(StockSeeder::class);
        // $this->call(InventorySeeder::class);
        // $this->call(OrderSeeder::class);
        $this->call(RatingSeeder::class);
        $this->call(ShippingZoneSeeder::class);

        // Run storage:link command automatically
        Artisan::call('storage:link');
        $this->command->info('Storage link created successfully!');

        $this->command->info('|||| Seeder Run Completed ||||');

    }
}