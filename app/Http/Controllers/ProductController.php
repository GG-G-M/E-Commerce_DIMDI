<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']) // Add 'brand' to with()
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0);

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('brand', function($brandQuery) use ($searchTerm) {
                      $brandQuery->where('name', 'like', '%' . $searchTerm . '%'); // Search by brand name
                  })
                  ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                      $categoryQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category)
                  ->where('is_active', true);
            });
        }

        // Brand filter (multiple brands)
        if ($request->filled('brands')) {
            $brandNames = explode(',', $request->brands);
            $query->whereHas('brand', function($q) use ($brandNames) {
                $q->whereIn('name', $brandNames);
            });
        }

        // Price filtering
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // Check if it's an AJAX request for search dropdown
        if ($request->ajax() && $request->has('search')) {
            $products = $query->limit(8)->get();
            return response()->json([
                'products' => $products,
                'html' => view('products.partials.search-results', compact('products'))->render()
            ]);
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        // Get unique brands for filter - FIXED: Use Brand model instead of Product brand column
        $brands = Brand::whereHas('products', function($q) {
                $q->where('is_active', true)
                  ->where('stock_quantity', '>', 0);
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'variants', 'brand']) // Add 'brand' here
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->firstOrFail();

        $relatedProducts = Product::with(['category', 'brand'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Quick search for header search bar
     */
    public function quickSearch(Request $request)
    {
        $query = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0);

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('brand', function($brandQuery) use ($searchTerm) {
                      $brandQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $products = $query->limit(5)->get();

        return response()->json([
            'products' => $products
        ]);
    }

    /**
     * Get products by brand
     */
    public function byBrand($brandSlug)
    {
        $products = Product::with(['category', 'brand'])
            ->whereHas('brand', function($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            })
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->paginate(12);

        $categories = Category::where('is_active', true)->get();
        $brands = Brand::whereHas('products', function($q) {
                $q->where('is_active', true)
                  ->where('stock_quantity', '>', 0);
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $selectedBrand = Brand::where('slug', $brandSlug)->first();

        return view('products.index', compact('products', 'categories', 'brands', 'selectedBrand'));
    }
}