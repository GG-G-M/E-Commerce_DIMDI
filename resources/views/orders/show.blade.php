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
                        <span class="-{{ $order->order_status === 'delivered' ? 'success' : ($order->order_status === 'cancelled' ? 'danger' : ($order->order_status === 'shipped' ? 'primary' : ($order->order_status === 'confirmed' ? 'info' : 'warning'))) }} ">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                        @php
                            // Get product and variant information
                            $product = $item->product;
                            $hasVariants = $product && $product->has_variants && $product->variants->count() > 0;
                            
                            // Get item image
                            $itemImage = $product ? $product->image_url : 'https://picsum.photos/400/300?random=' . uniqid();
                            
                            if ($hasVariants && $item->selected_size && $item->selected_size !== 'Standard') {
                                // Get the variant for this order item
                                $variant = $product->variants->first(function ($v) use ($item) {
                                    return ($v->size === $item->selected_size) || 
                                           ($v->variant_name === $item->selected_size);
                                });
                                
                                if ($variant) {
                                    if ($variant->image) {
                                        $itemImage = $variant->image_url;
                                    }
                                    $unitPrice = $variant->current_price;
                                    $originalUnitPrice = $variant->price;
                                    $hasDiscount = $variant->has_discount;
                                    $discountPercent = $variant->discount_percentage;
                                    $variantName = $variant->size ?? $variant->variant_name ?? $item->selected_size;
                                } else {
                                    // Fallback if variant not found
                                    $unitPrice = $product->current_price;
                                    $originalUnitPrice = $product->price;
                                    $hasDiscount = $product->has_discount;
                                    $discountPercent = $product->discount_percentage;
                                    $variantName = $item->selected_size;
                                }
                            } else {
                                // Product without variants
                                $variant = null;
                                $unitPrice = $product ? $product->current_price : $item->unit_price;
                                $originalUnitPrice = $product ? $product->price : $item->unit_price;
                                $hasDiscount = $product ? $product->has_discount : false;
                                $discountPercent = $product ? $product->discount_percentage : 0;
                                $variantName = 'Standard';
                            }
                            
                            // Use stored unit_price from order item if product is not available
                            if (!$product) {
                                $unitPrice = $item->unit_price;
                                $originalUnitPrice = $item->unit_price;
                                $hasDiscount = false;
                                $discountPercent = 0;
                            }
                            
                            $itemTotalPrice = $unitPrice * $item->quantity;
                            $itemOriginalTotalPrice = $originalUnitPrice * $item->quantity;
                            $itemSavings = $itemOriginalTotalPrice - $itemTotalPrice;
                        @endphp
                        
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-start" style="flex: 1;">
                                <div class="me-3">
                                    <img src="{{ $itemImage }}" 
                                        alt="{{ $item->product_name }}" 
                                        class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">{{ $item->product_name }}</h6>
                                    
                                    @if ($hasVariants && $variantName !== 'Standard')
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-tag me-1"></i>Variant: {{ $variantName }}
                                        </small>
                                    @endif
                                    
                                    <div class="mb-1">
                                        <small class="text-muted">Qty: {{ $item->quantity }} × </small>
                                        @if ($hasDiscount && $originalUnitPrice > $unitPrice)
                                            <span class="sale-price">₱{{ number_format($unitPrice, 2) }}</span>
                                            <small class="original-price ms-1">₱{{ number_format($originalUnitPrice, 2) }}</small>
                                            @if ($discountPercent > 0)
                                                <span class="badge bg-danger ms-1">-{{ $discountPercent }}%</span>
                                            @endif
                                        @else
                                            <span class="text-success fw-semibold">₱{{ number_format($unitPrice, 2) }}</span>
                                        @endif
                                    </div>
                                    
                                    @if ($hasDiscount && $itemSavings > 0)
                                        <small class="savings-text">
                                            <i></i>You save ₱{{ number_format($itemSavings, 2) }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end ms-3">
                                @if ($hasDiscount && $itemOriginalTotalPrice > $itemTotalPrice)
                                    <div>
                                        <span class="text-success fw-bold">₱{{ number_format($itemTotalPrice, 2) }}</span>
                                        <div>
                                            <small class="original-price">₱{{ number_format($itemOriginalTotalPrice, 2) }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-success fw-bold">₱{{ number_format($item->total_price, 2) }}</span>
                                @endif
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
                        @php
                            $isCurrentStatus = $history->status === $order->order_status;
                        @endphp
                        <div class="timeline-item {{ $isCurrentStatus ? 'current' : '' }}">
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
                                    @if($isCurrentStatus)
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
                    {{-- <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span>₱{{ number_format($order->tax_amount, 2) }}</span>
                    </div> --}}
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

/* Discount styling for order items */
.sale-price {
    color: #2C8F0C;
    font-weight: bold;
}

.price {
    color: #2C8F0C;
    font-weight: 300;
}

.original-price {
    text-decoration: line-through;
    color: #6c757d;
    font-size: 0.9rem;
}

.savings-text {
    color: #000000;
    font-weight: 100;
}
</style>
@endsection
