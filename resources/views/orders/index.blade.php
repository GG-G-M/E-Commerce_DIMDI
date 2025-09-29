@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">My Orders</h1>

    @if($orders->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->order_status == 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-receipt fa-4x text-muted mb-4"></i>
        <h3>No orders found</h3>
        <p class="text-muted mb-4">You haven't placed any orders yet.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Start Shopping</a>
    </div>
    @endif
</div>
@endsection