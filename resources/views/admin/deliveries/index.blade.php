@extends('layouts.admin')

@section('title', 'Delivery Management')

@section('content')
<style>
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-2px);
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
    }

    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-1px);
    }

    .btn-info {
        background: #17a2b8;
        border: none;
    }

    .btn-warning {
        background: #FBC02D;
        border: none;
        color: #fff;
    }

    .btn-danger {
        background: #C62828;
        border: none;
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 1rem;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    /* Status badges */
    .badge-success {
        background-color: #2C8F0C !important;
        color: #fff;
    }

    .badge-danger {
        background-color: #C62828 !important;
        color: #fff;
    }

    .badge-info {
        background-color: #2196F3 !important;
        color: #fff;
    }

    .badge-warning {
        background-color: #FF9800 !important;
        color: #000;
    }

    /* Button group styling */
    .btn-group .btn {
        margin-right: 0.25rem;
        border-radius: 6px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    /* Empty state styling */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #6c757d;
    }

    /* Delivery person avatar */
    .delivery-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .search-loading {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }

    .position-relative {
        position: relative;
    }

    /* Theme consistent buttons */
    .btn-outline-success {
        border-color: #2C8F0C;
        color: #2C8F0C;
    }

    .btn-outline-success:hover {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
        color: white;
    }

    .btn-outline-warning {
        border-color: #FBC02D;
        color: #FBC02D;
    }

    .btn-outline-warning:hover {
        background-color: #FBC02D;
        border-color: #FBC02D;
        color: white;
    }

    .btn-outline-danger {
        border-color: #C62828;
        color: #C62828;
    }

    .btn-outline-danger:hover {
        background-color: #C62828;
        border-color: #C62828;
        color: white;
    }
</style>

<!-- Filters -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.deliveries.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Delivery Personnel</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Search by name, email, or phone...">
                        <div class="search-loading" id="searchLoading">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Filter by Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="vehicle_type" class="form-label fw-bold">Vehicle Type</label>
                        <select class="form-select" id="vehicle_type" name="vehicle_type">
                            <option value="">All Vehicles</option>
                            <option value="motorcycle" {{ request('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                            <option value="car" {{ request('vehicle_type') == 'car' ? 'selected' : '' }}>Car</option>
                            <option value="van" {{ request('vehicle_type') == 'van' ? 'selected' : '' }}>Van</option>
                            <option value="truck" {{ request('vehicle_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="per_page" class="form-label fw-bold">Items per page</label>
                        <select class="form-select" id="per_page" name="per_page">
                            @foreach([5, 10, 15, 25, 50] as $option)
                                <option value="{{ $option }}" {{ request('per_page', 10) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delivery Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Delivery Personnel</h5>
        <a href="{{ route('admin.deliveries.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Delivery Person
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Vehicle Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $delivery)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="delivery-avatar">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="ms-3">
                                    <strong>{{ $delivery->name }}</strong>
                                    @if($delivery->vehicle_number)
                                    <br>
                                    <small class="text-muted">{{ $delivery->vehicle_number }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $delivery->email }}</td>
                        <td>{{ $delivery->phone }}</td>
                        <td>
                            <span class="badge badge-info">{{ $delivery->vehicle_type }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $delivery->is_active ? 'success' : 'danger' }}">
                                {{ $delivery->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.deliveries.show', $delivery) }}" 
                                   class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.deliveries.edit', $delivery) }}" 
                                   class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.deliveries.toggle-status', $delivery) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-{{ $delivery->is_active ? 'warning' : 'success' }} btn-sm">
                                        <i class="fas fa-{{ $delivery->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.deliveries.destroy', $delivery) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('Are you sure you want to delete this delivery person?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-truck"></i>
                                <h4 class="text-muted">No Delivery Personnel Found</h4>
                                <p class="text-muted mb-4">Get started by adding your first delivery person</p>
                                <a href="{{ route('admin.deliveries.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add First Delivery Person
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deliveries->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $deliveries->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- Custom Pagination Styling -->
<style>
    .pagination .page-item.active .page-link {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
        color: white;
    }

    .pagination .page-link {
        color: #2C8F0C;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
    }

    .pagination .page-link:hover {
        background-color: #E8F5E6;
        color: #1E6A08;
        border-color: #2C8F0C;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const vehicleTypeSelect = document.getElementById('vehicle_type');
    const perPageSelect = document.getElementById('per_page');
    const searchLoading = document.getElementById('searchLoading');
    
    let searchTimeout;

    // Auto-submit search with delay
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchLoading.style.display = 'block';
        
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 800); // 800ms delay after typing stops
    });

    // Auto-submit status filter immediately
    statusSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    // Auto-submit vehicle type filter immediately
    vehicleTypeSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    // Auto-submit per page selection immediately
    perPageSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    // Clear loading indicator when form submits
    filterForm.addEventListener('submit', function() {
        searchLoading.style.display = 'none';
    });
});
</script>
@endpush

@endsection