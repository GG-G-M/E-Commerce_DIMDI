<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Support\Collection;


class InventoryReportController extends Controller
{
    public function index(Request $request)
    {
        // -------------------------
        // 1. FILTERS
        // -------------------------
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->subMonth()->startOfDay();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();

        $productId = $request->product_id;
        $variantId = $request->variant_id;
        $categoryId = $request->category_id;
        $search = $request->search;

        // -------------------------
        // 2. FILTER PRODUCTS
        // -------------------------
        $productsQuery = Product::with('category');

        if ($categoryId) $productsQuery->where('category_id', $categoryId);
        if ($search) $productsQuery->where('name', 'like', '%' . $search . '%');
        if ($productId) $productsQuery->where('id', $productId);


        $filteredProductIds = $productsQuery->pluck('id')->toArray();

        // -------------------------
        // 3. STOCK IN / STOCK OUT QUERIES
        // -------------------------
        $stockInQuery = StockIn::with(['product', 'variant'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('product_id', $filteredProductIds);

        $stockOutQuery = StockOut::with(['product', 'variant'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('product_id', $filteredProductIds);

        if ($variantId) {
            $stockInQuery->where('variant_id', $variantId);
            $stockOutQuery->where('variant_id', $variantId);
        }

        $stockIns = $stockInQuery->get()->map(fn($row) => [
            'id' => $row->id,
            'type' => 'in',
            'product_id' => $row->product_id,
            'product_name' => $row->product->name,
            'variant_name' => $row->variant?->variant_name,
            'quantity' => $row->quantity,
            'created_at' => $row->created_at,
            'date' => $row->created_at->format('Y-m-d')
        ]);

        $stockOuts = $stockOutQuery->get()->map(fn($row) => [
            'id' => $row->id,
            'type' => 'out',
            'product_id' => $row->product_id,
            'product_name' => $row->product->name,
            'variant_name' => $row->variant?->variant_name,
            'quantity' => $row->quantity,
            'created_at' => $row->created_at,
            'date' => $row->created_at->format('Y-m-d')
        ]);

        // -------------------------
        // 4. MERGE & GROUP INVENTORY
        // -------------------------
        $merged = collect($stockIns)->merge($stockOuts)->sortByDesc('created_at');


        $inventoryGrouped = $merged->groupBy(fn($item) => $item['product_id'] . '-' . $item['variant_name']);

        $inventoryTable = $inventoryGrouped->map(function ($items) {

            $items = collect($items); // <-- make sure it’s a collection

            $first = $items->first();
            $stockIn = $items->where('type', 'in')->sum('quantity');
            $stockOut = $items->where('type', 'out')->sum('quantity');

            $product = Product::with('category')->find($first['product_id']);
            $categoryName = $product?->category?->name ?? '—';

            return [
                'product_id' => $first['product_id'],
                'product_name' => $first['product_name'],
                'variant_name' => $first['variant_name'],
                'category_name' => $categoryName,
                'total_stock_in' => $stockIn,
                'total_stock_out' => $stockOut,
                'current_stock' => $stockIn - $stockOut,
            ];
        })->values();

        // -------------------------
        // 5. PAGINATION
        // -------------------------
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;
        $paginatedInventory = new LengthAwarePaginator(
            $inventoryTable->forPage($page, $perPage),
            $inventoryTable->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // -------------------------
        // 6. OVERVIEW
        // -------------------------
        $overview = [
            'total_stock_in' => $stockIns->sum('quantity'),
            'total_stock_out' => $stockOuts->sum('quantity'),
            'low_stock_count' => $inventoryTable->where('current_stock', '<=', 5)->count(),
            'product_count' => $inventoryTable->count(),
        ];

        // -------------------------
        // 7. CHARTS - FIXED
        // -------------------------
        // INVENTORY TREND (cumulative stock by day)
        $trendGrouped = $merged->groupBy(fn($item) => Carbon::parse($item['created_at'])->format('Y-m-d'));
        $trendLabels = $trendGrouped->keys()->toArray();
        $trendData = [];
        $cumulativeStock = 0;
        foreach ($trendLabels as $date) {
            $dayItems = collect($trendGrouped[$date]); // <-- important
            $stockIn = $dayItems->where('type', 'in')->sum('quantity');
            $stockOut = $dayItems->where('type', 'out')->sum('quantity');
            $cumulativeStock += $stockIn - $stockOut;
            $trendData[] = $cumulativeStock;
        }

        // STOCK-IN VS STOCK-OUT (per day)
        $inOutStockIn = collect($trendGrouped)->map(fn($items) => collect($items)->where('type', 'in')->sum('quantity'))->values()->toArray();
        $inOutStockOut = collect($trendGrouped)->map(fn($items) => collect($items)->where('type', 'out')->sum('quantity'))->values()->toArray();

        // LOW STOCK ITEMS (top 5 lowest stock)
        $lowStockItems = $inventoryTable->sortBy('current_stock')->take(5);
        $lowStockLabels = $lowStockItems->pluck('product_name')->toArray();
        $lowStockData = $lowStockItems->pluck('current_stock')->toArray();

        // CATEGORY DISTRIBUTION
        $categoryGrouped = collect($inventoryTable)->groupBy('category_name');
        $categoryLabels = $categoryGrouped->keys()->toArray();
        $categoryData = $categoryGrouped->map(fn($items) => collect($items)->sum('current_stock'))->values()->toArray();
        $charts = [
            'trend' => ['labels' => [], 'data' => []],
            'in_out' => ['labels' => [], 'stock_in' => [], 'stock_out' => []],
            'low_stock' => ['labels' => [], 'data' => []],
            'categories' => ['labels' => [], 'data' => []],
        ];

        // Generate all dates in the range for consistent charts
        $dateRange = [];
        $currentDate = Carbon::parse($startDate);
        while ($currentDate <= Carbon::parse($endDate)) {
            $dateRange[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // -------------------------
        // 8. RENDER VIEW
        // -------------------------
        $categories = Category::all();
        
        return view('admin.inventory-reports.index', compact(
            'paginatedInventory',
            'overview',
            'charts',
            'categories',
            'startDate',
            'endDate',
            'productId',
            'variantId',
            'categoryId',
            'inventoryTable'
        ));
    }
}