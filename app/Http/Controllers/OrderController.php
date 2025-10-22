<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            'payment_method' => 'required|in:card,gcash,grab_pay,bank_transfer',
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
                'payment_status' => 'pending',
                'notes' => $request->notes
            ]);

            // Create order items (but DON'T update stock yet - wait for payment success)
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
            }

            // Store order ID in session for payment redirects
            session(['last_order_id' => $order->id]);

            // Handle different payment methods
            if (in_array($request->payment_method, ['gcash', 'grab_pay'])) {
                // For GCash/GrabPay, return JSON response for AJAX handling
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'order_id' => $order->id,
                        'payment_method' => $request->payment_method,
                        'amount' => $total,
                        'message' => 'Order created successfully. Redirecting to payment...'
                    ]);
                }
                
                // If not AJAX, initiate redirect payment directly
                return $this->initiateRedirectPayment($order, $request->payment_method, $total);
                
            } else {
                // For card/bank transfer, update status and reduce stock immediately
                $order->updateStatus('confirmed', 'Payment received via ' . ucfirst($request->payment_method));
                $order->reduceStock(); // Reduce stock for confirmed orders
                
                // Clear cart
                Cart::where('user_id', Auth::id())->delete();
                
                return redirect()->route('orders.show', $order)
                    ->with('success', 'Order placed successfully! Your order number is: ' . $order->order_number);
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to place order. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    // NEW METHOD: Handle GCash/GrabPay redirect
    private function initiateRedirectPayment(Order $order, $paymentMethod, $amount)
    {
        try {
            // Create PayMongo source
            $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
                ->post('https://api.paymongo.com/v1/sources', [
                    'data' => [
                        'attributes' => [
                            'type' => $paymentMethod,
                            'amount' => $amount * 100, // Convert to cents
                            'currency' => 'PHP',
                            'redirect' => [
                                'success' => route('payment.success'),
                                'failed' => route('payment.failed')
                            ]
                        ]
                    ]
                ]);

            $responseData = $response->json();

            if ($response->failed()) {
                throw new \Exception('Failed to create payment source: ' . json_encode($responseData));
            }

            // Update order with source ID
            $order->update([
                'payment_method_id' => $responseData['data']['id'],
                'payment_status' => 'pending'
            ]);

            // Redirect to PayMongo payment page
            return redirect()->away($responseData['data']['attributes']['redirect']['checkout_url']);

        } catch (\Exception $e) {
            // If payment setup fails, delete the order and show error
            $order->delete();
            
            return redirect()->route('cart.index')
                ->with('error', 'Payment setup failed: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order') ?? session('last_order_id');
        
        if ($orderId) {
            $order = Order::find($orderId);
            
            if ($order && $order->payment_status === 'pending') {
                // Update payment status
                $order->update([
                    'payment_status' => 'paid'
                ]);
                
                // Use the model's updateStatus method to update order status and create timeline
                $order->updateStatus('confirmed', 'Payment received via ' );
                $order->reduceStock(); // Reduce stock for confirmed orders
                
                // Clear cart
                Cart::where('user_id', $order->user_id)->delete();
                session()->forget('last_order_id');
                
                return redirect()->route('orders.show', $order)
                    ->with('success', 'Payment completed successfully! Your order is now confirmed.');
            }
        }
        
        return redirect()->route('orders.index')
            ->with('info', 'Payment completed successfully!');
    }

    public function cancel(Order $order, Request $request)
    {
        // Authorization - ensure customer can only cancel their own orders
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Check if order can be cancelled using the model's method
        if (!$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'Order cannot be cancelled at this stage.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        // Use the model's updateStatus method which handles the timeline and stock restoration
        $order->updateStatus('cancelled', $request->cancellation_reason);
        // Note: restoreStock() is automatically called by updateStatus when status is 'cancelled'

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