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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Writer;

class StockInController extends Controller
{
    /**
     * Display a listing of stock-ins.
     */
    public function index(Request $request)
    {
        $stockIns = StockIn::with(['product', 'variant', 'warehouse', 'supplier', 'checker'])
            ->when($request->search, fn($q) => $q->whereHas('product', fn($p) => $p->where('name', 'like', "%{$request->search}%"))
                ->orWhereHas('variant', fn($v) => $v->where('variant_name', 'like', "%{$request->search}%")))
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
            'price' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string|max:255',
        ]);

        StockIn::create(array_merge(
            $request->only([
                'warehouse_id',
                'product_id',
                'product_variant_id',
                'supplier_id',
                'stock_checker_id',
                'quantity',
                'price',
                'reason'
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
            'price' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string|max:255',
        ]);

        $oldQuantity = $stockIn->quantity;
        $newQuantity = $request->quantity;
        $difference = $newQuantity - $oldQuantity;

        $newRemaining = max(0, $stockIn->remaining_quantity + $difference);

        $stockIn->update(array_merge(
            $request->only([
                'warehouse_id',
                'product_id',
                'product_variant_id',
                'supplier_id',
                'stock_checker_id',
                'quantity',
                'price',
                'reason'
            ]),
            ['remaining_quantity' => $newRemaining]
        ));

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
        if ($stockIn->product_id) {
            $stockIn->product->decrement('stock_quantity', $stockIn->remaining_quantity);
        } elseif ($stockIn->product_variant_id) {
            $stockIn->variant->decrement('stock_quantity', $stockIn->remaining_quantity);
        }

        $stockIn->delete();

        return redirect()->route('admin.stock_in.index')->with('success', 'Stock-In deleted successfully.');
    }

    /**
     * Download CSV template for stock-in.
     */
    public function downloadCsvTemplate()
    {
        $csv = Writer::createFromString('');
        $csv->insertOne(['product_id', 'product_variant_id', 'warehouse_id', 'supplier_id', 'stock_checker_id', 'quantity', 'price', 'reason']);

        $filename = 'stock_in_template.csv';
        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    /**
     * Import stock-ins from CSV file.
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $errors = [];
        $createdCount = 0;

        foreach ($records as $index => $row) {
            // Convert empty strings to null for nullable columns
            $row['product_id'] = $row['product_id'] !== '' ? $row['product_id'] : null;
            $row['product_variant_id'] = $row['product_variant_id'] !== '' ? $row['product_variant_id'] : null;
            $row['price'] = $row['price'] !== '' ? $row['price'] : null;
            $row['reason'] = $row['reason'] !== '' ? $row['reason'] : null;

            $validator = Validator::make($row, [
                'warehouse_id' => 'required|exists:warehouses,id',
                'product_id' => 'nullable|exists:products,id',
                'product_variant_id' => 'nullable|exists:product_variants,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'stock_checker_id' => 'required|exists:stock_checkers,id',
                'quantity' => 'required|integer|min:1',
                'price' => 'nullable|numeric|min:0',
                'reason' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                $errors[$index + 2] = $validator->errors()->all(); // +2 because CSV header + 0-based index
                continue;
            }

            StockIn::create(array_merge(
                $validator->validated(),
                ['remaining_quantity' => $row['quantity']]
            ));

            $createdCount++;
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Some rows failed to import. Check row numbers.',
                'errors' => $errors,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "$createdCount stock-ins imported successfully.",
        ]);
    }
}
