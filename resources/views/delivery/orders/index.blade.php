@extends('layouts.delivery')

@section('title', 'All Orders - Delivery Dashboard')

@section('content')
<style>
    .card-available {
        border-left: 4px solid #28a745;
    }
    .card-my-order {
        border-left: 4px solid #007bff;
    }
    .badge-available {
        background-color: #28a745;
        color: white;
    }
    .order-item {
        border-bottom: 1px solid #e9ecef;
        padding: 8px 0;
    }
    .order-item:last-child {
        border-bottom: none;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2">All Orders</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-box me-1"></i> Available for Pickup
            </a>
            <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-list me-1"></i> My Orders
            </a>
            <a href="{{ route('delivery.orders.delivered') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-check-circle me-1"></i> Delivered
            </a>
        </div>
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

@if($orders->count() > 0)
    <div class="row">
        @foreach($orders as $order)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 {{ is_null($order->delivery_id) ? 'card-available' : ($order->delivery_id == Auth::id() ? 'card-my-order' : '') }}">
                <div class="card-header d-flex justify-content-between align-items-center 
                    {{ is_null($order->delivery_id) ? 'bg-success text-white' : ($order->delivery_id == Auth::id() ? 'bg-primary text-white' : 'bg-secondary text-white') }}">
                    <strong>#{{ $order->order_number }}</strong>
                    <div>
                        @if(is_null($order->delivery_id))
                            <span class="badge badge-available">Available</span>
                        @elseif($order->delivery_id == Auth::id())
                            <span class="badge bg-light text-primary">My Order</span>
                        @else
                            <span class="badge bg-light text-dark">Assigned to Other</span>
                        @endif
                        <span class="badge bg-light text-dark ms-1">
                            {{ strtoupper($order->order_status) }}
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Customer Information -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">CUSTOMER INFORMATION</h6>
                        <strong>{{ $order->customer_name }}</strong><br>
                        <small class="text-muted">
                            <i class="fas fa-phone me-1"></i>{{ $order->customer_phone }}<br>
                            <i class="fas fa-envelope me-1"></i>{{ $order->customer_email }}
                        </small>
                    </div>

                    <!-- Delivery Address -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">DELIVERY ADDRESS</h6>
                        <small>{{ $order->shipping_address }}</small>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">ORDER ITEMS ({{ $order->orderItems->count() }})</h6>
                        <div style="max-height: 120px; overflow-y: auto;">
                            @foreach($order->orderItems as $item)
                            <div class="order-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <strong class="d-block">{{ $item->product->name ?? 'Product' }}</strong>
                                        <small class="text-muted">
                                            Qty: {{ $item->quantity }} × ₱{{ number_format($item->unit_price, 2) }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <strong>₱{{ number_format($item->quantity * $item->unit_price, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">ORDER SUMMARY</h6>
                        <div class="d-flex justify-content-between">
                            <span>Total Amount:</span>
                            <strong>₱{{ number_format($order->total_amount, 2) }}</strong>
                        </div>
                        @if(!is_null($order->delivery_id) && $order->delivery_id == Auth::id())
                        <div class="d-flex justify-content-between mt-2">
                            <span>Assigned To You:</span>
                            <small class="text-muted">{{ $order->assigned_at ? $order->assigned_at->format('M j, Y g:i A') : 'N/A' }}</small>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <a href="{{ route('delivery.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i> View Details
                        </a>
                        
                        <!-- Action Buttons -->
                        @if(is_null($order->delivery_id) && $order->order_status == 'processing')
                        <form action="{{ route('delivery.orders.pickup-order', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="fas fa-hand-paper me-1"></i> Pick Up This Order
                            </button>
                        </form>
                        @endif

                        <!-- MARK AS DELIVERED BUTTON - ADD THIS -->
                        @if($order->delivery_id == Auth::id() && in_array($order->order_status, ['shipped', 'out_for_delivery']))
                        <form action="{{ route('delivery.orders.deliver-order', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm w-100" 
                                    onclick="return confirm('Mark order #{{ $order->order_number }} as delivered?')">
                                <i class="fas fa-check me-1"></i> Mark as Delivered
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
        <h4>No Orders Available</h4>
        <p class="text-muted">There are no orders available at the moment.</p>
        <a href="{{ route('delivery.dashboard') }}" class="btn btn-primary">
            <i class="fas fa-tachometer-alt me-1"></i> Back to Dashboard
        </a>
    </div>
@endif
@endsection