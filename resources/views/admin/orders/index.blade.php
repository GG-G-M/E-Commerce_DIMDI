@extends('layouts.admin')

@section('content')
<style>
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-2px);
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
    }

    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    }

    .btn-success {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    .status-pending { color: #FF9800; font-weight: 600; }
    .status-confirmed { color: #2196F3; font-weight: 600; }
    .status-processing { color: #9C27B0; font-weight: 600; }
    .status-shipped { color: #673AB7; font-weight: 600; }
    .status-delivered { color: #2C8F0C; font-weight: 600; }
    .status-cancelled { color: #C62828; font-weight: 600; }

    .btn-outline-primary {
        color: #2C8F0C;
        border-color: #2C8F0C;
    }

    .btn-outline-primary:hover {
        background-color: #2C8F0C;
        color: #fff;
    }

    .search-loading {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }

    .position-relative {
        position: relative;
    }

    .order-number {
        font-weight: 700;
        color: #2C8F0C;
    }

    .customer-name {
        font-weight: 600;
        color: #333;
    }

    .customer-email {
        color: #6c757d;
        font-size: 0.875em;
    }

    .total-amount {
        font-weight: 700;
        color: #2C8F0C;
        font-size: 1.1em;
    }

    .order-date {
        color: #6c757d;
        font-size: 0.875em;
    }

    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #2C8F0C;
    }

    .summary-card {
        background: linear-gradient(135deg, #E8F5E6, #F8FDF8);
        border: 1px solid #2C8F0C;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .summary-number {
        font-size: 2rem;
        font-weight: 700;
        color: #2C8F0C;
        line-height: 1;
    }

    .summary-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 600;
    }
</style>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="summary-card text-center">
            <div class="summary-number">{{ $orders->total() }}</div>
            <div class="summary-label">Total Orders</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card text-center">
            <div class="summary-number">₱{{ number_format($orders->sum('total_amount'), 0) }}</div>
            <div class="summary-label">Total Revenue</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card text-center">
            <div class="summary-number">{{ $orders->where('order_status', 'pending')->count() }}</div>
            <div class="summary-label">Pending Orders</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card text-center">
            <div class="summary-number">{{ $orders->where('order_status', 'delivered')->count() }}</div>
            <div class="summary-label">Delivered</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Orders</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Search by order number, customer name, email...">
                        <div class="search-loading" id="searchLoading">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Filter by Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="date_range" class="form-label fw-bold">Date Range</label>
                        <select class="form-select" id="date_range" name="date_range">
                            <option value="">All Time</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="per_page" class="form-label fw-bold">Items per page</label>
                        <select class="form-select" id="per_page" name="per_page">
                            @foreach([10, 25, 50, 100] as $option)
                                <option value="{{ $option }}" {{ request('per_page', 25) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Orders List</h5>
    </div>
    <div class="card-body">
        @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <div class="order-number">{{ $order->order_number }}</div>
                        </td>
                        <td>
                            <div class="customer-name">{{ $order->customer_name }}</div>
                            <div class="customer-email">{{ $order->customer_email }}</div>
                        </td>
                        <td>
                            @if($order->customer_phone)
                                <div class="text-dark">{{ $order->customer_phone }}</div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="total-amount">₱{{ number_format($order->total_amount, 2) }}</div>
                        </td>
                        <td>
                            <span class="status-{{ $order->order_status }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                            @if($order->order_status == 'cancelled' && $order->cancellation_reason)
                            <div class="text-muted small mt-1">
                                {{ Str::limit($order->cancellation_reason, 25) }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="order-date">
                                {{ $order->created_at->format('M j, Y') }}
                            </div>
                            <div class="text-muted smaller">
                                {{ $order->created_at->format('H:i') }}
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="View Order">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($order->order_status == 'cancelled' && !$order->refund_processed)
                                <a href="{{ route('admin.orders.refund.show', $order) }}" class="btn btn-sm btn-success" title="Process Refund">
                                    <i class="fas fa-money-bill-wave"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
        @endif

        @else
        <div class="empty-state">
            <i class="fas fa-shopping-cart"></i>
            <h4 class="text-success">No Orders Found</h4>
            <p class="text-muted mb-4">Try adjusting your search or filter criteria</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const searchInput = document.getElementById('search');
        const statusSelect = document.getElementById('status');
        const dateRangeSelect = document.getElementById('date_range');
        const perPageSelect = document.getElementById('per_page');
        const searchLoading = document.getElementById('searchLoading');
        
        let searchTimeout;

        // Auto-submit search with delay
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchLoading.style.display = 'block';
            
            searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 800);
        });

        // Auto-submit status filter immediately
        statusSelect.addEventListener('change', function() {
            filterForm.submit();
        });

        // Auto-submit date range filter immediately
        dateRangeSelect.addEventListener('change', function() {
            filterForm.submit();
        });

        // Auto-submit per page selection immediately
        perPageSelect.addEventListener('change', function() {
            filterForm.submit();
        });

        // Clear loading indicator when form submits
        filterForm.addEventListener('submit', function() {
            searchLoading.style.display = 'none';
        });
    });
</script>
@endpush
@endsection