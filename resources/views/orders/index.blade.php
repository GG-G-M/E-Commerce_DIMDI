@extends('layouts.app')

@section('content')
<style>
    /* 🌿 Enhanced Green Theme - Consistent with Products & Notifications */
    :root {
        --primary-green: #2C8F0C;
        --dark-green: #1E6A08;
        --light-green: #E8F5E6;
        --accent-green: #4CAF50;
        --light-gray: #F8F9FA;
        --medium-gray: #E9ECEF;
        --dark-gray: #6C757D;
        --text-dark: #212529;
    }

    /* Full-width header at the top - matching other pages */
    .orders-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50) !important;
        color: white;
        border-radius: 0 0 16px 16px;
        padding: 2.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        margin-top: -1.5rem;
    }

    .orders-header h1 {
        font-weight: 700;
        font-size: 2rem;
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .orders-header .subtitle {
        margin-bottom: 0;
        opacity: 0.95;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    /* Filter Card - Consistent styling */
    .filter-card {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--medium-gray);
        padding: 1.75rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    /* Orders Table Container - Consistent card design */
    .orders-table-container {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--medium-gray);
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .table-header {
        background: var(--light-green);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--medium-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-header h2 {
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
        font-size: 1.25rem;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table thead {
        background: var(--light-gray);
    }

    .orders-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 600;
        color: var(--dark-gray);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--medium-gray);
    }

    .orders-table tbody tr {
        border-bottom: 1px solid var(--medium-gray);
        transition: all 0.3s ease;
    }

    .orders-table tbody tr:hover {
        background: var(--light-green);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(44, 143, 12, 0.1);
    }

    .orders-table td {
        padding: 1.25rem 1.5rem;
        color: var(--text-dark);
        vertical-align: middle;
    }

    /* Order Number */
    .order-number {
        font-family: 'SF Mono', Monaco, monospace;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    /* Status Display - Simplified (no color badges) */
    .status-text {
        font-weight: 500;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Price Display */
    .price-display {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 1rem;
    }

    /* Action Buttons - Consistent with products/notifications */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    /* Icon buttons - consistent with notifications */
    .action-icon {
        background: transparent;
        color: var(--primary-green);
        border: 2px solid var(--primary-green);
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .action-icon:hover {
        background: var(--primary-green);
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(44, 143, 12, 0.2);
    }

    /* Cancel icon style */
    .action-icon.cancel-icon {
        color: #dc3545;
        border-color: #dc3545;
    }

    .action-icon.cancel-icon:hover {
        background: #dc3545;
        color: white;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
    }

    /* Primary button for continue shopping */
    .btn-primary-rounded {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border: 2px solid transparent;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        cursor: pointer;
        min-height: 40px;
    }

    .btn-primary-rounded:hover:not(:disabled) {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
        text-decoration: none;
    }

    /* Empty State - Consistent with other pages */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        color: var(--dark-gray);
        background: linear-gradient(135deg, #f8fff8, #f0f9f0);
        border-radius: 20px;
        margin: 2rem 0;
    }

    .empty-state i {
        font-size: 5rem;
        color: var(--primary-green);
        margin-bottom: 2rem;
        opacity: 0.7;
    }

    .empty-state h3 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 1rem;
    }

    .empty-state p {
        font-size: 1.05rem;
        color: var(--dark-gray);
        margin-bottom: 2rem;
        line-height: 1.6;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Form Elements */
    .form-label {
        font-weight: 500;
        color: var(--text-dark);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 1px solid var(--medium-gray);
        border-radius: 10px;
        padding: 0.625rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(44, 143, 12, 0.1);
        outline: none;
    }

    /* Pagination - Consistent with notifications */
    .pagination-container {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--medium-gray);
    }

    .pagination {
        justify-content: center;
    }

    .pagination .page-link {
        border-radius: 25px;
        margin: 0 0.25rem;
        padding: 0.5rem 1rem;
        border: 1px solid var(--medium-gray);
        color: var(--text-dark);
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background: var(--light-green);
        border-color: var(--primary-green);
        color: var(--primary-green);
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary-green), var(--accent-green));
        border-color: var(--primary-green);
        color: white;
    }

    /* Modal - Cleaner styling without icons */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .modal-header {
        background: #f8f9fa;
        border-bottom: 1px solid var(--medium-gray);
        padding: 1.5rem 1.75rem;
        border-radius: 16px 16px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        font-weight: 600;
        color: #dc3545;
        font-size: 1.25rem;
        margin: 0;
    }

    .modal-body {
        padding: 2rem 1.75rem;
    }

    .modal-footer {
        border-top: 1px solid var(--medium-gray);
        padding: 1.5rem 1.75rem;
        border-radius: 0 0 16px 16px;
        display: flex;
        gap: 1rem;
    }

    /* Alert - Consistent styling */
    .alert {
        border-radius: 12px;
        border: none;
        margin-bottom: 2rem;
        padding: 1rem 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .alert-success {
        background: linear-gradient(135deg, #DCFCE7, #BBF7D0);
        color: #166534;
        border-left: 4px solid var(--primary-green);
    }

    .alert-danger {
        background: linear-gradient(135deg, #FEE2E2, #FECACA);
        color: #991B1B;
        border-left: 4px solid #EF4444;
    }

    /* Button Styles */
    .btn-secondary {
        background: transparent;
        color: var(--dark-gray);
        border: 2px solid var(--medium-gray);
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        flex: 1;
    }

    .btn-secondary:hover {
        background: var(--medium-gray);
        border-color: var(--dark-gray);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.1);
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545, #ef4444);
        color: white;
        border: 2px solid transparent;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        flex: 1;
    }

    .btn-danger:hover:not(:disabled) {
        background: linear-gradient(135deg, #c82333, #dc3545);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
    }

    /* Confirmation Text */
    .confirmation-text {
        font-size: 1rem;
        line-height: 1.6;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
    }

    .order-highlight {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #dc3545;
    }

    .order-detail {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .order-detail:last-child {
        margin-bottom: 0;
    }

    .detail-label {
        color: var(--dark-gray);
        font-weight: 500;
    }

    .detail-value {
        color: var(--text-dark);
        font-weight: 600;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .orders-header {
            padding: 1.75rem 1.5rem;
            border-radius: 0 0 12px 12px;
            margin-top: -0.75rem;
        }

        .orders-header h1 {
            font-size: 1.5rem;
        }

        .filter-card {
            padding: 1.5rem 1.25rem;
            border-radius: 12px;
        }

        .orders-table-container {
            border-radius: 12px;
        }

        .table-header {
            padding: 1rem 1.25rem;
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .orders-table th,
        .orders-table td {
            padding: 0.875rem 1rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.5rem;
        }

        .action-icon {
            width: 32px;
            height: 32px;
            font-size: 0.85rem;
        }

        .empty-state {
            padding: 3rem 1.5rem;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }

        .empty-state h3 {
            font-size: 1.5rem;
        }

        .empty-state p {
            font-size: 0.95rem;
        }

        .modal-header,
        .modal-body,
        .modal-footer {
            padding: 1.25rem;
        }

        .modal-footer {
            flex-direction: column;
        }

        .btn-secondary,
        .btn-danger {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .orders-header {
            padding: 1.5rem 1.25rem;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .orders-table {
            display: block;
            overflow-x: auto;
        }

        .btn-primary-rounded {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="container mt-4">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if(session('error'))
<div class="container mt-4">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
</div>
@endif

<!-- Full-width header at the top -->
<div class="orders-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-start flex-wrap">
            <div class="flex-grow-1">
                <h1>
                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                </h1>
                <p class="subtitle">Track your purchases and order history</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('products.index') }}" class="btn btn-primary-rounded">
                    <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Container -->
<div class="container py-4">
    @if($orders->count() > 0)
    <!-- Orders Table -->
    <div class="orders-table-container">
        <div class="table-header">
            <h2><i class="fas fa-history me-2"></i>Order History</h2>
            <div class="text-muted">{{ $orders->count() }} orders found</div>
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
                            <div class="text-muted">{{ $order->created_at->format('M d, Y') }}</div>
                            <div class="text-muted" style="font-size: 0.8rem; color: var(--dark-gray);">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td>{{ $order->customer_name }}</td>
                        <td>
                            <span class="price-display">₱{{ number_format($order->total_amount, 2) }}</span>
                        </td>
                        <td>
                            <span class="status-text">
                                <i class="fas fa-circle fa-xs text-{{ $order->order_status == 'delivered' ? 'success' : ($order->order_status == 'cancelled' ? 'danger' : 'warning') }}"></i>
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('orders.show', $order) }}" class="action-icon" title="View order details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($order->canBeCancelled())
                                <button 
                                    type="button" 
                                    class="action-icon cancel-icon cancel-btn"
                                    data-order-id="{{ $order->id }}"
                                    data-order-number="{{ $order->order_number }}"
                                    data-order-date="{{ $order->created_at->format('M d, Y') }}"
                                    data-order-total="₱{{ number_format($order->total_amount, 2) }}"
                                    title="Cancel order">
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

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="pagination-container">
        <div class="d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif

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
                        <p class="confirmation-text">
                            Are you sure you want to cancel this order? This action cannot be undone.
                        </p>
                        
                        <div class="order-highlight">
                            <div class="order-detail">
                                <span class="detail-label">Order Number:</span>
                                <span class="detail-value" id="orderNumberText"></span>
                            </div>
                            <div class="order-detail">
                                <span class="detail-label">Order Date:</span>
                                <span class="detail-value" id="orderDateText"></span>
                            </div>
                            <div class="order-detail">
                                <span class="detail-label">Total Amount:</span>
                                <span class="detail-value" id="orderTotalText"></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">Reason for cancellation</label>
                            <textarea name="cancellation_reason" id="cancellation_reason" class="form-control" rows="3" required 
                                      placeholder="Please provide a reason for cancellation..."></textarea>
                            <div class="form-text">Your reason will help us improve our service.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Confirm Cancellation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @else
    <!-- Empty State -->
    <div class="empty-state">
        <i class="fas fa-receipt"></i>
        <h3>No orders yet</h3>
        <p>You haven't placed any orders. Start shopping to see your orders here.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary-rounded">
            <i class="fas fa-shopping-bag me-2"></i>Start Shopping
        </a>
    </div>
    @endif
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Toast notification function (consistent with other pages)
    function showToast(message, type = 'success') {
        // Remove existing toasts
        document.querySelectorAll('.upper-middle-toast').forEach(toast => toast.remove());

        const bgColors = {
            'success': '#2C8F0C',
            'error': '#dc3545',
            'warning': '#ffc107',
            'info': '#17a2b8'
        };

        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-triangle',
            'warning': 'fa-exclamation-circle',
            'info': 'fa-info-circle'
        };

        const bgColor = bgColors[type] || bgColors.success;
        const icon = icons[type] || icons.success;
        const textColor = type === 'warning' ? 'text-dark' : 'text-white';

        const toast = document.createElement('div');
        toast.className = 'upper-middle-toast position-fixed start-50 translate-middle-x p-3';
        toast.style.cssText = `
            top: 100px;
            z-index: 9999;
            min-width: 300px;
            text-align: center;
        `;

        toast.innerHTML = `
            <div class="toast align-items-center border-0 show shadow-lg" role="alert" style="background-color: ${bgColor}; border-radius: 10px;">
                <div class="d-flex justify-content-center align-items-center p-3">
                    <div class="toast-body ${textColor} d-flex align-items-center">
                        <i class="fas ${icon} me-2 fs-5"></i>
                        <span class="fw-semibold">${message}</span>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                // Add fade out animation
                toast.style.transition = 'all 0.3s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50%) translateY(-20px)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }
        }, 3000);
    }

    // Cancel Order Modal
    const cancelButtons = document.querySelectorAll(".cancel-btn");
    const modal = new bootstrap.Modal(document.getElementById("cancelOrderModal"));
    const form = document.getElementById("cancelOrderForm");
    const orderNumberText = document.getElementById("orderNumberText");
    const orderDateText = document.getElementById("orderDateText");
    const orderTotalText = document.getElementById("orderTotalText");

    cancelButtons.forEach(button => {
        button.addEventListener("click", () => {
            const orderId = button.getAttribute("data-order-id");
            const orderNumber = button.getAttribute("data-order-number");
            const orderDate = button.getAttribute("data-order-date");
            const orderTotal = button.getAttribute("data-order-total");
            
            form.action = `/orders/${orderId}/cancel`;
            orderNumberText.textContent = orderNumber;
            orderDateText.textContent = orderDate;
            orderTotalText.textContent = orderTotal;
            modal.show();
        });
    });

    // Handle form submission success
    if (performance.navigation.type === performance.navigation.TYPE_RELOAD) {
        // Check if we have a success/error message in session
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    }
});
</script>
@endsection