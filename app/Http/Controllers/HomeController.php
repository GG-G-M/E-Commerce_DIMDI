<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->where('is_featured', true)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::with(['products' => function($query) {
            $query->where('is_active', true)->where('stock_quantity', '>', 0);
        }])
        ->where('is_active', true)
        ->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}