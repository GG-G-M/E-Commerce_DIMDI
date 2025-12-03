@extends('layouts.superadmin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card super-admin-card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="fas fa-crown text-green me-2"></i>Super Admin Dashboard
                </h4>
                <p class="text-muted">
                    Welcome to the Super Admin Dashboard. You have full system access to manage users, roles, and system configurations.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-green text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Users</h6>
                        <h2 class="mb-0">{{ App\Models\User::count() }}</h2>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Super Admins</h6>
                        <h2 class="mb-0">{{ App\Models\User::where('role', 'super_admin')->count() }}</h2>
                    </div>
                    <i class="fas fa-crown fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Admins</h6>
                        <h2 class="mb-0">{{ App\Models\User::where('role', 'admin')->count() }}</h2>
                    </div>
                    <i class="fas fa-user-shield fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Active Users</h6>
                        <h2 class="mb-0">{{ App\Models\User::where('is_active', true)->count() }}</h2>
                    </div>
                    <i class="fas fa-user-check fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('superadmin.users.create') }}" class="btn btn-green w-100">
                            <i class="fas fa-user-plus me-2"></i>Create Admin
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('superadmin.users.index') }}" class="btn btn-success w-100">
                            <i class="fas fa-users me-2"></i>View All Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning w-100">
                            <i class="fas fa-user-shield me-2"></i>Go to Admin Panel
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('home') }}" class="btn btn-info w-100">
                            <i class="fas fa-store me-2"></i>Visit Store
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Users</h5>
                <a href="{{ route('superadmin.users.index') }}" class="btn btn-sm btn-green">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\User::orderBy('created_at', 'desc')->take(10)->get() as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'super_admin')
                                        <span class="badge bg-danger">Super Admin</span>
                                    @elseif($user->role == 'admin')
                                        <span class="badge bg-primary">Admin</span>
                                    @elseif($user->role == 'delivery')
                                        <span class="badge bg-warning">Delivery</span>
                                    @else
                                        <span class="badge bg-secondary">Customer</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="status-active">Active</span>
                                    @else
                                        <span class="status-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Status -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">System Overview</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-users text-green me-2"></i>Total Customers</span>
                        <span class="badge bg-green rounded-pill">{{ App\Models\User::where('role', 'customer')->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-truck text-warning me-2"></i>Delivery Staff</span>
                        <span class="badge bg-warning rounded-pill">{{ App\Models\User::where('role', 'delivery')->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-user-check text-primary me-2"></i>Stock Checkers</span>
                        <span class="badge bg-primary rounded-pill">{{ App\Models\User::where('role', 'stock_checker')->orWhere('role', 'checker')->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-box text-info me-2"></i>Total Products</span>
                        <span class="badge bg-info rounded-pill">{{ App\Models\Product::count() }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Quick Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded">
                            <h4 class="text-green">{{ App\Models\User::where('is_active', true)->count() }}</h4>
                            <small class="text-muted">Active Users</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded">
                            <h4 class="text-danger">{{ App\Models\User::where('is_active', false)->count() }}</h4>
                            <small class="text-muted">Inactive Users</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded">
                            <h4 class="text-primary">{{ App\Models\Order::count() }}</h4>
                            <small class="text-muted">Total Orders</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded">
                            <h4 class="text-warning">{{ App\Models\Product::where('stock_quantity', '<', 10)->count() }}</h4>
                            <small class="text-muted">Low Stock Items</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection