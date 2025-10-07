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

        if ($product->stock_quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Insufficient stock available.');
        }

        $cartIdentifier = $this->getCartIdentifier();

        // Check if same product with same size already exists in cart
        $cartItem = Cart::where($cartIdentifier)
            ->where('product_id', $request->product_id)
            ->where('selected_size', $request->selected_size)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
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

        if ($cart->product->stock_quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Insufficient stock available.');
        }

        $cartIdentifier = $this->getCartIdentifier();

        // Check if changing to a size that already exists for this product
        if ($cart->selected_size !== $request->selected_size) {
            $existingCartItem = Cart::where($cartIdentifier)
                ->where('product_id', $cart->product_id)
                ->where('selected_size', $request->selected_size)
                ->where('id', '!=', $cart->id) // Exclude current item
                ->first();

            if ($existingCartItem) {
                // Merge with existing item
                $existingCartItem->quantity += $request->quantity;
                $existingCartItem->save();
                $cart->delete();
                
                return redirect()->route('cart.index')->with('success', 'Item updated successfully!');
            }
        }

        // Update the current cart item
        $cart->update([
            'quantity' => $request->quantity,
            'selected_size' => $request->selected_size
        ]);

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