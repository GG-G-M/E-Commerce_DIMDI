<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status', 'active');
        
        $orders = Order::with('user')
            ->when($search, function($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%")
                      ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            })
            ->when($status, function($query) use ($status) {
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
            'delivered' => 'Delivered',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'all' => 'All Orders'
        ];

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'completed', 'cancelled'];
        
        $request->validate([
            'order_status' => 'required|in:' . implode(',', $validStatuses),
            'status_notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $order->order_status;
        $newStatus = $request->order_status;
        $notes = $request->status_notes;

        // Use the new updateStatus method from Order model
        $order->updateStatus($newStatus, $notes);

        return redirect()->back()->with('success', "Order status updated from " . ucfirst($oldStatus) . " to " . ucfirst($newStatus) . " successfully!");
    }

    // NEW METHOD: Show refund page
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

    // UPDATED METHOD: Process refund
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