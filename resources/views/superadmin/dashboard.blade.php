@extends('layouts.superadmin')

@section('content')
<style>
    /* === Custom Green Theme Styles === */
    :root {
        --super-green: #1A5D1A;
        --light-green: #2C8F0C;
        --dark-green: #0D3B0D;
        --success-green: #28a745;
        --primary-green: #1A5D1A;
        --warning-green: #8F6C0C;
        --info-green: #0C8F6C;
        --text-dark: #212529;
        --text-muted: #6c757d;
        --bg-light: #f8fdf8;
        --border-color: #e2efe2;
    }

    /* Header Card */
    .welcome-card {
        background: linear-gradient(135deg, var(--dark-green), var(--super-green));
        border: none;
        border-radius: 16px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(26, 93, 26, 0.2);
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.2;
    }

    .welcome-card h4 {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .welcome-card p {
        opacity: 0.9;
        max-width: 600px;
    }

    .welcome-icon {
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        opacity: 0.2;
    }

    /* Stats Cards */
    .stats-card {
        border: none;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .stats-card::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.1);
        border-radius: 0 0 0 60px;
    }

    .stats-card.bg-super-green { background: linear-gradient(135deg, var(--super-green), #2E7D32); }
    .stats-card.bg-success-green { background: linear-gradient(135deg, var(--success-green), #4CAF50); }
    .stats-card.bg-primary-green { background: linear-gradient(135deg, var(--primary-green), #388E3C); }
    .stats-card.bg-info-green { background: linear-gradient(135deg, var(--info-green), #00BCD4); }

    .stats-icon {
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 2.5rem;
        opacity: 0.3;
    }

    .stats-content h6 {
        font-weight: 500;
        opacity: 0.9;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-content h2 {
        font-weight: 700;
        font-size: 2.5rem;
        margin: 0;
        line-height: 1;
    }

    /* Quick Actions */
    .actions-card {
        background: white;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .card-header-custom {
        background: white;
        border-bottom: 2px solid var(--border-color);
        padding: 1.25rem 1.5rem;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        color: var(--dark-green);
    }

    .action-btn {
        border: none;
        border-radius: 10px;
        padding: 1.25rem;
        text-align: center;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        height: 100%;
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        color: white;
        text-decoration: none;
        filter: brightness(110%);
    }

    .btn-create { background: linear-gradient(135deg, var(--super-green), var(--light-green)); }
    .btn-view { background: linear-gradient(135deg, var(--success-green), #4CAF50); }
    .btn-admin { background: linear-gradient(135deg, #8F6C0C, #FF9800); }
    .btn-store { background: linear-gradient(135deg, var(--info-green), #00BCD4); }

    /* Recent Users Table */
    .users-card {
        background: white;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        background: white;
        border-bottom: 2px solid var(--border-color);
    }

    .view-all-btn {
        background: var(--light-green);
        color: white;
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .view-all-btn:hover {
        background: var(--dark-green);
        color: white;
        text-decoration: none;
        filter: brightness(110%);
    }

    .table-custom {
        margin: 0;
    }

    .table-custom thead th {
        background: var(--bg-light);
        color: var(--dark-green);
        font-weight: 600;
        border-bottom: 2px solid var(--border-color);
        padding: 1rem 1.5rem;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .table-custom tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
    }

    .table-custom tbody tr:hover {
        background-color: var(--bg-light);
    }

    /* Badge Styles - Plain Text Version */
    .badge-text {
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 0.25rem 0;
    }

    .badge-super-admin { color: #dc3545; }
    .badge-admin { color: var(--primary-green); }
    .badge-delivery { color: var(--warning-green); }
    .badge-checker { color: #8B4513; }
    .badge-customer { color: var(--text-muted); }

    /* Status - Plain Text Version */
    .status-text {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.25rem 0;
    }

    .status-active {
        color: var(--success-green);
    }

    .status-inactive {
        color: #dc3545;
    }

    /* System Status Cards */
    .system-card {
        background: white;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        height: 100%;
        border: 1px solid var(--border-color);
    }

    .list-group-custom .list-group-item {
        border: none;
        border-bottom: 1px solid var(--border-color);
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-group-custom .list-group-item:last-child {
        border-bottom: none;
    }

    .list-group-custom .list-group-item i {
        width: 24px;
        text-align: center;
    }

    .badge-count {
        background: var(--bg-light);
        color: var(--text-dark);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 0.35rem 0.75rem;
        font-weight: 600;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .stat-box {
        padding: 1.25rem;
        border-radius: 12px;
        border: 2px solid var(--border-color);
        text-align: center;
        transition: all 0.3s ease;
        background: var(--bg-light);
    }

    .stat-box:hover {
        border-color: var(--light-green);
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.1);
    }

    .stat-box h4 {
        font-weight: 700;
        margin-bottom: 0.25rem;
        font-size: 1.75rem;
    }

    .stat-box .text-green { color: var(--light-green); }
    .stat-box .text-red { color: #dc3545; }
    .stat-box .text-blue { color: var(--primary-green); }
    .stat-box .text-orange { color: var(--warning-green); }

    .stat-box small {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    /* Action Buttons in Table */
    .btn-view-action {
        background: var(--light-green);
        color: white;
        border: none;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-view-action:hover {
        background: var(--dark-green);
        color: white;
    }

    .btn-edit-action {
        background: var(--warning-green);
        color: white;
        border: none;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-edit-action:hover {
        background: #7A5B0A;
        color: white;
    }

    /* Text Colors */
    .text-dark {
        color: var(--text-dark) !important;
    }

    .email-link {
        color: var(--text-dark) !important;
        text-decoration: none;
        font-weight: 500;
    }

    .email-link:hover {
        color: var(--light-green) !important;
        text-decoration: underline;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .welcome-card {
            padding: 1.5rem;
            text-align: center;
        }
        
        .welcome-icon {
            position: relative;
            right: auto;
            top: auto;
            transform: none;
            margin-top: 1rem;
            font-size: 3rem;
        }
        
        .stats-content h2 {
            font-size: 2rem;
        }
        
        .stats-icon {
            font-size: 2rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .table-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
        
        .view-all-btn {
            width: 100%;
        }
    }
</style>

<!-- Welcome Header -->
<div class="welcome-card">
    <div class="position-relative">
        <h4>
            <i class="fas fa-crown me-2"></i>Super Admin Dashboard
        </h4>
        <p class="mb-0">
            Welcome back! You have complete system oversight with full administrative privileges.
            Monitor user activity, manage roles, and maintain system integrity from this central hub.
        </p>
        <i class="fas fa-shield-alt welcome-icon"></i>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card bg-super-green">
            <div class="stats-content">
                <h6>Total Users</h6>
                <h2>{{ App\Models\User::count() }}</h2>
            </div>
            <i class="fas fa-users stats-icon"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card bg-success-green">
            <div class="stats-content">
                <h6>Super Admins</h6>
                <h2>{{ App\Models\User::where('role', 'super_admin')->count() }}</h2>
            </div>
            <i class="fas fa-crown stats-icon"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card bg-primary-green">
            <div class="stats-content">
                <h6>Administrators</h6>
                <h2>{{ App\Models\User::where('role', 'admin')->count() }}</h2>
            </div>
            <i class="fas fa-user-shield stats-icon"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card bg-info-green">
            <div class="stats-content">
                <h6>Active Users</h6>
                <h2>{{ App\Models\User::where('is_active', true)->count() }}</h2>
            </div>
            <i class="fas fa-user-check stats-icon"></i>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="actions-card">
    <div class="card-header-custom">
        <h5>Quick Actions</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('superadmin.users.create') }}" class="action-btn btn-create">
                    <i class="fas fa-user-plus"></i>
                    <span>Create Admin</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('superadmin.users.index') }}" class="action-btn btn-view">
                    <i class="fas fa-users"></i>
                    <span>View All Users</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.dashboard') }}" class="action-btn btn-admin">
                    <i class="fas fa-user-shield"></i>
                    <span>Admin Panel</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('home') }}" class="action-btn btn-store">
                    <i class="fas fa-store"></i>
                    <span>Visit Store</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users -->
<div class="users-card">
    <div class="table-header">
        <h5 class="mb-0">Recent Users</h5>
        <a href="{{ route('superadmin.users.index') }}" class="view-all-btn">
            <i class="fas fa-external-link-alt me-1"></i>View All
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-custom">
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
                    <td>
                        <span class="text-dark">#{{ $user->id }}</span>
                    </td>
                    <td>
                        <strong class="text-dark">{{ $user->name }}</strong>
                    </td>
                    <td>
                        <a href="mailto:{{ $user->email }}" class="email-link">
                            {{ $user->email }}
                        </a>
                    </td>
                    <td>
                        @if($user->role == 'super_admin')
                            <span class="badge-text badge-super-admin">Super Admin</span>
                        @elseif($user->role == 'admin')
                            <span class="badge-text badge-admin">Admin</span>
                        @elseif($user->role == 'delivery')
                            <span class="badge-text badge-delivery">Delivery</span>
                        @elseif($user->role == 'stock_checker' || $user->role == 'checker')
                            <span class="badge-text badge-checker">Stock Checker</span>
                        @else
                            <span class="badge-text badge-customer">Customer</span>
                        @endif
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="status-text status-active">Active</span>
                        @else
                            <span class="status-text status-inactive">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <small class="text-dark">{{ $user->created_at->format('M d, Y') }}</small>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-view-action" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-edit-action" title="Edit User">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- System Status -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="system-card">
            <div class="card-header-custom">
                <h5>System Overview</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-custom">
                    <div class="list-group-item">
                        <span class="d-flex align-items-center">
                            <i class="fas fa-users text-success-green me-3"></i>
                            <span class="text-dark">Total Customers</span>
                        </span>
                        <span class="badge-count">
                            {{ App\Models\User::where('role', 'customer')->count() }}
                        </span>
                    </div>
                    <div class="list-group-item">
                        <span class="d-flex align-items-center">
                            <i class="fas fa-truck text-warning-green me-3"></i>
                            <span class="text-dark">Delivery Staff</span>
                        </span>
                        <span class="badge-count">
                            {{ App\Models\User::where('role', 'delivery')->count() }}
                        </span>
                    </div>
                    <div class="list-group-item">
                        <span class="d-flex align-items-center">
                            <i class="fas fa-clipboard-check text-primary-green me-3"></i>
                            <span class="text-dark">Stock Checkers</span>
                        </span>
                        <span class="badge-count">
                            {{ App\Models\User::where('role', 'stock_checker')->orWhere('role', 'checker')->count() }}
                        </span>
                    </div>
                    <div class="list-group-item">
                        <span class="d-flex align-items-center">
                            <i class="fas fa-box text-info-green me-3"></i>
                            <span class="text-dark">Total Products</span>
                        </span>
                        <span class="badge-count">
                            {{ App\Models\Product::count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="system-card">
            <div class="card-header-custom">
                <h5>Quick Statistics</h5>
            </div>
            <div class="card-body">
                <div class="stats-grid">
                    <div class="stat-box">
                        <h4 class="text-green">{{ App\Models\User::where('is_active', true)->count() }}</h4>
                        <small class="text-dark">Active Users</small>
                    </div>
                    <div class="stat-box">
                        <h4 class="text-red">{{ App\Models\User::where('is_active', false)->count() }}</h4>
                        <small class="text-dark">Inactive Users</small>
                    </div>
                    <div class="stat-box">
                        <h4 class="text-blue">{{ App\Models\Order::count() }}</h4>
                        <small class="text-dark">Total Orders</small>
                    </div>
                    <div class="stat-box">
                        <h4 class="text-orange">{{ App\Models\Product::where('stock_quantity', '<', 10)->count() }}</h4>
                        <small class="text-dark">Low Stock Items</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection