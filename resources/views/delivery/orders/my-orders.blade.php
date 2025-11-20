@extends('layouts.delivery')

@section('title', 'My Orders - Delivery Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2">My Active Orders</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('delivery.orders.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to All Orders
        </a>
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
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i> 
        You have {{ $orders->count() }} active order(s) assigned to you. Deliver them to customers and mark as delivered when completed.
    </div>

    <div class="row">
        @foreach($orders as $order)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 border-primary">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <strong>#{{ $order->order_number }}</strong>
                    <div>
                        <span class="badge bg-light text-primary">My Order</span>
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
                        <div class="d-flex justify-content-between mt-2">
                            <span>Assigned To You:</span>
                            <small class="text-muted">{{ $order->assigned_at ? $order->assigned_at->format('M j, Y g:i A') : 'N/A' }}</small>
                        </div>
                        @if($order->picked_up_at)
                        <div class="d-flex justify-content-between mt-1">
                            <span>Picked Up:</span>
                            <small class="text-muted">{{ $order->picked_up_at->format('M j, Y g:i A') }}</small>
                        </div>
                        @endif
                    </div>

                    <!-- Delivery Status -->
                    <div class="mb-3 p-3 bg-light rounded">
                        <h6 class="text-muted mb-2">DELIVERY STATUS</h6>
                        <div class="progress mb-2" style="height: 10px;">
                            @if($order->order_status == 'shipped')
                            <div class="progress-bar bg-warning" style="width: 50%">Shipped</div>
                            @elseif($order->order_status == 'out_for_delivery')
                            <div class="progress-bar bg-info" style="width: 75%">Out for Delivery</div>
                            @elseif($order->order_status == 'delivered')
                            <div class="progress-bar bg-success" style="width: 100%">Delivered</div>
                            @endif
                        </div>
                        
                        <!-- MARK AS DELIVERED BUTTON -->
                        @if(in_array($order->order_status, ['shipped', 'out_for_delivery']))
                        <form action="{{ route('delivery.orders.deliver-order', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm w-100" 
                                    onclick="return confirm('Mark order #{{ $order->order_number }} as delivered?')">
                                <i class="fas fa-check me-1"></i> Mark as Delivered
                            </button>
                        </form>
                        @elseif($order->order_status == 'delivered')
                        <div class="text-center text-success">
                            <i class="fas fa-check-circle me-1"></i> Successfully Delivered
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <a href="{{ route('delivery.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i> View Full Details
                        </a>
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
        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
        <h4>No Active Orders</h4>
        <p class="text-muted">You don't have any active orders assigned to you.</p>
        <div class="mt-3">
            <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-primary me-2">
                <i class="fas fa-box me-1"></i> Pick Up Available Orders
            </a>
            <a href="{{ route('delivery.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list me-1"></i> View All Orders
            </a>
        </div>
    </div>
@endif
@endsection