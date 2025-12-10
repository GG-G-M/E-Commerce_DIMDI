<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\Supplier;
use App\Models\StockChecker;
use App\Models\StockIn;
use Illuminate\Http\Request;
use League\Csv\Writer;

class LowStockController extends Controller
{
    /**
     * Display low-stock items.
     */
    public function index(Request $request)
    {
        $threshold = $request->threshold ?? 10;

        // Fetch products/variants below threshold
        $products = Product::where('stock_quantity', '<=', $threshold)->get();
        $variants = ProductVariant::with('product')->where('stock_quantity', '<=', $threshold)->get();

        $warehouses = Warehouse::all();
        $suppliers = Supplier::where('is_archived', false)->get();
        $stockCheckers = StockChecker::where('is_archived', false)->get();

        return view('admin.low_stock.index', compact(
            'products', 'variants', 'warehouses', 'suppliers', 'stockCheckers', 'threshold'
        ));
    }

    /**
     * Download CSV of low-stock items.
     */
    public function downloadCsv(Request $request)
    {
        $threshold = $request->threshold ?? 10;

        $products = Product::where('stock_quantity', '<=', $threshold)->get();
        $variants = ProductVariant::with('product')->where('stock_quantity', '<=', $threshold)->get();

        $csv = Writer::createFromString('');
        $csv->insertOne([
            'product_id', 'product_variant_id', 'warehouse_id', 'supplier_id', 'stock_checker_id', 'quantity', 'price', 'reason'
        ]);

        foreach ($products as $p) {
            // Get the latest price from stock_in records for this product
            $latestStockIn = StockIn::where('product_id', $p->id)
                ->whereNotNull('price')
                ->orderBy('created_at', 'desc')
                ->first();
            
            $price = $latestStockIn ? $latestStockIn->price : '';

            $csv->insertOne([
                $p->id,
                '',
                '', // warehouse_id can be filled by user
                '', // supplier_id can be filled by user
                '', // stock_checker_id can be filled by user
                $p->stock_quantity,
                $price,
                ''
            ]);
        }

        foreach ($variants as $v) {
            // Get the latest price from stock_in records for this variant
            $latestStockIn = StockIn::where('product_variant_id', $v->id)
                ->whereNotNull('price')
                ->orderBy('created_at', 'desc')
                ->first();
            
            $price = $latestStockIn ? $latestStockIn->price : '';

            $csv->insertOne([
                '',
                $v->id,
                '', // warehouse_id
                '', // supplier_id
                '', // stock_checker_id
                $v->stock_quantity,
                $price,
                ''
            ]);
        }

        $filename = 'low_stock.csv';
        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
