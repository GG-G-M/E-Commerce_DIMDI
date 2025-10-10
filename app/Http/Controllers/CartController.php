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
                $sessionId = Str::uuid()->toString();
                session(['cart_session_id' => $sessionId]);
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
        ]);

        $product = Product::findOrFail($request->product_id);

        // ✅ Check stock before adding
        if ($product->stock_quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Sorry, this product is out of stock.');
        }

        $cartIdentifier = $this->getCartIdentifier();

        // ✅ Add or update product in cart
        $cartItem = Cart::where($cartIdentifier)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            Cart::create(array_merge($cartIdentifier, [
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]));
        }

        // ✅ Redirect straight to cart page
        return redirect()->route('cart.index')->with('success', "{$product->name} added to cart successfully!");
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        if ($cart->product->stock_quantity < $request->quantity) {
            return redirect()->route('cart.index')->with('error', 'Not enough stock available.');
        }

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
