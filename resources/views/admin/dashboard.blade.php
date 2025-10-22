@extends('layouts.admin')

@section('content')
<style>
    .stats-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }
    
    .card-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
    }
    
    .card-success {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        color: white;
    }
    
    .card-info {
        background: linear-gradient(135deg, #4CAF50, #66BB6A);
        color: white;
    }
    
    .card-warning {
        background: linear-gradient(135deg, #FFA000, #FFB300);
        color: white;
    }
    
    .stats-icon {
        font-size: 2rem;
        opacity: 0.8;
    }
    
    .stats-number {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
    }
    
    .stats-label {
        font-size: 0.85rem;
        opacity: 0.9;
        font-weight: 500;
    }
    
    .dashboard-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #2C8F0C;
    }
    
    .section-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }
    
    .section-card .card-header {
        background: white;
        border-bottom: 2px solid #E8F5E6;
        font-weight: 600;
        color: #2C8F0C;
        padding: 1rem 1.5rem;
    }
    
    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
    }
    
    .badge-pending {
        background-color: #FFF3CD;
        color: #856404;
    }
    
    .badge-completed {
        background-color: #D1ECF1;
        color: #0C5460;
    }
    
    .badge-processing {
        background-color: #E8F5E6;
        color: #2C8F0C;
    }
    
    .list-group-item {
        border: 1px solid #E8F5E6;
        border-radius: 8px !important;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .list-group-item:hover {
        background-color: #F8FDF8;
        border-color: #2C8F0C;
        transform: translateX(5px);
    }
    
    .stock-alert {
        color: #DC3545;
        font-weight: 600;
    }
</style>

<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">Dashboard Overview</h1>
            <p class="mb-0 text-muted">Welcome back, {{ Auth::user()->name }}! Here's what's happening with your store today.</p>
        </div>
        <div class="text-end">
            <small class="text-muted">Last updated: {{ now()->format('M d, Y \\a\\t h:i A') }}</small>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card card-primary h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stats-label">TOTAL PRODUCTS</div>
                        <div class="stats-number">{{ $stats['total_products'] }}</div>
                        <small>Active in catalog</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card card-success h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stats-label">TOTAL ORDERS</div>
                        <div class="stats-number">{{ $stats['total_orders'] }}</div>
                        <small>All time orders</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card card-info h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stats-label">TOTAL CUSTOMERS</div>
                        <div class="stats-number">{{ $stats['total_customers'] }}</div>
                        <small>Registered users</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card card-warning h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stats-label">TOTAL REVENUE</div>
                        <div class="stats-number">₱{{ number_format($stats['revenue'], 2) }}</div>
                        <small>Lifetime sales</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="card section-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-shopping-cart me-2"></i>Recent Orders
                </h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-success">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-bold" style="color: #2C8F0C;">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td>{{ $order->customer_name }}</td>
                                <td class="fw-bold">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pending' => 'badge-pending',
                                            'completed' => 'badge-completed',
                                            'processing' => 'badge-processing'
                                        ][$order->order_status] ?? 'badge-pending';
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-3 py-2">
                                        <i class="fas fa-{{ $order->order_status == 'pending' ? 'clock' : ($order->order_status == 'completed' ? 'check' : 'cog') }} me-1"></i>
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card section-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                </h6>
                <span class="badge bg-danger">{{ $lowStockProducts->count() }}</span>
            </div>
            <div class="card-body">
                @if($lowStockProducts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($lowStockProducts as $product)
                        <a href="{{ route('admin.products.edit', $product) }}" class="list-group-item list-group-item-action border-0">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($product->name, 30) }}</h6>
                                    <small class="text-muted">SKU: {{ $product->sku }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="stock-alert d-block">Stock: {{ $product->stock_quantity }}</span>
                                    <small class="text-muted">Reorder needed</small>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted mb-0">All products are well stocked!</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card section-card mt-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Add New Product
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-success">
                        <i class="fas fa-tags me-2"></i>Add Category
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection