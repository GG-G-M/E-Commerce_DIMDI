@extends('layouts.admin')

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

    /* Summary Cards - Consistent with low stock page */
    .summary-card {
        background: linear-gradient(135deg, #E8F5E6, #F8FDF8);
        border: none;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .summary-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2C8F0C;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .summary-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
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

    /* Alternating row colors for better readability */
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #F8FDF8;
    }

    /* Filter Section - Consistent */
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

    /* Order styling */
    .order-number {
        font-weight: 700;
        color: #2C8F0C;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }

    .customer-name {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }

    .customer-email {
        color: #2C8F0C;
        font-size: 0.85rem;
        word-break: break-word;
    }

    .customer-contact {
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

    /* Enhanced Status styling - Consistent text with pulsing dots */
    .status-text {
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-pending {
        color: #FF9800;
    }
    
    .status-pending::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #FF9800;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-confirmed {
        color: #2196F3;
    }
    
    .status-confirmed::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #2196F3;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-processing {
        color: #9C27B0;
    }
    
    .status-processing::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #9C27B0;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-shipped {
        color: #673AB7;
    }
    
    .status-shipped::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #673AB7;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-delivered {
        color: #2C8F0C;
    }
    
    .status-delivered::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #2C8F0C;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-cancelled {
        color: #C62828;
    }
    
    .status-cancelled::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #C62828;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
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

    /* Enhanced Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: nowrap;
        justify-content: center;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        border: 2px solid;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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
    
    .btn-refund {
        background-color: white;
        border-color: #28a745;
        color: #28a745;
    }
    
    .btn-refund:hover {
        background-color: #28a745;
        color: white;
    }

    /* Table Container */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        max-width: 100%;
    }

    /* Column widths - More compact */
    .order-col { width: 120px; min-width: 120px; }
    .customer-col { width: 180px; min-width: 180px; }
    .contact-col { width: 100px; min-width: 100px; }
    .total-col { width: 100px; min-width: 100px; }
    .status-col { width: 120px; min-width: 120px; }
    .date-col { width: 100px; min-width: 100px; }
    .action-col { width: 80px; min-width: 80px; }

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
    
    .pagination-info {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }

    /* Empty state - Consistent */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #C8E6C9;
        margin-bottom: 1rem;
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
        
        .action-btn {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }
        
        .summary-number {
            font-size: 1.5rem;
        }
        
        .summary-label {
            font-size: 0.8rem;
        }
        
        .status-text {
            font-size: 0.8rem;
        }
    }
</style>

<!-- Summary Cards - Consistent with low stock page -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $orders->total() }}</div>
            <div class="summary-label">Total Orders</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">₱{{ number_format($orders->sum('total_amount'), 2) }}</div>
            <div class="summary-label">Total Revenue</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $orders->where('order_status', 'pending')->count() }}</div>
            <div class="summary-label">Pending Orders</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $orders->where('order_status', 'delivered')->count() }}</div>
            <div class="summary-label">Delivered</div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" id="filterForm">
            <div class="row">
                <!-- Search by Order, Customer, or Email -->
                <div class="col-md-5">
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

                <!-- Filter by Status -->
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

                <!-- Date Range and Items per page -->
                <div class="col-md-2">
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
                        <tr data-id="{{ $order->id }}">
                            <td class="order-col">
                                <div class="order-number">#{{ $order->order_number }}</div>
                            </td>
                            <td class="customer-col">
                                <div class="customer-name">{{ $order->customer_name }}</div>
                                <a href="mailto:{{ $order->customer_email }}" class="customer-email" title="{{ $order->customer_email }}">
                                    {{ $order->customer_email }}
                                </a>
                            </td>
                            <td class="contact-col">
                                @if($order->customer_phone)
                                    <div class="customer-contact">{{ $order->customer_phone }}</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="total-col">
                                <div class="total-amount">₱{{ number_format($order->total_amount, 2) }}</div>
                            </td>
                            <td class="status-col">
                                <span class="status-text status-{{ $order->order_status }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                                @if($order->order_status == 'cancelled' && $order->cancellation_reason)
                                <div class="cancellation-reason" title="{{ $order->cancellation_reason }}">
                                    {{ Str::limit($order->cancellation_reason, 30) }}
                                </div>
                                @endif
                            </td>
                            <td class="date-col">
                                <div class="order-date">
                                    {{ $order->created_at->format('M j, Y') }}
                                </div>
                                <div class="order-time">
                                    {{ $order->created_at->format('h:i A') }}
                                </div>
                            </td>
                            <td class="action-col">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="action-btn btn-view" title="View Order Details">
                                        <i class="fas fa-search ms-1"></i>
                                    </a>
                                    @if($order->order_status == 'cancelled' && !$order->refund_processed)
                                    <a href="{{ route('admin.orders.refund.show', $order) }}" class="action-btn btn-refund" title="Process Refund">
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
            <div class="d-flex justify-content-center p-3">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
            <div class="d-flex justify-content-center">
                <div class="pagination-info">
                    Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} entries
                </div>
            </div>
            @endif

        @else
            <div class="empty-state p-5">
                <i class="fas fa-shopping-cart"></i>
                <h5 class="text-muted">No Orders Found</h5>
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

        // Auto-submit other filters immediately
        statusSelect.addEventListener('change', function() {
            filterForm.submit();
        });

        dateRangeSelect.addEventListener('change', function() {
            filterForm.submit();
        });

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