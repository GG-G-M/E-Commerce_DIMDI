<?php

namespace Database\Seeders;

use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Product;
use App\Models\ProductVariant;
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
        
        $baseDate = Carbon::now()->subMonths(3);
        $products = Product::all();
        
        // Create initial stock ins (received from suppliers at start)
        foreach ($products as $product) {
            $initialQuantity = rand(20, 100);
            
            StockIn::create([
                'product_id' => $product->id,
                'product_variant_id' => null,
                'warehouse_id' => $warehouse->id,
                'supplier_id' => $suppliers[rand(0, count($suppliers) - 1)]->id,
                'stock_checker_id' => $checkers[rand(0, count($checkers) - 1)]->id,
                'quantity' => $initialQuantity,
                'remaining_quantity' => $initialQuantity,
                'reason' => 'Initial stock setup',
                'is_archived' => false,
                'created_at' => $baseDate->copy(),
                'updated_at' => $baseDate->copy(),
            ]);
            
            // Create stock ins for variants if they exist
            $variants = ProductVariant::where('product_id', $product->id)->get();
            foreach ($variants as $variant) {
                $variantQuantity = rand(10, 50);
                
                StockIn::create([
                    'product_id' => null,
                    'product_variant_id' => $variant->id,
                    'warehouse_id' => $warehouse->id,
                    'supplier_id' => $suppliers[rand(0, count($suppliers) - 1)]->id,
                    'stock_checker_id' => $checkers[rand(0, count($checkers) - 1)]->id,
                    'quantity' => $variantQuantity,
                    'remaining_quantity' => $variantQuantity,
                    'reason' => 'Initial variant stock',
                    'is_archived' => false,
                    'created_at' => $baseDate->copy(),
                    'updated_at' => $baseDate->copy(),
                ]);
            }
        }
        
        // Create additional stock ins during the 3-month period (restocking)
        for ($i = 0; $i < 30; $i++) {
            $restockDate = $baseDate->copy()->addDays(rand(5, 90));
            $product = $products->random();
            $quantity = rand(10, 50);
            
            StockIn::create([
                'product_id' => $product->id,
                'product_variant_id' => null,
                'warehouse_id' => $warehouse->id,
                'supplier_id' => $suppliers[rand(0, count($suppliers) - 1)]->id,
                'stock_checker_id' => $checkers[rand(0, count($checkers) - 1)]->id,
                'quantity' => $quantity,
                'remaining_quantity' => $quantity,
                'reason' => 'Restocking',
                'is_archived' => false,
                'created_at' => $restockDate,
                'updated_at' => $restockDate,
            ]);
        }
        
        // Create stock outs based on orders (simulating order fulfillment)
        for ($i = 0; $i < 40; $i++) {
            $outDate = $baseDate->copy()->addDays(rand(0, 90));
            $product = $products->random();
            $quantity = rand(1, 5);
            
            StockOut::create([
                'product_id' => $product->id,
                'product_variant_id' => null,
                'quantity' => $quantity,
                'reason' => 'Order fulfillment',
                'created_at' => $outDate,
                'updated_at' => $outDate,
            ]);
            
            // Occasional stock outs for variants
            if (rand(0, 1)) {
                $variant = ProductVariant::inRandomOrder()->first();
                if ($variant) {
                    StockOut::create([
                        'product_id' => null,
                        'product_variant_id' => $variant->id,
                        'quantity' => rand(1, 3),
                        'reason' => 'Variant order fulfillment',
                        'created_at' => $outDate->copy()->addHours(rand(1, 12)),
                        'updated_at' => $outDate->copy()->addHours(rand(1, 12)),
                    ]);
                }
            }
        }
        
        // Create damage/adjustment stock outs
        for ($i = 0; $i < 8; $i++) {
            $product = $products->random();
            
            StockOut::create([
                'product_id' => $product->id,
                'product_variant_id' => null,
                'quantity' => rand(1, 3),
                'reason' => 'Damaged goods write-off',
                'created_at' => $baseDate->copy()->addDays(rand(0, 90)),
                'updated_at' => $baseDate->copy()->addDays(rand(0, 90)),
            ]);
        }
        
        $this->command->info('Stock In/Out data created successfully with 3-month history!');
    }
}
