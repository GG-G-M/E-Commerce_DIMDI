<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Notifications\OrderPlaced;
use App\Notifications\OrderStatusUpdated;
use App\Notifications\PaymentReceived;
use App\Services\ShippingCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Notification;


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

        // Check if there are selected items from multi-select checkout
        $selectedItemIds = session()->get('selected_cart_items');

        $cartItemsQuery = Cart::with('product')
            ->where('user_id', Auth::id());

        // If selected items exist, load only those
        if ($selectedItemIds && is_array($selectedItemIds) && count($selectedItemIds) > 0) {
            $cartItems = $cartItemsQuery->whereIn('id', $selectedItemIds)->get();
        } else {
            // Otherwise load all cart items (backward compatibility)
            $cartItems = $cartItemsQuery->get();
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validate stock before showing checkout
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;

            if ($product->has_variants) {
                $variant = $product->variants->first(function ($v) use ($cartItem) {
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
        // For now, use default shipping until customer enters address
        // Shipping will be recalculated in store() with actual GPS coordinates
        $shipping = 0; // Will be calculated based on address
        $total = $subtotal + $shipping;

        $user = Auth::user();

        return view('orders.create', compact('cartItems', 'subtotal', 'shipping', 'total', 'user'));
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
            'customer_phone' => 'sometimes|string|max:20',
            'shipping_latitude' => 'nullable|numeric|between:-90,90',
            'shipping_longitude' => 'nullable|numeric|between:-180,180'
        ]);

        // Check if there are selected items from multi-select checkout
        $selectedItemIds = session()->get('selected_cart_items');

        $cartItemsQuery = Cart::with(['product.variants'])
            ->where('user_id', Auth::id());

        // If selected items exist, load only those
        if ($selectedItemIds && is_array($selectedItemIds) && count($selectedItemIds) > 0) {
            $cartItems = $cartItemsQuery->whereIn('id', $selectedItemIds)->get();
        } else {
            // Otherwise load all cart items (backward compatibility)
            $cartItems = $cartItemsQuery->get();
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validate stock before creating order
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;

            if ($product->has_variants) {
                $variant = $product->variants->first(function ($v) use ($cartItem) {
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
        // Ensure we have the current user available for fallback address construction
        $user = Auth::user();

        // Determine coordinates: use provided coordinates or estimate from address
        $latitude = $request->input('shipping_latitude');
        $longitude = $request->input('shipping_longitude');

        if (empty($latitude) || empty($longitude)) {
            // Build a fallback address string: prefer submitted shipping_address, else use user's profile
            $address = $request->input('shipping_address');
            if (empty($address) && $user) {
                $parts = [];
                if (!empty($user->street_address)) $parts[] = $user->street_address;
                if (!empty($user->barangay)) $parts[] = $user->barangay;
                if (!empty($user->city)) $parts[] = $user->city;
                if (!empty($user->province)) $parts[] = $user->province;
                if (!empty($user->region)) $parts[] = $user->region;
                if (!empty($user->country)) $parts[] = $user->country;
                $address = implode(', ', $parts);
            }

            // Use AddressController estimator to get approximate coordinates
            $estimated = \App\Http\Controllers\AddressController::estimateCoordinatesFromAddress($address ?? '');
            $latitude = $estimated['latitude'];
            $longitude = $estimated['longitude'];
        }

        // Calculate shipping fee based on distance from pivot point
        $shippingResult = ShippingCalculationService::calculateShippingFeeWithFallback(
            $latitude,
            $longitude,
            100 // Default fallback fee of 100 PHP if no zone matches
        );
        $shipping = $shippingResult['fee'];
        $total = $subtotal + $shipping;

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
                'shipping_cost' => $shipping,
                'tax_amount' => 0,
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

            // Send order placed notification immediately so DB notification is present
            // even if queue workers are not running (use sendNow to bypass queue).
            // Original: $user->notifyNow(new OrderPlaced($order)); // Undefined method
            \Illuminate\Support\Facades\Notification::sendNow($user, new OrderPlaced($order));

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
                // Note: updateStatus('confirmed') already calls reduceStock() internally
                $order->updateStatus('confirmed', 'Payment received via ' . ucfirst($request->payment_method));

                // Send status update + receipt notification (safe)
                $this->safeNotify($user, new OrderStatusUpdated($order, 'pending', 'confirmed', 'Payment received via ' . ucfirst($request->payment_method)));
                $this->safeNotify($user, new PaymentReceived($order));

                // Clear selected items from cart if multi-select was used
                if ($selectedItemIds && is_array($selectedItemIds) && count($selectedItemIds) > 0) {
                    Cart::where('user_id', Auth::id())
                        ->whereIn('id', $selectedItemIds)
                        ->delete();
                    session()->forget('selected_cart_items');
                } else {
                    // Otherwise clear entire cart (backward compatibility)
                    Cart::where('user_id', Auth::id())->delete();
                }

                return redirect()->route('orders.show', $order)
                    ->with('success', 'Order placed successfully! Your order number is: ' . $order->order_number);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to place order. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

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
                $order->updateStatus('confirmed', 'Payment received via ');

                // Create notification for payment success
                if ($order->user) {
                    $notificationData = [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'message' => "Payment received for Order #{$order->order_number}. Your order is now being processed.",
                        'icon' => 'fas fa-credit-card',
                        'color' => 'success',
                        'url' => route('orders.show', $order),
                        'receipt_view_url' => route('orders.receipt.preview', $order),
                        'receipt_download_url' => route('orders.receipt.download', $order),
                        'status_display' => 'Payment Received',
                        'amount' => '₱' . number_format($order->total_amount, 2),
                        'time_ago' => 'Just now'
                    ];

                    // Create the notification record
                    $order->user->notifications()->create([
                        'type' => 'App\Notifications\PaymentReceived',
                        'data' => $notificationData,
                        'read_at' => null
                    ]);

                    // Also trigger the OrderStatusUpdated notification if you have it (safe)
                    if (class_exists('App\Notifications\OrderStatusUpdated')) {
                        $this->safeNotify($order->user, new \App\Notifications\OrderStatusUpdated($order, 'pending', 'confirmed', 'Payment completed successfully'));
                    }
                }

                // Clear selected items from cart if multi-select was used
                $selectedItemIds = session()->get('selected_cart_items');
                if ($selectedItemIds && is_array($selectedItemIds) && count($selectedItemIds) > 0) {
                    Cart::where('user_id', $order->user_id)
                        ->whereIn('id', $selectedItemIds)
                        ->delete();
                    session()->forget('selected_cart_items');
                } else {
                    // Otherwise clear entire cart (backward compatibility)
                    Cart::where('user_id', $order->user_id)->delete();
                }

                session()->forget('last_order_id');

                return redirect()->route('orders.show', $order)
                    ->with('success', 'Payment completed successfully! Your order is now confirmed.')
                    ->with('notification', [
                        'type' => 'success',
                        'message' => 'Payment successful! Check your notifications for details.',
                        'order_number' => $order->order_number
                    ]);
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

        // Store old status for notification
        $oldStatus = $order->order_status;

        // Use the model's updateStatus method which handles the timeline and stock restoration
        $order->updateStatus('cancelled', $request->cancellation_reason);

        // Send cancellation notification (safe)
        if ($order->user) {
            $this->safeNotify($order->user, new OrderStatusUpdated($order, $oldStatus, 'cancelled', $request->cancellation_reason));
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

    /**
     * Download receipt for order
     */
    public function downloadReceipt(Order $order)
    {
        // Authorization check
        $this->authorizeOrderView($order);

        $order->load(['items.product', 'user']);

        // Generate PDF using the alias
        $pdf = PDF::loadView('receipt.pdf', compact('order'));

        return $pdf->download("receipt-{$order->order_number}.pdf");
    }

    /**
     * Preview receipt in browser
     */
    public function previewReceipt(Order $order)
    {
        // Authorization check
        $this->authorizeOrderView($order);

        $order->load(['items.product', 'user']);

        // Generate PDF using the alias
        $pdf = PDF::loadView('receipt.pdf', compact('order'));

        return $pdf->stream("receipt-{$order->order_number}.pdf");
    }

    /**
     * Safely notify a user (resolves user model when possible).
     *
     * @param mixed $userOrId
     * @param \Illuminate\Contracts\Support\Renderable|\Illuminate\Notifications\Notification $notification
     * @return void
     */
    private function safeNotify($userOrId, $notification)
    {
        // If null, nothing to do
        if (empty($userOrId)) return;

        // If it's already a User model and has notify method, use it
        if (is_object($userOrId) && method_exists($userOrId, 'notify')) {
            try {
                $userOrId->notify($notification);
                return;
            } catch (\Throwable $e) {
                // fallthrough to Notification facade
            }
        }

        // If it's an ID, try to resolve the user model
        $user = null;
        if (is_numeric($userOrId)) {
            $user = User::find($userOrId);
        } elseif (is_object($userOrId) && property_exists($userOrId, 'id')) {
            $user = User::find($userOrId->id);
        }

        if ($user) {
            try {
                $user->notify($notification);
                return;
            } catch (\Throwable $e) {
                // fallback
            }
        }

        // Last resort: use Notification facade if possible
        try {
            Notification::route('mail', config('mail.default'))->notify($notification);
        } catch (\Throwable $e) {
            // give up silently
        }
    }

    /**
     * Calculate shipping fee based on delivery coordinates or address
     * Called via AJAX from checkout form
     * Accepts either: (latitude + longitude) OR (address)
     */
    public function calculateShipping(Request $request)
    {
        // Accept either coordinates or address
        $request->validate([
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address' => 'nullable|string|min:5'
        ]);

        try {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            // If coordinates not provided, estimate from address
            if (empty($latitude) || empty($longitude)) {
                $address = $request->input('address');
                if (empty($address)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Address or coordinates required',
                    ], 400);
                }

                // Use address estimator to get approximate coordinates
                $estimated = \App\Http\Controllers\AddressController::estimateCoordinatesFromAddress($address);
                $latitude = $estimated['latitude'];
                $longitude = $estimated['longitude'];
            }

            $result = ShippingCalculationService::calculateShippingFeeWithFallback(
                $latitude,
                $longitude,
                100 // Default fallback fee
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'shipping_fee' => $result['fee'],
                    'distance' => $result['distance'] ?? null,
                    'zone_name' => $result['zone_name'] ?? null,
                    'zone_id' => $result['zone_id'] ?? null,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Unable to calculate shipping fee',
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Shipping calculation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error calculating shipping fee',
            ], 500);
        }
    }
}
