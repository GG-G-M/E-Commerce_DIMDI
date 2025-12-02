@extends('layouts.app')

@section('content')
<style>
    :root {
        --green-primary: #2C8F0C;
        --green-light: #E6F4E1;
        --green-gradient: linear-gradient(135deg, #2C8F0C 0%, #3DB814 100%);
    }

    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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

    .btn-success {
        background: linear-gradient(135deg, #48b036ff, #ffffffff);
        border: none;
        color: black;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        color: white;
    }

    .btn-outline-success {
        border-color: #2C8F0C;
        color: #2C8F0C;
    }

    .btn-outline-success:hover {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
        color: white;
    }

    .btn-outline-warning {
        border-color: #FBC02D;
        color: #FBC02D;
    }

    .btn-outline-warning:hover {
        background-color: #FBC02D;
        border-color: #FBC02D;
        color: white;
    }

    .btn-outline-danger {
        border-color: #C62828;
        color: #C62828;
    }

    .btn-outline-danger:hover {
        background-color: #C62828;
        border-color: #C62828;
        color: white;
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

    .badge-success {
        background-color: #2C8F0C !important;
    }

    .badge-warning {
        background-color: #FBC02D !important;
        color: #333;
    }

    .badge-primary {
        background-color: #1976D2 !important;
    }

    .badge-danger {
        background-color: #C62828 !important;
    }

    .badge-secondary {
        background-color: #6c757d !important;
    }

    .order-number {
        font-weight: 600;
        color: #2C8F0C;
    }

    .total-amount {
        font-weight: 700;
        color: #2C8F0C;
    }

    .text-center h1 {
        font-weight: 700;
        color: #2C8F0C;
        margin-bottom: 0.5rem;
    }

    .text-center p {
        color: #6c757d;
        font-size: 1.1rem;
    }

    .empty-state {
        padding: 3rem 0;
    }

    .empty-state i {
        color: #2C8F0C;
        opacity: 0.3;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: #2C8F0C;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }

    .modal-content {
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(44, 143, 12, 0.25);
    }

    .modal-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border-bottom: none;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .alert {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .pagination .page-item .page-link {
        color: #2C8F0C;
        border-radius: 50px;
        margin: 0 4px;
        border: 1px solid #2C8F0C;
        transition: all 0.25s ease;
        font-weight: 500;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2C8F0C 0%, #3DB814 100%);
        border-color: #2C8F0C;
        color: #fff;
        box-shadow: 0 3px 8px rgba(44, 143, 12, 0.3);
    }

    .pagination .page-link:hover {
        background: linear-gradient(135deg, #2C8F0C 0%, #3DB814 100%);
        color: #fff;
        transform: scale(1.05);
    }
</style>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="container py-4">
    <div class="text-center mb-5">
        <h1>My Orders</h1>
        <p>Track your purchases and manage your orders efficiently.</p>
    </div>

    @if($orders->count() > 0)
    <!-- Search and Filters -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('orders.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3 position-relative">
                            <label for="search" class="form-label fw-bold">Search Orders</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Search by order number or customer name...">
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
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="sort_by" class="form-label fw-bold">Sort By</label>
                            <select class="form-select" id="sort_by" name="sort_by">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                                <option value="order_number" {{ request('sort_by') == 'order_number' ? 'selected' : '' }}>Order Number</option>
                                <option value="total_amount" {{ request('sort_by') == 'total_amount' ? 'selected' : '' }}>Total Amount</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="per_page" class="form-label fw-bold">Items per page</label>
                            <select class="form-select" id="per_page" name="per_page">
                                @foreach([5, 10, 15, 25, 50] as $option)
                                    <option value="{{ $option }}" {{ request('per_page', 10) == $option ? 'selected' : '' }}>
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
            <h5 class="mb-0">Order History</h5>
            <a href="{{ route('products.index') }}" class="btn btn-success">
                <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td><span class="order-number">{{ $order->order_number }}</span></td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td class="total-amount">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->order_status === 'delivered' ? 'success' : 
                                    ($order->order_status === 'cancelled' ? 'danger' : 
                                    ($order->order_status === 'shipped' ? 'primary' : 
                                    ($order->order_status === 'processing' ? 'info' : 'warning'))) 
                                }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-success me-1" title="View Order">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($order->canBeCancelled())
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-outline-danger cancel-btn"
                                        data-order-id="{{ $order->id }}"
                                        data-order-number="{{ $order->order_number }}"
                                        title="Cancel Order">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>

    <!-- ✅ Single Cancel Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="cancelOrderForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to cancel <strong id="orderNumberText"></strong>?</p>
                        <div class="mb-3">
                            <label class="form-label">Reason for cancellation</label>
                            <textarea class="form-control" name="cancellation_reason" rows="3" required placeholder="Please provide a reason..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @else
    <div class="text-center py-5 empty-state">
        <i class="fas fa-receipt fa-4x mb-4"></i>
        <h3>No orders found</h3>
        <p class="text-muted mb-4">You haven't placed any orders yet.</p>
        <a href="{{ route('products.index') }}" class="btn btn-lg btn-success px-4">
            <i class="fas fa-shopping-bag me-2"></i> Start Shopping
        </a>
    </div>
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const cancelButtons = document.querySelectorAll(".cancel-btn");
        const modal = new bootstrap.Modal(document.getElementById("cancelOrderModal"));
        const form = document.getElementById("cancelOrderForm");
        const orderText = document.getElementById("orderNumberText");

        cancelButtons.forEach(button => {
            button.addEventListener("click", () => {
                const orderId = button.getAttribute("data-order-id");
                const orderNumber = button.getAttribute("data-order-number");
                
                // Set the form action to the correct route
                form.action = `/orders/${orderId}/cancel`;
                orderText.textContent = orderNumber;
                modal.show();
            });
        });

        // Filter form auto-submit functionality
        const filterForm = document.getElementById('filterForm');
        const searchInput = document.getElementById('search');
        const statusSelect = document.getElementById('status');
        const sortBySelect = document.getElementById('sort_by');
        const perPageSelect = document.getElementById('per_page');
        const searchLoading = document.getElementById('searchLoading');
        
        let searchTimeout;

        // Auto-submit search with delay
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchLoading.style.display = 'block';
            
            searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 800); // 800ms delay after typing stops
        });

        // Auto-submit status filter immediately
        statusSelect.addEventListener('change', function() {
            filterForm.submit();
        });

        // Auto-submit sort by selection immediately
        sortBySelect.addEventListener('change', function() {
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
@endsection