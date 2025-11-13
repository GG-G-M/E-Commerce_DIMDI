<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:1000'
        ]);

        // Check if user has purchased this product
        if (!$product->purchasedBy(Auth::user())) {
            return back()->with('error', 'You can only rate products you have purchased.');
        }

        // Check if user has already rated this product
        if ($product->ratedBy(Auth::user())) {
            return back()->with('error', 'You have already rated this product.');
        }

        // Find the order where user purchased this product
        $order = Order::where('user_id', Auth::id())
            ->where('order_status', 'delivered')
            ->whereHas('items', function($query) use ($product) { // Changed from 'orderItems' to 'items'
                $query->where('product_id', $product->id);
            })->first();

        if (!$order) {
            return back()->with('error', 'Order not found or product not delivered yet.');
        }

        // Create rating
        Rating::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        return back()->with('success', 'Thank you for your rating!');
    }

    public function update(Request $request, Rating $rating)
    {
        // Check if the rating belongs to the user
        if ($rating->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:1000'
        ]);

        $rating->update([
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        return back()->with('success', 'Rating updated successfully!');
    }
}