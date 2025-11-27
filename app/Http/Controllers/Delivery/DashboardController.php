<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $deliveryUserId = Auth::id();
        
        $stats = [
            // Orders assigned to current delivery person
            'myActiveOrdersCount' => Order::where('delivery_id', $deliveryUserId)
                ->whereIn('order_status', ['shipped', 'out_for_delivery'])
                ->count(),
                
            // Available orders for pickup (PROCESSING status, no delivery person assigned)
            'availableOrdersCount' => Order::where('order_status', 'processing')
                ->whereNull('delivery_id')
                ->count(),
                
            'outForDeliveryCount' => Order::where('delivery_id', $deliveryUserId)
                ->where('order_status', 'out_for_delivery')
                ->count(),
                
            'deliveredTodayCount' => Order::where('delivery_id', $deliveryUserId)
                ->where('order_status', 'delivered')
                ->whereDate('updated_at', today())
                ->count(),
                
            'totalDeliveredCount' => Order::where('delivery_id', $deliveryUserId)
                ->where('order_status', 'delivered')
                ->count(),
        ];

        // Get available orders for quick pickup (PROCESSING status)
        $availableOrders = Order::where('order_status', 'processing')
            ->whereNull('delivery_id')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Get current delivery person's active orders
        $myActiveOrders = Order::where('delivery_id', $deliveryUserId)
            ->whereIn('order_status', ['shipped', 'out_for_delivery'])
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->take(3)
            ->get();

        // Get recent delivered orders
        $recentDelivered = Order::where('delivery_id', $deliveryUserId)
            ->where('order_status', 'delivered')
            ->with('user')
            ->orderBy('delivered_at', 'desc')
            ->take(5)
            ->get();

        return view('delivery.dashboard', compact(
            'stats', 
            'availableOrders', 
            'myActiveOrders', 
            'recentDelivered'
        ));
    }
}