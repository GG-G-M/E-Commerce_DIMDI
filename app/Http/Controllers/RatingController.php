<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Store a newly created rating/review
     */
    public function store(Request $request, Product $product)
    {
        // Validate request
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'required|string|max:1000',
            'order_id' => 'required|exists:orders,id'
        ]);

        // Verify the order belongs to the user, is delivered, and contains the product
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->where('order_status', 'delivered')
            ->whereHas('items', function($query) use ($product) {
                $query->where('product_id', $product->id);
            })->first();

        if (!$order) {
            return back()->with('error', 'Order not found, not delivered, or does not contain this product.');
        }

        // Check if user already reviewed this product for this specific order
        $existingRating = Rating::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('order_id', $order->id)
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'review' => $request->review,
                'updated_at' => now()
            ]);
            
            return back()->with('success', 'Review updated successfully!');
        }

        // Create new rating
        Rating::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        return back()->with('success', 'Thank you for your review!');
    }

    /**
     * Update the specified rating
     */
    public function update(Request $request, Rating $rating)
    {
        // Check if the rating belongs to the user
        if ($rating->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'required|string|max:1000'
        ]);

        $rating->update([
            'rating' => $request->rating,
            'review' => $request->review,
            'updated_at' => now()
        ]);

        return back()->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified rating
     */
    public function destroy(Rating $rating)
    {
        // Check if the rating belongs to the user
        if ($rating->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $rating->delete();

        return back()->with('success', 'Review deleted successfully!');
    }

    /**
     * Show the form for editing the specified rating
     */
    public function edit(Rating $rating)
    {
        // Check if the rating belongs to the user
        if ($rating->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('ratings.edit', compact('rating'));
    }
}