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

    /* Enhanced Modal Styles */
    .delivery-confirmation-modal .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(44, 143, 12, 0.3);
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .delivery-confirmation-modal.show .modal-content {
        transform: translateY(0);
        opacity: 1;
    }
    
    .delivery-confirmation-modal .modal-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-bottom: none;
        padding: 2rem 2rem 1.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .delivery-confirmation-modal .modal-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.3;
        animation: pulse 2s infinite alternate;
    }
    
    @keyframes pulse {
        0% { opacity: 0.2; }
        100% { opacity: 0.4; }
    }
    
    .delivery-confirmation-modal .modal-title {
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .delivery-confirmation-modal .modal-title i {
        font-size: 1.8rem;
        background: rgba(255,255,255,0.2);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .delivery-confirmation-modal .btn-close {
        position: relative;
        z-index: 2;
        filter: brightness(0) invert(1);
        opacity: 0.8;
        transition: all 0.3s;
    }
    
    .delivery-confirmation-modal .btn-close:hover {
        opacity: 1;
        transform: rotate(90deg);
    }
    
    .delivery-confirmation-modal .modal-body {
        padding: 2.5rem 2rem;
        background: linear-gradient(135deg, #f8fff8, #f0f9f0);
    }
    
    .confirmation-content {
        text-align: center;
        position: relative;
    }
    
    .confirmation-icon {
        font-size: 4rem;
        color: #2C8F0C;
        margin-bottom: 1.5rem;
        animation: bounce 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }
    
    .confirmation-title {
        color: #2C8F0C;
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 1rem;
    }
    
    .confirmation-message {
        color: #555;
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }
    
    .order-details-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        border-left: 4px solid #2C8F0C;
        box-shadow: 0 4px 15px rgba(44, 143, 12, 0.1);
        text-align: left;
    }
    
    .order-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .order-detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        color: #666;
        font-weight: 500;
    }
    
    .detail-value {
        color: #2C8F0C;
        font-weight: 600;
    }
    
    .confirmation-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }
    
    .btn-confirm-delivery {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 15px rgba(44, 143, 12, 0.3);
    }
    
    .btn-confirm-delivery:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(44, 143, 12, 0.4);
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    }
    
    .btn-confirm-delivery:active {
        transform: translateY(-1px);
    }
    
    .btn-cancel-delivery {
        background: transparent;
        border: 2px solid #ddd;
        color: #666;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
    }
    
    .btn-cancel-delivery:hover {
        background: #f8f9fa;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .success-animation {
        display: none;
    }
    
    .success-animation.show {
        display: block;
        animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    @keyframes scaleIn {
        0% { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    /* Toast Notification */
    .delivery-toast {
        position: fixed;
        top: 100px;
        right: 30px;
        z-index: 9999;
        min-width: 350px;
        transform: translateX(150%);
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .delivery-toast.show {
        transform: translateX(0);
    }
    
    .toast-success {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(44, 143, 12, 0.3);
        color: white;
        overflow: hidden;
        position: relative;
    }
    
    .toast-success::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: rgba(255,255,255,0.3);
    }
    
    .toast-body {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 15px;
        font-weight: 500;
    }
    
    .toast-body i {
        font-size: 1.5rem;
    }
    
    /* Loading animation for confirm button */
    .loading-spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .btn-confirm-delivery.loading .loading-spinner {
        display: inline-block;
    }
    
    .btn-confirm-delivery.loading span {
        display: none;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .delivery-confirmation-modal .modal-dialog {
            margin: 10px;
        }
        
        .delivery-confirmation-modal .modal-body {
            padding: 1.5rem 1rem;
        }
        
        .confirmation-actions {
            flex-direction: column;
        }
        
        .btn-confirm-delivery,
        .btn-cancel-delivery {
            width: 100%;
            justify-content: center;
        }
        
        .delivery-toast {
            min-width: calc(100% - 60px);
            right: 30px;
            left: 30px;
        }
    }
</style>

<!-- Delivery Confirmation Modal -->
<div class="modal fade delivery-confirmation-modal" id="deliveryConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle"></i>
                    Confirm Order Delivery
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Initial Confirmation View -->
                <div class="confirmation-content" id="confirmationView">
                    <div class="confirmation-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    
                    <h3 class="confirmation-title">Confirm Delivery Completion</h3>
                    
                    <p class="confirmation-message">
                        Are you sure you want to mark this order as delivered? 
                        Please verify that you have successfully delivered the order to the customer.
                    </p>
                    
                    <!-- Order Details Card -->
                    <div class="order-details-card">
                        <div class="order-detail-row">
                            <span class="detail-label">Order Number:</span>
                            <span class="detail-value" id="modalOrderNumber">#ORD-00000</span>
                        </div>
                        <div class="order-detail-row">
                            <span class="detail-label">Customer:</span>
                            <span class="detail-value" id="modalCustomerName">Loading...</span>
                        </div>
                        <div class="order-detail-row">
                            <span class="detail-label">Address:</span>
                            <span class="detail-value" id="modalDeliveryAddress">Loading...</span>
                        </div>
                        <div class="order-detail-row">
                            <span class="detail-label">Amount:</span>
                            <span class="detail-value" id="modalOrderAmount">₱0.00</span>
                        </div>
                        <div class="order-detail-row">
                            <span class="detail-label">Items:</span>
                            <span class="detail-value" id="modalItemCount">0 items</span>
                        </div>
                    </div>
                    
                    <!-- Verification Checkbox -->
                    <div class="form-check text-start mb-4">
                        <input class="form-check-input" type="checkbox" id="deliveryVerified">
                        <label class="form-check-label" for="deliveryVerified">
                            I confirm that I have successfully delivered this order to the customer
                        </label>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="confirmation-actions">
                        <button type="button" class="btn-cancel-delivery" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i>
                            <span>Cancel</span>
                        </button>
                        <button type="button" class="btn-confirm-delivery" id="confirmDeliveryBtn" disabled>
                            <div class="loading-spinner"></div>
                            <i class="fas fa-check"></i>
                            <span>Mark as Delivered</span>
                        </button>
                    </div>
                </div>
                
                <!-- Success View (shown after confirmation) -->
                <div class="confirmation-content success-animation" id="successView">
                    <div class="confirmation-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    
                    <h3 class="confirmation-title text-success">Delivery Confirmed!</h3>
                    
                    <p class="confirmation-message">
                        Order <strong id="successOrderNumber">#ORD-00000</strong> has been successfully marked as delivered.
                        The customer has been notified about the delivery completion.
                    </p>
                    
                    <div class="success-details mt-4">
                        <div class="alert alert-success">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Delivery Time:</strong> <span id="deliveryTime">{{ now()->format('h:i A') }}</span>
                        </div>
                    </div>
                    
                    <div class="confirmation-actions mt-4">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                            <i class="fas fa-check me-2"></i>
                            Continue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast Notification -->
<div class="delivery-toast" id="deliverySuccessToast">
    <div class="toast toast-success" role="alert">
        <div class="toast-body">
            <i class="fas fa-check-circle"></i>
            <span id="toastMessage">Order delivered successfully!</span>
        </div>
    </div>
</div>

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
            <a href="{{ route('delivery.orders.index') }}" class="btn btn-sm btn-outline-success">
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
                        <tr data-order-id="{{ $order->id }}"
                            data-order-number="{{ $order->order_number }}"
                            data-customer-name="{{ $order->customer_name }}"
                            data-customer-phone="{{ $order->customer_phone }}"
                            data-customer-email="{{ $order->customer_email }}"
                            data-delivery-address="{{ $order->shipping_address }}"
                            data-order-amount="₱{{ number_format($order->total_amount, 2) }}"
                            data-item-count="{{ $order->orderItems->count() }} items">
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
                                    <a href="{{ route('delivery.orders.show', $order) }}" 
                                       class="btn btn-outline-success" 
                                       title="View Order Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if(in_array($order->order_status, ['shipped', 'out_for_delivery']))
                                    <button type="button" 
                                            class="btn btn-success deliver-order-btn"
                                            data-order-id="{{ $order->id }}"
                                            title="Mark as Delivered">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    @elseif($order->order_status == 'delivered')
                                    <span class="btn btn-outline-success disabled" title="Already Delivered">
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
                    <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-success">
                        <i class="fas fa-box me-1"></i> Pick Up Available Orders
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deliveryModal = new bootstrap.Modal(document.getElementById('deliveryConfirmationModal'));
    const deliverySuccessToast = document.getElementById('deliverySuccessToast');
    const confirmBtn = document.getElementById('confirmDeliveryBtn');
    const deliveryVerified = document.getElementById('deliveryVerified');
    const confirmationView = document.getElementById('confirmationView');
    const successView = document.getElementById('successView');
    let currentOrderId = null;
    
    // Store form data
    let formData = new FormData();
    
    // Click event for deliver order buttons
    document.querySelectorAll('.deliver-order-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            currentOrderId = this.getAttribute('data-order-id');
            
            // Update modal with order details
            document.getElementById('modalOrderNumber').textContent = '#' + row.getAttribute('data-order-number');
            document.getElementById('modalCustomerName').textContent = row.getAttribute('data-customer-name');
            document.getElementById('modalDeliveryAddress').textContent = row.getAttribute('data-delivery-address');
            document.getElementById('modalOrderAmount').textContent = row.getAttribute('data-order-amount');
            document.getElementById('modalItemCount').textContent = row.getAttribute('data-item-count');
            
            // Update success view
            document.getElementById('successOrderNumber').textContent = '#' + row.getAttribute('data-order-number');
            document.getElementById('deliveryTime').textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            // Reset modal state
            deliveryVerified.checked = false;
            confirmBtn.disabled = true;
            confirmBtn.classList.remove('loading');
            confirmationView.style.display = 'block';
            successView.classList.remove('show');
            
            // Show modal
            deliveryModal.show();
        });
    });
    
    // Enable/disable confirm button based on checkbox
    deliveryVerified.addEventListener('change', function() {
        confirmBtn.disabled = !this.checked;
    });
    
    // Confirm delivery button click
    confirmBtn.addEventListener('click', function() {
        if (!deliveryVerified.checked) return;
        
        // Show loading state
        this.classList.add('loading');
        
        // Prepare form data
        formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        
        // Send AJAX request
        fetch(`/delivery/orders/${currentOrderId}/deliver`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success view
                confirmationView.style.display = 'none';
                successView.classList.add('show');
                
                // Update toast message
                document.getElementById('toastMessage').textContent = data.message;
                
                // Hide success view after 2 seconds and close modal
                setTimeout(() => {
                    successView.classList.remove('show');
                    confirmationView.style.display = 'block';
                    deliveryModal.hide();
                    
                    // Show success toast
                    deliverySuccessToast.classList.add('show');
                    setTimeout(() => {
                        deliverySuccessToast.classList.remove('show');
                    }, 3000);
                    
                    // Reload page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }, 2000);
            } else {
                // Show error
                showToast(data.message || 'Error marking order as delivered', 'error');
                confirmBtn.classList.remove('loading');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred. Please try again.', 'error');
            confirmBtn.classList.remove('loading');
        });
    });
    
    // Reset modal when hidden
    document.getElementById('deliveryConfirmationModal').addEventListener('hidden.bs.modal', function() {
        deliveryVerified.checked = false;
        confirmBtn.disabled = true;
        confirmBtn.classList.remove('loading');
        confirmationView.style.display = 'block';
        successView.classList.remove('show');
        currentOrderId = null;
    });
    
    // Toast notification function
    function showToast(message, type = 'success') {
        const bgColors = {
            'success': 'linear-gradient(135deg, #2C8F0C, #4CAF50)',
            'error': 'linear-gradient(135deg, #dc3545, #c82333)',
            'warning': 'linear-gradient(135deg, #ffc107, #e0a800)',
            'info': 'linear-gradient(135deg, #17a2b8, #138496)'
        };
        
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-triangle',
            'warning': 'fa-exclamation-circle',
            'info': 'fa-info-circle'
        };
        
        // Create or update toast
        let toast = document.getElementById('customToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'customToast';
            toast.className = 'delivery-toast';
            toast.innerHTML = `
                <div class="toast" role="alert">
                    <div class="toast-body">
                        <i class="fas ${icons[type]}"></i>
                        <span id="customToastMessage">${message}</span>
                    </div>
                </div>
            `;
            document.body.appendChild(toast);
        } else {
            toast.querySelector('.toast-body i').className = `fas ${icons[type]}`;
            toast.querySelector('#customToastMessage').textContent = message;
        }
        
        // Set background
        toast.querySelector('.toast').style.background = bgColors[type] || bgColors.success;
        
        // Show toast
        toast.classList.add('show');
        
        // Auto hide after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentNode && toast.id === 'customToast') {
                    document.body.removeChild(toast);
                }
            }, 500);
        }, 3000);
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && deliveryModal._isShown) {
            deliveryModal.hide();
        }
        
        if (e.key === 'Enter' && deliveryModal._isShown && deliveryVerified.checked && !confirmBtn.classList.contains('loading')) {
            confirmBtn.click();
        }
    });
});
</script>
@endsection