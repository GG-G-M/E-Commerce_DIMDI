<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get the deliveries table ID for the current authenticated user
     */
    private function getDeliveriesTableId()
    {
        $deliveryUser = Auth::user();
        if (!$deliveryUser) {
            return null;
        }
        
        $deliveryEntry = DB::table('deliveries')->where('email', $deliveryUser->email)->first();
        return $deliveryEntry ? $deliveryEntry->id : null;
    }

    public function index()
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            return view('delivery.dashboard')
                ->with('error', 'You are not registered in the deliveries system.');
        }
        
        // Get stats based on deliveries table ID
        $stats = [
            // My active orders (shipped and out_for_delivery) - using deliveries table ID
            'myActiveOrdersCount' => Order::where('delivery_id', $deliveriesTableId)
                ->whereIn('order_status', ['shipped', 'out_for_delivery'])
                ->count(),
                
            // Available orders for pickup (confirmed status, no delivery person assigned)
            'availableOrdersCount' => Order::where('order_status', 'confirmed')
                ->whereNull('delivery_id')
                ->count(),
                
            // Orders that are out for delivery specifically
            'outForDeliveryCount' => Order::where('delivery_id', $deliveriesTableId)
                ->where('order_status', 'out_for_delivery')
                ->count(),
                
            // Delivered today by current delivery person
            'deliveredTodayCount' => Order::where('delivery_id', $deliveriesTableId)
                ->where('order_status', 'delivered')
                ->whereDate('delivered_at', today())
                ->count(),
                
            // Total delivered by this delivery person
            'totalDeliveredCount' => Order::where('delivery_id', $deliveriesTableId)
                ->where('order_status', 'delivered')
                ->count(),
        ];

        // Get available orders for quick pickup (confirmed status)
        $availableOrders = Order::where('order_status', 'confirmed')
            ->whereNull('delivery_id')
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Get current delivery person's active orders (shipped and out_for_delivery)
        $myActiveOrders = Order::where('delivery_id', $deliveriesTableId)
            ->whereIn('order_status', ['shipped', 'out_for_delivery'])
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'asc')
            ->take(3)
            ->get();

        // Get recent delivered orders
        $recentDelivered = Order::where('delivery_id', $deliveriesTableId)
            ->where('order_status', 'delivered')
            ->with(['user', 'orderItems.product'])
            ->orderBy('delivered_at', 'desc')
            ->take(5)
            ->get();

        // Debug information (uncomment if needed)
        /*
        \Log::info('Dashboard Data', [
            'deliveries_table_id' => $deliveriesTableId,
            'user_email' => Auth::user()->email,
            'stats' => $stats,
            'available_orders_count' => $availableOrders->count(),
            'my_active_orders_count' => $myActiveOrders->count(),
            'recent_delivered_count' => $recentDelivered->count(),
        ]);
        */

        return view('delivery.dashboard', compact(
            'stats', 
            'availableOrders', 
            'myActiveOrders', 
            'recentDelivered'
        ));
    }
}