<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Get existing products and customers (wait for UserSeeder to complete)
        $products = Product::whereNotNull('id')->get();
        $customers = User::where('role', 'customer')->get();
        
        if ($products->isEmpty()) {
            $this->command->warn('No products found. Skipping order seeding.');
            return;
        }

        if ($customers->isEmpty()) {
            $this->command->warn('No customer accounts found. Please ensure UserSeeder runs before OrderSeeder.');
            return;
        }

        $baseDate = Carbon::now()->subMonths(3);
        $orderNumber = 1000;

        // Create orders spanning 3 months (increased to 80 orders)
        for ($i = 0; $i < 80; $i++) {
            $orderDate = $baseDate->copy()->addDays(rand(0, 90));
            $customer = $customers->random();
            
            // Random number of items per order (1-4)
            $itemCount = rand(1, 4);
            $selectedProducts = $products->random($itemCount);
            
            $subtotal = 0;
            $orderItems = [];
            
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 3);
                $unitPrice = $product->sale_price ?? $product->price;
                $totalPrice = $unitPrice * $quantity;
                $subtotal += $totalPrice;
                
                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                    'selected_size' => $product->sizes ? $product->sizes[0] : null,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ];
            }
            
            $shippingCost = rand(100, 500);
            $taxAmount = $subtotal * 0.12;
            $totalAmount = $subtotal + $shippingCost + $taxAmount;
            
            // Create order with varying statuses
            $orderStatus = $this->getRandomOrderStatus();
            $isDelivered = in_array($orderStatus, ['delivered', 'cancelled']);
            
            $order = Order::create([
                'order_number' => 'ORD-' . str_pad($orderNumber++, 6, '0', STR_PAD_LEFT),
                'user_id' => $customer->id,
                'delivery_id' => in_array($orderStatus, ['shipped', 'delivered']) ? rand(1, 3) : null,
                'assigned_at' => in_array($orderStatus, ['shipped', 'delivered']) ? $orderDate->copy()->addHours(rand(1, 24)) : null,
                'customer_email' => $customer->email,
                'customer_name' => $customer->first_name . ' ' . $customer->last_name,
                'customer_phone' => $customer->phone,
                'shipping_address' => $customer->address . ', ' . $customer->city . ', ' . $customer->state,
                'billing_address' => $customer->address . ', ' . $customer->city . ', ' . $customer->state,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'payment_method' => rand(0, 1) ? 'cash' : 'card',
                'payment_status' => $isDelivered ? 'paid' : 'pending',
                'order_status' => $orderStatus,
                'delivered_at' => $orderStatus === 'delivered' ? $orderDate->copy()->addDays(rand(2, 7)) : null,
                'cancelled_at' => $orderStatus === 'cancelled' ? $orderDate->copy()->addDays(rand(1, 3)) : null,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);
            
            // Create order items
            foreach ($orderItems as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }
            
            // Create order status history
            $this->createOrderStatusHistory($order, $orderDate);
        }
        
        $this->command->info('Orders created successfully with 3-month date range!');
    }

    private function getRandomOrderStatus()
    {
        $statuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
        $weights = [0.10, 0.15, 0.20, 0.50, 0.05]; // Increased delivered to 50%
        
        $rand = rand(0, 100) / 100;
        $cumulative = 0;
        
        foreach ($statuses as $index => $status) {
            $cumulative += $weights[$index];
            if ($rand <= $cumulative) {
                return $status;
            }
        }
        
        return 'pending';
    }

    private function createOrderStatusHistory($order, $orderDate)
    {
        $statuses = [];
        $currentStatus = 'pending';
        $currentDate = $orderDate->copy();
        
        // Always start with pending
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'notes' => 'Order placed',
            'created_at' => $currentDate,
        ]);
        
        // Add progression based on final status
        if ($order->order_status !== 'cancelled') {
            // confirmed
            $currentDate->addHours(rand(1, 12));
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'confirmed',
                'notes' => 'Order confirmed',
                'created_at' => $currentDate,
            ]);
            
            if (in_array($order->order_status, ['shipped', 'delivered'])) {
                // shipped
                $currentDate->addHours(rand(2, 24));
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => 'shipped',
                    'notes' => 'Order shipped',
                    'created_at' => $currentDate,
                ]);
                
                if ($order->order_status === 'delivered') {
                    // delivered
                    $currentDate->addDays(rand(2, 7));
                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'status' => 'delivered',
                        'notes' => 'Order delivered',
                        'created_at' => $currentDate,
                    ]);
                }
            }
        } else {
            // cancelled
            $currentDate->addDays(rand(1, 3));
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'cancelled',
                'notes' => 'Order cancelled by customer',
                'created_at' => $currentDate,
            ]);
        }
    }
}
