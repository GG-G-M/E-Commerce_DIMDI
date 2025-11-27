@extends('layouts.app')

@section('content')
<style>
    :root {
        --green-primary: #2C8F0C;
        --green-light: #E6F4E1;
        --green-gradient: linear-gradient(135deg, #2C8F0C 0%, #3DB814 100%);
    }

    h1 {
        font-weight: 700;
        color: var(--green-primary);
    }

    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.15);
        transition: all 0.25s ease;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 22px rgba(44, 143, 12, 0.25);
    }

    .table thead th {
        background: var(--green-gradient);
        color: #fff;
        border: none;
    }

    .table tbody tr:hover {
        background-color: rgba(44, 143, 12, 0.05);
        transition: background 0.2s ease;
    }

    .btn {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.25s ease;
        box-shadow: 0 2px 5px rgba(44, 143, 12, 0.2);
    }

    .btn:active {
        transform: scale(0.97);
    }

    .btn-primary,
    .btn-success {
        background: var(--green-gradient);
        border: none;
        color: #fff;
    }

    .btn-primary:hover,
    .btn-success:hover {
        box-shadow: 0 4px 10px rgba(44, 143, 12, 0.35);
        transform: translateY(-2px);
        background: linear-gradient(135deg, #3DB814 0%, #2C8F0C 100%);
    }

    .btn-outline-primary {
        color: var(--green-primary);
        border: 2px solid var(--green-primary);
        background-color: transparent;
    }

    .btn-outline-primary:hover {
        background: var(--green-gradient);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(44, 143, 12, 0.3);
    }

    .btn-outline-danger {
        color: #dc3545;
        border: 2px solid #dc3545;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.35);
    }

    .badge-success {
        background-color: var(--green-primary) !important;
    }

    .pagination .page-item .page-link {
        color: var(--green-primary);
        border-radius: 50px;
        margin: 0 4px;
        border: 1px solid var(--green-primary);
        transition: all 0.25s ease;
        font-weight: 500;
    }

    .pagination .page-item.active .page-link {
        background: var(--green-gradient);
        border-color: var(--green-primary);
        color: #fff;
        box-shadow: 0 3px 8px rgba(44, 143, 12, 0.3);
    }

    .pagination .page-link:hover {
        background: var(--green-gradient);
        color: #fff;
        transform: scale(1.05);
    }

    .modal-content {
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(44, 143, 12, 0.25);
    }

    .modal-header {
        background: var(--green-gradient);
        color: #fff;
        border-bottom: none;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .empty-state i {
        color: var(--green-primary);
        opacity: 0.3;
    }

    /* Success/Error Alert Styles */
    .alert {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
</style>

<div class="container py-4">
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

    <div class="text-center mb-5">
        <h1 class="mb-2">My Orders</h1>
        <p class="text-muted">Track your purchases and manage your orders efficiently.</p>
    </div>

    @if($orders->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
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
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>₱{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->order_status === 'delivered' ? 'success' : 
                                    ($order->order_status === 'cancelled' ? 'danger' : 
                                    ($order->order_status === 'shipped' ? 'primary' : 'warning')) 
                                }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-eye me-1"></i> View
                                </a>

                                @if($order->canBeCancelled())
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-outline-danger cancel-btn"
                                    data-order-id="{{ $order->id }}"
                                    data-order-number="{{ $order->order_number }}">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </button>
                                @endif
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
        <a href="{{ route('products.index') }}" class="btn btn-lg btn-primary px-4">
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
    });
</script>
@endsection