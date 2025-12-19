@extends('layouts.delivery')

@section('title', 'All Orders - Delivery Dashboard')

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
    background: linear-gradient(135deg, #2C8F0C, #4CAF50);
    border: none;
    color: white;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(44, 143, 12, 0.2);
    text-decoration: none;
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

    /* Filter Buttons */
    .filter-active {
        background-color: #2C8F0C !important;
        color: white !important;
        border-color: #2C8F0C !important;
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
    .customer-col { width: 180px; min-width: 180px; }
    .amount-col { width: 120px; min-width: 120px; }
    .status-col { width: 130px; min-width: 130px; }
    .assignment-col { width: 120px; min-width: 120px; }
    .date-col { width: 100px; min-width: 100px; }

    /* Customer Info */
    .customer-name {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
        line-height: 1.2;
        text-align: left;
    }
    
    .customer-phone {
        color: #6c757d;
        font-size: 0.75rem;
        text-align: left;
    }

    /* Status and Assignment Styling */
    .status-col span,
    .assignment-col span {
        text-align: left;
        display: block;
    }

    /* Amount Styling */
    .amount-text {
        font-weight: 700;
        color: #2C8F0C;
        font-size: 0.9rem;
        text-align: right;
        display: block;
    }

    /* Order Number Styling */
    .order-number {
        text-align: right;
        display: block;
        font-weight: 600;
    }

    /* Date Styling */
    .date-text {
        font-size: 0.85rem;
        color: #6c757d;
        text-align: left;
    }

    .time-text {
        font-size: 0.75rem;
        color: #adb5bd;
        text-align: left;
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

    /* Ensure header buttons content aligns left */
    .header-buttons .btn i {
        margin-right: 6px;
    }

    /* Form Styling */
    .form-label {
        font-weight: 600;
        color: #2C8F0C;
        font-size: 0.9rem;
    }

    /* Quick Filter Buttons */
    .btn-outline-success-custom {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
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
    }
</style>


@if(session('success'))
    <div class="alert alert-success border-success" style="background-color: #E8F5E6; border-color: #C8E6C9;" role="alert">
        <i class="fas fa-check-circle me-2" style="color: #2C8F0C;"></i> 
        <strong style="color: #2C8F0C;">{{ session('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger border-danger" style="background-color: #FFEBEE; border-color: #FFCDD2;" role="alert">
        <i class="fas fa-exclamation-circle me-2" style="color: #C62828;"></i> 
        <strong style="color: #C62828;">{{ session('error') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Orders Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Orders Management</h5>
        <div class="header-buttons">
            <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-outline-success-custom">
                <i class="fas fa-box"></i> Available
            </a>
            <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-outline-success-custom">
                <i class="fas fa-list"></i> My Orders
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
                            <th class="amount-col">Amount</th>
                            <th class="status-col">Status</th>
                            <th class="assignment-col">Assignment</th>
                            <th class="date-col">Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="order-col">
                                <strong class="text-dark order-number">#{{ $order->order_number }}</strong>
                            </td>
                            <td class="customer-col">
                                <div>
                                    <div class="customer-name">{{ $order->customer_name }}</div>
                                    <div class="customer-phone">{{ $order->customer_phone }}</div>
                                </div>
                            </td>
                            <td class="amount-col">
                                <div class="amount-text">₱{{ number_format($order->total_amount, 2) }}</div>
                            </td>
                            <td class="status-col">
                                @php
                                    $statusClass = '' . str_replace('_', '-', $order->order_status);
                                    $statusText = ucfirst(str_replace('_', ' ', $order->order_status));
                                @endphp
                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td class="assignment-col">
                                @if(is_null($order->delivery_id))
                                    <span >Available</span>
                                @elseif($order->delivery_id == Auth::id())
                                    <span >My Order</span>
                                @else
                                    <span>Assigned</span>
                                @endif
                            </td>
                            <td class="date-col">
                                <div class="date-text">{{ $order->created_at->format('M j, Y') }}</div>
                                <div class="time-text" style="font-size: 0.75rem; color: #adb5bd;">
                                    {{ $order->created_at->format('h:i A') }}
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
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                    </small>
                </div>
                <div>
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-box-open"></i>
                <h5 class="text-muted">No Orders Found</h5>
                <p class="text-muted mb-4">
                    @if(request()->hasAny(['search', 'status', 'assignment', 'date_filter']))
                        No orders match your current filters. Try adjusting your search criteria.
                    @else
                        There are no orders available at the moment.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status', 'assignment', 'date_filter']))
                    <a href="{{ route('delivery.orders.index') }}" class="btn btn-success-custom">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                @else
                    <a href="{{ route('delivery.dashboard') }}" class="btn btn-success-custom">
                        <i class="fas fa-tachometer-alt me-1"></i> Back to Dashboard
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when radio buttons change
    const dateFilters = document.querySelectorAll('input[name="date_filter"]');
    dateFilters.forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    // Add active class to filter buttons
    const filterButtons = document.querySelectorAll('.btn-check');
    filterButtons.forEach(button => {
        if (button.checked) {
            const label = document.querySelector(`label[for="${button.id}"]`);
            label.classList.add('filter-active');
        }
    });
});
</script>
@endsection