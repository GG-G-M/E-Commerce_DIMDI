@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Order Details - {{ $order->order_number }}</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order Items</h5>
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
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" 
                                             class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
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
        <!-- Order Summary -->
        <div class="card mb-4">
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
                        <span class="badge bg-{{ $order->order_status == 'cancelled' ? 'danger' : ($order->order_status == 'completed' ? 'success' : ($order->order_status == 'processing' ? 'primary' : 'warning')) }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
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

        <!-- Update Order Status -->
        <div class="card mb-4">
            <div class="card-header">
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
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card">
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

        <!-- Shipping Address -->
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
@endsection