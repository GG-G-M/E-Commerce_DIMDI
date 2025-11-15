<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema; // ADD THIS LINE

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0);

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('brand', function($brandQuery) use ($searchTerm) {
                      $brandQuery->where('name', 'like', '%' . $searchTerm . '%');
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

        // Get unique brands for filter
        $brands = Brand::whereHas('products', function($q) {
                $q->where('is_active', true)
                  ->where('stock_quantity', '>', 0);
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get banners from database
        $banners = $this->getBanners();

        return view('products.index', compact('products', 'categories', 'brands', 'banners'));
    }

    /**
     * Display the specified product.
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'brand'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get related products (same category, excluding current product)
        $relatedProducts = Product::with(['category', 'brand'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // Increment view count if you have that field - FIXED
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'views')) {
            $product->increment('views');
        }

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Display products by category.
     */
    public function byCategory($categorySlug)
    {
        $products = Product::with(['category', 'brand'])
            ->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)
                  ->where('is_active', true);
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

        $selectedCategory = Category::where('slug', $categorySlug)->first();

        return view('products.index', compact('products', 'categories', 'brands', 'selectedCategory'));
    }

    /**
     * Display products by brand.
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

    /**
     * Quick search for header search bar.
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
     * Get featured products.
     */
    public function featured()
    {
        $products = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = Category::where('is_active', true)->get();
        $brands = Brand::whereHas('products', function($q) {
                $q->where('is_active', true)
                  ->where('stock_quantity', '>', 0);
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Get products on sale.
     */
    public function onSale()
    {
        $products = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->where(function($query) {
                $query->where('sale_price', '>', 0)
                      ->whereColumn('sale_price', '<', 'price');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = Category::where('is_active', true)->get();
        $brands = Brand::whereHas('products', function($q) {
                $q->where('is_active', true)
                  ->where('stock_quantity', '>', 0);
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Get banners from database.
     */
    private function getBanners()
    {
        // Check if Banner model exists and has records
        if (class_exists(Banner::class)) {
            $banners = Banner::active()
                ->ordered()
                ->get()
                ->map(function($banner) {
                    return [
                        'image' => asset('storage/banners/' . $banner->image_path),
                        'alt' => $banner->alt_text ?? $banner->title,
                        'title' => $banner->title,
                        'description' => $banner->description
                    ];
                })
                ->toArray();

            // Return banners if we have any
            if (!empty($banners)) {
                return $banners;
            }
        }

        // Fallback to default banners if no database banners exist
        return [
            [
                'image' => asset('images/banner1.png'),
                'alt' => 'Big Sale',
                'title' => '🔥 Big Sale!',
                'description' => 'Up to 50% off on selected products.'
            ],
            [
                'image' => asset('images/NW.png'),
                'alt' => 'New Arrivals',
                'title' => '✨ New Arrivals',
                'description' => 'Check out our latest collections.'
            ],
            [
                'image' => asset('images/GO.jpeg'),
                'alt' => 'Exclusive Deals',
                'title' => '💎 Exclusive Deals',
                'description' => 'Shop now before the offers end!'
            ]
        ];
    }

    /**
     * Get new arrivals.
     */
    public function newArrivals()
    {
        $products = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return response()->json([
            'products' => $products
        ]);
    }

    /**
     * Get product suggestions based on current product.
     */
    public function suggestions($productId)
    {
        $product = Product::findOrFail($productId);

        $suggestions = Product::with(['category', 'brand'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return response()->json([
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Display products by tag.
     */
    public function byTag($tag)
    {
        $products = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->where('tags', 'like', '%' . $tag . '%')
            ->paginate(12);

        $categories = Category::where('is_active', true)->get();
        $brands = Brand::whereHas('products', function($q) {
                $q->where('is_active', true)
                  ->where('stock_quantity', '>', 0);
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'categories', 'brands', 'tag'));
    }
}