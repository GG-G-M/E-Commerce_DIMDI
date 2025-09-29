<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $sessionId = session()->get('cart_session_id');
        $orders = Order::when($sessionId, function($query) use ($sessionId) {
            $query->where('user_id', null)->where('customer_email', session()->get('guest_email'));
        })
        ->latest()
        ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Verify order belongs to guest session
        $sessionId = session()->get('cart_session_id');
        if (!$order->user_id && $order->customer_email !== session()->get('guest_email')) {
            abort(404);
        }

        return view('orders.show', compact('order'));
    }

    public function create()
    {
        $sessionId = session()->get('cart_session_id');
        $cartItems = Cart::with('product')
            ->where('session_id', $sessionId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum('total_price');
        $tax = $subtotal * 0.10;
        $shipping = $subtotal > 100 ? 0 : 10;
        $total = $subtotal + $tax + $shipping;

        return view('orders.create', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cash,card,bank_transfer'
        ]);

        $sessionId = session()->get('cart_session_id');
        $cartItems = Cart::with('product')
            ->where('session_id', $sessionId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = $cartItems->sum('total_price');
        $tax = $subtotal * 0.10;
        $shipping = $subtotal > 100 ? 0 : 10;
        $total = $subtotal + $tax + $shipping;

        // Create order
        $order = Order::create([
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'shipping_address' => $request->shipping_address,
            'billing_address' => $request->billing_address ?? $request->shipping_address,
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'shipping_cost' => $shipping,
            'total_amount' => $total,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes
        ]);

        // Create order items and update product stock
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product->name,
                'unit_price' => $cartItem->product->current_price,
                'quantity' => $cartItem->quantity,
                'total_price' => $cartItem->total_price
            ]);

            // Update product stock
            $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
        }

        // Clear cart
        Cart::where('session_id', $sessionId)->delete();

        // Store guest email in session for order tracking
        session()->put('guest_email', $request->customer_email);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order placed successfully! Your order number is: ' . $order->order_number);
    }
}