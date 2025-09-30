@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Orders</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <strong>{{ $order->order_number }}</strong>
                        </td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->customer_email }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $order->order_status == 'cancelled' ? 'danger' : ($order->order_status == 'completed' ? 'success' : ($order->order_status == 'processing' ? 'primary' : ($order->order_status == 'confirmed' ? 'secondary' : 'warning'))) }}"
                                @if($order->order_status == 'cancelled' && $order->cancellation_reason)
                                data-bs-toggle="tooltip" data-bs-placement="top" 
                                title="Reason: {{ $order->cancellation_reason }}"
                                @endif>
                                {{ ucfirst($order->order_status) }}
                            </span>
                            {{-- Cancelation Thing --}}
                            {{-- @if($order->order_status == 'cancelled' && $order->cancellation_reason)
                            <br>
                            <small class="text-muted">Reason: {{ Str::limit($order->cancellation_reason, 30) }}</small>
                            @endif --}}
                        </td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
</div>

{{-- Tool Tip --}}
@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush

@endsection