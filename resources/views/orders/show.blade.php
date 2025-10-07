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
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                    <div class="row align-items-center mb-3 pb-3 border-bottom">
                        <div class="col-md-2">
                            <img src="{{ $item->product->image_url }}" 
                                 alt="{{ $item->product_name }}" 
                                 class="img-fluid rounded">
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1">{{ $item->product_name }}</h6>
                            <p class="text-muted mb-0">${{ number_format($item->unit_price, 2) }}</p>
                            @if($item->selected_size)
                            <p class="text-muted mb-0">
                                <strong>Size:</strong> {{ $item->selected_size }}
                            </p>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <p class="mb-0">Quantity: {{ $item->quantity }}</p>
                        </div>
                        <div class="col-md-3 text-end">
                            <strong>${{ number_format($item->total_price, 2) }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span>${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <span>${{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong>${{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('M j, Y g:i A') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $order->order_status === 'completed' ? 'success' : ($order->order_status === 'cancelled' ? 'danger' : 'warning') }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </p>
                    <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                    
                    @if($order->order_status === 'pending' || $order->order_status === 'confirmed')
                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">Cancellation Reason</label>
                            <textarea name="cancellation_reason" id="cancellation_reason" 
                                      class="form-control" rows="3" 
                                      placeholder="Please provide a reason for cancellation" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Are you sure you want to cancel this order?')">
                            Cancel Order
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection