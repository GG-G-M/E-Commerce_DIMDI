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

        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        if ($search) {
            $productsQuery->where('name', 'like', '%' . $search . '%');
        }

        if ($productId) {
            $productsQuery->where('id', $productId);
        }

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
        $merged = $stockIns->merge($stockOuts)->sortBy('created_at');

        $inventoryGrouped = $merged->groupBy(fn($item) => $item['product_id'] . '-' . $item['variant_name']);

        $inventoryTable = $inventoryGrouped->map(function ($items) {
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

        // Group by date for trend charts
        $groupedByDate = $merged->groupBy('date');

        // Trend Chart - Stock movement over time
        $cumulative = 0;
        foreach ($dateRange as $date) {
            $dailyItems = $groupedByDate[$date] ?? collect();
            $dailyIn = $dailyItems->where('type', 'in')->sum('quantity');
            $dailyOut = $dailyItems->where('type', 'out')->sum('quantity');
            $cumulative += ($dailyIn - $dailyOut);
            
            $charts['trend']['labels'][] = $date;
            $charts['trend']['data'][] = $cumulative;
            
            $charts['in_out']['labels'][] = $date;
            $charts['in_out']['stock_in'][] = $dailyIn;
            $charts['in_out']['stock_out'][] = $dailyOut;
        }

        // Low Stock Items (top 5 lowest)
        $lowStockItems = $inventoryTable->where('current_stock', '<=', 20)
            ->sortBy('current_stock')
            ->take(5);

        if ($lowStockItems->isNotEmpty()) {
            $charts['low_stock']['labels'] = $lowStockItems->pluck('product_name')->toArray();
            $charts['low_stock']['data'] = $lowStockItems->pluck('current_stock')->toArray();
        }

        // Category Distribution (only for filtered products)
        $filteredCategories = Category::whereHas('products', function($query) use ($filteredProductIds) {
            $query->whereIn('id', $filteredProductIds);
        })->get();

        if ($filteredCategories->isNotEmpty()) {
            $charts['categories']['labels'] = $filteredCategories->pluck('name')->toArray();
            
            // Count products per category within filtered results
            $categoryCounts = [];
            foreach ($inventoryTable as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->category) {
                    $categoryName = $product->category->name;
                    $categoryCounts[$categoryName] = ($categoryCounts[$categoryName] ?? 0) + 1;
                }
            }
            
            // Match counts with labels
            foreach ($charts['categories']['labels'] as $label) {
                $charts['categories']['data'][] = $categoryCounts[$label] ?? 0;
            }
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