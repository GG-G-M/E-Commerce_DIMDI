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

    public function cancel(Order $order, Request $request)
    {
        // Authorization - ensure customer can only cancel their own orders
        if ($order->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Check if order can be cancelled
        if (!in_array($order->order_status, ['pending', 'confirmed', 'processing'])) {
            return redirect()->back()->with('error', 'Order cannot be cancelled at this stage.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|min:5|max:500'
        ]);

        // Use the same updateStatus method that admin uses
        $order->updateStatus('cancelled', $request->cancellation_reason);

        // Eager load items with product and variants relationships
        $order->load(['items.product.variants']);

        // Restore stock quantities
        foreach ($order->items as $item) {
            if ($item->product) {
                if ($item->product->has_variants && $item->selected_size) {
                    // Find and update variant stock - FIXED
                    $variant = $item->product->variants->first(function($variant) use ($item) {
                        return ($variant->size === $item->selected_size) || 
                            ($variant->variant_name === $item->selected_size);
                    });
                    
                    if ($variant) {
                        $variant->stock_quantity += $item->quantity;
                        $variant->save();
                    }
                } else {
                    // Update product stock
                    $item->product->stock_quantity += $item->quantity;
                    $item->product->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Order has been cancelled successfully.');
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