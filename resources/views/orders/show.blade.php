@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">My Orders</a></li>
            <li class="breadcrumb-item active">Order #{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4 shadow-sm">
                <div class="card-header text-white" style="background: #2C8F0C;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Order Items</h5>
                        <span class="badge bg-{{ $order->order_status === 'delivered' ? 'success' : ($order->order_status === 'cancelled' ? 'danger' : ($order->order_status === 'shipped' ? 'primary' : ($order->order_status === 'confirmed' ? 'info' : 'warning'))) }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                    <div class="row align-items-center mb-3 pb-3 border-bottom">
                        <div class="col-md-2">
                            @php
                                $itemImage = $item->product->image_url;
                                if ($item->selected_size && $item->selected_size !== 'Standard') {
                                    $variant = $item->product->variants->first(function($v) use ($item) {
                                        return ($v->size === $item->selected_size) || ($v->variant_name === $item->selected_size);
                                    });
                                    if ($variant && $variant->image) {
                                        $itemImage = $variant->image_url;
                                    }
                                }
                            @endphp
                            <img src="{{ $itemImage }}" 
                                alt="{{ $item->product_name }}" 
                                class="img-fluid rounded" style="height: 80px; object-fit: cover;">
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1">{{ $item->product_name }}</h6>
                            <p class="text-muted mb-0">₱{{ number_format($item->unit_price, 2) }}</p>
                            @if($item->selected_size && $item->selected_size !== 'Standard')
                            <p class="text-muted mb-0">
                                <strong>Variant:</strong> {{ $item->selected_size }}
                            </p>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <p class="mb-0">Quantity: {{ $item->quantity }}</p>
                        </div>
                        <div class="col-md-3 text-end">
                            <strong>₱{{ number_format($item->total_price, 2) }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Status History Timeline -->
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background: #2C8F0C;">
                    <h5 class="mb-0">Order Status Timeline</h5>
                </div>
                <div class="card-body">
                    @if($order->statusHistory->count() > 0)
                    <div class="timeline">
                        @foreach($order->statusHistory as $history)
                        <div class="timeline-item {{ $loop->first ? 'current' : '' }}">
                            <div class="timeline-marker 
                                {{ $history->status === 'cancelled' ? 'bg-danger' : 
                                   ($history->status === 'delivered' ? 'bg-success' : 
                                   ($history->status === 'shipped' ? 'bg-primary' : 
                                   ($history->status === 'confirmed' ? 'bg-info' : 'bg-warning'))) }}">
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1 text-{{ $history->status === 'cancelled' ? 'danger' : 
                                   ($history->status === 'delivered' ? 'success' : 
                                   ($history->status === 'shipped' ? 'primary' : 
                                   ($history->status === 'confirmed' ? 'info' : 'warning'))) }}">
                                    {{ ucfirst($history->status) }}
                                    @if($loop->first)
                                    <small class="text-muted">(Current)</small>
                                    @endif
                                </h6>
                                <p class="text-muted mb-1 small">{{ $history->created_at->format('M j, Y g:i A') }}</p>
                                @if($history->notes && $history->notes !== 'Order created')
                                <p class="mb-0 small text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    {{ $history->notes }}
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
            <div class="card mb-4 shadow-sm">
                <div class="card-header text-white" style="background: #2C8F0C;">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span>₱{{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <span>₱{{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong>₱{{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header text-white" style="background: #2C8F0C;">
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Order Number:</strong><br>
                        {{ $order->order_number }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Order Date:</strong><br>
                        {{ $order->created_at->format('M j, Y g:i A') }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Payment Method:</strong><br>
                        {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Shipping Address:</strong><br>
                        {!! nl2br(e($order->shipping_address)) !!}
                    </div>

                    @if($order->is_delivered)
                    <div class="mb-3">
                        <strong>Delivered Date:</strong><br>
                        {{ $order->delivered_at->format('M j, Y g:i A') }}
                    </div>
                    @endif
                    
                    @if($order->is_cancelled)
                    <div class="mb-3">
                        <strong>Cancellation Reason:</strong><br>
                        {{ $order->cancellation_reason_display }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Cancelled At:</strong><br>
                        {{ $order->cancelled_at->format('M j, Y g:i A') }}
                    </div>
                    @endif

                    <div class="mt-4">
                        @if($order->canBeCancelled())
                        <button type="button" class="btn btn-outline-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
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
                        <textarea name="cancellation_reason" id="cancellation_reason" 
                                  class="form-control" rows="3" 
                                  placeholder="Please provide a reason for cancellation" required></textarea>
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

<style>
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
    box-shadow: 0 0 0 3px #2C8F0C;
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
    border-left-color: #2C8F0C;
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
</style>
@endsection
