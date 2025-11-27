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
        $cartItems = Cart::with(['product.variants'])
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

        // Check if selected_size is empty (from index page when no variants in stock)
        if (empty($request->selected_size)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No available variants in stock.'
                ], 422);
            }
            return redirect()->back()->with('error', 'No available variants in stock.');
        }

        $product = Product::with('variants')->findOrFail($request->product_id);
        
        // Get the correct price based on variant or product
        $unitPrice = $product->current_price;
        
        // Check if product has variants/sizes
        if ($product->has_variants) {
            $variant = $product->variants->first(function($v) use ($request) {
                return ($v->size === $request->selected_size) || ($v->variant_name === $request->selected_size);
            });
            
            if (!$variant) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected variant is not available.'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Selected variant is not available.');
            }
            
            // Check if variant is in stock
            if ($variant->stock_quantity <= 0) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected variant is out of stock.'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Selected variant is out of stock.');
            }
            
            $stock = $variant->stock_quantity;
            // Use variant price if available, otherwise use product price
            $unitPrice = $variant->current_price ?? $variant->price ?? $product->current_price;
        } else {
            // For products without variants
            if ($product->stock_quantity <= 0) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product is out of stock.'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Product is out of stock.');
            }
            
            $stock = $product->stock_quantity;
        }

        $cartIdentifier = $this->getCartIdentifier();

        // Check if same product with same size already exists in cart
        $cartItem = Cart::where($cartIdentifier)
            ->where('product_id', $request->product_id)
            ->where('selected_size', $request->selected_size)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            // Check stock again for updated quantity
            if ($newQuantity > $stock) {
                $maxCanAdd = $stock - $cartItem->quantity;
                
                if ($maxCanAdd <= 0) {
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => "Cannot add more items. Only {$stock} available total and you already have {$cartItem->quantity} in cart."
                        ], 422);
                    }
                    return redirect()->back()->with('error', "Cannot add more items. Only {$stock} available total and you already have {$cartItem->quantity} in cart.");
                }
                
                // Limit the quantity to what's actually available
                $newQuantity = $stock;
                $cartItem->update([
                    'quantity' => $newQuantity,
                    'total_price' => $newQuantity * $cartItem->unit_price
                ]);
                
                if ($request->ajax()) {
                    $cartCount = Cart::where($cartIdentifier)->sum('quantity');
                    return response()->json([
                        'success' => true,
                        'message' => "Limited to available stock. Quantity updated to {$newQuantity}.",
                        'cart_count' => $cartCount,
                        'limited' => true
                    ]);
                }
                return redirect()->route('cart.index')->with('warning', "Limited to available stock. Quantity updated to {$newQuantity}.");
            }
            
            // If we have enough stock, update normally
            $cartItem->update([
                'quantity' => $newQuantity,
                'total_price' => $newQuantity * $cartItem->unit_price
            ]);
        } else {
            // For new cart items, also check stock
            if ($request->quantity > $stock) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot add {$request->quantity} items. Only {$stock} available."
                    ], 422);
                }
                return redirect()->back()->with('error', "Cannot add {$request->quantity} items. Only {$stock} available.");
            }
            
            // FIXED: Include unit_price and total_price when creating cart item
            Cart::create(array_merge($cartIdentifier, [
                'product_id' => $request->product_id,
                'selected_size' => $request->selected_size,
                'quantity' => $request->quantity,
                'unit_price' => $unitPrice,
                'total_price' => $request->quantity * $unitPrice
            ]));
        }

        $cartCount = Cart::where($cartIdentifier)->sum('quantity');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, Cart $cart)
    {
        // Authorization check
        $this->authorizeCartItem($cart);

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'selected_size' => 'required|string|max:50'
        ]);

        $product = $cart->product;
        
        // Get the correct price based on variant or product
        $unitPrice = $product->current_price;
        
        // Find the selected variant
        $selectedVariant = null;
        if ($product->has_variants) {
            $selectedVariant = $product->variants->first(function($v) use ($request) {
                return ($v->size === $request->selected_size) || ($v->variant_name === $request->selected_size);
            });
            
            if (!$selectedVariant) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected option is not available.'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Selected option is not available.');
            }
            
            $stock = $selectedVariant->stock_quantity;
            // Use variant price if available
            $unitPrice = $selectedVariant->current_price ?? $selectedVariant->price ?? $product->current_price;
        } else {
            $stock = $product->stock_quantity;
        }

        // If changing variant, adjust quantity to new variant's stock
        $newQuantity = $request->quantity;
        if ($cart->selected_size !== $request->selected_size) {
            // When changing variant, limit quantity to new variant's stock
            $newQuantity = min($request->quantity, $stock);
            
            // If current quantity exceeds new variant's stock, reduce it
            if ($request->quantity > $stock) {
                $newQuantity = $stock;
            }
        } else {
            // When just updating quantity, check stock
            if ($newQuantity > $stock) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock. Only {$stock} available."
                    ], 422);
                }
                return redirect()->back()->with('error', "Insufficient stock. Only {$stock} available.");
            }
        }

        $cartIdentifier = $this->getCartIdentifier();

        // Check if changing to a variant that already exists for this product
        if ($cart->selected_size !== $request->selected_size) {
            $existingCartItem = Cart::where($cartIdentifier)
                ->where('product_id', $cart->product_id)
                ->where('selected_size', $request->selected_size)
                ->where('id', '!=', $cart->id)
                ->first();

            if ($existingCartItem) {
                $mergedQuantity = $existingCartItem->quantity + $newQuantity;
                
                // Check stock for merged quantity
                if ($mergedQuantity > $stock) {
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => "Cannot merge items. Only {$stock} available for {$request->selected_size}."
                        ], 422);
                    }
                    return redirect()->back()->with('error', "Cannot merge items. Only {$stock} available for {$request->selected_size}.");
                }
                
                // Merge with existing item
                $existingCartItem->update([
                    'quantity' => $mergedQuantity,
                    'total_price' => $mergedQuantity * $existingCartItem->unit_price
                ]);
                $cart->delete();
                
                if ($request->ajax()) {
                    // Calculate updated totals with fresh data
                    $cartItems = Cart::with(['product.variants'])->where($cartIdentifier)->get();
                    
                    $subtotal = $cartItems->sum('total_price');
                    $tax = $subtotal * 0.10;
                    $shipping = $subtotal > 100 ? 0 : 10;
                    $total = $subtotal + $tax + $shipping;

                    return response()->json([
                        'success' => true,
                        'message' => 'Cart updated successfully!',
                        'item_removed' => true,
                        'item_merged' => true,
                        'new_quantity' => $mergedQuantity,
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
        }

        // Update the current cart item with correct pricing
        $cart->update([
            'quantity' => $newQuantity,
            'selected_size' => $request->selected_size,
            'unit_price' => $unitPrice,
            'total_price' => $newQuantity * $unitPrice
        ]);

        // Reload the cart item with fresh data to get updated price
        $cart->load(['product.variants']);

        if ($request->ajax()) {
            // Calculate updated totals
            $cartItems = Cart::with(['product.variants'])->where($cartIdentifier)->get();
            
            $subtotal = $cartItems->sum('total_price');
            $tax = $subtotal * 0.10;
            $shipping = $subtotal > 100 ? 0 : 10;
            $total = $subtotal + $tax + $shipping;

            // Get the current variant price for display
            $currentVariantPrice = 0;
            if ($cart->product->has_variants) {
                $currentVariant = $cart->product->variants->first(function($v) use ($cart) {
                    return ($v->size === $cart->selected_size) || ($v->variant_name === $cart->selected_size);
                });
                $currentVariantPrice = $currentVariant ? ($currentVariant->current_price ?? $currentVariant->price ?? 0) : 0;
            } else {
                $currentVariantPrice = $cart->product->current_price;
            }

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'item_total' => number_format($cart->total_price, 2),
                'item_quantity' => $cart->quantity,
                'unit_price' => number_format($currentVariantPrice, 2),
                'max_quantity' => $stock,
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
        // Authorization check
        $this->authorizeCartItem($cart);

        $cart->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully!'
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart successfully!');
    }

    public function getCartCount()
    {
        $cartIdentifier = $this->getCartIdentifier();
        $count = Cart::where($cartIdentifier)->sum('quantity');
        return response()->json(['count' => $count]);
    }

    public function clear()
    {
        $cartIdentifier = $this->getCartIdentifier();
        Cart::where($cartIdentifier)->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully!',
                'cart_count' => 0
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully!');
    }

    /**
     * Get selected cart items for checkout
     */
    public function getSelectedItems(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:carts,id'
        ]);

        $cartIdentifier = $this->getCartIdentifier();
        
        // Get only the selected cart items
        $selectedItems = Cart::with(['product.variants'])
            ->where($cartIdentifier)
            ->whereIn('id', $request->selected_items)
            ->get();

        // Calculate totals for selected items only
        $subtotal = $selectedItems->sum('total_price');
        $tax = $subtotal * 0.10;
        $shipping = $subtotal > 100 ? 0 : 10;
        $total = $subtotal + $tax + $shipping;

        // Store selected items in session for checkout process
        session()->put('checkout_items', $selectedItems->pluck('id')->toArray());
        session()->put('checkout_summary', [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'item_count' => $selectedItems->sum('quantity')
        ]);

        return response()->json([
            'success' => true,
            'selected_items' => $selectedItems,
            'summary' => [
                'subtotal' => number_format($subtotal, 2),
                'tax' => number_format($tax, 2),
                'shipping' => number_format($shipping, 2),
                'total' => number_format($total, 2),
                'item_count' => $selectedItems->sum('quantity')
            ]
        ]);
    }

    /**
     * Remove selected items from cart (after successful checkout)
     */
    public function removeSelectedItems(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:carts,id'
        ]);

        $cartIdentifier = $this->getCartIdentifier();
        
        Cart::where($cartIdentifier)
            ->whereIn('id', $request->selected_items)
            ->delete();

        // Clear checkout session
        session()->forget(['checkout_items', 'checkout_summary']);

        if ($request->ajax()) {
            $cartCount = Cart::where($cartIdentifier)->sum('quantity');
            return response()->json([
                'success' => true,
                'message' => 'Selected items removed from cart!',
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Selected items removed from cart!');
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