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
        padding: 1rem 0.75rem;
        white-space: nowrap;
    }

    .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    /* Simplified status text styling */
    .status-text {
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
    }
    
    .status-pending { color: #FF9800; }
    .status-confirmed { color: #2196F3; }
    .status-processing { color: #9C27B0; }
    .status-shipped { color: #673AB7; }
    .status-delivered { color: #2C8F0C; }
    .status-cancelled { color: #C62828; }

    .btn-outline-primary {
        color: #2C8F0C;
        border-color: #2C8F0C;
        border-radius: 6px;
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
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }

    .customer-name {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        margin-bottom: 0.1rem;
    }

    .customer-email {
        color: #6c757d;
        font-size: 0.8rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 180px;
        display: block;
    }

    .customer-contact {
        font-weight: 500;
        color: #495057;
        font-size: 0.9rem;
    }

    .total-amount {
        font-weight: 700;
        color: #2C8F0C;
        font-size: 1rem;
    }

    .order-date {
        color: #6c757d;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .order-time {
        color: #adb5bd;
        font-size: 0.8rem;
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

    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: nowrap;
    }

    .cancellation-reason {
        font-size: 0.8rem;
        color: #dc3545;
        background: #f8d7da;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        margin-top: 0.25rem;
        display: inline-block;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .table thead th:first-child,
    .table tbody td:first-child {
        padding-left: 1.5rem;
    }

    .table thead th:last-child,
    .table tbody td:last-child {
        padding-right: 1.5rem;
    }

    /* Alternating row colors for better readability */
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #F8FDF8;
    }

    /* Better column widths */
    .order-col { min-width: 140px; }
    .customer-col { min-width: 200px; }
    .contact-col { min-width: 130px; }
    .total-col { min-width: 120px; }
    .status-col { min-width: 140px; }
    .date-col { min-width: 130px; }
    .action-col { min-width: 120px; text-align: center; }
    
    /* Pagination styling */
    .pagination .page-item .page-link {
        color: #2C8F0C;
        border: 1px solid #dee2e6;
        margin: 0 2px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-color: #2C8F0C;
        color: white;
    }
    
    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #E8F5E6;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
    }
    
    .pagination-info {
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 0.5rem;
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
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>All Active</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
    <div class="card-body p-0">
        @if($orders->count() > 0)
        <div class="table-container">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="order-col">Order #</th>
                        <th class="customer-col">Customer</th>
                        <th class="contact-col">Contact</th>
                        <th class="total-col">Total</th>
                        <th class="status-col">Status</th>
                        <th class="date-col">Date</th>
                        <th class="action-col">Actions</th>
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
                            <a href="mailto:{{ $order->customer_email }}" class="customer-email" title="{{ $order->customer_email }}">
                                {{ $order->customer_email }}
                            </a>
                        </td>
                        <td>
                            @if($order->customer_phone)
                                <div class="customer-contact">{{ $order->customer_phone }}</div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="total-amount">₱{{ number_format($order->total_amount, 2) }}</div>
                        </td>
                        <td>
                            <span class="status-text status-{{ $order->order_status }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                            @if($order->order_status == 'cancelled' && $order->cancellation_reason)
                            <div class="cancellation-reason" title="{{ $order->cancellation_reason }}">
                                {{ Str::limit($order->cancellation_reason, 30) }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="order-date">
                                {{ $order->created_at->format('M j, Y') }}
                            </div>
                            <div class="order-time">
                                {{ $order->created_at->format('h:i A') }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="action-buttons justify-content-center">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="View Order Details">
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
        <div class="d-flex flex-column align-items-center p-4">
            <nav aria-label="Page navigation">
                {{ $orders->links('pagination::bootstrap-5') }}
            </nav>
            <div class="pagination-info text-center mt-2">
                Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} entries
            </div>
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