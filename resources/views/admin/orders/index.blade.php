@extends('layouts.admin')

@section('content')
<style>
    /* 🌿 Green Theme Consistent with Category Page */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #2C8F0C;
    }

    .page-header h1 {
        color: #2C8F0C;
        font-weight: 700;
    }

    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    .badge {
        padding: 0.5em 0.75em;
        font-size: 0.85em;
        font-weight: 600;
        border-radius: 6px;
    }

    .badge-success { background-color: #2C8F0C !important; color: #fff; }
    .badge-info { background-color: #4CAF50 !important; color: #fff; }
    .badge-primary { background-color: #1E6A08 !important; color: #fff; }
    .badge-warning { background-color: #FFC107 !important; color: #000; }
    .badge-danger { background-color: #C62828 !important; color: #fff; }
    .badge-secondary { background-color: #6C757D !important; color: #fff; }

    .btn-outline-primary {
        color: #2C8F0C;
        border-color: #2C8F0C;
    }

    .btn-outline-primary:hover {
        background-color: #2C8F0C;
        color: #fff;
    }

    .pagination .page-item.active .page-link {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
    }

    .pagination .page-link {
        color: #2C8F0C;
    }

    .pagination .page-link:hover {
        background-color: #E8F5E6;
    }
</style>

<!-- 🌿 Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Order Management</h1>
        <p class="text-muted mb-0">View and manage customer orders efficiently.</p>
    </div>
</div>

<!-- 🌿 Filters and Search -->
<div class="card card-custom mb-4">
    <div class="card-header card-header-custom">
        <i class="fas fa-filter me-2"></i> Filters & Search
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}">
            <div class="row align-items-end">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="search" class="form-label fw-bold">Search Orders</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Search by order number, customer name, email, or phone...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Filter by Status</label>
                        <select class="form-select" id="status" name="status">
                            @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status', 'active') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Apply
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- 🌿 Orders Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <i class="fas fa-shopping-cart me-2"></i> Orders List
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
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
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->customer_email }}</td>
                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ 
                                $order->order_status == 'cancelled' ? 'danger' :
                                ($order->order_status == 'completed' ? 'success' :
                                ($order->order_status == 'delivered' ? 'success' :
                                ($order->order_status == 'shipped' ? 'info' :
                                ($order->order_status == 'processing' ? 'primary' :
                                ($order->order_status == 'confirmed' ? 'secondary' : 'warning'))))) 
                            }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                            @if($order->order_status == 'cancelled' && $order->cancellation_reason)
                            <br>
                            <small class="text-muted">Reason: {{ Str::limit($order->cancellation_reason, 30) }}</small>
                            @endif
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

        <div class="d-flex justify-content-center mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
