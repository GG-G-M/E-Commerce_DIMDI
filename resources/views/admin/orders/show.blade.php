@extends('layouts.admin')

@section('content')
<style>
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #2C8F0C;
    }
    
    .order-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }
    
    .order-card .card-header {
        background: white;
        border-bottom: 2px solid #E8F5E6;
        font-weight: 600;
        color: #2C8F0C;
        padding: 1rem 1.5rem;
    }
    
    .order-card .card-body {
        padding: 1.5rem;
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 1rem 0.75rem;
    }
    
    .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-color: #E8F5E6;
    }
    
    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #E8F5E6;
        transition: all 0.3s ease;
    }
    
    .product-image:hover {
        border-color: #2C8F0C;
        transform: scale(1.05);
    }
    
    .badge-status {
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
    }
    
    .badge-pending {
        background-color: #FFF3CD !important;
        color: #856404 !important;
    }
    
    .badge-confirmed {
        background-color: #D1ECF1 !important;
        color: #0C5460 !important;
    }
    
    .badge-processing {
        background-color: #E8F5E6 !important;
        color: #2C8F0C !important;
    }
    
    .badge-shipped {
        background-color: #CCE5FF !important;
        color: #004085 !important;
    }
    
    .badge-delivered {
        background-color: #D4EDDA !important;
        color: #155724 !important;
    }
    
    .badge-cancelled {
        background-color: #F8D7DA !important;
        color: #721C24 !important;
    }
    
    .alert-danger {
        background-color: #F8D7DA;
        border-color: #F5C6CB;
        color: #721C24;
        border-radius: 8px;
    }
    
    .form-select {
        border: 2px solid #E8F5E6;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-select:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
    }
    
    .info-item {
        display: flex;
        justify-content: between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #E8F5E6;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #2C8F0C;
        flex: 1;
    }
    
    .info-value {
        color: #495057;
        font-weight: 500;
        text-align: right;
    }
    
    .total-amount {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2C8F0C;
    }
    
    .shipping-address {
        background: #F8FDF8;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #2C8F0C;
    }
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">Order Details</h1>
            <p class="mb-0 text-muted">Order #{{ $order->order_number }} - {{ $order->created_at->format('M d, Y') }}</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card order-card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-shopping-basket me-2"></i>
                <h5 class="mb-0">Order Items</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" 
                                             class="product-image me-3">
                                        <div>
                                            <h6 class="mb-0">{{ $item->product_name }}</h6>
                                            <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">${{ number_format($item->unit_price, 2) }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $item->quantity }}</span>
                                </td>
                                <td class="fw-bold">${{ number_format($item->total_price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Order Summary -->
        <div class="card order-card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-receipt me-2"></i>
                <h5 class="mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="info-item">
                    <span class="info-label">Order Number:</span>
                    <span class="info-value">{{ $order->order_number }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Order Date:</span>
                    <span class="info-value">{{ $order->created_at->format('M d, Y g:i A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        @php
                            $statusClass = [
                                'pending' => 'badge-pending',
                                'confirmed' => 'badge-confirmed',
                                'processing' => 'badge-processing',
                                'shipped' => 'badge-shipped',
                                'delivered' => 'badge-delivered',
                                'cancelled' => 'badge-cancelled'
                            ][$order->order_status] ?? 'badge-pending';
                        @endphp
                        <span class="badge badge-status {{ $statusClass }}">
                            <i class="fas fa-{{ $order->order_status == 'pending' ? 'clock' : ($order->order_status == 'confirmed' ? 'check' : ($order->order_status == 'processing' ? 'cog' : ($order->order_status == 'shipped' ? 'shipping-fast' : ($order->order_status == 'delivered' ? 'check-circle' : 'times')))) }} me-1"></i>
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </span>
                </div>
                
                @if($order->order_status == 'cancelled')
                <div class="alert alert-danger mt-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-ban me-2"></i>
                        <h6 class="mb-0">Order Cancelled</h6>
                    </div>
                    @if($order->cancellation_reason)
                        <p class="mb-1 mt-2"><strong>Reason:</strong> {{ $order->cancellation_reason }}</p>
                    @endif
                    @if($order->cancelled_at)
                        <small class="text-muted">Cancelled on: {{ $order->cancelled_at->format('M d, Y g:i A') }}</small>
                    @endif
                </div>
                @endif
                
                <hr>
                
                <div class="info-item">
                    <span class="info-label">Subtotal:</span>
                    <span class="info-value">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tax:</span>
                    <span class="info-value">${{ number_format($order->tax_amount, 2) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Shipping:</span>
                    <span class="info-value">${{ number_format($order->shipping_cost, 2) }}</span>
                </div>
                
                <hr>
                
                <div class="info-item">
                    <span class="info-label total-amount">Total:</span>
                    <span class="info-value total-amount">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Update Order Status -->
        <div class="card order-card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-sync-alt me-2"></i>
                <h5 class="mb-0">Update Order Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <select class="form-select" name="order_status" required>
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card order-card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-user me-2"></i>
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <div class="info-item">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $order->customer_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $order->customer_email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $order->customer_phone ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Payment Method:</span>
                    <span class="info-value text-capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                </div>
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="card order-card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-truck me-2"></i>
                <h5 class="mb-0">Shipping Address</h5>
            </div>
            <div class="card-body">
                <div class="shipping-address">
                    <p class="mb-0">{{ nl2br($order->shipping_address) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection