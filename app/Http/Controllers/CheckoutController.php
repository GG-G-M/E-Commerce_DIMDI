<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function buyNow(Request $request, Product $product)
    {
        if (!$product->is_active || $product->stock_quantity <= 0) {
            return redirect()->back()->with('error', 'Product not available.');
        }

        // Create a temporary order for direct checkout
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $product->sale_price > 0 ? $product->sale_price : $product->price,
            'payment_status' => 'pending',
            'order_status' => 'pending'
        ]);

        // Attach the product to the order (you may have an order_items table)
        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->sale_price > 0 ? $product->sale_price : $product->price,
            'subtotal' => $product->sale_price > 0 ? $product->sale_price : $product->price,
        ]);

        // Store last order ID in session for PaymentController
        Session::put('last_order_id', $order->id);

        // Redirect to payment page (or directly trigger your createIntent)
        return redirect()->route('checkout.index');
    }

    public function index()
    {
        $orderId = session('last_order_id');
        if (!$orderId) {
            return redirect()->route('products.index')->with('error', 'No product selected for checkout.');
        }

        $order = Order::with('items.product')->findOrFail($orderId);

        return view('checkout.index', compact('order'));
    }
}
