<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $deliveryUserId = Auth::id();
        
        $orders = Order::where(function($query) use ($deliveryUserId) {
                $query->where('delivery_id', $deliveryUserId)
                      ->whereIn('order_status', ['shipped', 'out_for_delivery']);
            })
            ->orWhere(function($query) {
                // CHANGE: From 'processing' to 'confirmed'
                $query->where('order_status', 'confirmed')
                      ->whereNull('delivery_id');
            })
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.index', compact('orders'));
    }

    public function pickup()
    {
        // CHANGE: From 'processing' to 'confirmed'
        $orders = Order::where('order_status', 'confirmed')
            ->whereNull('delivery_id')
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.pickup', compact('orders'));
    }

    public function delivered()
    {
        $deliveryUserId = Auth::id();
        
        $orders = Order::where('delivery_id', $deliveryUserId)
            ->where('order_status', 'delivered')
            ->with(['user', 'orderItems.product'])
            ->orderBy('delivered_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.delivered', compact('orders'));
    }

    public function myOrders()
    {
        $deliveryUserId = Auth::id();
        
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

        $order->load(['user', 'orderItems.product', 'orderItems.product.images', 'statusHistory']);

        return view('delivery.orders.show', compact('order'));
    }

    public function markAsPickedUp(Order $order, Request $request)
    {
        $deliveryUserId = Auth::id();
        
        // Check if order can be picked up using model method
        if (!$order->canBePickedUp()) {
            return redirect()->back()->with('error', 'This order is no longer available for pickup.');
        }

        try {
            // ✅ FIX: Use updateStatus LIKE ADMIN DOES - This creates proper timeline entries!
            DB::transaction(function () use ($order, $deliveryUserId) {
                // First assign delivery person and update timestamps
                $order->update([
                    'delivery_id' => $deliveryUserId,
                    'picked_up_at' => now(),
                    'assigned_at' => now(),
                ]);
                
                // ✅ USE updateStatus (same as admin) - This creates timeline entries!
                $order->updateStatus('shipped', 'Order picked up by delivery personnel');
            });

            return redirect()->route('delivery.orders.index')
                ->with('success', 'Order #' . $order->order_number . ' has been assigned to you and marked as shipped!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark order as picked up: ' . $e->getMessage());
        }
    }

    public function markAsDelivered(Order $order, Request $request)
    {
        $deliveryUserId = Auth::id();
        
        // Verify the order belongs to the current delivery driver
        if ($order->delivery_id !== $deliveryUserId) {
            return redirect()->back()->with('error', 'Unauthorized action. This order is not assigned to you.');
        }

        // Check if order can be marked as delivered using model method
        if (!$order->canBeMarkedAsDelivered()) {
            return redirect()->back()->with('error', 'This order cannot be marked as delivered. Current status: ' . $order->order_status);
        }

        try {
            // ✅ FIX: Use updateStatus LIKE ADMIN DOES - This creates proper timeline entries!
            $order->updateStatus('delivered', 'Order delivered by delivery personnel');

            return redirect()->route('delivery.orders.index')
                ->with('success', 'Order #' . $order->order_number . ' has been marked as delivered successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark order as delivered: ' . $e->getMessage());
        }
    }

    public function markAsOutForDelivery(Order $order, Request $request)
    {
        $deliveryUserId = Auth::id();
        
        if ($order->delivery_id !== $deliveryUserId) {
            return redirect()->back()->with('error', 'Unauthorized action. This order is not assigned to you.');
        }

        if ($order->order_status !== 'shipped') {
            return redirect()->back()->with('error', 'Order must be in shipped status to mark as out for delivery.');
        }

        try {
            $order->updateStatus('out_for_delivery', 'Order is out for delivery');

            return redirect()->back()->with('success', 'Order marked as out for delivery successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    public function updateDeliveryNotes(Order $order, Request $request)
    {
        $deliveryUserId = Auth::id();
        
        if ($order->delivery_id !== $deliveryUserId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'delivery_notes' => 'nullable|string|max:500'
        ]);

        try {
            $order->update([
                'notes' => $request->delivery_notes
            ]);

            return response()->json(['success' => 'Delivery notes updated successfully']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update notes'], 500);
        }
    }

    public function claimOrder(Order $order, Request $request)
    {
        // Alias for markAsPickedUp
        return $this->markAsPickedUp($order, $request);
    }

    public function getOrderStats()
    {
        $deliveryUserId = Auth::id();
        
        $stats = [
            'total_assigned' => Order::where('delivery_id', $deliveryUserId)
                ->whereIn('order_status', ['shipped', 'out_for_delivery'])
                ->count(),
                
            // CHANGE: From 'processing' to 'confirmed'
            'available_for_pickup' => Order::where('order_status', 'confirmed')
                ->whereNull('delivery_id')
                ->count(),
                
            'delivered_today' => Order::where('delivery_id', $deliveryUserId)
                ->where('order_status', 'delivered')
                ->whereDate('delivered_at', today())
                ->count(),
                
            'total_delivered' => Order::where('delivery_id', $deliveryUserId)
                ->where('order_status', 'delivered')
                ->count(),
        ];

        return response()->json($stats);
    }

    public function searchOrders(Request $request)
    {
        $deliveryUserId = Auth::id();
        $search = $request->get('search');
        
        $orders = Order::where(function($query) use ($deliveryUserId, $search) {
                $query->where('delivery_id', $deliveryUserId)
                      ->whereIn('order_status', ['shipped', 'out_for_delivery'])
                      ->where(function($q) use ($search) {
                          $q->where('order_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%")
                            ->orWhere('customer_phone', 'like', "%{$search}%");
                      });
            })
            ->orWhere(function($query) use ($search) {
                // CHANGE: From 'processing' to 'confirmed'
                $query->where('order_status', 'confirmed')
                      ->whereNull('delivery_id')
                      ->where(function($q) use ($search) {
                          $q->where('order_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%")
                            ->orWhere('customer_phone', 'like', "%{$search}%");
                      });
            })
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.index', compact('orders'));
    }
}