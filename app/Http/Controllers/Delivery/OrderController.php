<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $deliveryUserId = Auth::id();
        
        // Get orders assigned to current delivery person AND available orders
        $orders = Order::where(function($query) use ($deliveryUserId) {
                // Orders assigned to current delivery person
                $query->where('delivery_id', $deliveryUserId)
                      ->whereIn('order_status', ['shipped', 'out_for_delivery']);
            })
            ->orWhere(function($query) {
                // Available orders that anyone can pick up (PROCESSING status)
                $query->where('order_status', 'processing')
                      ->whereNull('delivery_id');
            })
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.index', compact('orders'));
    }

    public function pickup()
    {
        // Show ONLY available orders (processing status, no delivery person assigned)
        $orders = Order::where('order_status', 'processing')
            ->whereNull('delivery_id')
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.pickup', compact('orders'));
    }

    public function delivered()
    {
        $deliveryUserId = Auth::id();
        
        // Show only orders delivered by current delivery person
        $orders = Order::where('delivery_id', $deliveryUserId)
            ->where('order_status', 'delivered')
            ->with(['user', 'orderItems.product'])
            ->orderBy('updated_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.delivered', compact('orders'));
    }

    public function myOrders()
    {
        $deliveryUserId = Auth::id();
        
        // Show only orders currently assigned to this delivery person
        $orders = Order::where('delivery_id', $deliveryUserId)
            ->whereIn('order_status', ['shipped', 'out_for_delivery'])
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.my-orders', compact('orders'));
    }

    public function show(Order $order)
    {
        $deliveryUserId = Auth::id();
        
        // Allow viewing if:
        // 1. Order is assigned to current delivery person, OR
        // 2. Order is available for pickup (no delivery person assigned)
        if ($order->delivery_id !== $deliveryUserId && !is_null($order->delivery_id)) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['user', 'orderItems.product', 'orderItems.product.images']);

        return view('delivery.orders.show', compact('order'));
    }

    public function markAsPickedUp(Order $order, Request $request)
{
    $deliveryUserId = Auth::id();
    
    // Check if order is available for pickup (should be 'processing' status)
    if ($order->order_status !== 'processing' || !is_null($order->delivery_id)) {
        return redirect()->back()->with('error', 'This order is no longer available for pickup.');
    }

    // Assign order to current delivery person and update status to 'shipped'
    $order->update([
        'delivery_id' => $deliveryUserId,
        'order_status' => 'shipped', // Automatically change status to shipped
        'assigned_at' => now(),
        // Remove picked_up_at and let updated_at track the timestamp
    ]);

    return redirect()->route('delivery.orders.index')
        ->with('success', 'Order #' . $order->order_number . ' has been assigned to you and marked as shipped!');
}
    public function markAsDelivered(Order $order, Request $request)
    {
        $deliveryUserId = Auth::id();
        
        // Verify the order belongs to the current delivery driver
        if ($order->delivery_id !== $deliveryUserId) {
            return redirect()->back()->with('error', 'Unauthorized action. This order is not assigned to you.');
        }

        // Check if order is in correct status for delivery
        if (!in_array($order->order_status, ['shipped', 'out_for_delivery'])) {
            return redirect()->back()->with('error', 'This order cannot be marked as delivered. Current status: ' . $order->order_status);
        }

        // Update order status to 'delivered'
        $order->update([
            'order_status' => 'delivered',
            'delivered_at' => now(),
        ]);

        return redirect()->route('delivery.orders.index')
            ->with('success', 'Order #' . $order->order_number . ' has been marked as delivered successfully!');
    }
    public function claimOrder(Order $order, Request $request)
{
    // This is just an alias for markAsPickedUp
    return $this->markAsPickedUp($order, $request);
}
}