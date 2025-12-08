@extends('layouts.delivery')

@section('title', 'My Orders - Delivery Dashboard')

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
    
    .status-processing { color: #ffc107; font-weight: 600; }
    .status-shipped { color: #17a2b8; font-weight: 600; }
    .status-out-for-delivery { color: #fd7e14; font-weight: 600; }
    .status-delivered { color: #28a745; font-weight: 600; }
    .status-cancelled { color: #dc3545; font-weight: 600; }
    
    .progress-bar {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
    }

    /* Unified action icon (green) */
    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: all 0.2s ease;
        text-decoration: none;
        border: 1px solid transparent;
    }

    .action-icon {
        background: transparent;
        color: #2C8F0C;
        border: 1.5px solid #2C8F0C;
        padding: 0.35rem;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 0.95rem;
    }

    .action-icon:hover {
        background: #E8F5E9;
        color: #1B5E20;
        text-decoration: none;
        transform: translateY(-1px);
    }
</style>

<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">My Active Orders</h1>
            <p class="mb-0 text-muted">Manage and track orders assigned to you for delivery.</p>
        </div>
        <div class="text-end">
            <small class="text-muted">Total Orders: {{ $orders->total() }}</small>
        </div>
    </div>
</div>

<!-- Filter and Search Section -->
<div class="card section-card mb-4">
    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('delivery.orders.my-orders') }}">
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

                <!-- Status Filter -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold text-muted mb-2">Order Status</label>
                    <select class="form-select search-box" name="status" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Statuses</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold text-muted mb-2">Time Period</label>
                    <select class="form-select search-box" name="date_filter" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>
            </div>

            <!-- Reset Filters -->
            @if(request()->hasAny(['search', 'status', 'date_filter']))
            <div class="row mt-2">
                <div class="col-12">
                    <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Orders Table -->
<div class="card section-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-clipboard-list me-2"></i>My Orders List
            @if(request()->hasAny(['search', 'status', 'date_filter']))
                <small class="text-muted ms-2">(Filtered Results)</small>
            @endif
        </h6>
        <div class="btn-group">
            <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-box me-1"></i> Available Orders
            </a>
            <a href="{{ route('delivery.orders.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-list me-1"></i> All Orders
            </a>
        </div>
    </div>
    
    <div class="card-body p-0">
        @if($orders->count() > 0)
            <div class="alert alert-info m-3 mb-0">
                <i class="fas fa-info-circle me-2"></i> 
                You have {{ $orders->count() }} active order(s) assigned to you. Deliver them to customers and mark as delivered when completed.
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Delivery Address</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Assigned Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <strong class="text-dark">#{{ $order->order_number }}</strong>
                                <br>
                                <small class="text-muted">{{ $order->orderItems->count() }} items</small>
                            </td>
                            <td>
                                <strong>{{ $order->customer_name }}</strong>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-phone me-1"></i>{{ $order->customer_phone }}<br>
                                    <i class="fas fa-envelope me-1"></i>{{ $order->customer_email }}
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">{{ Str::limit($order->shipping_address, 40) }}</small>
                            </td>
                            <td class="fw-bold text-success">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td>
                                <span class="status-{{ str_replace('_', '-', $order->order_status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                </span>
                                @if(in_array($order->order_status, ['shipped', 'out_for_delivery']))
                                <div class="progress mt-1" style="height: 6px;">
                                    @if($order->order_status == 'shipped')
                                    <div class="progress-bar" style="width: 50%"></div>
                                    @elseif($order->order_status == 'out_for_delivery')
                                    <div class="progress-bar" style="width: 75%"></div>
                                    @endif
                                </div>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $order->assigned_at ? $order->assigned_at->format('M j, Y') : 'N/A' }}
                                </small>
                                @if($order->picked_up_at)
                                <br>
                                <small class="text-muted">
                                    Picked: {{ $order->picked_up_at->format('M j') }}
                                </small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('delivery.orders.show', $order) }}" class="action-btn action-icon" title="View {{ $order->order_number }}" aria-label="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if(in_array($order->order_status, ['shipped', 'out_for_delivery']))
                                    <form action="{{ route('delivery.orders.deliver-order', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" 
                                                onclick="return confirm('Mark order #{{ $order->order_number }} as delivered?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @elseif($order->order_status == 'delivered')
                                    <span class="btn btn-outline-success disabled">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    @endif
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
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                        </small>
                    </div>
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h5 class="text-muted">No Active Orders</h5>
                <p class="text-muted mb-3">
                    @if(request()->hasAny(['search', 'status', 'date_filter']))
                        No orders match your current filters.
                    @else
                        You don't have any active orders assigned to you.
                    @endif
                </p>
                <div class="mt-3">
                    @if(request()->hasAny(['search', 'status', 'date_filter']))
                        <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-success">
                            <i class="fas fa-times me-1"></i> Clear Filters
                        </a>
                    @endif
                    <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-primary">
                        <i class="fas fa-box me-1"></i> Pick Up Available Orders
                    </a>
                    <a href="{{ route('delivery.orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-1"></i> View All Orders
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation for mark as delivered
    const deliverForms = document.querySelectorAll('form[action*="deliver-order"]');
    deliverForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to mark this order as delivered?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection