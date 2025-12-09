@extends('layouts.delivery')

@section('title', 'Delivered Orders - Delivery Dashboard')

@section('content')
<style>
    /* === Consistent Green Theme === */
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
    }

    /* Dashboard Header */
    .dashboard-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-left: 4px solid #2C8F0C;
    }
    
    /* Table Styling - Compact */
    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 0.75rem 0.5rem;
        white-space: nowrap;
    }

    .table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    /* Alternating row colors */
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #F8FDF8;
    }

    /* Button Styles - Consistent */
    .btn-success-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(44, 143, 12, 0.2);
    }
    
    .btn-success-custom:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
        color: white;
    }

    .btn-outline-success-custom {
        background: white;
        border: 2px solid #2C8F0C;
        color: #2C8F0C;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-success-custom:hover {
        background: #2C8F0C;
        color: white;
        transform: translateY(-1px);
    }

    /* Search Box */
    .search-box {
        border-radius: 8px;
        border: 1px solid #C8E6C9;
        transition: border-color 0.3s ease;
        font-size: 0.9rem;
    }

    .search-box:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.15rem rgba(44,143,12,0.2);
    }

   

    /* Delivery Time Styling */
    .delivery-time-recent {
        color: #2C8F0C;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .delivery-time-old {
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    .delivery-hours {
        color: #495057;
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #C8E6C9;
        margin-bottom: 1rem;
    }

    /* Table Container */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        max-width: 100%;
    }

    /* Column widths - More compact */
    .order-col { width: 100px; min-width: 100px; }
    .customer-col { width: 150px; min-width: 150px; }
    .contact-col { width: 130px; min-width: 130px; }
    .address-col { width: 180px; min-width: 180px; }
    .amount-col { width: 100px; min-width: 100px; }
    .items-col { width: 80px; min-width: 80px; }
    .delivered-col { width: 120px; min-width: 120px; }
    .time-col { width: 100px; min-width: 100px; }
    .action-col { width: 70px; min-width: 70px; }

    /* Customer Info */
    .customer-name {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
        line-height: 1.2;
    }
    
    .customer-phone {
        color: #6c757d;
        font-size: 0.75rem;
    }
    
    .customer-email {
        color: #6c757d;
        font-size: 0.75rem;
    }

    /* Items Count */
    .items-count {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
    }

    /* Amount Styling */
    .amount-text {
        font-weight: 700;
        color: #2C8F0C;
        font-size: 0.9rem;
    }

    /* Address Styling */
    .address-text {
        font-size: 0.85rem;
        color: #495057;
        line-height: 1.3;
    }

    /* Date Styling */
    .date-text {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .time-text {
        font-size: 0.75rem;
        color: #adb5bd;
    }

    /* Statistics Cards */
    .stats-card {
        background: linear-gradient(135deg, #E8F5E6, #F8FDF8);
        border: none;
        border-radius: 10px;
        padding: 1.5rem 1rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .stats-icon {
        font-size: 2rem;
        color: #2C8F0C;
        margin-bottom: 0.75rem;
    }
    
    .stats-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2C8F0C;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .stats-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        justify-content: center;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        border: 2px solid;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    
    .btn-view {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-view:hover {
        background-color: #2C8F0C;
        color: white;
    }

    /* Pagination styling - Consistent */
    .pagination .page-item .page-link {
        color: #2C8F0C;
        border: 1px solid #dee2e6;
        margin: 0 1px;
        border-radius: 4px;
        transition: all 0.3s ease;
        padding: 0.4rem 0.7rem;
        font-size: 0.85rem;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-color: #2C8F0C;
        color: white;
    }
    
    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #E8FDF8;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
    }

    /* Card body padding fix */
    .card-body {
        padding: 0 !important;
    }

    /* Header button group */
    .header-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .header-buttons .btn {
        margin: 0;
        font-size: 0.9rem;
    }

    /* Form Styling */
    .form-label {
        font-weight: 600;
        color: #2C8F0C;
        font-size: 0.9rem;
    }

    /* Make table more compact on mobile */
    @media (max-width: 768px) {
        .header-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
        }
        
        .customer-name {
            font-size: 0.8rem;
        }
        
        .btn-outline-success-custom,
        .btn-success-custom {
            padding: 0.4rem 0.7rem;
            font-size: 0.8rem;
        }
        
        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }
        
        .stats-card {
            padding: 1rem 0.5rem;
        }
        
        .stats-number {
            font-size: 1.5rem;
        }
    }
</style>


<!-- Filter and Search Section -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('delivery.orders.delivered') }}">
            <div class="row g-2 align-items-end">
                <!-- Search Box -->
                <div class="col-md-4">
                    <div class="mb-2">
                        <label class="form-label fw-bold">Search Orders</label>
                        <div class="input-group">
                            <input type="text" class="form-control search-box" name="search" 
                                   placeholder="Search by order number, customer name..." 
                                   value="{{ request('search') }}">
                            <button class="btn btn-success-custom" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Date Filter -->
                <div class="col-md-4">
                    <div class="mb-2">
                        <label class="form-label fw-bold">Delivery Date</label>
                        <select class="form-select search-box" name="date_filter" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Time</option>
                            <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ request('date_filter') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        </select>
                    </div>
                </div>

                <!-- Amount Filter -->
                <div class="col-md-4">
                    <div class="mb-2">
                        <label class="form-label fw-bold">Order Amount</label>
                        <select class="form-select search-box" name="amount_filter" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Any Amount</option>
                            <option value="low" {{ request('amount_filter') == 'low' ? 'selected' : '' }}>Under ₱500</option>
                            <option value="medium" {{ request('amount_filter') == 'medium' ? 'selected' : '' }}>₱500 - ₱2,000</option>
                            <option value="high" {{ request('amount_filter') == 'high' ? 'selected' : '' }}>Over ₱2,000</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Reset Filters -->
            @if(request()->hasAny(['search', 'date_filter', 'amount_filter']))
            <div class="row mt-2">
                <div class="col-12">
                    <a href="{{ route('delivery.orders.delivered') }}" class="btn btn-outline-success-custom btn-sm">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Delivered Orders History</h5>
        <div class="header-buttons">
            <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-outline-success-custom">
                <i class="fas fa-box"></i> Ready
            </a>
            <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-outline-success-custom">
                <i class="fas fa-list"></i> My Orders
            </a>
            <a href="{{ route('delivery.orders.index') }}" class="btn btn-outline-success-custom">
                <i class="fas fa-list-alt"></i> All
            </a>
        </div>
    </div>
    
    <div class="card-body p-0">
        @if($orders->count() > 0)
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="order-col">Order #</th>
                            <th class="customer-col">Customer</th>
                            <th class="contact-col">Contact</th>
                            <th class="address-col">Delivery Address</th>
                            <th class="amount-col">Amount</th>
                            <th class="items-col">Items</th>
                            <th class="delivered-col">Delivered On</th>
                            <th class="time-col">Delivery Time</th>
                            <th class="action-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        @php
                            $deliveryTime = $order->delivered_at ? $order->delivered_at->diffInHours($order->picked_up_at ?? $order->assigned_at) : null;
                            $isRecentDelivery = $order->delivered_at && $order->delivered_at->gt(now()->subDays(1));
                        @endphp
                        <tr>
                            <td class="order-col">
                                <strong class="text-dark">#{{ $order->order_number }}</strong>
                                <div class="mt-1">
                                    <span >
                                        <i class="fas fa-check-circle me-1"></i>Delivered
                                    </span>
                                </div>
                            </td>
                            <td class="customer-col">
                                <div class="customer-name">{{ $order->customer_name }}</div>
                            </td>
                            <td class="contact-col">
                                <div class="customer-phone">
                                    <i class="fas fa-phone me-1"></i>{{ $order->customer_phone }}
                                </div>
                                <div class="customer-email">
                                    <i class="fas fa-envelope me-1"></i>{{ Str::limit($order->customer_email, 15) }}
                                </div>
                            </td>
                            <td class="address-col">
                                <div class="address-text">{{ Str::limit($order->shipping_address, 40) }}</div>
                            </td>
                            <td class="amount-col">
                                <div class="amount-text">₱{{ number_format($order->total_amount, 2) }}</div>
                            </td>
                            <td class="items-col">
                                <div class="items-count">{{ $order->orderItems->count() }}</div>
                            </td>
                            <td class="delivered-col">
                                @if($order->delivered_at)
                                    <div class="{{ $isRecentDelivery ? 'delivery-time-recent' : 'delivery-time-old' }}">
                                        {{ $order->delivered_at->format('M j, Y') }}
                                    </div>
                                    <div class="time-text">
                                        {{ $order->delivered_at->format('h:i A') }}
                                    </div>
                                @else
                                    <div class="text-muted">N/A</div>
                                @endif
                            </td>
                            <td class="time-col">
                                @if($deliveryTime !== null)
                                    <div class="delivery-hours">{{ $deliveryTime }} hours</div>
                                @else
                                    <div class="text-muted">N/A</div>
                                @endif
                            </td>
                            <td class="action-col">
                                <div class="action-buttons">
                                    <a href="{{ route('delivery.orders.show', $order) }}" class="action-btn btn-view" title="View Delivery Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
            <div class="d-flex justify-content-between align-items-center p-3">
                <div>
                    <small class="text-muted">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} delivered orders
                    </small>
                </div>
                <div>
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-check-circle"></i>
                <h5 class="text-muted">No Delivered Orders</h5>
                <p class="text-muted mb-4">
                    @if(request()->hasAny(['search', 'date_filter', 'amount_filter']))
                        No delivered orders match your current filters.
                    @else
                        You haven't delivered any orders yet.
                    @endif
                </p>
                <div class="d-flex gap-3 justify-content-center">
                    @if(request()->hasAny(['search', 'date_filter', 'amount_filter']))
                        <a href="{{ route('delivery.orders.delivered') }}" class="btn btn-success-custom">
                            <i class="fas fa-times me-1"></i> Clear Filters
                        </a>
                    @endif
                    <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-success-custom">
                        <i class="fas fa-box me-1"></i> Pick Up Orders
                    </a>
                    <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-outline-success-custom">
                        <i class="fas fa-clipboard-list me-1"></i> My Orders
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
        <div class="stats-card">
            <i class="fas fa-shipping-fast stats-icon"></i>
            <div class="stats-number">{{ $orders->total() }}</div>
            <div class="stats-label">Total Delivered</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <i class="fas fa-calendar-day stats-icon"></i>
            <div class="stats-number">{{ $orders->where('delivered_at', '>=', now()->startOfDay())->count() }}</div>
            <div class="stats-label">Delivered Today</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <i class="fas fa-money-bill-wave stats-icon"></i>
            <div class="stats-number">₱{{ number_format($orders->sum('total_amount'), 2) }}</div>
            <div class="stats-label">Total Value Delivered</div>
        </div>
    </div>
</div>
@endif
@endsection