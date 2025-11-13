<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockOut;
use Illuminate\Http\Request;

class StockOutController extends Controller
{
    public function index()
    {
        $stockOuts = StockOut::with(['product', 'variant'])->latest()->paginate(10);
        $products = Product::all();
        $variants = ProductVariant::with('product')->get();

        return view('admin.stock_out.index', compact('stockOuts', 'products', 'variants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $stockOut = StockOut::create($request->all());

        // Deduct stock quantity
        if ($stockOut->product_id) {
            $stockOut->product->decrement('stock_quantity', $stockOut->quantity);
        } elseif ($stockOut->product_variant_id) {
            $stockOut->variant->decrement('stock_quantity', $stockOut->quantity);
        }

        return redirect()->route('admin.stock_out.index')->with('success', 'Stock-Out recorded successfully.');
    }

    public function update(Request $request, StockOut $stockOut)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        // Adjust stock quantity based on difference
        $oldQuantity = $stockOut->quantity;
        $newQuantity = $request->quantity;
        $difference = $newQuantity - $oldQuantity;

        $stockOut->update($request->all());

        if ($stockOut->product_id) {
            $stockOut->product->decrement('stock_quantity', $difference);
        } elseif ($stockOut->product_variant_id) {
            $stockOut->variant->decrement('stock_quantity', $difference);
        }

        return redirect()->route('admin.stock_out.index')->with('success', 'Stock-Out updated successfully.');
    }

    public function destroy(StockOut $stockOut)
    {
        // Restore stock
        if ($stockOut->product_id) {
            $stockOut->product->increment('stock_quantity', $stockOut->quantity);
        } elseif ($stockOut->product_variant_id) {
            $stockOut->variant->increment('stock_quantity', $stockOut->quantity);
        }

        $stockOut->delete();

        return redirect()->route('admin.stock_out.index')->with('success', 'Stock-Out deleted successfully.');
    }
}

