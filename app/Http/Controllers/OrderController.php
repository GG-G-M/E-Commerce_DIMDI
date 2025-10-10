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
            $sessionId = session()->get('cart_session_id');
            $orders = Order::where('user_id', null)
                ->where('customer_email', session()->get('guest_email'))
                ->latest()
                ->paginate(10);
        }

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorizeOrderView($order);
        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    public function create()
    {
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
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:card,bank_transfer,gcash,grab_pay'
        ]);

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // ✅ Validate stock (without variant)
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            if (!$product || $product->stock_quantity < $cartItem->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Sorry, {$product->name} is no longer available in the requested quantity.");
            }
        }

        // ✅ Calculate totals
        $subtotal = $cartItems->sum('total_price');
        $tax = $subtotal * 0.10;
        $shipping = $subtotal > 100 ? 0 : 10;
        $total = $subtotal + $tax + $shipping;

        $user = Auth::user();

        // ✅ Create Order
        $order = Order::create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone,
            'shipping_address' => $request->shipping_address ?: $user->address,
            'billing_address' => $request->billing_address ?: $user->address,
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'shipping_cost' => $shipping,
            'total_amount' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status ?? 'pending',
            'order_status' => 'pending',
            'notes' => $request->notes
        ]);

        // ✅ Create Order Items & Deduct Stock
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_price' => $product->current_price,
                'quantity' => $cartItem->quantity,
                'total_price' => $cartItem->total_price,
            ]);

            // Deduct stock
            $product->decrement('stock_quantity', $cartItem->quantity);
        }

        // ✅ Clear cart
        Cart::where('user_id', Auth::id())->delete();

        session(['last_order_id' => $order->id]);

        // ✅ Payment redirection
        if (in_array($request->payment_method, ['gcash', 'grab_pay'])) {
            try {
                $paymentController = new PaymentController();
                $sourceResponse = $paymentController->createSourceForOrder($order);

                if (isset($sourceResponse['data']['attributes']['redirect']['checkout_url'])) {
                    $checkoutUrl = $sourceResponse['data']['attributes']['redirect']['checkout_url'];
                    session(['last_order_id' => $order->id]);
                    return redirect()->away($checkoutUrl);
                }

                return redirect()->route('orders.show', $order)
                    ->with('error', 'Failed to retrieve PayMongo checkout URL.');

            } catch (\Exception $e) {
                return redirect()->route('orders.show', $order)
                    ->with('error', 'Order created but payment initialization failed: ' . $e->getMessage());
            }
        }

        // ✅ Default redirect
        return redirect()->route('orders.show', $order)
            ->with('success', 'Order placed successfully! Your order number is: ' . $order->order_number);
    }

    public function cancel(Request $request, Order $order)
    {
        $this->authorizeOrderView($order);

        if (!$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'This order cannot be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $order->cancel($request->cancellation_reason);

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
