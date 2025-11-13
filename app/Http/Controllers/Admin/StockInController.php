<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\StockIn;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    public function index(Request $request)
    {
        $stockIns = StockIn::with(['product', 'variant', 'warehouse'])
            ->when($request->search, fn($q) => $q->whereHas('product', fn($p) => $p->where('name', 'like', "%{$request->search}%"))
                                                ->orWhereHas('variant', fn($v) => $v->where('variant_name', 'like', "%{$request->search}%")) )
            ->when($request->warehouse_id, fn($q) => $q->where('warehouse_id', $request->warehouse_id))
            ->latest()
            ->paginate($request->per_page ?? 10);

        $warehouses = Warehouse::all();

        // Fetch all products and variants
        $products = Product::all();
        $variants = ProductVariant::with('product')->get();

        // Pass products and variants to the view
        return view('admin.stock_in.index', compact('stockIns', 'warehouses', 'products', 'variants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'nullable|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        StockIn::create($request->all());

        return redirect()->route('admin.stock_in.index')->with('success', 'Stock-In added successfully.');
    }

    public function edit(StockIn $stockIn)
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        $variants = ProductVariant::with('product')->get();
        return view('admin.stock_in.edit', compact('stockIn', 'warehouses', 'products', 'variants'));
    }

    public function update(Request $request, StockIn $stockIn)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'nullable|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $stockIn->update($request->all());

        return redirect()->route('admin.stock_in.index')->with('success', 'Stock-In updated successfully.');
    }

    public function destroy(StockIn $stockIn)
    {
        $stockIn->delete();
        return redirect()->route('admin.stock_in.index')->with('success', 'Stock-In deleted successfully.');
    }
}
