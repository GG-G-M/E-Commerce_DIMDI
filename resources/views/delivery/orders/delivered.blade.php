@extends('layouts.delivery')

@section('title', 'Delivered Orders - Delivery Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2">Delivered Orders</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('delivery.orders.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to All Orders
        </a>
    </div>
</div>

@if($orders->count() > 0)
    <div class="row">
        @foreach($orders as $order)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 border-success">
                <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
                    <strong>#{{ $order->order_number }}</strong>
                    <span class="badge bg-light text-success">Delivered</span>
                </div>
                
                <div class="card-body">
                    <div class="mb-3">
                        <strong>{{ $order->customer_name }}</strong><br>
                        <small class="text-muted">
                            <i class="fas fa-phone me-1"></i>{{ $order->customer_phone }}
                        </small>
                    </div>

                    <div class="mb-3">
                        <strong>Delivery Address:</strong><br>
                        <small>{{ Str::limit($order->shipping_address, 100) }}</small>
                    </div>

                    <div class="mb-3">
                        <strong>Total Amount:</strong> ₱{{ number_format($order->total_amount, 2) }}<br>
                        <strong>Items:</strong> {{ $order->orderItems->count() }} items<br>
                        <strong>Delivered On:</strong> {{ $order->updated_at->format('M j, Y g:i A') }}
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-grid">
                        <a href="{{ route('delivery.orders.show', $order) }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-eye me-1"></i> View Delivery Details
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
        <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
        <h4>No Delivered Orders</h4>
        <p class="text-muted">You haven't delivered any orders yet.</p>
        <a href="{{ route('delivery.orders.index') }}" class="btn btn-primary">
            <i class="fas fa-list me-1"></i> View Active Orders
        </a>
    </div>
@endif
@endsection