<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    public function index()
    {
        $stockOuts = StockOut::with(['product', 'variant', 'stockInBatches'])->latest()->paginate(10);
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

        $quantityToDeduct = $request->quantity;

        // Create the stock-out record
        $stockOut = StockOut::create($request->only([
            'product_id',
            'product_variant_id',
            'quantity',
            'reason'
        ]));

        // Get FIFO stock-in batches with remaining_quantity > 0
        $stockInBatches = StockIn::where(function ($q) use ($request) {
            $q->when($request->product_id, fn($x) => $x->where('product_id', $request->product_id))
                ->when($request->product_variant_id, fn($x) => $x->where('product_variant_id', $request->product_variant_id));
        })
            ->where('remaining_quantity', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($stockInBatches as $batch) {
            if ($quantityToDeduct <= 0) break;

            $deducted = min($batch->remaining_quantity, $quantityToDeduct);

            // Deduct stock from remaining_quantity
            $batch->decrement('remaining_quantity', $deducted);

            // Log in pivot table
            $stockOut->stockInBatches()->attach($batch->id, ['deducted_quantity' => $deducted]);

            $quantityToDeduct -= $deducted;
        }

        if ($quantityToDeduct > 0) {
            $stockOut->delete(); // rollback if not enough stock
            return back()->withErrors(['quantity' => 'Not enough stock available (FIFO).']);
        }

        // Decrement total product/variant stock
        if ($stockOut->product_id) {
            $stockOut->product->decrement('stock_quantity', $request->quantity);
        } elseif ($stockOut->product_variant_id) {
            $stockOut->variant->decrement('stock_quantity', $request->quantity);
        }

        return redirect()->route('admin.stock_out.index')
            ->with('success', 'Stock-Out processed using FIFO successfully.');
    }

    public function update(Request $request, StockOut $stockOut)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        // 1️⃣ Restore previous stock-outs to stock-in batches
        foreach ($stockOut->stockInBatches as $batch) {
            $batch->increment('remaining_quantity', $batch->pivot->deducted_quantity);
        }
        $stockOut->stockInBatches()->detach(); // remove old pivot

        // 2️⃣ Update stock-out record
        $stockOut->update($request->only(['product_id', 'product_variant_id', 'quantity', 'reason']));

        // 3️⃣ Apply FIFO again for new quantity
        $quantityToDeduct = $request->quantity;
        $stockInBatches = StockIn::where(function ($q) use ($request) {
            $q->when($request->product_id, fn($x) => $x->where('product_id', $request->product_id))
                ->when($request->product_variant_id, fn($x) => $x->where('product_variant_id', $request->product_variant_id));
        })
            ->where('remaining_quantity', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($stockInBatches as $batch) {
            if ($quantityToDeduct <= 0) break;

            $deducted = min($batch->remaining_quantity, $quantityToDeduct);

            $batch->decrement('remaining_quantity', $deducted);
            $stockOut->stockInBatches()->attach($batch->id, ['deducted_quantity' => $deducted]);

            $quantityToDeduct -= $deducted;
        }

        if ($quantityToDeduct > 0) {
            return back()->withErrors(['quantity' => 'Not enough stock available (FIFO).']);
        }

        // Update total stock
        if ($stockOut->product_id) {
            $stockOut->product->update(['stock_quantity' => StockIn::where('product_id', $stockOut->product_id)->sum('remaining_quantity')]);
        } elseif ($stockOut->product_variant_id) {
            $stockOut->variant->update(['stock_quantity' => StockIn::where('product_variant_id', $stockOut->product_variant_id)->sum('remaining_quantity')]);
        }

        return redirect()->route('admin.stock_out.index')
            ->with('success', 'Stock-Out updated using FIFO successfully.');
    }

    public function destroy(StockOut $stockOut)
    {
        // Restore stock to batches
        foreach ($stockOut->stockInBatches as $batch) {
            $batch->increment('remaining_quantity', $batch->pivot->deducted_quantity);
        }
        $stockOut->stockInBatches()->detach();

        // Update total stock
        if ($stockOut->product_id) {
            $stockOut->product->update(['stock_quantity' => StockIn::where('product_id', $stockOut->product_id)->sum('remaining_quantity')]);
        } elseif ($stockOut->product_variant_id) {
            $stockOut->variant->update(['stock_quantity' => StockIn::where('product_variant_id', $stockOut->product_variant_id)->sum('remaining_quantity')]);
        }

        $stockOut->delete();

        return redirect()->route('admin.stock_out.index')
            ->with('success', 'Stock-Out deleted and stock restored successfully.');
    }


    public function autoStockOut($productId, $variantId, $quantity, $reason = 'Order Shipped')
    {
        $request = new \Illuminate\Http\Request([
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'quantity' => $quantity,
            'reason' => $reason,
        ]);

        return $this->store($request);
    }

}
