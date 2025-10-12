<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['category', 'variants'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->where('is_archived', false)
            ->latest()
            ->take(8)
            ->get();

        // Fallback if no featured products
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::with(['category', 'variants'])
                ->where('is_active', true)
                ->where('is_archived', false)
                ->inRandomOrder()
                ->take(8)
                ->get();
        }

        $categories = Category::with(['products' => function($query) {
            $query->where('is_active', true)->where('is_archived', false);
        }])
        ->where('is_active', true)
        ->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}