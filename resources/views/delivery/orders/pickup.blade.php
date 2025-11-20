@extends('layouts.delivery')

@section('title', 'Ready for Pickup - Delivery Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2">Orders Ready for Pickup</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('delivery.orders.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to All Orders
        </a>
    </div>
</div>

@if($orders->count() > 0)
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i> 
        You have {{ $orders->count() }} order(s) ready for pickup. Please pick them up from the warehouse.
    </div>

    <div class="row">
        @foreach($orders as $order)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 border-warning">
                <div class="card-header d-flex justify-content-between align-items-center bg-warning text-dark">
                    <strong>#{{ $order->order_number }}</strong>
                    <span class="badge bg-dark">Ready for Pickup</span>
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
                        <strong>Items:</strong> {{ $order->orderItems->count() }} items
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <a href="{{ route('delivery.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i> View Details
                        </a>
                        <form action="{{ route('delivery.orders.markAsPickedUp', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm w-100">
                                <i class="fas fa-box me-1"></i> Mark as Picked Up
                            </button>
                        </form>
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
        <i class="fas fa-box fa-3x text-muted mb-3"></i>
        <h4>No Orders Ready for Pickup</h4>
        <p class="text-muted">All your assigned orders have been picked up or are in delivery.</p>
        <a href="{{ route('delivery.orders.index') }}" class="btn btn-primary">
            <i class="fas fa-list me-1"></i> View All Orders
        </a>
    </div>
@endif
@endsection