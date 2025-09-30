@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
            <li class="breadcrumb-item active">Order {{ $order->order_number }}</li>
        </ol>
    </nav>

    <!-- Order Status Alert -->
    @if($order->order_status == 'cancelled')
    <div class="alert alert-danger">
        <h5><i class="fas fa-times-circle me-2"></i>Order Cancelled</h5>
        <p class="mb-0"><strong>Reason:</strong> {{ $order->cancellation_reason }}</p>
        <small>Cancelled on: {{ $order->cancelled_at->format('M d, Y g:i A') }}</small>
    </div>
    @elseif($order->canBeCancelled())
    <div class="alert alert-warning">
        <div class="d-flex justify-content-between align-items-center">
            <span>You can cancel this order if needed.</span>
            <button type="button" class="btn btn-sm btn-outline-danger" 
                    data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                Cancel Order
            </button>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
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
                                            <img src="{{ $item->product->image ?: 'https://via.placeholder.com/50x50' }}" 
                                                 alt="{{ $item->product_name }}" class="img-thumbnail me-3" style="width: 50px;">
                                            <div>
                                                <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Order Number:</strong>
                        <span class="float-end">{{ $order->order_number }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Order Date:</strong>
                        <span class="float-end">{{ $order->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="float-end">
                            @if($order->order_status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @elseif($order->order_status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($order->order_status == 'delivered')
                                <span class="badge bg-success">Delivered</span>
                            @elseif($order->order_status == 'shipped')
                                <span class="badge bg-info">Shipped</span>
                            @elseif($order->order_status == 'processing')
                                <span class="badge bg-primary">Processing</span>
                            @elseif($order->order_status == 'confirmed')
                                <span class="badge bg-secondary">Confirmed</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </span>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <span>Subtotal:</span>
                        <span class="float-end">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="mb-2">
                        <span>Tax:</span>
                        <span class="float-end">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="mb-2">
                        <span>Shipping:</span>
                        <span class="float-end">${{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <strong>Total:</strong>
                        <strong class="float-end">${{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Name:</strong>
                        <span class="float-end">{{ $order->customer_name }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Email:</strong>
                        <span class="float-end">{{ $order->customer_email }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Phone:</strong>
                        <span class="float-end">{{ $order->customer_phone }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Payment Method:</strong>
                        <span class="float-end text-capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Shipping Address</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ nl2br($order->shipping_address) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
@if($order->canBeCancelled())
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('orders.cancel', $order) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to cancel order <strong>{{ $order->order_number }}</strong>?</p>
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Reason for cancellation:</label>
                        <textarea class="form-control" id="cancellation_reason" 
                                  name="cancellation_reason" rows="3" required 
                                  placeholder="Please provide a reason for cancellation"></textarea>
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