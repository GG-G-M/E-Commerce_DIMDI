@extends('layouts.admin')

@section('content')
<style>
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #2C8F0C;
    }
    
    .orders-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .orders-card .card-body {
        padding: 0;
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 1rem 0.75rem;
    }
    
    .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-color: #E8F5E6;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background-color: #F8FDF8;
        transform: translateY(-1px);
    }
    
    .badge-status {
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
    }
    
    .badge-pending {
        background-color: #FFF3CD !important;
        color: #856404 !important;
    }
    
    .badge-confirmed {
        background-color: #D1ECF1 !important;
        color: #0C5460 !important;
    }
    
    .badge-processing {
        background-color: #E8F5E6 !important;
        color: #2C8F0C !important;
    }
    
    .badge-completed {
        background-color: #D4EDDA !important;
        color: #155724 !important;
    }
    
    .badge-cancelled {
        background-color: #F8D7DA !important;
        color: #721C24 !important;
    }
    
    .btn-outline-primary {
        border-color: #2C8F0C;
        color: #2C8F0C;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(44, 143, 12, 0.2);
    }
    
    .order-number {
        color: #2C8F0C;
        font-weight: 700;
        font-size: 0.95rem;
    }
    
    .customer-name {
        font-weight: 600;
        color: #495057;
    }
    
    .customer-email {
        color: #6c757d;
        font-size: 0.875rem;
    }
    
    .total-amount {
        font-weight: 700;
        color: #2C8F0C;
        font-size: 1rem;
    }
    
    .order-date {
        color: #6c757d;
        font-size: 0.875rem;
    }
    
    .pagination .page-link {
        color: #2C8F0C;
        border-color: #E8F5E6;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
    }
    
    .pagination .page-link:hover {
        background-color: #E8F5E6;
        border-color: #2C8F0C;
    }
    
    .tooltip-inner {
        background-color: #2C8F0C;
        color: white;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
    }
    
    .tooltip-arrow {
        display: none;
    }
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">Order Management</h1>
            <p class="mb-0 text-muted">View and manage customer orders</p>
        </div>
        <div class="text-end">
            <small class="text-muted">Total Orders: {{ $orders->total() }}</small>
        </div>
    </div>
</div>

<div class="card orders-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
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
                            <span class="order-number">{{ $order->order_number }}</span>
                        </td>
                        <td>
                            <div class="customer-name">{{ $order->customer_name }}</div>
                        </td>
                        <td>
                            <div class="customer-email">{{ $order->customer_email }}</div>
                        </td>
                        <td>
                            <span class="total-amount">${{ number_format($order->total_amount, 2) }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = [
                                    'pending' => 'badge-pending',
                                    'confirmed' => 'badge-confirmed',
                                    'processing' => 'badge-processing',
                                    'completed' => 'badge-completed',
                                    'cancelled' => 'badge-cancelled'
                                ][$order->order_status] ?? 'badge-pending';
                            @endphp
                            <span class="badge badge-status {{ $statusClass }}"
                                @if($order->order_status == 'cancelled' && $order->cancellation_reason)
                                data-bs-toggle="tooltip" data-bs-placement="top" 
                                title="Cancellation Reason: {{ $order->cancellation_reason }}"
                                @endif>
                                <i class="fas fa-{{ $order->order_status == 'pending' ? 'clock' : ($order->order_status == 'confirmed' ? 'check' : ($order->order_status == 'processing' ? 'cog' : ($order->order_status == 'completed' ? 'check-circle' : 'times'))) }} me-1"></i>
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td>
                            <div class="order-date">{{ $order->created_at->format('M d, Y') }}</div>
                            <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary btn-sm"
                               data-bs-toggle="tooltip" title="View Order Details">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center py-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush

@endsection