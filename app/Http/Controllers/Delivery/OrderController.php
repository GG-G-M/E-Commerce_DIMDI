<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
            $orders = collect([]);
            return view('delivery.orders.index', compact('orders'))
                ->with('error', 'You are not registered in the deliveries system.');
        }
        
        $orders = Order::where(function($query) use ($deliveriesTableId) {
                $query->where('delivery_id', $deliveriesTableId)
                      ->whereIn('order_status', ['shipped', 'out_for_delivery']);
            })
            ->orWhere(function($query) {
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
        $orders = Order::where('order_status', 'confirmed')
            ->whereNull('delivery_id')
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.pickup', compact('orders'));
    }

    public function delivered()
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            $orders = collect([]);
            return view('delivery.orders.delivered', compact('orders'))
                ->with('error', 'You are not registered in the deliveries system.');
        }
        
        $orders = Order::where('delivery_id', $deliveriesTableId)
            ->where('order_status', 'delivered')
            ->with(['user', 'orderItems.product'])
            ->orderBy('delivered_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.delivered', compact('orders'));
    }

    public function myOrders()
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            $orders = collect([]);
            return view('delivery.orders.my-orders', compact('orders'))
                ->with('error', 'You are not registered in the deliveries system.');
        }
        
        $orders = Order::where('delivery_id', $deliveriesTableId)
            ->whereIn('order_status', ['shipped', 'out_for_delivery'])
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

    return view('delivery.orders.my-orders', compact('orders'));
}

    public function show(Order $order)
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            abort(403, 'You are not registered in the deliveries system.');
        }
        
        // Allow viewing if:
        // 1. Order is assigned to current delivery person, OR
        // 2. Order is available for pickup (no delivery person assigned)
        if ($order->delivery_id !== $deliveriesTableId && !is_null($order->delivery_id)) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['user', 'orderItems.product', 'orderItems.product.images', 'statusHistory']);

        return view('delivery.orders.show', compact('order'));
    }

    public function markAsPickedUp(Order $order, Request $request)
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            \Log::error('Delivery staff not found in deliveries table', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email
            ]);
            return redirect()->back()->with('error', 'You are not registered in the deliveries system.');
        }
        
        // Check if order can be picked up using model method
        if (!$order->canBePickedUp()) {
            return redirect()->back()->with('error', 'This order is no longer available for pickup.');
        }

        try {
            DB::transaction(function () use ($order, $deliveriesTableId) {
                // Use deliveries table ID
                $order->update([
                    'delivery_id' => $deliveriesTableId, // This is from deliveries table
                    'picked_up_at' => now(),
                    'assigned_at' => now(),
                ]);
                
                $order->updateStatus('shipped', 'Order picked up by delivery personnel');
            });

        return redirect()->route('delivery.orders.index')
            ->with('success', 'Order #' . $order->order_number . ' has been assigned to you and marked as shipped!');

        } catch (\Exception $e) {
            \Log::error('Failed to mark order as picked up', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to mark order as picked up: ' . $e->getMessage());
        }
    }
    
    public function markAsDelivered(Order $order, Request $request)
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            return redirect()->back()->with('error', 'You are not registered in the deliveries system.');
        }
        
        // Verify the order belongs to the current delivery driver
        if ($order->delivery_id !== $deliveriesTableId) {
            return redirect()->back()->with('error', 'Unauthorized action. This order is not assigned to you.');
        }

        // Check if order can be marked as delivered using model method
        if (!$order->canBeMarkedAsDelivered()) {
            return redirect()->back()->with('error', 'This order cannot be marked as delivered. Current status: ' . $order->order_status);
        }

        try {
            // Use updateStatus LIKE ADMIN DOES - This creates proper timeline entries!
            $order->updateStatus('delivered', 'Order delivered by delivery personnel');

            return redirect()->route('delivery.orders.index')
                ->with('success', 'Order #' . $order->order_number . ' has been marked as delivered successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark order as delivered: ' . $e->getMessage());
        }
    }

    public function markAsOutForDelivery(Order $order, Request $request)
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            return redirect()->back()->with('error', 'You are not registered in the deliveries system.');
        }
        
        if ($order->delivery_id !== $deliveriesTableId) {
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
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            return response()->json(['error' => 'You are not registered in the deliveries system.'], 403);
        }
        
        if ($order->delivery_id !== $deliveriesTableId) {
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
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            return response()->json([
                'total_assigned' => 0,
                'available_for_pickup' => 0,
                'delivered_today' => 0,
                'total_delivered' => 0
            ]);
        }
        
        $stats = [
            'total_assigned' => Order::where('delivery_id', $deliveriesTableId)
                ->whereIn('order_status', ['shipped', 'out_for_delivery'])
                ->count(),
                
            'available_for_pickup' => Order::where('order_status', 'confirmed')
                ->whereNull('delivery_id')
                ->count(),
                
            'delivered_today' => Order::where('delivery_id', $deliveriesTableId)
                ->where('order_status', 'delivered')
                ->whereDate('delivered_at', today())
                ->count(),
                
            'total_delivered' => Order::where('delivery_id', $deliveriesTableId)
                ->where('order_status', 'delivered')
                ->count(),
        ];

        return response()->json($stats);
    }

    public function searchOrders(Request $request)
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        $search = $request->get('search');
        
        if (!$deliveriesTableId) {
            $orders = collect([]);
            return view('delivery.orders.index', compact('orders'))
                ->with('error', 'You are not registered in the deliveries system.');
        }
        
        $orders = Order::where(function($query) use ($deliveriesTableId, $search) {
                $query->where('delivery_id', $deliveriesTableId)
                      ->whereIn('order_status', ['shipped', 'out_for_delivery'])
                      ->where(function($q) use ($search) {
                          $q->where('order_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%")
                            ->orWhere('customer_phone', 'like', "%{$search}%");
                      });
            })
            ->orWhere(function($query) use ($search) {
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