<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use App\Models\Supplier;
use App\Models\StockChecker;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockIn;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run()
    {
        // ----------------------------------------------------
        // 1. Warehouses
        // ----------------------------------------------------
        $warehouse = Warehouse::firstOrCreate(
            ['name' => 'Main Warehouse'],
            ['is_archived' => false]
        );

        // ----------------------------------------------------
        // 2. Suppliers
        // ----------------------------------------------------
        $suppliers = collect();
        $supplierNames = [
            'ElectroSupply Co.',
            'Premium Appliances Ltd.',
            'Direct Importer Inc.',
            'Quality Parts Corp.'
        ];

        foreach ($supplierNames as $name) {
            $suppliers->push(
                Supplier::firstOrCreate(
                    ['name' => $name],
                    [
                        'contact' => 'contact@' . strtolower(str_replace(' ', '', $name)) . '.com',
                        'address' => '123 Supply Street, Metro',
                        'contact_person' => 'Manager',
                        'is_archived' => false
                    ]
                )
            );
        }

        // ----------------------------------------------------
        // 3. Stock Checkers
        // ----------------------------------------------------
        $checkers = collect();
        for ($i = 1; $i <= 3; $i++) {
            $checkers->push(
                StockChecker::firstOrCreate(
                    ['contact' => "checker000$i@warehouse.local"],
                    [
                        'firstname' => 'Stock',
                        'lastname' => "Checker $i",
                        'address' => "Warehouse Zone $i",
                        'is_archived' => false
                    ]
                )
            );
        }

        // ----------------------------------------------------
        // 4. AVAILABLE PRODUCTS & VARIANTS
        // ----------------------------------------------------
        $products = Product::all();
        $variants = ProductVariant::all();

        if ($products->isEmpty()) {
            $this->command->warn('⚠ No products found. Run CommerceSeeder first.');
            return;
        }

        // ----------------------------------------------------
        // 5. Realistic Stock-In Entries
        // ----------------------------------------------------
        // Amounts are balanced for appliances:
        // - Minor items: 10–20
        // - Medium appliances: 5–10
        // - Big appliances/TVs: 3–5

        $stockIns = [];

        foreach ($products as $product) {

            // If product has variants → stock those variants instead
            if ($product->has_variants) {
                foreach ($product->variants as $variant) {
                    $qty = rand(5, 15);

                    $stockIns[] = [
                        'product_id'         => $product->id,
                        'product_variant_id' => $variant->id,
                        'quantity'           => $qty,
                        'reason'             => 'Initial stock of variant: ' . $variant->variant_name
                    ];

                    // Update variant stock
                    $variant->increment('stock_quantity', $qty);
                }

                // Update product total stock
                $product->updateTotalStock();
            }

            // If no variants → stock product directly
            else {
                $qty = rand(8, 20);

                $stockIns[] = [
                    'product_id'         => $product->id,
                    'product_variant_id' => null,
                    'quantity'           => $qty,
                    'reason'             => 'Initial product stock'
                ];

                $product->increment('stock_quantity', $qty);
            }
        }

        // ----------------------------------------------------
        // 6. Insert StockIn Records
        // ----------------------------------------------------
        foreach ($stockIns as $data) {
            StockIn::create([
                'product_id'         => $data['product_id'],
                'product_variant_id' => $data['product_variant_id'],
                'warehouse_id'       => $warehouse->id,
                'supplier_id'        => $suppliers->random()->id,
                'stock_checker_id'   => $checkers->random()->id,
                'quantity'           => $data['quantity'],
                'remaining_quantity' => $data['quantity'],
                'reason'             => $data['reason'],
                'is_archived'        => false,
            ]);
        }

        $this->command->info('StockSeeder: Stock entries created successfully.');
    }
}
