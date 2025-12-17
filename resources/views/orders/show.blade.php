@extends('layouts.app')

@section('content')
<style>
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
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .info-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid var(--medium-gray);
    }
    .info-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--light-green);
    }
    .info-header i {
        background: var(--light-green);
        color: var(--primary-green);
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 1rem;
    }
    .info-header h5 {
        margin: 0;
        color: var(--text-dark);
        font-weight: 600;
    }
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--light-gray);
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 500;
        color: var(--dark-gray);
        flex: 1;
        font-size: 0.9rem;
    }
    .info-value {
        color: var(--text-dark);
        font-weight: 500;
        text-align: right;
        flex: 1;
        font-size: 0.9rem;
    }
    .shipping-card {
        background: linear-gradient(135deg, #f8fdf8 0%, #f0f9f0 100%);
        border: 1px solid var(--light-green);
        border-radius: 12px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .shipping-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    .shipping-header i {
        color: var(--primary-green);
        font-size: 1.25rem;
        margin-right: 0.5rem;
    }
    .shipping-header h6 {
        margin: 0;
        color: var(--text-dark);
        font-weight: 600;
    }
    .address-content {
        color: var(--text-dark);
        line-height: 1.6;
        font-size: 0.95rem;
    }
    .address-content p {
        margin: 0.25rem 0;
    }
    .order-items-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .order-items-header {
        background: white;
        padding: 1.25rem 1.5rem;
        border-bottom: 2px solid var(--light-green);
        display: flex;
        align-items: center;
    }
    .order-items-header i {
        color: var(--primary-green);
        margin-right: 0.5rem;
        font-size: 1.1rem;
    }
    .order-items-header h5 {
        margin: 0;
        color: var(--text-dark);
        font-weight: 600;
    }
    .product-row {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--light-gray);
        transition: background-color 0.2s ease;
    }
    .product-row:hover {
        background-color: var(--light-gray);
    }
    .product-row:last-child {
        border-bottom: none;
    }
    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid var(--light-gray);
        margin-right: 1rem;
        flex-shrink: 0;
    }
    .product-info {
        flex: 1;
    }
    .product-name {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }
    .product-sku {
        color: var(--dark-gray);
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }
    .product-size {
        display: inline-block;
        background: var(--light-green);
        color: var(--primary-green);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .product-details {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-top: 0.5rem;
    }
    .product-price, .product-quantity, .product-total {
        font-size: 0.9rem;
        color: var(--text-dark);
    }
    .product-price {
        font-weight: 600;
        color: var(--primary-green);
    }
    .product-total {
        font-weight: 700;
        color: var(--text-dark);
    }
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .badge-pending { background: #FFF3CD; color: #856404; }
    .badge-confirmed { background: #D1ECF1; color: #0C5460; }
    .badge-processing { background: #E8F5E6; color: var(--primary-green); }
    .badge-shipped { background: #CCE5FF; color: #004085; }
    .badge-delivered { background: #D4EDDA; color: #155724; }
    .badge-cancelled { background: #F8D7DA; color: #721C24; }
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--light-gray);
    }
    .summary-item:last-child {
        border-bottom: none;
    }
    .summary-total {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-green);
    }
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 25px;
    }
    .timeline-item.current .timeline-marker {
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px var(--primary-green);
        transform: scale(1.3);
        animation: pulse 2s infinite;
    }
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 5px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #6c757d;
        transition: all 0.3s ease;
        z-index: 2;
    }
    .timeline-content {
        padding-bottom: 15px;
        border-left: 2px solid #e9ecef;
        padding-left: 25px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }
    .timeline-item:last-child .timeline-content {
        border-left-color: transparent;
    }
    .timeline-item:hover .timeline-marker {
        transform: scale(1.4);
    }
    .timeline-item:hover .timeline-content {
        border-left-color: var(--primary-green);
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 10px 15px;
        margin-left: -5px;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(44, 143, 12, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(44, 143, 12, 0); }
        100% { box-shadow: 0 0 0 0 rgba(44, 143, 12, 0); }
    }
    .timeline-marker.bg-pending { background-color: #ffc107 !important; }
    .timeline-marker.bg-confirmed { background-color: #17a2b8 !important; }
    .timeline-marker.bg-processing { background-color: var(--primary-green) !important; }
    .timeline-marker.bg-shipped { background-color: #007bff !important; }
    .timeline-marker.bg-delivered { background-color: #28a745 !important; }
    .timeline-marker.bg-cancelled { background-color: #dc3545 !important; }
    .btn-primary-rounded {
        background: linear-gradient(135deg, var(--primary-green), var(--accent-green));
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
        background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
        text-decoration: none;
    }
    .btn-outline-danger {
        border: 2px solid #dc3545;
        color: #dc3545;
        background: transparent;
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
    .btn-outline-danger:hover {
        background: #dc3545;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        text-decoration: none;
    }
    .btn-outline-secondary {
        border: 2px solid var(--dark-gray);
        color: var(--dark-gray);
        background: transparent;
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
    .btn-outline-secondary:hover {
        background: var(--dark-gray);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
        text-decoration: none;
    }
</style>

<div class="container py-4">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1" style="color: var(--primary-green); font-weight: 700;">Order Details</h1>
                <p class="mb-0 text-muted">Order #{{ $order->order_number }} • {{ $order->created_at->format('M d, Y') }}</p>
            </div>
            <div>
                @php
                    $statusClass = [
                        'pending' => '1badge-pending',
                        'confirmed' => '1badge-confirmed',
                        'processing' => '1badge-processing',
                        'shipped' => '1badge-shipped',
                        'delivered' => '1badge-delivered',
                        'cancelled' => '1badge-cancelled'
                    ][$order->order_status] ?? '1badge-pending';
                @endphp
                <span class="status-badge {{ $statusClass }}">
                    <i class="fas fa-{{ $order->order_status == 'pending' ? 'clock' : ($order->order_status == 'confirmed' ? 'check' : ($order->order_status == 'processing' ? 'cog' : ($order->order_status == 'shipped' ? 'shipping-fast' : ($order->order_status == 'delivered' ? 'check-circle' : 'times')))) }}"></i>
                    {{ ucfirst($order->order_status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-section">
            <div class="info-header">
                <i class="fas fa-user"></i>
                <h5>Customer Information</h5>
            </div>
            <div class="info-content">
                <div class="info-item">
                    <span class="info-label">Name</span>
                    <span class="info-value">{{ $order->customer_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $order->customer_email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $order->customer_phone ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Payment Method</span>
                    <span class="info-value text-capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                </div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-header">
                <i class="fas fa-receipt"></i>
                <h5>Order Summary</h5>
            </div>
            <div class="info-content">
                <div class="info-item">
                    <span class="info-label">Order Number</span>
                    <span class="info-value">{{ $order->order_number }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Order Date</span>
                    <span class="info-value">{{ $order->created_at->format('M d, Y g:i A') }}</span>
                </div>
                @if($order->is_delivered && $order->delivered_at)
                <div class="info-item">
                    <span class="info-label">Delivered</span>
                    <span class="info-value">{{ $order->delivered_at->format('M d, Y') }}</span>
                </div>
                @endif
                <div class="summary-item">
                    <span class="info-label">Total Amount</span>
                    <span class="info-value summary-total">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="shipping-card">
            <div class="shipping-header">
                <i class="fas fa-map-marker-alt"></i>
                <h6>Shipping Address</h6>
            </div>
            <div class="address-content">
                {!! nl2br(e($order->shipping_address)) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="order-items-card">
                <div class="order-items-header">
                    <i class="fas fa-shopping-basket"></i>
                    <h5>Order Items ({{ $order->items->count() }})</h5>
                </div>
                <div class="order-items-body">
                    @foreach($order->items as $item)
                    <div class="product-row">
                        @php
                            $itemImage = $item->product->image_url;
                            $displayUnitPrice = $item->unit_price;
                            $displayTotalPrice = $item->total_price;
                            if ($item->selected_size && $item->selected_size !== 'Standard') {
                                $variant = $item->product->variants->first(function($v) use ($item) {
                                    return ($v->size === $item->selected_size) || ($v->variant_name === $item->selected_size);
                                });
                                if ($variant) {
                                    if ($variant->image_url) {
                                        $itemImage = $variant->image_url;
                                    }
                                    $displayUnitPrice = $variant->has_discount ? $variant->sale_price : $variant->current_price;
                                    $displayTotalPrice = $displayUnitPrice * $item->quantity;
                                }
                            }
                        @endphp
                        <img src="{{ $itemImage }}" alt="{{ $item->product_name }}" class="product-image">
                        <div class="product-info">
                            <div class="product-name">{{ $item->product_name }}</div>
                            <div class="product-sku">SKU: {{ $item->product->sku }}</div>
                            @if($item->selected_size)
                            <span class="product-size">{{ $item->selected_size }}</span>
                            @endif
                            <div class="product-details">
                                <span class="product-price">₱{{ number_format($displayUnitPrice, 2) }}</span>
                                <span class="product-quantity">Qty: {{ $item->quantity }}</span>
                                <span class="product-total">₱{{ number_format($displayTotalPrice, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="info-section">
                <div class="info-header">
                    <i class="fas fa-history"></i>
                    <h5>Status History</h5>
                </div>
                <div class="info-content">
                    @if($order->statusHistory->count() > 0)
                    <div class="timeline">
                        @foreach($order->statusHistory as $history)
                        @php
                            $isCurrentStatus = $history->status === $order->order_status;
                        @endphp
                        <div class="timeline-item {{ $isCurrentStatus ? 'current' : '' }}">
                            <div class="timeline-marker {{ $history->status === 'cancelled' ? 'bg-danger' : ($history->status === 'delivered' ? 'bg-success' : ($history->status === 'shipped' ? 'bg-primary' : ($history->status === 'confirmed' ? 'bg-info' : ($history->status === 'processing' ? 'bg-success' : 'bg-warning')))) }}">
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1 text-{{ $history->status === 'cancelled' ? 'danger' : ($history->status === 'delivered' ? 'success' : ($history->status === 'shipped' ? 'primary' : ($history->status === 'confirmed' ? 'info' : ($history->status === 'processing' ? 'success' : 'warning')))) }}">
                                    {{ ucfirst($history->status) }}
                                    @if($isCurrentStatus)
                                    <small class="text-muted">(Current)</small>
                                    @endif
                                </h6>
                                <p class="text-muted mb-1 small">{{ $history->created_at->format('M j, Y g:i A') }}</p>
                                @if($history->notes && $history->notes !== 'Order created')
                                <p class="mb-0 small text-muted">
                                    <i class="fas fa-info-circle me-1"></i>{{ $history->notes }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-history fa-2x mb-2"></i>
                        <p>No status history available</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="info-section">
                <div class="info-header">
                    <i class="fas fa-calculator"></i>
                    <h5>Payment Breakdown</h5>
                </div>
                <div class="info-content">
                    <div class="summary-item">
                        <span class="info-label">Subtotal</span>
                        <span class="info-value">₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    {{-- <div class="summary-item">
                        <span class="info-label">Tax</span>
                        <span class="info-value">₱{{ number_format($order->tax_amount, 2) }}</span>
                    </div> --}}
                    <div class="summary-item">
                        <span class="info-label">Shipping</span>
                        <span class="info-value">₱{{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <div class="summary-item summary-total">
                        <span class="info-label">Total</span>
                        <span class="info-value">₱{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="info-section">
                <div class="info-header">
                    <i class="fas fa-cogs"></i>
                    <h5>Actions</h5>
                </div>
                <div class="info-content">
                    @if($order->canBeCancelled())
                    <button type="button" class="btn btn-outline-danger w-100 mb-3" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                        <i class="fas fa-times-circle me-2"></i>Cancel Order
                    </button>
                    @endif
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if($order->canBeCancelled())
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background: #2C8F0C;">
                <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Order</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.cancel', $order) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to cancel this order? This action cannot be undone.</p>
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Reason for cancellation:</label>
                        <textarea name="cancellation_reason" id="cancellation_reason" class="form-control" rows="3" placeholder="Please provide a reason for cancellation" required></textarea>
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
@endif
@endsection