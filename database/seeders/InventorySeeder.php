<?php

namespace Database\Seeders;

use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\Supplier;
use App\Models\StockChecker;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🏭 Starting comprehensive inventory seeding...');
        
        // Get all products and variants
        $products = Product::whereNotNull('id')->get();
        $variants = ProductVariant::whereNotNull('id')->get();
        
        if ($products->isEmpty()) {
            $this->command->warn('No products found. Skipping inventory seeding.');
            return;
        }

        // Get warehouses, suppliers, and stock checkers
        $warehouses = Warehouse::all();
        $suppliers = Supplier::all();
        $stockCheckers = StockChecker::all();

        if ($warehouses->isEmpty() || $suppliers->isEmpty() || $stockCheckers->isEmpty()) {
            $this->command->warn('Missing required dependencies (warehouses, suppliers, or stock checkers).');
            return;
        }

        $this->command->info('📦 Creating stock-in records with FIFO batches...');
        
        // Create stock-in records for products (FIFO batches)
        $this->createStockInBatches($products, $variants, $warehouses, $suppliers, $stockCheckers);
        
        $this->command->info('📤 Creating stock-out records for existing orders...');
        
        // Create stock-out records for orders
        $this->createStockOutForOrders();
        
        $this->command->info('✅ Inventory seeding completed!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Total StockIn records: ' . StockIn::count());
        $this->command->info('   - Total StockOut records: ' . StockOut::count());
        $this->command->info('   - Total Inventory Batches: ' . DB::table('stock_in_stock_out')->count());
    }

    private function createStockInBatches($products, $variants, $warehouses, $suppliers, $stockCheckers)
    {
        $stockInCount = 0;
        $baseDate = Carbon::now()->subMonths(6);
        
        // Create 2-5 stock-in batches per product over 6 months
        foreach ($products as $product) {
            $batchCount = rand(2, 5);
            
            for ($i = 0; $i < $batchCount; $i++) {
                $batchDate = $baseDate->copy()->addDays(rand(0, 180));
                $quantity = rand(10, 100);
                
                // Base cost price (usually lower than sale price)
                $costPrice = $product->sale_price ?? $product->price;
                $costPrice = $costPrice * (0.6 + (rand(10, 30) / 100)); // 60-90% of sale price
                
                $stockIn = StockIn::create([
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'warehouse_id' => $warehouses->random()->id,
                    'supplier_id' => $suppliers->random()->id,
                    'stock_checker_id' => $stockCheckers->random()->id,
                    'quantity' => $quantity,
                    'remaining_quantity' => $quantity,
                    'price' => round($costPrice, 2),
                    'reason' => 'Initial stock purchase',
                    'created_at' => $batchDate,
                    'updated_at' => $batchDate,
                ]);
                
                $stockInCount++;
            }
            
            // Create stock-in records for product variants if they exist
            $productVariants = $variants->where('product_id', $product->id);
            foreach ($productVariants as $variant) {
                $variantBatchCount = rand(1, 3);
                
                for ($i = 0; $i < $variantBatchCount; $i++) {
                    $batchDate = $baseDate->copy()->addDays(rand(0, 180));
                    $quantity = rand(5, 50);
                    
                    // Variant cost price
                    $variantCostPrice = $variant->sale_price ?? $variant->price;
                    $variantCostPrice = $variantCostPrice * (0.6 + (rand(10, 30) / 100));
                    
                    StockIn::create([
                        'product_id' => null, // No main product for variant stock-in
                        'product_variant_id' => $variant->id,
                        'warehouse_id' => $warehouses->random()->id,
                        'supplier_id' => $suppliers->random()->id,
                        'stock_checker_id' => $stockCheckers->random()->id,
                        'quantity' => $quantity,
                        'remaining_quantity' => $quantity,
                        'price' => round($variantCostPrice, 2),
                        'reason' => 'Variant stock purchase',
                        'created_at' => $batchDate,
                        'updated_at' => $batchDate,
                    ]);
                    
                    $stockInCount++;
                }
            }
        }
        
        $this->command->info("   Created {$stockInCount} stock-in batches");
    }

    private function createStockOutForOrders()
    {
        $orders = Order::whereNotIn('order_status', ['cancelled'])->get();
        $stockOutCount = 0;
        
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                // Determine if this item used a variant or main product
                $productId = null;
                $variantId = null;
                
                if ($item->selected_size && $item->product->has_variants) {
                    // Find the variant that was selected
                    $variant = $item->product->variants()
                        ->where('variant_name', $item->selected_size)
                        ->first();
                    
                    if ($variant) {
                        $variantId = $variant->id;
                    } else {
                        $productId = $item->product_id;
                    }
                } else {
                    $productId = $item->product_id;
                }
                
                // Create stock-out record for this order item
                $stockOut = StockOut::create([
                    'product_id' => $productId,
                    'product_variant_id' => $variantId,
                    'quantity' => $item->quantity,
                    'reason' => "Order #{$order->order_number} - {$item->product_name}",
                ]);
                
                // Apply FIFO deduction from stock-in batches
                $stockOut->deductFromBatches($item->quantity);
                
                $stockOutCount++;
            }
        }
        
        $this->command->info("   Created {$stockOutCount} stock-out records for orders");
    }
}