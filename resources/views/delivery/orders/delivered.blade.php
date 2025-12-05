@extends('layouts.delivery')

@section('title', 'Delivered Orders - Delivery Dashboard')

@section('content')
<style>
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
    
    .btn-success {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        font-weight: 500;
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-1px);
    }
    
    .search-box {
        border-radius: 8px;
        border: 1px solid #E8F5E6;
        padding: 0.5rem 1rem;
    }
    
    .search-box:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.25);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(44, 143, 12, 0.05);
    }
    
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .status-delivered {
        color: #28a745;
        font-weight: 600;
    }
    
    .delivery-time-recent {
        color: #28a745;
        font-weight: 600;
    }
    
    .delivery-time-old {
        color: #6c757d;
    }
</style>

<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">Delivered Orders</h1>
            <p class="mb-0 text-muted">View your completed delivery orders and track your performance.</p>
        </div>
        <div class="text-end">
            <small class="text-muted">Total Delivered: {{ $orders->total() }}</small>
        </div>
    </div>
</div>

<!-- Filter and Search Section -->
<div class="card section-card mb-4">
    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('delivery.orders.delivered') }}">
            <div class="row align-items-end">
                <!-- Search Box -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold text-muted mb-2">Search Orders</label>
                    <div class="input-group">
                        <input type="text" class="form-control search-box" name="search" 
                               placeholder="Search by order number, customer name..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-success" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Date Filter -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold text-muted mb-2">Delivery Date</label>
                    <select class="form-select search-box" name="date_filter" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_month" {{ request('date_filter') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                    </select>
                </div>

                <!-- Amount Filter -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold text-muted mb-2">Order Amount</label>
                    <select class="form-select search-box" name="amount_filter" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Any Amount</option>
                        <option value="low" {{ request('amount_filter') == 'low' ? 'selected' : '' }}>Under ₱500</option>
                        <option value="medium" {{ request('amount_filter') == 'medium' ? 'selected' : '' }}>₱500 - ₱2,000</option>
                        <option value="high" {{ request('amount_filter') == 'high' ? 'selected' : '' }}>Over ₱2,000</option>
                    </select>
                </div>
            </div>

            <!-- Reset Filters -->
            @if(request()->hasAny(['search', 'date_filter', 'amount_filter']))
            <div class="row mt-2">
                <div class="col-12">
                    <a href="{{ route('delivery.orders.delivered') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card section-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-check-circle me-2"></i>Delivered Orders History
            @if(request()->hasAny(['search', 'date_filter', 'amount_filter']))
                <small class="text-muted ms-2">(Filtered Results)</small>
            @endif
        </h6>
        <div class="btn-group">
            <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-sm btn-outline-warning">
                <i class="fas fa-box me-1"></i> Ready for Pickup
            </a>
            <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-list me-1"></i> My Orders
            </a>
            <a href="{{ route('delivery.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-list-alt me-1"></i> All Orders
            </a>
        </div>
    </div>
    
    <div class="card-body p-0">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Delivery Address</th>
                            <th>Amount</th>
                            <th>Items</th>
                            <th>Delivered On</th>
                            <th>Delivery Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        @php
                            $deliveryTime = $order->delivered_at ? $order->delivered_at->diffInHours($order->picked_up_at ?? $order->assigned_at) : null;
                            $isRecentDelivery = $order->delivered_at && $order->delivered_at->gt(now()->subDays(1));
                        @endphp
                        <tr>
                            <td>
                                <strong class="text-dark">#{{ $order->order_number }}</strong>
                                <br>
                                <span class="status-delivered">
                                    <i class="fas fa-check-circle me-1"></i>Delivered
                                </span>
                            </td>
                            <td>
                                <strong>{{ $order->customer_name }}</strong>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-phone me-1"></i>{{ $order->customer_phone }}<br>
                                    <i class="fas fa-envelope me-1"></i>{{ Str::limit($order->customer_email, 15) }}
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">{{ Str::limit($order->shipping_address, 40) }}</small>
                            </td>
                            <td class="fw-bold text-success">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td>
                                <span class="fw-bold">{{ $order->orderItems->count() }}</span> items
                            </td>
                            <td>
                                @if($order->delivered_at)
                                    <small class="{{ $isRecentDelivery ? 'delivery-time-recent' : 'delivery-time-old' }}">
                                        {{ $order->delivered_at->format('M j, Y') }}<br>
                                        {{ $order->delivered_at->format('g:i A') }}
                                    </small>
                                @else
                                    <small class="text-muted">N/A</small>
                                @endif
                            </td>
                            <td>
                                @if($deliveryTime !== null)
                                    <small class="text-muted">
                                        {{ $deliveryTime }} hours
                                    </small>
                                @else
                                    <small class="text-muted">N/A</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('delivery.orders.show', $order) }}" class="btn btn-outline-success" title="View Delivery Details">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} delivered orders
                        </small>
                    </div>
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-check-circle"></i>
                <h5 class="text-muted">No Delivered Orders</h5>
                <p class="text-muted mb-3">
                    @if(request()->hasAny(['search', 'date_filter', 'amount_filter']))
                        No delivered orders match your current filters.
                    @else
                        You haven't delivered any orders yet.
                    @endif
                </p>
                <div class="mt-3">
                    @if(request()->hasAny(['search', 'date_filter', 'amount_filter']))
                        <a href="{{ route('delivery.orders.delivered') }}" class="btn btn-success">
                            <i class="fas fa-times me-1"></i> Clear Filters
                        </a>
                    @endif
                    <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-primary">
                        <i class="fas fa-box me-1"></i> Pick Up Orders
                    </a>
                    <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-clipboard-list me-1"></i> My Active Orders
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delivery Statistics -->
@if($orders->count() > 0)
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card section-card">
            <div class="card-body text-center">
                <i class="fas fa-shipping-fast fa-2x text-success mb-2"></i>
                <h4 class="text-success">{{ $orders->total() }}</h4>
                <p class="text-muted mb-0">Total Delivered</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card section-card">
            <div class="card-body text-center">
                <i class="fas fa-calendar-day fa-2x text-success mb-2"></i>
                <h4 class="text-success">
                    {{ $orders->where('delivered_at', '>=', now()->startOfDay())->count() }}
                </h4>
                <p class="text-muted mb-0">Delivered Today</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card section-card">
            <div class="card-body text-center">
                <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                <h4 class="text-success">
                    ₱{{ number_format($orders->sum('total_amount'), 2) }}
                </h4>
                <p class="text-muted mb-0">Total Value Delivered</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection