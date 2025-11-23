@extends('layouts.admin')

@section('title', 'Delivery Management')

@section('content')
<style>
    /* === Green Theme and Card Styling === */
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
        margin-bottom: 0;
    }

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
        padding: 1rem 1.5rem;
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
</style>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Delivery Management</h1>
        <p class="text-muted mb-0">Manage delivery personnel and their assignments</p>
    </div>
    <a href="{{ route('admin.deliveries.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Delivery Person
    </a>
</div>

<div class="card card-custom">
    <div class="card-header card-header-custom">
        <i class="fas fa-truck me-2"></i> Delivery Personnel
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
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.deliveries.edit', $delivery) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.deliveries.toggle-status', $delivery) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-{{ $delivery->is_active ? 'warning' : 'success' }} btn-sm">
                                        <i class="fas fa-{{ $delivery->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.deliveries.destroy', $delivery) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
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
@endsection