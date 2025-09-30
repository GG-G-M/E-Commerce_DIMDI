<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_categories' => Category::count(),
            'revenue' => Order::where('order_status', '!=', 'cancelled')->sum('total_amount'),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }
}