<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use App\Models\Supplier;
use App\Models\StockChecker;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StockSeeder extends Seeder
{
    public function run()
    {
        // Create initial warehouses
        $warehouse = Warehouse::firstOrCreate(
            ['name' => 'Main Warehouse'],
            ['is_archived' => false]
        );
        
        // Create suppliers
        $suppliers = [];
        $supplierNames = ['ElectroSupply Co.', 'Premium Appliances Ltd.', 'Direct Importer Inc.', 'Quality Parts Corp.'];
        
        foreach ($supplierNames as $name) {
            $suppliers[] = Supplier::firstOrCreate(
                ['name' => $name],
                [
                    'contact' => 'contact@' . strtolower(str_replace(' ', '', $name)) . '.com',
                    'address' => '123 Supply Street, Metro',
                    'contact_person' => 'Manager',
                    'is_archived' => false
                ]
            );
        }
        
        // Create stock checkers
        $checkers = [];
        for ($i = 1; $i <= 3; $i++) {
            $checkers[] = StockChecker::firstOrCreate(
                ['contact' => "checker000$i@warehouse.local"],
                [
                    'firstname' => 'Stock',
                    'lastname' => 'Checker ' . $i,
                    'address' => 'Warehouse Zone ' . $i,
                    'is_archived' => false
                ]
            );
        }
        
    }
}