<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\WarehouseController as AdminWarehouseController;
use App\Http\Controllers\Admin\StockInController;
use App\Http\Controllers\Admin\StockOutController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Delivery\DashboardController as DeliveryDashboardController;
use App\Http\Controllers\Delivery\OrderController as DeliveryOrderController;
use App\Http\Controllers\Delivery\ProfileController as DeliveryProfileController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Authentication Routes (SINGLE LOGIN FOR ALL)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');

// Payment Routes - UPDATED SECTION
Route::get('/payment/{order}/pay', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
Route::post('/payment/{order}/process', [PaymentController::class, 'createIntent'])->name('payment.create-intent');
Route::post('/payment/create-source', [PaymentController::class, 'createSource'])->name('payment.create-source');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');

// Authenticated User Routes (All logged-in users)
Route::middleware('auth')->group(function () {
    // Profile - Accessible by ALL logged-in users
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Customer Routes - UPDATED SECTION
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    
    // NEW PAYMENT ROUTES FOR ORDERS
    Route::get('/orders/{order}/payment', [OrderController::class, 'showPayment'])->name('orders.payment');
    Route::get('/orders/{order}/retry-payment', [OrderController::class, 'retryPayment'])->name('orders.retry-payment');
    
    // Rating
    Route::post('/products/{product}/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::put('/ratings/{rating}', [RatingController::class, 'update'])->name('ratings.update');
});

// DELIVERY ROUTES (First come, first served system)
Route::prefix('delivery')->name('delivery.')->middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DeliveryDashboardController::class, 'index'])->name('dashboard');
    
    // Order Routes - STATIC ROUTES FIRST
    Route::get('/orders', [DeliveryOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/pickup', [DeliveryOrderController::class, 'pickup'])->name('orders.pickup');
    Route::get('/orders/delivered', [DeliveryOrderController::class, 'delivered'])->name('orders.delivered');
    Route::get('/orders/my-orders', [DeliveryOrderController::class, 'myOrders'])->name('orders.my-orders');
    
    // PARAMETERIZED ROUTES LAST - CORRECTED METHOD NAMES
    Route::get('/orders/{order}', [DeliveryOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/claim', [DeliveryOrderController::class, 'claimOrder'])->name('orders.claim');
    Route::post('/orders/{order}/deliver', [DeliveryOrderController::class, 'markAsDelivered'])->name('orders.deliver');
    
    // ADD THIS LINE TO FIX THE ERROR
    Route::post('/orders/{order}/deliver-order', [DeliveryOrderController::class, 'markAsDelivered'])->name('orders.deliver-order');
    
    // ADD THIS LINE TO FIX THE pickup-order ERROR
    Route::post('/orders/{order}/pickup-order', [DeliveryOrderController::class, 'claimOrder'])->name('orders.pickup-order');
    
    Route::post('/orders/{order}/unclaim', [DeliveryOrderController::class, 'unclaimOrder'])->name('orders.unclaim');
    
    // ADD THIS LINE - For compatibility with existing views
    Route::post('/orders/{order}/pickup', [DeliveryOrderController::class, 'claimOrder'])->name('orders.markAsPickedUp');
    
    // Profile Routes
    Route::get('/profile', [DeliveryProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [DeliveryProfileController::class, 'update'])->name('profile.update');
});

// Admin Routes (Role checking in controllers)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::post('/customers/{id}/archive', [CustomerController::class, 'archive'])->name('customers.archive');
    Route::post('/customers/{id}/unarchive', [CustomerController::class, 'unarchive'])->name('customers.unarchive');

    // DELIVERY CRUD ROUTES (Now using User model)
    Route::get('/deliveries', [DeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('/deliveries/create', [DeliveryController::class, 'create'])->name('deliveries.create');
    Route::post('/deliveries', [DeliveryController::class, 'store'])->name('deliveries.store');
    Route::get('/deliveries/{delivery}', [DeliveryController::class, 'show'])->name('deliveries.show');
    Route::get('/deliveries/{delivery}/edit', [DeliveryController::class, 'edit'])->name('deliveries.edit');
    Route::put('/deliveries/{delivery}', [DeliveryController::class, 'update'])->name('deliveries.update');
    Route::delete('/deliveries/{delivery}', [DeliveryController::class, 'destroy'])->name('deliveries.destroy');
    Route::post('/deliveries/{delivery}/toggle-status', [DeliveryController::class, 'toggleStatus'])->name('deliveries.toggle-status');

    // Warehouses
    Route::get('/warehouses', [AdminWarehouseController::class, 'index'])->name('warehouses.index');
    Route::post('/warehouses', [AdminWarehouseController::class, 'store'])->name('warehouses.store');
    Route::put('/warehouses/{id}', [AdminWarehouseController::class, 'update'])->name('warehouses.update');
    Route::post('/warehouses/{id}/archive', [AdminWarehouseController::class, 'archive'])->name('warehouses.archive');
    Route::post('/warehouses/{id}/unarchive', [AdminWarehouseController::class, 'unarchive'])->name('warehouses.unarchive');
    Route::delete('/warehouses/{id}', [AdminWarehouseController::class, 'destroy'])->name('warehouses.destroy');

    // Products
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/archive', [AdminProductController::class, 'archive'])->name('products.archive');
    Route::post('/products/{product}/unarchive', [AdminProductController::class, 'unarchive'])->name('products.unarchive');
    
    // CSV Upload Routes
    Route::post('/products/import/csv', [AdminProductController::class, 'importCSV'])->name('products.import.csv');
    Route::get('/products/csv/template', [AdminProductController::class, 'downloadCSVTemplate'])->name('products.csv.template');

    // Stock-Ins
    Route::get('/stock-ins', [StockInController::class, 'index'])->name('stock_in.index');
    Route::post('/stock-ins', [StockInController::class, 'store'])->name('stock_in.store');
    Route::put('/stock-ins/{stockIn}', [StockInController::class, 'update'])->name('stock_in.update');
    Route::delete('/stock-ins/{stockIn}', [StockInController::class, 'destroy'])->name('stock_in.destroy');

    // Stock-Outs
    Route::get('/stock-outs', [StockOutController::class, 'index'])->name('stock_out.index');
    Route::post('/stock-outs', [StockOutController::class, 'store'])->name('stock_out.store');
    Route::put('/stock-outs/{stockOut}', [StockOutController::class, 'update'])->name('stock_out.update');
    Route::delete('/stock-outs/{stockOut}', [StockOutController::class, 'destroy'])->name('stock_out.destroy');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // REFUND ROUTES
    Route::get('/orders/{order}/refund', [AdminOrderController::class, 'showRefund'])->name('orders.refund.show');
    Route::post('/orders/{order}/refund', [AdminOrderController::class, 'processRefund'])->name('orders.refund.process');

    // DELIVERY ASSIGNMENT ROUTES (Admin can manually assign/reassign)
    Route::post('/orders/{order}/assign-delivery', [AdminOrderController::class, 'assignToDelivery'])->name('orders.assign-delivery');
    Route::post('/orders/{order}/unassign-delivery', [AdminOrderController::class, 'unassignDelivery'])->name('orders.unassign-delivery');
    Route::post('/orders/{order}/mark-picked-up', [AdminOrderController::class, 'markAsPickedUp'])->name('orders.mark-picked-up');
    Route::post('/orders/{order}/mark-delivered', [AdminOrderController::class, 'markAsDelivered'])->name('orders.mark-delivered');
    Route::get('/orders/delivery-assignment', [AdminOrderController::class, 'showDeliveryAssignment'])->name('orders.delivery-assignment');
    Route::post('/orders/bulk-assign-delivery', [AdminOrderController::class, 'bulkAssignDelivery'])->name('orders.bulk-assign-delivery');
    Route::get('/orders/available-deliveries', [AdminOrderController::class, 'getAvailableDeliveries'])->name('orders.available-deliveries');

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Brand Routes
    Route::resource('brands', BrandController::class);
    Route::post('brands/quick-store', [BrandController::class, 'quickStore'])->name('brands.quick-store');
    
    // SALES REPORT ROUTES
    Route::prefix('sales-report')->name('sales-report.')->group(function () {
        Route::get('/', [SalesReportController::class, 'index'])->name('index');
        Route::get('/charts', [SalesReportController::class, 'charts'])->name('charts');
        Route::get('/export', [SalesReportController::class, 'export'])->name('export');
        Route::get('/comparison', [SalesReportController::class, 'comparison'])->name('comparison');
    });

    // BANNER ROUTES
    Route::resource('banners', BannerController::class);
    Route::post('/banners/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banners.toggle-status');
});