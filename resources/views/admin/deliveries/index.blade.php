@extends('layouts.admin')

@section('title', 'Delivery Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Delivery Management</h1>
        <a href="{{ route('admin.deliveries.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add Delivery Person
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Delivery Personnel</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-dark">
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
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-truck text-white"></i>
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
                                <span class="badge bg-info text-dark">{{ $delivery->vehicle_type }}</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $delivery->is_active ? 'success' : 'danger' }}">
                                    {{ $delivery->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.deliveries.show', $delivery) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.deliveries.edit', $delivery) }}" 
                                       class="btn btn-primary btn-sm">
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
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No delivery personnel found.</p>
                                <a href="{{ route('admin.deliveries.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add First Delivery Person
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection