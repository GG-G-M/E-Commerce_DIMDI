@extends('layouts.delivery')

@section('title', 'Delivery Dashboard')

@section('content')
<style>
    :root {
        --green-primary: #2C8F0C;
        --green-secondary: #4CAF50;
        --green-light: #E8F5E6;
        --green-lighter: #F8FDF8;
        --green-dark: #1E6A08;
        --orange-primary: #FF9800;
        --orange-secondary: #FFB74D;
        --orange-light: #FFF3E0;
        --gray-light: #f8f9fa;
        --gray-medium: #6c757d;
        --gray-dark: #495057;
    }

    /* Dashboard Header */
    .dashboard-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-left: 4px solid var(--green-primary);
    }

    /* Stats Cards */
    .stats-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        color: white !important;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }
    
    .card-primary {
        background: linear-gradient(135deg, var(--green-primary), var(--green-secondary));
        color: white !important;
    }
    
    .card-success {
        background: linear-gradient(135deg, var(--green-dark), var(--green-primary));
        color: white;
    }
    
    .card-orange {
        background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
        color: white;
    }
    
    .card-info {
        background: linear-gradient(135deg, var(--green-secondary), #66BB6A);
        color: white;
    }
    
    .stats-icon {
        font-size: 2rem;
        opacity: 0.8;
    }
    
    .stats-number {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
    }
    
    .stats-label {
        font-size: 0.85rem;
        opacity: 0.9;
        font-weight: 500;
    }
    
    /* Clickable Cards */
    .clickable-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .clickable-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 12px 20px rgba(0,0,0,0.2) !important;
    }

    /* Section Cards */
    .section-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
        background: white;
        color: white; 
    }
    
    .section-card .card-header {
        background: white;
        border-bottom: 2px solid var(--green-light);
        font-weight: 600;
        color: var(--green-primary);
        padding: 1rem 1.5rem;
    }
    
    /* Order Cards */
    .order-card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
        border-left: 4px solid var(--green-primary);
        background: white;
    }
    
    .order-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        transform: translateX(2px);
    }
    
    .order-card-available {
        border-left-color: var(--green-secondary);
    }
    
    .order-card-my {
        border-left-color: var(--green-dark);
    }
    
    /* Table Styling */
    .table th {
        background-color: var(--green-light);
        color: var(--green-primary);
        font-weight: 600;
        border-bottom: 2px solid var(--green-primary);
    }
    
    .table-hover tbody tr:hover {
        background-color: var(--green-lighter);
    }
    
    /* Badges */
    .badge-success {
        background-color: var(--green-light);
        color: var(--green-primary);
        border: 1px solid #C8E6C9;
    }
    
    .badge-warning {
        background-color: var(--orange-light);
        color: var(--orange-primary);
        border: 1px solid #FFE0B2;
    }
    
    .badge-primary {
        background-color: #E3F2FD;
        color: #1976D2;
        border: 1px solid #BBDEFB;
    }
    
    /* Buttons */
    .btn-success {
        background: linear-gradient(135deg, var(--green-primary), var(--green-secondary));
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, var(--green-dark), var(--green-primary));
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
    }
    
    .btn-outline-success {
        background: white;
        border: 2px solid var(--green-primary);
        color: var(--green-primary);
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-outline-success:hover {
        background: var(--green-primary);
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-warning {
        background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-warning:hover {
        background: linear-gradient(135deg, #F57C00, var(--orange-primary));
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(255, 152, 0, 0.3);
    }
    
    /* Empty State */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: var(--gray-medium);
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Action Buttons - Green Theme */
    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: all 0.2s ease;
        text-decoration: none;
        border: 1px solid transparent;
    }

    .btn-pickup {
        background: linear-gradient(135deg, var(--green-primary), var(--green-secondary));
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-pickup:hover {
        background: linear-gradient(135deg, var(--green-dark), var(--green-primary));
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(44, 143, 12, 0.3);
    }

    .btn-deliver {
        background: linear-gradient(135deg, var(--orange-primary), var(--orange-secondary));
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-deliver:hover {
        background: linear-gradient(135deg, #F57C00, var(--orange-primary));
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(255, 152, 0, 0.3);
    }

    /* Quick Actions */
    .quick-action-btn {
        padding: 0.75rem;
        border-radius: 8px;
        font-weight: 500;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Status Colors */
    .status-ready {
        color: var(--green-primary);
        font-weight: 600;
    }

    .status-active {
        color: var(--green-dark);
        font-weight: 600;
    }

    .status-delivered {
        color: var(--green-secondary);
        font-weight: 600;
    }

    /* Price Styling */
    .price-amount {
        color: var(--green-primary);
        font-weight: 700;
        font-size: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-number {
            font-size: 1.5rem;
        }
        
        .stats-icon {
            font-size: 1.5rem;
        }
        
        .order-card .card-body {
            padding: 0.75rem;
        }
        
        .btn-pickup, .btn-deliver {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
        }
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stats-card, .section-card {
        animation: fadeIn 0.5s ease-out;
    }

    /* Card Body */
    .card-body {
        color: var(--gray-dark);
    }
    /* Make all text white in stats cards */
.card-primary *, .card-success *, .card-orange *, .card-info * { color: white !important; }
</style>

<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: var(--green-primary); font-weight: 700;">Delivery Dashboard</h1>
            <p class="mb-0 text-muted">Welcome back, {{ Auth::user()->name }}! Manage your delivery assignments efficiently.</p>
        </div>
        <div class="text-end">
            <small class="text-muted">Last updated: {{ now()->format('M d, Y \\a\\t h:i A') }}</small>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <!-- Available for Pickup -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('delivery.orders.pickup') }}" class="text-decoration-none">
            <div class="card stats-card card-primary h-100 clickable-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="stats-label">AVAILABLE FOR PICKUP</div>
                            <div class="stats-number">{{ $stats['availableOrdersCount'] }}</div>
                            <small>Orders ready to be picked up</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box-open stats-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- My Active Orders -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('delivery.orders.my-orders') }}" class="text-decoration-none">
            <div class="card stats-card card-success h-100 clickable-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="stats-label">MY ACTIVE ORDERS</div>
                            <div class="stats-number">{{ $stats['myActiveOrdersCount'] }}</div>
                            <small>Orders you're delivering</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck-loading stats-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Out for Delivery -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card card-orange h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stats-label">OUT FOR DELIVERY</div>
                        <div class="stats-number">{{ $stats['outForDeliveryCount'] }}</div>
                        <small>Currently being delivered</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shipping-fast stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivered Today -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('delivery.orders.delivered') }}" class="text-decoration-none">
            <div class="card stats-card card-info h-100 clickable-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="stats-label">DELIVERED TODAY</div>
                            <div class="stats-number">{{ $stats['deliveredTodayCount'] }}</div>
                            <small>Completed deliveries today</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle stats-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row">
    <!-- Available Orders for Quick Pickup -->
    <div class="col-lg-6">
        <div class="card section-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-box me-2"></i>Available for Pickup
                </h6>
                <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-sm btn-outline-success">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @if($availableOrders->count() > 0)
                    <div class="p-3">
                        @foreach($availableOrders as $order)
                        <div class="card order-card order-card-available mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0 fw-bold text-dark">#{{ $order->order_number }}</h6>
                                            <span class="badge badge-success">Ready for Pickup</span>
                                        </div>
                                        <p class="mb-1 text-muted">
                                            <i class="fas fa-user me-1"></i>{{ $order->customer_name }}
                                        </p>
                                        <p class="mb-2 text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($order->shipping_address, 40) }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="price-amount">₱{{ number_format($order->total_amount, 2) }}</span>
                                            <div>
                                                <form action="{{ route('delivery.orders.markAsPickedUp', $order) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn-pickup btn-sm">
                                                        <i class="fas fa-hand-paper me-1"></i>Pick Up Order
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <h5 class="text-muted">No Orders Available</h5>
                        <p class="text-muted mb-3">There are no orders ready for pickup at the moment.</p>
                        <a href="{{ route('delivery.orders.index') }}" class="btn btn-outline-success">
                            <i class="fas fa-list me-1"></i> View All Orders
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- My Active Orders -->
    <div class="col-lg-6">
        <div class="card section-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-clipboard-list me-2"></i>My Active Orders
                </h6>
                <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-sm btn-outline-success">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @if($myActiveOrders->count() > 0)
                    <div class="p-3">
                        @foreach($myActiveOrders as $order)
                        <div class="card order-card order-card-my mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0 fw-bold text-dark">#{{ $order->order_number }}</h6>
                                            <span class="badge badge-warning">{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</span>
                                        </div>
                                        <p class="mb-1 text-muted">
                                            <i class="fas fa-user me-1"></i>{{ $order->customer_name }}
                                        </p>
                                        <p class="mb-2 text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($order->shipping_address, 40) }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="price-amount">₱{{ number_format($order->total_amount, 2) }}</span>
                                            <div>
                                                @if($order->order_status == 'picked_up')
                                                <form action="{{ route('delivery.orders.markAsDelivered', $order) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn-deliver btn-sm">
                                                        <i class="fas fa-check me-1"></i>Mark as Delivered
                                                    </button>
                                                </form>
                                                @else
                                                <span class="badge badge-primary">In Transit</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-clipboard-list"></i>
                        <h5 class="text-muted">No Active Orders</h5>
                        <p class="text-muted mb-3">You don't have any active orders assigned to you.</p>
                        <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-success">
                            <i class="fas fa-box me-1"></i> Pick Up Orders
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Delivered Orders -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card section-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-check-circle me-2"></i>Recently Delivered
                </h6>
                <a href="{{ route('delivery.orders.delivered') }}" class="btn btn-sm btn-outline-success">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentDelivered->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Delivery Address</th>
                                    <th>Amount</th>
                                    <th>Delivered At</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDelivered as $order)
                                <tr>
                                    <td>
                                        <strong class="text-success">#{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user me-2 text-muted"></i>
                                            {{ $order->customer_name }}
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($order->shipping_address, 30) }}</small>
                                    </td>
                                    <td class="fw-bold text-success">₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $order->delivered_at ? $order->delivered_at->format('M j, Y g:i A') : 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">Delivered</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <h5 class="text-muted">No Delivered Orders</h5>
                        <p class="text-muted">You haven't delivered any orders yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card section-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-3">
                        <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-success w-100 quick-action-btn">
                            <i class="fas fa-box me-2"></i>Pick Up Orders
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-outline-success w-100 quick-action-btn">
                            <i class="fas fa-list me-2"></i>My Orders
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('delivery.orders.index') }}" class="btn btn-outline-success w-100 quick-action-btn">
                            <i class="fas fa-shopping-cart me-2"></i>All Orders
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('delivery.profile.show') }}" class="btn btn-outline-success w-100 quick-action-btn">
                            <i class="fas fa-user me-2"></i>My Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delivery Tips -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card section-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-lightbulb me-2"></i>Delivery Tips
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3">
                                <div class="bg-success bg-opacity-10 p-2 rounded-circle">
                                    <i class="fas fa-clock text-success"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Check Order Details</h6>
                                <p class="small text-muted mb-0">Always verify order contents before pickup.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3">
                                <div class="bg-warning bg-opacity-10 p-2 rounded-circle">
                                    <i class="fas fa-map-marker-alt text-warning"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Optimize Route</h6>
                                <p class="small text-muted mb-0">Plan your delivery route for efficiency.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3">
                                <div class="bg-info bg-opacity-10 p-2 rounded-circle">
                                    <i class="fas fa-phone text-info"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Customer Contact</h6>
                                <p class="small text-muted mb-0">Call ahead if you're running late.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection