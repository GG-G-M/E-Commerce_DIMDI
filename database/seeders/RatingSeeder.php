<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RatingSeeder extends Seeder
{
    public function run()
    {
        // Get delivered orders
        $deliveredOrders = Order::where('order_status', 'delivered')
            ->with('items', 'user')
            ->get();
        
        if ($deliveredOrders->isEmpty()) {
            $this->command->warn('No delivered orders found. Skipping rating seeding.');
            return;
        }
        
        $ratingsCreated = 0;
        
        // Create ratings for about 60% of delivered orders
        foreach ($deliveredOrders as $order) {
            // Verify the user exists
            if (!$order->user || !$order->user->id) {
                $this->command->warn('Order #' . $order->id . ' has no valid user. Skipping.');
                continue;
            }

            if (rand(0, 100) < 60) {
                // Create rating for each item in the order
                foreach ($order->items as $item) {
                    try {
                        Rating::create([
                            'user_id' => $order->user_id,
                            'product_id' => $item->product_id,
                            'order_id' => $order->id,
                            'rating' => rand(3, 5), // 3-5 stars
                            'review' => $this->getRandomReview(),
                            'created_at' => $order->delivered_at ? $order->delivered_at->copy()->addDays(rand(1, 5)) : now(),
                            'updated_at' => $order->delivered_at ? $order->delivered_at->copy()->addDays(rand(1, 5)) : now(),
                        ]);
                        $ratingsCreated++;
                    } catch (\Exception $e) {
                        $this->command->warn('Failed to create rating: ' . $e->getMessage());
                    }
                }
            }
        }
        
        $this->command->info('Ratings created successfully! Total: ' . $ratingsCreated);
    }
    
    private function getRandomReview()
    {
        $reviews = [
            'Great product! Arrived on time.',
            'Excellent quality and fast delivery.',
            'Very satisfied with this purchase.',
            'Good value for money.',
            'Highly recommended!',
            'Perfect! Just as described.',
            'Quality is top notch.',
            'Amazing service and product.',
            'Will definitely buy again.',
            'Fantastic experience overall.',
            'Really happy with my purchase.',
            'Exceeded my expectations.',
        ];
        
        return $reviews[rand(0, count($reviews) - 1)];
    }
}
