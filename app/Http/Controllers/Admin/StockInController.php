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

        // Get paginated products for modal
        $productPage = $request->get('product_page', 1);
        $productPerPage = $request->get('product_per_page', 10);
        $productsForModal = Product::with(['category', 'variants'])
            ->when($request->get('product_search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->product_search}%")
                          ->orWhere('sku', 'like', "%{$request->product_search}%");
                });
            })
            ->orderBy('name')
            ->paginate($productPerPage, ['*'], 'product_page', $productPage);

        $warehouses = Warehouse::all();
        $products = Product::all();
        $variants = ProductVariant::with('product')->get();
        $suppliers = Supplier::where('is_archived', false)->get();
        $stockCheckers = StockChecker::where('is_archived', false)->get();

        return view('admin.stock_in.index', compact('stockIns', 'warehouses', 'products', 'variants', 'suppliers', 'stockCheckers', 'productsForModal'));      
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
                'warehouse_id',
                'product_id',
                'product_variant_id',
                'supplier_id',
                'stock_checker_id',
                'quantity',
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
        $csv->insertOne(['product_id', 'product_variant_id', 'warehouse_id', 'supplier_id', 'stock_checker_id', 'quantity', 'reason']);

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
            $row['reason'] = $row['reason'] !== '' ? $row['reason'] : null;

            $validator = Validator::make($row, [
                'warehouse_id' => 'required|exists:warehouses,id',
                'product_id' => 'nullable|exists:products,id',
                'product_variant_id' => 'nullable|exists:product_variants,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'stock_checker_id' => 'required|exists:stock_checkers,id',
                'quantity' => 'required|integer|min:1',
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

    /**
     * Get products for modal selection with pagination
     */
    public function getProducts(Request $request)
    {
        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        $filter = $request->get('filter', 'active');
        $perPage = 10;

        $query = Product::with(['category', 'variants'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($filter === 'active', function ($q) {
                $q->where('is_archived', false);
            })
            ->when($filter === 'archived', function ($q) {
                $q->where('is_archived', true);
            })
            ->orderBy('name');

        $products = $query->paginate($perPage, ['*'], 'page', $page);

        $productsData = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'category_name' => $product->category->name ?? 'N/A',
                'image_url' => $product->image_url,
                'is_archived' => $product->is_archived,
                'has_variants' => $product->has_variants,
                'variants_count' => $product->variants->count(),
                'stock_quantity' => $product->stock_quantity,
                'total_stock' => $product->total_stock,
            ];
        });

        return response()->json([
            'success' => true,
            'products' => $productsData,
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'first_item' => $products->firstItem(),
                'last_item' => $products->lastItem(),
            ]
        ]);
    }

    /**
     * Get variants for modal selection with pagination
     */
    public function getVariants(Request $request)
    {
        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        $productId = $request->get('product_id');
        $perPage = $request->get('per_page', 10);

        $query = ProductVariant::with(['product'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('variant_name', 'like', "%{$search}%")
                          ->orWhere('sku', 'like', "%{$search}%")
                          ->orWhereHas('product', function ($productQuery) use ($search) {
                              $productQuery->where('name', 'like', "%{$search}%");
                          });
                });
            })
            ->when($productId, function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->orderBy('variant_name');

        $variants = $query->paginate($perPage, ['*'], 'page', $page);

        $variantsData = $variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'variant_name' => $variant->variant_name,
                'sku' => $variant->sku,
                'stock_quantity' => $variant->stock_quantity,
                'price' => $variant->price,
                'product' => [
                    'id' => $variant->product->id,
                    'name' => $variant->product->name,
                    'sku' => $variant->product->sku,
                    'image_url' => $variant->product->image_url,
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'variants' => $variantsData,
            'pagination' => [
                'current_page' => $variants->currentPage(),
                'last_page' => $variants->lastPage(),
                'per_page' => $variants->perPage(),
                'total' => $variants->total(),
                'first_item' => $variants->firstItem(),
                'last_item' => $variants->lastItem(),
            ]
        ]);
    }
}
