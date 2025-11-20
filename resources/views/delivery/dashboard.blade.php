@extends('layouts.delivery')

@section('title', 'Delivery Dashboard')

@section('content')
<style>
    .stat-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
    }
    
    .card-available {
        border-left: 4px solid #28a745;
    }
    
    .card-my-order {
        border-left: 4px solid #007bff;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2">Delivery Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-box me-1"></i> Available Orders
            </a>
            <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-list me-1"></i> My Orders
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Available for Pickup</h6>
                        <div class="stat-number">{{ $stats['availableOrdersCount'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-box-open fa-2x"></i>
                    </div>
                </div>
                <small>Orders ready to be picked up</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">My Active Orders</h6>
                        <div class="stat-number">{{ $stats['myActiveOrdersCount'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-truck-loading fa-2x"></i>
                    </div>
                </div>
                <small>Orders you're delivering</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Out for Delivery</h6>
                        <div class="stat-number">{{ $stats['outForDeliveryCount'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shipping-fast fa-2x"></i>
                    </div>
                </div>
                <small>Currently being delivered</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Delivered Today</h6>
                        <div class="stat-number">{{ $stats['deliveredTodayCount'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
                <small>Completed deliveries today</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Available Orders for Quick Pickup -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-box me-2"></i>Available for Pickup</h6>
                <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-sm btn-light">View All</a>
            </div>
            <div class="card-body">
                @if($availableOrders->count() > 0)
                    @foreach($availableOrders as $order)
                    <div class="card mb-2 card-available">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>#{{ $order->order_number }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $order->customer_name }}</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">₱{{ number_format($order->total_amount, 2) }}</small>
                                    <br>
                                    <a href="{{ route('delivery.orders.show', $order) }}" class="btn btn-sm btn-outline-primary mt-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('delivery.orders.markAsPickedUp', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success mt-1">
                                            <i class="fas fa-hand-paper"></i> Pick Up
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No orders available for pickup</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- My Active Orders -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-list me-2"></i>My Active Orders</h6>
                <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-sm btn-light">View All</a>
            </div>
            <div class="card-body">
                @if($myActiveOrders->count() > 0)
                    @foreach($myActiveOrders as $order)
                    <div class="card mb-2 card-my-order">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>#{{ $order->order_number }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $order->customer_name }}</small>
                                    <br>
                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</span>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">₱{{ number_format($order->total_amount, 2) }}</small>
                                    <br>
                                    <a href="{{ route('delivery.orders.show', $order) }}" class="btn btn-sm btn-outline-primary mt-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($order->order_status == 'picked_up')
                                    <form action="{{ route('delivery.orders.markAsDelivered', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning mt-1">
                                            <i class="fas fa-check"></i> Deliver
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-clipboard-list fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No active orders assigned to you</p>
                        <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-box me-1"></i> Pick Up Orders
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Delivered Orders -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Recently Delivered</h6>
                <a href="{{ route('delivery.orders.delivered') }}" class="btn btn-sm btn-light">View All</a>
            </div>
            <div class="card-body">
                @if($recentDelivered->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Delivered At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDelivered as $order)
                                <tr>
                                    <td><strong>#{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td>{{ $order->delivered_at ? $order->delivered_at->format('M j, Y g:i A') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('delivery.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-check-circle fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No delivered orders yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection