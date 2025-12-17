<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Delivery;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status', 'all');

        $orders = Order::with(['user', 'delivery'])
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                if ($status === 'active') {
                    return $query->whereNotIn('order_status', ['cancelled', 'completed', 'delivered']);
                } elseif ($status !== 'all') {
                    return $query->where('order_status', $status);
                }
                return $query;
            })
            ->latest()
            ->paginate(10)
            ->appends($request->all());

        $statuses = [
            'active' => 'Active Orders',
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'all' => 'All Orders'
        ];

        // GET ACTIVE DELIVERY PERSONNEL FOR ASSIGNMENT
        $deliveries = Delivery::active()->get();

        return view('admin.orders.index', compact('orders', 'statuses', 'deliveries'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'delivery']);
        $deliveries = Delivery::active()->get();

        return view('admin.orders.show', compact('order', 'deliveries'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered', 'completed', 'cancelled'];

        $request->validate([
            'order_status' => 'required|in:' . implode(',', $validStatuses),
            'status_notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $order->order_status;
        $newStatus = $request->order_status;
        $notes = $request->status_notes;

        // Use the new updateStatus method from Order model
        $order->updateStatus($newStatus, $notes);

        // AUTO STOCK-OUT WHEN ORDER BECOMES SHIPPED
        if ($newStatus === 'shipped') {
            // If FIFO stock-out already occurred at confirmation, skip to avoid double deduction
            $existingStockOut = \App\Models\StockOut::where('reason', 'like', '%Order #' . $order->id . '%')->exists();
            if (!$existingStockOut) {
                foreach ($order->items as $item) {
                    // Resolve variant id from selected_size when available
                    $variantId = null;
                    if (!empty($item->selected_size) && $item->product) {
                        // First try a DB lookup by variant_name
                        $variant = $item->product->variants()->where('variant_name', $item->selected_size)->first();

                        // Fallback: check the loaded collection (accessors like ->size may exist)
                        if (!$variant) {
                            $variant = $item->product->variants->first(function ($v) use ($item) {
                                return ($v->variant_name === $item->selected_size) || (isset($v->size) && $v->size === $item->selected_size);
                            });
                        }

                        if ($variant) {
                            $variantId = $variant->id;
                        }
                    }

                    app(\App\Http\Controllers\Admin\StockOutController::class)
                        ->autoStockOut(
                            $item->product_id,
                            $variantId,
                            $item->quantity,
                            'Order #' . $order->id . ' shipped'
                        );
                }
            }
        }

        return redirect()->back()->with('success', "Order status updated from " . ucfirst($oldStatus) . " to " . ucfirst($newStatus) . " successfully!");
    }

    // NEW METHOD: Assign order to delivery
    public function assignToDelivery(Request $request, Order $order)
    {
        $request->validate([
            'delivery_id' => 'required|exists:deliveries,id'
        ]);

        // Check if order is ready for delivery assignment
        if (!in_array($order->order_status, ['confirmed', 'processing', 'shipped'])) {
            return redirect()->back()->with('error', 'Order must be in confirmed, processing, or shipped status to assign for delivery.');
        }

        // Check if delivery person is active
        $delivery = Delivery::find($request->delivery_id);
        if (!$delivery->is_active) {
            return redirect()->back()->with('error', 'Selected delivery person is not active.');
        }

        try {
            $order->assignToDelivery($request->delivery_id);

            return redirect()->back()->with('success', 'Order successfully assigned to ' . $delivery->name . '!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to assign delivery: ' . $e->getMessage());
        }
    }

    // NEW METHOD: Unassign delivery from order
    public function unassignDelivery(Order $order)
    {
        if (!$order->delivery_id) {
            return redirect()->back()->with('error', 'Order is not assigned to any delivery person.');
        }

        try {
            $deliveryName = $order->delivery->name;
            $order->update([
                'delivery_id' => null,
                'assigned_at' => null
            ]);

            // Update status back to shipped if it was out for delivery
            if ($order->order_status === 'out_for_delivery') {
                $order->updateStatus('shipped', 'Delivery assignment removed');
            }

            return redirect()->back()->with('success', 'Delivery assignment removed from ' . $deliveryName . ' successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove delivery assignment: ' . $e->getMessage());
        }
    }

    // NEW METHOD: Get orders ready for delivery assignment
    public function getOrdersForDelivery()
    {
        $orders = Order::readyForDelivery()
            ->with('user')
            ->latest()
            ->get();

        $deliveries = Delivery::active()->get();

        return view('admin.orders.delivery-assignment', compact('orders', 'deliveries'));
    }

    // NEW METHOD: Bulk assign delivery
    public function bulkAssignDelivery(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'delivery_id' => 'required|exists:deliveries,id'
        ]);

        $delivery = Delivery::find($request->delivery_id);
        $assignedCount = 0;

        foreach ($request->order_ids as $orderId) {
            $order = Order::find($orderId);

            // Check if order is ready for delivery assignment
            if (in_array($order->order_status, ['confirmed', 'processing', 'shipped']) && !$order->delivery_id) {
                $order->assignToDelivery($request->delivery_id);
                $assignedCount++;
            }
        }

        if ($assignedCount > 0) {
            return redirect()->back()->with('success', "Successfully assigned {$assignedCount} orders to {$delivery->name}!");
        } else {
            return redirect()->back()->with('error', 'No orders were assigned. Please check if orders are in the correct status.');
        }
    }

    // NEW METHOD: Show delivery assignment page
    public function showDeliveryAssignment()
    {
        $orders = Order::readyForDelivery()
            ->with('user')
            ->latest()
            ->paginate(10);

        $deliveries = Delivery::active()->get();

        return view('admin.orders.delivery-assignment', compact('orders', 'deliveries'));
    }

    // NEW METHOD: Get available deliveries (for AJAX)
    public function getAvailableDeliveries()
    {
        $deliveries = Delivery::active()->get();
        return response()->json($deliveries);
    }

    // NEW METHOD: Mark order as picked up
    public function markAsPickedUp(Order $order)
    {
        if (!$order->delivery_id) {
            return redirect()->back()->with('error', 'Order must be assigned to a delivery person first.');
        }

        if ($order->order_status !== 'out_for_delivery') {
            return redirect()->back()->with('error', 'Order must be out for delivery to mark as picked up.');
        }

        try {
            $order->markAsPickedUp();
            return redirect()->back()->with('success', 'Order marked as picked up successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark order as picked up: ' . $e->getMessage());
        }
    }

    // NEW METHOD: Mark order as delivered
    public function markAsDelivered(Order $order)
    {
        if (!$order->delivery_id) {
            return redirect()->back()->with('error', 'Order must be assigned to a delivery person first.');
        }

        if (!in_array($order->order_status, ['out_for_delivery', 'shipped'])) {
            return redirect()->back()->with('error', 'Order must be out for delivery or shipped to mark as delivered.');
        }

        try {
            $order->markAsDelivered();
            return redirect()->back()->with('success', 'Order marked as delivered successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark order as delivered: ' . $e->getMessage());
        }
    }

    // REFUND METHODS (keep your existing refund methods)
    public function showRefund(Order $order)
    {
        // Check if order is cancelled and not already refunded
        if ($order->order_status !== 'cancelled') {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Only cancelled orders can be refunded.');
        }

        if ($order->refund_processed) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'This order has already been refunded.');
        }

        return view('admin.orders.refund', compact('order'));
    }

    public function processRefund(Order $order, Request $request)
    {
        // Check if order is eligible for refund
        if ($order->order_status !== 'cancelled') {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Only cancelled orders can be refunded.');
        }

        if ($order->refund_processed) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'This order has already been refunded.');
        }

        // Validate the request
        $request->validate([
            'refund_amount' => 'required|numeric|min:0|max:' . $order->total_amount,
            'refund_method' => 'required|string|in:original_payment,bank_transfer,store_credit,cash',
            'refund_notes' => 'nullable|string|max:500',
            'send_notification' => 'boolean'
        ]);

        try {
            // Process refund
            $order->update([
                'refund_processed' => true,
                'refund_amount' => $request->refund_amount,
                'refund_method' => $request->refund_method,
                'refund_notes' => $request->refund_notes,
                'refund_processed_at' => now(),
            ]);

            // Send notification if requested
            if ($request->send_notification) {
                // Add your notification logic here
                // Mail::to($order->customer_email)->send(new OrderRefundProcessed($order));
            }

            return redirect()->route('admin.orders.index')
                ->with('success', 'Refund processed successfully for order #' . $order->order_number . '!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process refund: ' . $e->getMessage())
                ->withInput();
        }
    }
}
