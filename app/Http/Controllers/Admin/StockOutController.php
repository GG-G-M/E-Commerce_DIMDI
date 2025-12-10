<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;

class StockOutController extends Controller
{
    public function index()
    {
        $stockOuts = StockOut::with(['product', 'variant', 'stockInBatches'])
            ->latest()
            ->paginate(10);

        $products = Product::all();
        $variants = ProductVariant::with('product')->get();

        return view('admin.stock_out.index', compact('stockOuts', 'products', 'variants'));
    }

    public function store(Request $request, $isAuto = false)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        // Check available stock before proceeding
        $availableStock = StockIn::where('product_id', $request->product_id)
            ->when($request->product_variant_id, function ($query) use ($request) {
                return $query->where('product_variant_id', $request->product_variant_id);
            })
            ->when(!$request->product_variant_id, function ($query) {
                return $query->where(function ($q) {
                    $q->whereNull('product_variant_id')
                      ->orWhereNotNull('product_variant_id');
                });
            })
            ->where('remaining_quantity', '>', 0)
            ->sum('remaining_quantity');

        if ($request->quantity > $availableStock) {
            $errorMessage = "Insufficient stock. Requested quantity ({$request->quantity}) exceeds available stock ({$availableStock}).";
            
            if ($isAuto) {
                throw new \Exception($errorMessage);
            }
            
            return back()
                ->withInput()
                ->withErrors(['quantity' => $errorMessage]);
        }

        // If variant is selected, force product_id to match variant
        $variant = null;
        if ($request->product_variant_id) {
            $variant = ProductVariant::find($request->product_variant_id);
            $request->merge(['product_id' => $variant->product_id]);
        }

        // Prevent selecting only product if variant stock exists (manual mode)
        if (!$isAuto && $request->product_id && !$request->product_variant_id) {
            $variantStockExists = StockIn::where('product_id', $request->product_id)
                ->whereNotNull('product_variant_id')
                ->exists();

            if ($variantStockExists) {
                return back()->withErrors([
                    'product_variant_id' => 'This product has variant-based stock. You must select a product variant.'
                ]);
            }
        }

        // Create Stock Out record
        $stockOut = StockOut::create($request->only([
            'product_id',
            'product_variant_id',
            'quantity',
            'reason'
        ]));

        $quantityToDeduct = $request->quantity;

        // FIFO batch deduction
        $stockInBatches = StockIn::where('product_id', $request->product_id)
            ->when($request->product_variant_id, fn($q) => $q->where('product_variant_id', $request->product_variant_id))
            ->when(!$request->product_variant_id, fn($q) => $q->where(function ($q2) {
                $q2->whereNull('product_variant_id')->orWhereNotNull('product_variant_id');
            }))
            ->where('remaining_quantity', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($stockInBatches as $batch) {
            if ($quantityToDeduct <= 0) break;

            $deducted = min($batch->remaining_quantity, $quantityToDeduct);

            $batch->decrement('remaining_quantity', $deducted);

            // Deduct stock from variant or main product
            if ($request->product_variant_id) {
                $variant->decrement('stock_quantity', $deducted);
            } else {
                Product::where('id', $request->product_id)->decrement('stock_quantity', $deducted);
            }

            $stockOut->stockInBatches()->attach($batch->id, [
                'deducted_quantity' => $deducted
            ]);

            $quantityToDeduct -= $deducted;
        }

        if ($quantityToDeduct > 0) {
            $stockOut->delete();
            if ($isAuto) {
                throw new \Exception('Not enough stock available (FIFO).');
            }
            return back()->withErrors(['quantity' => 'Not enough stock available (FIFO).']);
        }

        // Update parent product stock as sum of its variants
        if ($request->product_variant_id) {
            Product::where('id', $variant->product_id)
                ->update(['stock_quantity' => ProductVariant::where('product_id', $variant->product_id)->sum('stock_quantity')]);
        }

        if ($isAuto) {
            return $stockOut;
        }

        return redirect()->route('admin.stock_out.index')
            ->with('success', 'Stock-Out processed successfully using FIFO.');
    }

    public function update(Request $request, StockOut $stockOut)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        // Check available stock before proceeding (accounting for current stock-out record)
        $availableStock = StockIn::where('product_id', $request->product_id)
            ->when($request->product_variant_id, function ($query) use ($request) {
                return $query->where('product_variant_id', $request->product_variant_id);
            })
            ->when(!$request->product_variant_id, function ($query) {
                return $query->where(function ($q) {
                    $q->whereNull('product_variant_id')
                      ->orWhereNotNull('product_variant_id');
                });
            })
            ->where('remaining_quantity', '>', 0)
            ->sum('remaining_quantity');

        // Add back the current stock-out quantity since we're updating it
        $netAvailableStock = $availableStock + $stockOut->quantity;

        if ($request->quantity > $netAvailableStock) {
            $errorMessage = "Insufficient stock. Requested quantity ({$request->quantity}) exceeds available stock ({$netAvailableStock}).";
            return back()
                ->withInput()
                ->withErrors(['quantity' => $errorMessage]);
        }

        $variant = null;
        if ($request->product_variant_id) {
            $variant = ProductVariant::find($request->product_variant_id);
            $request->merge(['product_id' => $variant->product_id]);
        }

        // Restore previous stock
        foreach ($stockOut->stockInBatches as $batch) {
            $batch->increment('remaining_quantity', $batch->pivot->deducted_quantity);
        }
        $stockOut->stockInBatches()->detach();

        $stockOut->update($request->only([
            'product_id',
            'product_variant_id',
            'quantity',
            'reason'
        ]));

        $quantityToDeduct = $request->quantity;

        // FIFO batch deduction
        $stockInBatches = StockIn::where('product_id', $request->product_id)
            ->when($request->product_variant_id, fn($q) => $q->where('product_variant_id', $request->product_variant_id))
            ->when(!$request->product_variant_id, fn($q) => $q->where(function ($q2) {
                $q2->whereNull('product_variant_id')->orWhereNotNull('product_variant_id');
            }))
            ->where('remaining_quantity', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($stockInBatches as $batch) {
            if ($quantityToDeduct <= 0) break;

            $deducted = min($batch->remaining_quantity, $quantityToDeduct);

            $batch->decrement('remaining_quantity', $deducted);

            if ($request->product_variant_id) {
                $variant->decrement('stock_quantity', $deducted);
            } else {
                Product::where('id', $request->product_id)->decrement('stock_quantity', $deducted);
            }

            $stockOut->stockInBatches()->attach($batch->id, [
                'deducted_quantity' => $deducted
            ]);

            $quantityToDeduct -= $deducted;
        }

        if ($quantityToDeduct > 0) {
            return back()->withErrors(['quantity' => 'Not enough stock available (FIFO).']);
        }

        // Update parent product stock as sum of its variants
        if ($request->product_variant_id) {
            Product::where('id', $variant->product_id)
                ->update(['stock_quantity' => ProductVariant::where('product_id', $variant->product_id)->sum('stock_quantity')]);
        }

        return redirect()->route('admin.stock_out.index')
            ->with('success', 'Stock-Out updated successfully.');
    }

    public function destroy(StockOut $stockOut)
    {
        foreach ($stockOut->stockInBatches as $batch) {
            $batch->increment('remaining_quantity', $batch->pivot->deducted_quantity);
        }

        $stockOut->stockInBatches()->detach();

        // Restore stock
        if ($stockOut->product_variant_id) {
            $variant = ProductVariant::find($stockOut->product_variant_id);
            $variant->increment('stock_quantity', $stockOut->quantity);

            // Update parent product stock
            Product::where('id', $variant->product_id)
                ->update(['stock_quantity' => ProductVariant::where('product_id', $variant->product_id)->sum('stock_quantity')]);
        } else {
            $product = Product::find($stockOut->product_id);
            $product->increment('stock_quantity', $stockOut->quantity);
        }

        $stockOut->delete();

        return redirect()->route('admin.stock_out.index')
            ->with('success', 'Stock-Out deleted and stock restored successfully.');
    }

    public function autoStockOut($productId, $variantId, $quantity, $reason = 'Order Shipped')
    {
        $request = new Request([
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'quantity' => $quantity,
            'reason' => $reason,
        ]);

        return $this->store($request, true);
    }

    /**
     * Check available stock for a product or variant
     */
    public function checkStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $productId = $request->product_id;
        $variantId = $request->variant_id;

        // Calculate available stock using FIFO (same logic as stock-out)
        $availableStock = StockIn::where('product_id', $productId)
            ->when($variantId, function ($query) use ($variantId) {
                return $query->where('product_variant_id', $variantId);
            })
            ->when(!$variantId, function ($query) {
                return $query->where(function ($q) {
                    $q->whereNull('product_variant_id')
                      ->orWhereNotNull('product_variant_id');
                });
            })
            ->where('remaining_quantity', '>', 0)
            ->sum('remaining_quantity');

        return response()->json([
            'available_stock' => (int) $availableStock,
            'product_id' => $productId,
            'variant_id' => $variantId,
        ]);
    }
}
