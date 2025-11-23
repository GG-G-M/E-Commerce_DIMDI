@extends('layouts.delivery')

@section('title', 'All Orders - Delivery Dashboard')

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
    
    .filter-active {
        background-color: #2C8F0C !important;
        color: white !important;
        border-color: #2C8F0C !important;
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
    
    .status-available {
        color: #28a745;
        font-weight: 600;
    }
    
    .status-my-order {
        color: #007bff;
        font-weight: 600;
    }
    
    .status-assigned {
        color: #6c757d;
        font-weight: 600;
    }
    
    .status-processing { color: #ffc107; font-weight: 600; }
    .status-shipped { color: #17a2b8; font-weight: 600; }
    .status-out-for-delivery { color: #fd7e14; font-weight: 600; }
    .status-delivered { color: #28a745; font-weight: 600; }
    .status-cancelled { color: #dc3545; font-weight: 600; }
</style>

<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">All Orders</h1>
            <p class="mb-0 text-muted">Manage and track all delivery orders in one place.</p>
        </div>
        <div class="text-end">
            <small class="text-muted">Total Orders: {{ $orders->total() }}</small>
        </div>
    </div>
</div>

<!-- Filter and Search Section -->
<div class="card section-card mb-4">
    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('delivery.orders.index') }}">
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
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Assignment Filter -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold text-muted mb-2">Assignment</label>
                    <select class="form-select search-box" name="assignment" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Assignments</option>
                        <option value="available" {{ request('assignment') == 'available' ? 'selected' : '' }}>Available for Pickup</option>
                        <option value="my_orders" {{ request('assignment') == 'my_orders' ? 'selected' : '' }}>My Orders</option>
                        <option value="assigned" {{ request('assignment') == 'assigned' ? 'selected' : '' }}>Assigned to Others</option>
                    </select>
                </div>
            </div>

            <!-- Quick Filter Buttons -->
            <div class="row mt-3">
                <div class="col-12">
                    <label class="form-label fw-bold text-muted mb-2">Quick Filters</label>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="date_filter" value="today" id="today" 
                               {{ request('date_filter') == 'today' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-success" for="today">Today</label>

                        <input type="radio" class="btn-check" name="date_filter" value="week" id="week" 
                               {{ request('date_filter') == 'week' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-success" for="week">This Week</label>

                        <input type="radio" class="btn-check" name="date_filter" value="month" id="month" 
                               {{ request('date_filter') == 'month' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-success" for="month">This Month</label>

                        <input type="radio" class="btn-check" name="date_filter" value="" id="all_dates" 
                               {{ !request('date_filter') ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-success" for="all_dates">All Dates</label>
                    </div>

                    <!-- Reset Filters -->
                    @if(request()->hasAny(['search', 'status', 'assignment', 'date_filter']))
                    <a href="{{ route('delivery.orders.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                    @endif
                </div>
            </div>
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
            <i class="fas fa-list me-2"></i>Orders List
            @if(request()->hasAny(['search', 'status', 'assignment', 'date_filter']))
                <small class="text-muted ms-2">(Filtered Results)</small>
            @endif
        </h6>
        <div class="btn-group">
            <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-box me-1"></i> Available Orders
            </a>
            <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-list me-1"></i> My Orders
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
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Assignment</th>
                            <th>Order Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <strong class="text-dark">#{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $order->customer_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $order->customer_phone }}</small>
                                </div>
                            </td>
                            <td class="fw-bold text-success">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td>
                                <span class="status-{{ str_replace('_', '-', $order->order_status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                </span>
                            </td>
                            <td>
                                @if(is_null($order->delivery_id))
                                    <span class="status-available">Available</span>
                                @elseif($order->delivery_id == Auth::id())
                                    <span class="status-my-order">My Order</span>
                                @else
                                    <span class="status-assigned">Assigned</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $order->created_at->format('M j, Y') }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('delivery.orders.show', $order) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    
                                    @if(is_null($order->delivery_id) && $order->order_status == 'processing')
                                    <form action="{{ route('delivery.orders.pickup-order', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-hand-paper"></i> Pick Up
                                        </button>
                                    </form>
                                    @endif

                                    @if($order->delivery_id == Auth::id() && in_array($order->order_status, ['shipped', 'out_for_delivery']))
                                    <form action="{{ route('delivery.orders.deliver-order', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning" 
                                                onclick="return confirm('Mark order #{{ $order->order_number }} as delivered?')">
                                            <i class="fas fa-check"></i> Deliver
                                        </button>
                                    </form>
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
                <i class="fas fa-box-open"></i>
                <h5 class="text-muted">No Orders Found</h5>
                <p class="text-muted mb-3">
                    @if(request()->hasAny(['search', 'status', 'assignment', 'date_filter']))
                        No orders match your current filters.
                    @else
                        There are no orders available at the moment.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status', 'assignment', 'date_filter']))
                    <a href="{{ route('delivery.orders.index') }}" class="btn btn-success">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                @else
                    <a href="{{ route('delivery.dashboard') }}" class="btn btn-success">
                        <i class="fas fa-tachometer-alt me-1"></i> Back to Dashboard
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when radio buttons change
    const dateFilters = document.querySelectorAll('input[name="date_filter"]');
    dateFilters.forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    // Add active class to filter buttons
    const filterButtons = document.querySelectorAll('.btn-check');
    filterButtons.forEach(button => {
        if (button.checked) {
            const label = document.querySelector(`label[for="${button.id}"]`);
            label.classList.add('filter-active');
        }
    });
});
</script>
@endsection