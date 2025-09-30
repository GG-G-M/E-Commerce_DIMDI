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
                            <th>Actions</th>
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
                                @if($order->order_status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @elseif($order->order_status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($order->order_status == 'shipped')
                                    <span class="badge bg-info">Shipped</span>
                                @elseif($order->order_status == 'processing')
                                    <span class="badge bg-primary">Processing</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                                @if($order->canBeCancelled())
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        data-bs-toggle="modal" data-bs-target="#cancelOrderModal{{ $order->id }}">
                                    Cancel
                                </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Cancel Order Modal -->
                        @if($order->canBeCancelled())
                        <div class="modal fade" id="cancelOrderModal{{ $order->id }}" tabindex="-1">
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
                                                <label for="cancellation_reason{{ $order->id }}" class="form-label">Reason for cancellation:</label>
                                                <textarea class="form-control" id="cancellation_reason{{ $order->id }}" 
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