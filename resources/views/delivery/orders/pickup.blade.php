@extends('layouts.delivery')

@section('title', 'Ready for Pickup - Delivery Dashboard')

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
    
    .btn-warning {
        background: linear-gradient(135deg, #FFA000, #FFB300);
        border: none;
        font-weight: 500;
        color: white;
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
    
    .status-ready {
        color: #FFA000;
        font-weight: 600;
    }
    
    .priority-high {
        border-left: 4px solid #dc3545;
    }
    
    .priority-medium {
        border-left: 4px solid #FFA000;
    }
    
    .priority-low {
        border-left: 4px solid #28a745;
    }
</style>

<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">Orders Ready for Pickup</h1>
            <p class="mb-0 text-muted">Pick up orders from the warehouse and start delivery process.</p>
        </div>
        <div class="text-end">
            <small class="text-muted">Available for Pickup: {{ $orders->total() }}</small>
        </div>
    </div>
</div>

<!-- Filter and Search Section -->
<div class="card section-card mb-4">
    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('delivery.orders.pickup') }}">
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

                <!-- Items Filter -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold text-muted mb-2">Number of Items</label>
                    <select class="form-select search-box" name="items_filter" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Any Number</option>
                        <option value="1-3" {{ request('items_filter') == '1-3' ? 'selected' : '' }}>1-3 Items</option>
                        <option value="4-6" {{ request('items_filter') == '4-6' ? 'selected' : '' }}>4-6 Items</option>
                        <option value="7+" {{ request('items_filter') == '7+' ? 'selected' : '' }}>7+ Items</option>
                    </select>
                </div>
            </div>

            <!-- Reset Filters -->
            @if(request()->hasAny(['search', 'amount_filter', 'items_filter']))
            <div class="row mt-2">
                <div class="col-12">
                    <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-outline-secondary btn-sm">
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
            <i class="fas fa-box me-2"></i>Available for Pickup
            @if(request()->hasAny(['search', 'amount_filter', 'items_filter']))
                <small class="text-muted ms-2">(Filtered Results)</small>
            @endif
        </h6>
        <div class="btn-group">
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
            <div class="alert alert-info m-3 mb-0">
                <i class="fas fa-info-circle me-2"></i> 
                You have {{ $orders->count() }} order(s) ready for pickup. Please pick them up from the warehouse.
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
                            <th>Items</th>
                            <th>Order Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        @php
                            $itemCount = $order->orderItems->count();
                            $priority = $order->total_amount > 2000 ? 'high' : ($order->total_amount > 500 ? 'medium' : 'low');
                        @endphp
                        <tr class="priority-{{ $priority }}">
                            <td>
                                <strong class="text-dark">#{{ $order->order_number }}</strong>
                                <br>
                                <span class="status-ready">
                                    <i class="fas fa-box me-1"></i>Ready for Pickup
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
                                <small class="text-muted">{{ Str::limit($order->shipping_address, 50) }}</small>
                            </td>
                            <td class="fw-bold text-success">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td>
                                <span class="fw-bold">{{ $itemCount }}</span> items
                                <br>
                                <small class="text-muted">
                                    @foreach($order->orderItems->take(2) as $item)
                                    {{ $item->product->name ?? 'Item' }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                    @if($itemCount > 2)
                                    +{{ $itemCount - 2 }} more
                                    @endif
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $order->created_at->format('M j, Y') }}
                                    <br>
                                    {{ $order->created_at->format('g:i A') }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('delivery.orders.show', $order) }}" class="btn btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <form action="{{ route('delivery.orders.markAsPickedUp', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning" 
                                                onclick="return confirm('Mark order #{{ $order->order_number }} as picked up?')"
                                                title="Mark as Picked Up">
                                            <i class="fas fa-box"></i>
                                        </button>
                                    </form>
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
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders ready for pickup
                        </small>
                    </div>
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-box"></i>
                <h5 class="text-muted">No Orders Ready for Pickup</h5>
                <p class="text-muted mb-3">
                    @if(request()->hasAny(['search', 'amount_filter', 'items_filter']))
                        No orders match your current filters.
                    @else
                        All available orders have been picked up or are in delivery.
                    @endif
                </p>
                <div class="mt-3">
                    @if(request()->hasAny(['search', 'amount_filter', 'items_filter']))
                        <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-success">
                            <i class="fas fa-times me-1"></i> Clear Filters
                        </a>
                    @endif
                    <a href="{{ route('delivery.orders.index') }}" class="btn btn-primary">
                        <i class="fas fa-list me-1"></i> View All Orders
                    </a>
                    <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-clipboard-list me-1"></i> My Orders
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation for mark as picked up
    const pickupForms = document.querySelectorAll('form[action*="markAsPickedUp"]');
    pickupForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to mark this order as picked up?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection