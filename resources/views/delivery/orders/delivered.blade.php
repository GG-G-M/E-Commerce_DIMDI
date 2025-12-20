@extends('layouts.delivery')

@section('title', 'Delivered Orders - Delivery Dashboard')

@section('content')
<style>
    /* === Consistent Green Theme === */
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
    }

    /* Dashboard Header */
    .dashboard-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-left: 4px solid #2C8F0C;
    }
    
    /* Table Styling - Compact */
    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 0.75rem 0.5rem;
        white-space: nowrap;
    }

    .table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    /* Alternating row colors */
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #F8FDF8;
    }

    /* Update the button styles to match pick up button design */
.btn-outline-success-custom {
    background: white;
    color: #2C8F0C;
    border: 2px solid rgba(44, 143, 12, 0.3);
    padding: 0.5rem 1.25rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    text-decoration: none;
    white-space: nowrap;
    min-width: fit-content;
    height: auto;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.btn-outline-success-custom:hover {
    background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
}

/* If you want the active button to look different */
.btn-outline-success-custom.active {
    background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    box-shadow: 0 0 0 2px rgba(44, 143, 12, 0.3);
}

/* Update quick filter buttons to also match */
.btn-outline-success-custom {
    font-size: 0.85rem;
    padding: 0.5rem 1rem;
}

/* Make sure header buttons have proper spacing */
.header-buttons .btn {
    margin: 0;
    font-size: 0.9rem;
    min-height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
    /* Search Box */
    .search-box {
        border-radius: 8px;
        border: 1px solid #C8E6C9;
        transition: border-color 0.3s ease;
        font-size: 0.9rem;
    }

    .search-box:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.15rem rgba(44,143,12,0.2);
    }

   

    /* Delivery Time Styling */
    .delivery-time-recent {
        color: #2C8F0C;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .delivery-time-old {
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    .delivery-hours {
        color: #495057;
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #C8E6C9;
        margin-bottom: 1rem;
    }

    /* Table Container */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        max-width: 100%;
    }

    /* Column widths - More compact */
    .order-col { width: 100px; min-width: 100px; }
    .customer-col { width: 150px; min-width: 150px; }
    .contact-col { width: 130px; min-width: 130px; }
    .address-col { width: 180px; min-width: 180px; }
    .amount-col { width: 100px; min-width: 100px; }
    .items-col { width: 80px; min-width: 80px; }
    .delivered-col { width: 120px; min-width: 120px; }
    .delivery-proof-col { width: 100px; min-width: 100px; }
    .action-col { width: 70px; min-width: 70px; }

    /* Customer Info */
    .customer-name {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
        line-height: 1.2;
    }
    
    .customer-phone {
        color: #6c757d;
        font-size: 0.75rem;
    }
    
    .customer-email {
        color: #6c757d;
        font-size: 0.75rem;
    }

    /* Items Count */
    .items-count {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
    }

    /* Amount Styling */
    .amount-text {
        font-weight: 700;
        color: #2C8F0C;
        font-size: 0.9rem;
    }

    /* Address Styling */
    .address-text {
        font-size: 0.85rem;
        color: #495057;
        line-height: 1.3;
    }

    /* Date Styling */
    .date-text {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .time-text {
        font-size: 0.75rem;
        color: #adb5bd;
    }

    /* Statistics Cards */
    .stats-card {
        background: linear-gradient(135deg, #E8F5E6, #F8FDF8);
        border: none;
        border-radius: 10px;
        padding: 1.5rem 1rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .stats-icon {
        font-size: 2rem;
        color: #2C8F0C;
        margin-bottom: 0.75rem;
    }
    
    .stats-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2C8F0C;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .stats-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        justify-content: center;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        border: 2px solid;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    
    .btn-view {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-view:hover {
        background-color: #2C8F0C;
        color: white;
    }

    /* Pagination styling - Consistent */
    .pagination .page-item .page-link {
        color: #2C8F0C;
        border: 1px solid #dee2e6;
        margin: 0 1px;
        border-radius: 4px;
        transition: all 0.3s ease;
        padding: 0.4rem 0.7rem;
        font-size: 0.85rem;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-color: #2C8F0C;
        color: white;
    }
    
    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #E8FDF8;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
    }

    /* Header button group */
    .header-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .header-buttons .btn {
        margin: 0;
        font-size: 0.9rem;
    }

    /* Form Styling */
    .form-label {
        font-weight: 600;
        color: #2C8F0C;
        font-size: 0.9rem;
    }

    /* Make table more compact on mobile */
    @media (max-width: 768px) {
        .header-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
        }
        
        .customer-name {
            font-size: 0.8rem;
        }
        
        .btn-outline-success-custom,
        .btn-success-custom {
            padding: 0.4rem 0.7rem;
            font-size: 0.8rem;
        }
        
        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }
        
        .stats-card {
            padding: 1rem 0.5rem;
        }
        
        .stats-number {
            font-size: 1.5rem;
        }
    }
</style>


<!-- Delivery Statistics -->
@if($orders->count() > 0)
<div class="row mt-4 mb-4">
    <div class="col-md-4">
        <div class="stats-card">
            <i class="fas fa-shipping-fast stats-icon"></i>
            <div class="stats-number">{{ $orders->total() }}</div>
            <div class="stats-label">Total Delivered</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <i class="fas fa-calendar-day stats-icon"></i>
            <div class="stats-number">{{ $orders->where('delivered_at', '>=', now()->startOfDay())->count() }}</div>
            <div class="stats-label">Delivered Today</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <i class="fas fa-money-bill-wave stats-icon"></i>
            <div class="stats-number">₱{{ number_format($orders->sum('total_amount'), 2) }}</div>
            <div class="stats-label">Total Value Delivered</div>
        </div>
    </div>
</div>
@endif

<div class="mt-4"></div>

<!-- Orders Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Delivered Orders History</h5>
        <div class="header-buttons">
            <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-outline-success-custom">
                <i class="fas fa-box"></i> Ready
            </a>
            <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-outline-success-custom">
                <i class="fas fa-list"></i> My Orders
            </a>
            <a href="{{ route('delivery.orders.index') }}" class="btn btn-outline-success-custom">
                <i class="fas fa-list-alt"></i> All
            </a>
        </div>
    </div>
    
    <div class="card-body p-0">
        @if($orders->count() > 0)
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="order-col">Order #</th>
                            <th class="customer-col">Customer</th>
                            <th class="contact-col">Contact</th>
                            <th class="address-col">Delivery Address</th>
                            <th class="amount-col">Amount</th>
                            <th class="items-col">Items</th>
                            <th class="delivered-col">Delivered On</th>
                            <th class="delivery-proof-col">Proof</th>
                            <th class="action-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        @php
                            $isRecentDelivery = $order->delivered_at && $order->delivered_at->gt(now()->subDays(1));
                        @endphp
                        <tr>
                            <td class="order-col">
                                <strong class="text-dark">#{{ $order->order_number }}</strong>
                                <div class="mt-1">
                                    <span >
                                        <i class="fas fa-check-circle me-1"></i>Delivered
                                    </span>
                                </div>
                            </td>
                            <td class="customer-col">
                                <div class="customer-name">{{ $order->customer_name }}</div>
                            </td>
                            <td class="contact-col">
                                <div class="customer-phone">
                                    <i class="fas fa-phone me-1"></i>{{ $order->customer_phone }}
                                </div>
                                <div class="customer-email">
                                    <i class="fas fa-envelope me-1"></i>{{ Str::limit($order->customer_email, 15) }}
                                </div>
                            </td>
                            <td class="address-col">
                                <div class="address-text">{{ Str::limit($order->shipping_address, 40) }}</div>
                            </td>
                            <td class="amount-col">
                                <div class="amount-text">₱{{ number_format($order->total_amount, 2) }}</div>
                            </td>
                            <td class="items-col">
                                <div class="items-count">{{ $order->orderItems->count() }}</div>
                            </td>
                            <td class="delivered-col">
                                @if($order->delivered_at)
                                    <div class="{{ $isRecentDelivery ? 'delivery-time-recent' : 'delivery-time-old' }}">
                                        {{ $order->delivered_at->format('M j, Y') }}
                                    </div>
                                    <div class="time-text">
                                        {{ $order->delivered_at->format('h:i A') }}
                                    </div>
                                @else
                                    <div class="text-muted">N/A</div>
                                @endif
                            </td>
                            <td class="delivery-proof-col">
                                @if($order->delivery_proof_photo)
                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#proofModal{{ $order->id }}"
                                            title="View Delivery Proof">
                                        <i class="fas fa-camera"></i> View
                                    </button>
                                @else
                                    <span class="text-muted">No Photo</span>
                                @endif
                                
                                @if($order->delivery_notes)
                                    <div class="mt-1">
                                        <small class="text-muted">
                                            <i class="fas fa-comment me-1"></i>Notes
                                        </small>
                                    </div>
                                @endif
                            </td>
                            <td class="action-col">
                                <div class="action-buttons">
                                    <a href="{{ route('delivery.orders.show', $order) }}" class="action-btn btn-view" title="View Delivery Details">
                                        <i class="fas fa-search"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
            <div class="d-flex justify-content-between align-items-center p-3">
                <div>
                    <small class="text-muted">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} delivered orders
                    </small>
                </div>
                <div>
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-check-circle"></i>
                <h5 class="text-muted">No Delivered Orders</h5>
                <p class="text-muted mb-4">
                    @if(request()->hasAny(['search', 'date_filter', 'amount_filter']))
                        No delivered orders match your current filters.
                    @else
                        You haven't delivered any orders yet.
                    @endif
                </p>
                <div class="d-flex gap-3 justify-content-center">
                    @if(request()->hasAny(['search', 'date_filter', 'amount_filter']))
                        <a href="{{ route('delivery.orders.delivered') }}" class="btn btn-success-custom">
                            <i class="fas fa-times me-1"></i> Clear Filters
                        </a>
                    @endif
                    <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-success-custom">
                        <i class="fas fa-box me-1"></i> Pick Up Orders
                    </a>
                    <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-outline-success-custom">
                        <i class="fas fa-clipboard-list me-1"></i> My Orders
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>



<!-- Delivery Proof Modals -->
@foreach($orders as $order)
    @if($order->delivery_proof_photo || $order->delivery_notes)
    <div class="modal fade" id="proofModal{{ $order->id }}" tabindex="-1" aria-labelledby="proofModalLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2C8F0C, #4CAF50); color: white;">
                    <h5 class="modal-title" id="proofModalLabel{{ $order->id }}">
                        <i class="fas fa-camera me-2"></i>Delivery Proof - Order #{{ $order->order_number }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-info-circle me-1"></i>Order Information
                            </h6>
                            <div class="alert alert-info" style="background-color: #E8F5E6; border-color: #C8E6C9; color: #2C8F0C;">
                                <strong>{{ $order->customer_name }}</strong><br>
                                <small>
                                    <i class="fas fa-phone me-1"></i>{{ $order->customer_phone }}<br>
                                    <i class="fas fa-envelope me-1"></i>{{ $order->customer_email }}
                                </small>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Delivery Address:</strong><br>
                                <small class="text-muted">{{ $order->shipping_address }}</small>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Delivered On:</strong><br>
                                <small class="text-muted">
                                    @if($order->delivered_at)
                                        {{ $order->delivered_at->format('M j, Y \a\t h:i A') }}
                                    @else
                                        N/A
                                    @endif
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            @if($order->delivery_proof_photo)
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-camera me-1"></i>Delivery Photo
                                </h6>
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $order->delivery_proof_photo) }}" 
                                         alt="Delivery Proof" 
                                         class="img-fluid rounded border"
                                         style="max-height: 300px; width: 100%; object-fit: contain;">
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $order->delivered_at ? $order->delivered_at->format('M j, Y h:i A') : 'N/A' }}
                                        </small>
                                    </div>
                                </div>
                            @endif
                            
                            @if($order->delivery_notes)
                                <div class="mt-3">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-comment me-1"></i>Delivery Notes
                                    </h6>
                                    <div class="card" style="background-color: #F8FDF8; border-color: #C8E6C9;">
                                        <div class="card-body py-2">
                                            <small class="text-dark">{{ $order->delivery_notes }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if(!$order->delivery_proof_photo && !$order->delivery_notes)
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <p>No delivery proof or notes available for this order.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <a href="{{ route('delivery.orders.show', $order) }}" class="btn" style="background: linear-gradient(135deg, #2C8F0C, #4CAF50); color: white;">
                        <i class="fas fa-eye me-1"></i>View Full Details
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

@endsection