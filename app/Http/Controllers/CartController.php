<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private function getCartIdentifier()
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id()];
        } else {
            $sessionId = session()->get('cart_session_id');
            if (!$sessionId) {
                $sessionId = Str::random(40);
                session()->put('cart_session_id', $sessionId);
            }
            return ['session_id' => $sessionId];
        }
    }

    public function index()
    {
        $cartIdentifier = $this->getCartIdentifier();
        $cartItems = Cart::with('product')
            ->where($cartIdentifier)
            ->get();

        $subtotal = $cartItems->sum('total_price');
        $tax = $subtotal * 0.10;
        $shipping = $subtotal > 100 ? 0 : 10;
        $total = $subtotal + $tax + $shipping;

        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'selected_size' => 'required|string|max:50'
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = $product->getVariantBySize($request->selected_size);

        if (!$variant) {
            return redirect()->back()->with('error', 'Selected size is not available for this product.');
        }

        if ($variant->stock_quantity < $request->quantity) {
            return redirect()->back()->with('error', "Insufficient stock for {$request->selected_size} size. Only {$variant->stock_quantity} available.");
        }

        $cartIdentifier = $this->getCartIdentifier();

        // Check if same product with same size already exists in cart
        $cartItem = Cart::where($cartIdentifier)
            ->where('product_id', $request->product_id)
            ->where('selected_size', $request->selected_size)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($variant->stock_quantity < $newQuantity) {
                return redirect()->back()->with('error', "Cannot add more items. Only {$variant->stock_quantity} available for {$request->selected_size} size.");
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            Cart::create(array_merge($cartIdentifier, [
                'product_id' => $request->product_id,
                'selected_size' => $request->selected_size,
                'quantity' => $request->quantity
            ]));
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, Cart $cart)
    {
        // Authorization check - ensure user owns this cart item
        $this->authorizeCartItem($cart);

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'selected_size' => 'required|string|max:50'
        ]);

        $variant = $cart->product->getVariantBySize($request->selected_size);
        
        if (!$variant) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected size is not available for this product.'
                ], 422);
            }
            return redirect()->back()->with('error', 'Selected size is not available for this product.');
        }

        if ($variant->stock_quantity < $request->quantity) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for {$request->selected_size} size. Only {$variant->stock_quantity} available."
                ], 422);
            }
            return redirect()->back()->with('error', "Insufficient stock for {$request->selected_size} size. Only {$variant->stock_quantity} available.");
        }

        $cartIdentifier = $this->getCartIdentifier();

        // Check if changing to a size that already exists for this product
        if ($cart->selected_size !== $request->selected_size) {
            $existingCartItem = Cart::where($cartIdentifier)
                ->where('product_id', $cart->product_id)
                ->where('selected_size', $request->selected_size)
                ->where('id', '!=', $cart->id)
                ->first();

            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $request->quantity;
                if ($variant->stock_quantity < $newQuantity) {
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => "Cannot merge items. Only {$variant->stock_quantity} available for {$request->selected_size} size."
                        ], 422);
                    }
                    return redirect()->back()->with('error', "Cannot merge items. Only {$variant->stock_quantity} available for {$request->selected_size} size.");
                }
                
                // Merge with existing item
                $existingCartItem->quantity = $newQuantity;
                $existingCartItem->save();
                $cart->delete();
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Item updated successfully!',
                        'redirect' => true
                    ]);
                }
                return redirect()->route('cart.index')->with('success', 'Item updated successfully!');
            }
        }

        // Update the current cart item
        $cart->update([
            'quantity' => $request->quantity,
            'selected_size' => $request->selected_size
        ]);

        // Reload the cart item with fresh data
        $cart->load('product');

        if ($request->ajax()) {
            // Calculate updated totals
            $cartIdentifier = $this->getCartIdentifier();
            $cartItems = Cart::with('product')->where($cartIdentifier)->get();
            
            $subtotal = $cartItems->sum('total_price');
            $tax = $subtotal * 0.10;
            $shipping = $subtotal > 100 ? 0 : 10;
            $total = $subtotal + $tax + $shipping;

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'item_total' => number_format($cart->total_price, 2),
                'summary' => [
                    'subtotal' => number_format($subtotal, 2),
                    'tax' => number_format($tax, 2),
                    'shipping' => number_format($shipping, 2),
                    'total' => number_format($total, 2)
                ]
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    public function destroy(Cart $cart)
    {
        // Authorization check - ensure user owns this cart item
        $this->authorizeCartItem($cart);

        $cart->delete();
        return redirect()->route('cart.index')->with('success', 'Item removed from cart successfully!');
    }

    public function getCartCount()
    {
        $cartIdentifier = $this->getCartIdentifier();
        $count = Cart::where($cartIdentifier)->sum('quantity');
        return response()->json(['count' => $count]);
    }

    private function authorizeCartItem(Cart $cart)
    {
        if (Auth::check()) {
            if ($cart->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            $sessionId = session()->get('cart_session_id');
            if ($cart->session_id !== $sessionId) {
                abort(403, 'Unauthorized action.');
            }
        }
    }
}