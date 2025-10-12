<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $orders = Order::where('user_id', Auth::id())->latest()->paginate(10);
        } else {
            $orders = Order::where('user_id', null)
                ->where('customer_email', session()->get('guest_email'))
                ->latest()
                ->paginate(10);
        }

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Authorization check
        $this->authorizeOrderView($order);

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    public function create()
    {
        // Redirect to login if not authenticated
        if (!Auth::check()) {
            session(['url.intended' => route('orders.create')]);
            return redirect()->route('login')->with('info', 'Please login or register to complete your order.');
        }

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validate stock before showing checkout
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            if ($product->all_sizes && count($product->all_sizes) > 0) {
                if (!$product->isSizeInStock($cartItem->selected_size)) {
                    return redirect()->route('cart.index')->with('error', "Sorry, {$product->name} in size {$cartItem->selected_size} is no longer available.");
                }
                
                $stock = $product->getStockForSize($cartItem->selected_size);
                if ($stock < $cartItem->quantity) {
                    return redirect()->route('cart.index')->with('error', "Sorry, {$product->name} in size {$cartItem->selected_size} only has {$stock} items available.");
                }
            } else {
                if ($product->stock_quantity < $cartItem->quantity) {
                    return redirect()->route('cart.index')->with('error', "Sorry, {$product->name} only has {$product->stock_quantity} items available.");
                }
            }
        }

        $subtotal = $cartItems->sum('total_price');
        $tax = $subtotal * 0.10;
        $shipping = $subtotal > 100 ? 0 : 10;
        $total = $subtotal + $tax + $shipping;

        $user = Auth::user();

        return view('orders.create', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total', 'user'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to place an order.');
        }

        $request->validate([
            'shipping_address' => 'required|string|min:10',
            'billing_address' => 'required|string|min:10',
            'payment_method' => 'required|in:card,bank_transfer',
            'customer_phone' => 'sometimes|string|max:20'
        ]);

        $cartItems = Cart::with(['product.variants'])
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validate stock before creating order
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            
            if ($product->has_variants) {
                $variant = $product->variants->first(function($v) use ($cartItem) {
                    return ($v->size === $cartItem->selected_size) || ($v->variant_name === $cartItem->selected_size);
                });
                
                if (!$variant) {
                    return redirect()->route('cart.index')->with('error', "Sorry, {$product->name} in variant {$cartItem->selected_size} is no longer available.");
                }
                
                if ($variant->stock_quantity < $cartItem->quantity) {
                    return redirect()->route('cart.index')->with('error', "Sorry, {$product->name} in variant {$cartItem->selected_size} only has {$variant->stock_quantity} items available.");
                }
            } else {
                if ($product->stock_quantity < $cartItem->quantity) {
                    return redirect()->route('cart.index')->with('error', "Sorry, {$product->name} only has {$product->stock_quantity} items available.");
                }
            }
        }

        // Calculate totals
        $subtotal = $cartItems->sum('total_price');
        $tax = $subtotal * 0.10;
        $shipping = $subtotal > 100 ? 0 : 10;
        $total = $subtotal + $tax + $shipping;

        $user = Auth::user();

        // Create order
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $request->customer_phone ?: $user->phone,
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_cost' => $shipping,
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'order_status' => 'pending',
                'notes' => $request->notes
            ]);

            // Create order items and update product stock
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->current_price,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $cartItem->total_price,
                    'selected_size' => $cartItem->selected_size
                ]);

                // Update product stock
                if ($product->has_variants) {
                    $variant = $product->variants->first(function($v) use ($cartItem) {
                        return ($v->size === $cartItem->selected_size) || ($v->variant_name === $cartItem->selected_size);
                    });
                    if ($variant) {
                        $variant->decrement('stock_quantity', $cartItem->quantity);
                    }
                } else {
                    $product->decrement('stock_quantity', $cartItem->quantity);
                }
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully! Your order number is: ' . $order->order_number);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to place order. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function cancel(Request $request, Order $order)
    {
        // Authorization check
        $this->authorizeOrderView($order);

        if (!$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'This order cannot be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $order->update([
            'order_status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_at' => now()
        ]);

        // Restore product stock - FIXED VERSION
        foreach ($order->items as $item) {
            $product = $item->product;
            
            if ($product) {
                // Check if product has variants
                if ($product->has_variants && $item->selected_size) {
                    // Find the specific variant that was ordered
                    $variant = $product->variants->first(function($v) use ($item) {
                        return ($v->size === $item->selected_size) || ($v->variant_name === $item->selected_size);
                    });
                    
                    if ($variant) {
                        // Restore variant stock
                        $variant->increment('stock_quantity', $item->quantity);
                    } else {
                        // Fallback: restore main product stock if variant not found
                        $product->increment('stock_quantity', $item->quantity);
                    }
                } else {
                    // For products without variants, restore main product stock
                    $product->increment('stock_quantity', $item->quantity);
                }
            }
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order has been cancelled successfully.');
    }

    private function authorizeOrderView(Order $order)
    {
        if (Auth::check()) {
            if ($order->user_id !== Auth::id()) {
                abort(404);
            }
        } else {
            if ($order->user_id !== null || $order->customer_email !== session()->get('guest_email')) {
                abort(404);
            }
        }
    }
}