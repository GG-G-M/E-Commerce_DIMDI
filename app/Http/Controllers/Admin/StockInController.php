<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\StockIn;
use App\Models\Supplier;
use App\Models\StockChecker;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    /**
     * Display a listing of stock-ins.
     */
    public function index(Request $request)
    {
        $stockIns = StockIn::with(['product', 'variant', 'warehouse', 'supplier', 'checker'])
            ->when($request->search, fn($q) => $q->whereHas('product', fn($p) => $p->where('name', 'like', "%{$request->search}%"))
                ->orWhereHas('variant', fn($v) => $v->where('variant_name', 'like', "%{$request->search}%")) )
            ->when($request->warehouse_id, fn($q) => $q->where('warehouse_id', $request->warehouse_id))
            ->latest()
            ->paginate($request->per_page ?? 10);

        $warehouses = Warehouse::all();
        $products = Product::all();
        $variants = ProductVariant::with('product')->get();
        $suppliers = Supplier::where('is_archived', false)->get();
        $stockCheckers = StockChecker::where('is_archived', false)->get();

        return view('admin.stock_in.index', compact('stockIns', 'warehouses', 'products', 'variants', 'suppliers', 'stockCheckers'));
    }

    /**
     * Store a newly created stock-in.
     */
    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'nullable|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'stock_checker_id' => 'required|exists:stock_checkers,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        StockIn::create(array_merge(
            $request->only([
                'warehouse_id', 'product_id', 'product_variant_id', 
                'supplier_id', 'stock_checker_id', 'quantity', 'reason'
            ]),
            ['remaining_quantity' => $request->quantity]
        ));

        return redirect()->route('admin.stock_in.index')->with('success', 'Stock-In added successfully.');
    }

    /**
     * Update an existing stock-in.
     */
    public function update(Request $request, StockIn $stockIn)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'nullable|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'stock_checker_id' => 'required|exists:stock_checkers,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $oldQuantity = $stockIn->quantity;
        $newQuantity = $request->quantity;
        $difference = $newQuantity - $oldQuantity;

        $newRemaining = max(0, $stockIn->remaining_quantity + $difference);

        $stockIn->update(array_merge(
            $request->only([
                'warehouse_id', 'product_id', 'product_variant_id', 
                'supplier_id', 'stock_checker_id', 'quantity', 'reason'
            ]),
            ['remaining_quantity' => $newRemaining]
        ));

        // Update total stock for product or variant
        if ($stockIn->product_id) {
            $stockIn->product->increment('stock_quantity', $difference);
        } elseif ($stockIn->product_variant_id) {
            $stockIn->variant->increment('stock_quantity', $difference);
        }

        return redirect()->route('admin.stock_in.index')->with('success', 'Stock-In updated successfully.');
    }

    /**
     * Remove the specified stock-in.
     */
    public function destroy(StockIn $stockIn)
    {
        // Reduce total stock by remaining_quantity before deletion
        if ($stockIn->product_id) {
            $stockIn->product->decrement('stock_quantity', $stockIn->remaining_quantity);
        } elseif ($stockIn->product_variant_id) {
            $stockIn->variant->decrement('stock_quantity', $stockIn->remaining_quantity);
        }

        $stockIn->delete();

        return redirect()->route('admin.stock_in.index')->with('success', 'Stock-In deleted successfully.');
    }
}
