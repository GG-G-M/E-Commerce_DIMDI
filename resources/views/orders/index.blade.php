@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #2C8F0C;
        --primary-light: #E8F5E9;
        --primary-dark: #1B5E20;
        --gray-50: #F9FAFB;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
    }

    /* Modern Header */
    .page-header {
        text-align: center;
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .page-header h1 {
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 0.5rem;
        font-size: 2rem;
    }

    .page-header .subtitle {
        color: var(--gray-600);
        font-size: 1rem;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--gray-200);
        margin-bottom: 2rem;
        padding: 1.5rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    /* Orders Table */
    .orders-table-container {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--gray-200);
        overflow: hidden;
    }

    .table-header {
        background: var(--gray-50);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-header h2 {
        font-weight: 600;
        color: var(--gray-800);
        margin: 0;
        font-size: 1.25rem;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table thead {
        background: var(--gray-50);
    }

    .orders-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 600;
        color: var(--gray-700);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--gray-200);
    }

    .orders-table tbody tr {
        border-bottom: 1px solid var(--gray-200);
        transition: background-color 0.2s ease;
    }

    .orders-table tbody tr:hover {
        background: var(--gray-50);
    }

    .orders-table td {
        padding: 1.25rem 1.5rem;
        color: var(--gray-700);
        vertical-align: middle;
    }

    /* Order Number */
    .order-number {
        font-family: 'SF Mono', Monaco, monospace;
        font-weight: 600;
        color: var(--gray-800);
        font-size: 0.95rem;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8125rem;
        font-weight: 500;
        gap: 0.375rem;
    }

    .status-pending {
        background: linear-gradient(135deg, #FEF3C7, #FDE68A);
        color: #92400E;
    }

    .status-processing {
        background: linear-gradient(135deg, #E0F2FE, #BAE6FD);
        color: #0369A1;
    }

    .status-shipped {
        background: linear-gradient(135deg, #E0E7FF, #C7D2FE);
        color: #3730A3;
    }

    .status-delivered {
        background: linear-gradient(135deg, #DCFCE7, #BBF7D0);
        color: #166534;
    }

    .status-cancelled {
        background: linear-gradient(135deg, #FEE2E2, #FECACA);
        color: #991B1B;
    }

    /* Price Display */
    .price-display {
        font-weight: 600;
        color: var(--gray-800);
    }

    /* Action Buttons */
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

    .btn-view {
        background: var(--primary-light);
        color: var(--primary);
        border-color: rgba(44, 143, 12, 0.2);
    }

    .btn-view:hover {
        background: rgba(44, 143, 12, 0.15);
        color: var(--primary-dark);
        text-decoration: none;
    }

    .btn-cancel {
        background: #FEF2F2;
        color: #DC2626;
        border-color: rgba(220, 38, 38, 0.2);
    }

    .btn-cancel:hover {
        background: rgba(220, 38, 38, 0.1);
        color: #991B1B;
        text-decoration: none;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        color: var(--gray-300);
        font-size: 3rem;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: var(--gray-600);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--gray-500);
        margin-bottom: 2rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark), #1B5E20);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Form Elements */
    .form-label {
        font-weight: 500;
        color: var(--gray-700);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        padding: 0.625rem 0.75rem;
        font-size: 0.875rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(44, 143, 12, 0.1);
    }

    /* Pagination */
    .pagination-container {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--gray-200);
    }

    /* Modal */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .modal-header {
        border-bottom: 1px solid var(--gray-200);
        padding: 1.25rem 1.5rem;
    }

    .modal-title {
        font-weight: 600;
        color: var(--gray-800);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid var(--gray-200);
        padding: 1.25rem 1.5rem;
    }

    .btn-secondary {
        background: var(--gray-200);
        color: var(--gray-700);
        border: none;
        border-radius: 8px;
    }

    .btn-danger {
        background: linear-gradient(135deg, #DC2626, #B91C1C);
        border: none;
        border-radius: 8px;
    }

    /* Loading Indicator */
    .search-loading {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }

    /* Alert */
    .alert {
        border-radius: 8px;
        border: none;
        margin-bottom: 2rem;
        padding: 1rem 1.25rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #DCFCE7, #BBF7D0);
        color: #166534;
        border-left: 4px solid #22C55E;
    }

    .alert-danger {
        background: linear-gradient(135deg, #FEE2E2, #FECACA);
        color: #991B1B;
        border-left: 4px solid #EF4444;
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
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
@endif

<div class="container py-5">
    <!-- Page Header -->
    <div class="page-header">
        <h1>My Orders</h1>
        <p class="subtitle">Track your purchases and order history</p>
    </div>

    @if($orders->count() > 0)
    <!-- Search and Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('orders.index') }}" id="filterForm">
            <div class="filter-grid">
                <div class="position-relative">
                    <label for="search" class="form-label">Search Orders</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Order number or customer name">
                    <div class="search-loading" id="searchLoading">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="status" class="form-label">Filter by Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div>
                    <label for="sort_by" class="form-label">Sort By</label>
                    <select class="form-select" id="sort_by" name="sort_by">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="order_number" {{ request('sort_by') == 'order_number' ? 'selected' : '' }}>Order Number</option>
                        <option value="total_amount" {{ request('sort_by') == 'total_amount' ? 'selected' : '' }}>Total Amount</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="orders-table-container">
        <div class="table-header">
            <h2>Order History</h2>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <span class="order-number">{{ $order->order_number }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $order->created_at->format('M d, Y') }}</span>
                        </td>
                        <td>{{ $order->customer_name }}</td>
                        <td>
                            <span class="price-display">₱{{ number_format($order->total_amount, 2) }}</span>
                        </td>
                        <td>
                            @php
                                $statusClasses = [
                                    'pending' => 'status-pending',
                                    'processing' => 'status-processing',
                                    'shipped' => 'status-shipped',
                                    'delivered' => 'status-delivered',
                                    'cancelled' => 'status-cancelled'
                                ];
                            @endphp
                            <span class="status-badge {{ $statusClasses[$order->order_status] ?? 'status-pending' }}">
                                <i class="fas fa-circle fa-xs"></i>
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('orders.show', $order) }}" class="action-btn btn-view">
                                    <i class="fas fa-eye"></i>
                                    View
                                </a>

                                @if($order->canBeCancelled())
                                <button 
                                    type="button" 
                                    class="action-btn btn-cancel cancel-btn"
                                    data-order-id="{{ $order->id }}"
                                    data-order-number="{{ $order->order_number }}">
                                    <i class="fas fa-times"></i>
                                    Cancel
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

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>

    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="cancelOrderForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="mb-3">Are you sure you want to cancel order <strong id="orderNumberText"></strong>?</p>
                        <div class="mb-3">
                            <label class="form-label">Reason for cancellation</label>
                            <textarea class="form-control" name="cancellation_reason" rows="3" required 
                                      placeholder="Please provide a reason for cancellation..."></textarea>
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
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-receipt"></i>
        </div>
        <h3>No orders yet</h3>
        <p>You haven't placed any orders. Start shopping to see your orders here.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="fas fa-shopping-bag me-2"></i> Start Shopping
        </a>
    </div>
    @endif
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Cancel Order Modal
    const cancelButtons = document.querySelectorAll(".cancel-btn");
    const modal = new bootstrap.Modal(document.getElementById("cancelOrderModal"));
    const form = document.getElementById("cancelOrderForm");
    const orderText = document.getElementById("orderNumberText");

    cancelButtons.forEach(button => {
        button.addEventListener("click", () => {
            const orderId = button.getAttribute("data-order-id");
            const orderNumber = button.getAttribute("data-order-number");
            
            form.action = `/orders/${orderId}/cancel`;
            orderText.textContent = orderNumber;
            modal.show();
        });
    });

    // Auto-submit filters
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const sortBySelect = document.getElementById('sort_by');
    const searchLoading = document.getElementById('searchLoading');
    
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchLoading.style.display = 'block';
        
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500);
    });

    statusSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    sortBySelect.addEventListener('change', function() {
        filterForm.submit();
    });

    filterForm.addEventListener('submit', function() {
        searchLoading.style.display = 'none';
    });
});
</script>
@endsection