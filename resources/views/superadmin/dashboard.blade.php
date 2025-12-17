@extends('layouts.superadmin')

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

    /* Welcome Header */
    .welcome-card {
        background: linear-gradient(135deg, #1A5D1A, #2C8F0C);
        border: none;
        border-radius: 12px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(26, 93, 26, 0.2);
        position: relative;
        overflow: hidden;
    }

    .welcome-card h4 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
    }

    .welcome-card p {
        opacity: 0.9;
        max-width: 600px;
        margin-bottom: 0;
    }

    .welcome-icon {
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3rem;
        opacity: 0.2;
    }

    /* Stats Cards - Compact */
    .stats-card {
        border: none;
        border-radius: 10px;
        padding: 1rem;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        height: 100%;
        position: relative;
        overflow: hidden;
        min-height: 120px;
    }

    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .stats-card::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.1);
        border-radius: 0 0 0 50px;
    }

    .bg-super-green { background: linear-gradient(135deg, #2C8F0C, #4CAF50); }
    .bg-success-green { background: linear-gradient(135deg, #28a745, #2C8F0C); }
    .bg-primary-green { background: linear-gradient(135deg, #1A5D1A, #2C8F0C); }
    .bg-info-green { background: linear-gradient(135deg, #17a2b8, #6f42c1); }

    .stats-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 2rem;
        opacity: 0.3;
    }

    .stats-content h6 {
        font-weight: 500;
        opacity: 0.9;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-content h2 {
        font-weight: 700;
        font-size: 1.75rem;
        margin: 0;
        line-height: 1;
    }

    /* Quick Actions */
    .actions-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }

    .action-btn {
        border: none;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        height: 100%;
        min-height: 80px;
        font-size: 0.9rem;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        color: white;
        text-decoration: none;
        filter: brightness(110%);
    }

    .btn-create { background: linear-gradient(135deg, #2C8F0C, #4CAF50); }
    .btn-view { background: linear-gradient(135deg, #28a745, #2C8F0C); }
    .btn-admin { background: linear-gradient(135deg, #FBC02D, #FFB300); }
    .btn-store { background: linear-gradient(135deg, #17a2b8, #6f42c1); }

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
<<<<<<< HEAD
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #F8FDF8;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        justify-content: center;
    }
    
    .action-btn-small {
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
    }
    
    .action-btn-small:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    
    .btn-view-small {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-view-small:hover {
        background-color: #2C8F0C;
        color: white;
    }
    
    .btn-edit-small {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-edit-small:hover {
        background-color: #2C8F0C;
        color: white;
    }
=======
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #F8FDF8;
    }

    /* Badges - Compact */
    .badge-text {
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        display: inline-block;
        text-align: center;
        min-width: 80px;
    }
    
    .badge-super-admin {
        background-color: #FFEBEE;
        color: #C62828;
        border: 1px solid #FFCDD2;
    }
    
    .badge-admin {
        background-color: #E8F5E6;
        color: #2C8F0C;
        border: 1px solid #C8E6C9;
    }
    
    .badge-delivery {
        background-color: #FFF3CD;
        color: #856404;
        border: 1px solid #FFEAA7;
    }
    
    .badge-checker {
        background-color: #E8F5E9;
        color: #2C8F0C;
        border: 1px solid #C8E6C9;
    }
    
    .badge-customer {
        background-color: #F8F9FA;
        color: #495057;
        border: 1px solid #E9ECEF;
    }

    /* Status Badges - Compact */
    .status-text {
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        display: inline-block;
        text-align: center;
        min-width: 60px;
    }
    
    .status-active {
        background-color: #E8F5E6;
        color: #2C8F0C;
        border: 1px solid #C8E6C9;
    }
    
    .status-inactive {
        background-color: #FFEBEE;
        color: #C62828;
        border: 1px solid #FFCDD2;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        justify-content: center;
    }
    
    .action-btn-small {
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
    }
    
    .action-btn-small:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    
    .btn-view-small {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-view-small:hover {
        background-color: #2C8F0C;
        color: white;
    }
    
    .btn-edit-small {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-edit-small:hover {
        background-color: #2C8F0C;
        color: white;
    }
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527

    /* View All Button */
    .view-all-btn {
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
        font-size: 0.9rem;
    }
    
    .view-all-btn:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
        color: white;
        text-decoration: none;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .stat-box {
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #E8F5E6;
        text-align: center;
        transition: all 0.3s ease;
        background: #F8FDF8;
    }

    .stat-box:hover {
        border-color: #2C8F0C;
        box-shadow: 0 4px 6px rgba(44, 143, 12, 0.1);
    }

    .stat-box h4 {
        font-weight: 700;
        margin-bottom: 0.25rem;
        font-size: 1.5rem;
    }

    .stat-box .text-green { color: #2C8F0C; }
    .stat-box .text-red { color: #C62828; }
    .stat-box .text-blue { color: #1A5D1A; }
    .stat-box .text-orange { color: #FBC02D; }

    .stat-box small {
        color: #6c757d;
        font-size: 0.85rem;
    }

    /* Table Container */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        max-width: 100%;
    }

    /* Column widths - More compact */
    .id-col { width: 70px; min-width: 70px; }
    .name-col { width: 150px; min-width: 150px; }
    .email-col { width: 180px; min-width: 180px; }
    .role-col { width: 120px; min-width: 120px; }
    .status-col { width: 100px; min-width: 100px; }
    .date-col { width: 100px; min-width: 100px; }
    .action-col { width: 100px; min-width: 100px; }

    /* Email Link */
    .email-link {
        color: #495057;
        text-decoration: none;
        font-size: 0.85rem;
    }

    .email-link:hover {
        color: #2C8F0C;
        text-decoration: underline;
    }

    /* List Group */
    .list-group-custom .list-group-item {
        border: none;
        border-bottom: 1px solid #e9ecef;
        padding: 0.75rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-group-custom .list-group-item:last-child {
        border-bottom: none;
    }

<<<<<<< HEAD
    /* Status Text Styles */
    .status-text {
=======
    .badge-count {
        background: #E8F5E6;
        color: #2C8F0C;
        border: 1px solid #C8E6C9;
        border-radius: 12px;
        padding: 0.25rem 0.5rem;
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
        font-weight: 600;
        font-size: 0.85rem;
    }
 

<<<<<<< HEAD
    /* Role Text Styles */
    .role-text {
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .role-super-admin {
        color: #9C27B0;
    }
    
    .role-admin {
        color: #2C8F0C;
    }
    
    .role-delivery {
        color: #FBC02D;
    }
    
    .role-checker {
        color: #1A5D1A;
    }
    
    .role-customer {
        color: #6c757d;
    }

=======
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
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

    /* Card body padding fix */
    .card-body {
        padding: 0 !important;
    }

    /* Make table more compact on mobile */
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
            font-size: 2rem;
        }
        
        .stats-content h2 {
            font-size: 1.5rem;
        }
        
        .stats-icon {
            font-size: 1.5rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .action-btn {
            min-height: 70px;
            font-size: 0.8rem;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
        }
        
        .action-btn-small {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
<<<<<<< HEAD
=======
        }
        
        .badge-text,
        .status-text {
            min-width: 70px;
            font-size: 0.7rem;
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
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

<div class="actions-card">
    <div class="card-body p-3">
        <div class="row g-2">
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
<div class="card card-custom mb-4">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Users</h5>
        <a href="{{ route('superadmin.users.index') }}" class="view-all-btn">
            <i class="fas fa-external-link-alt"></i> View All
        </a>
    </div>
    
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="id-col">ID</th>
                        <th class="name-col">Name</th>
                        <th class="email-col">Email</th>
                        <th class="role-col">Role</th>
                        <th class="status-col">Status</th>
                        <th class="date-col">Created</th>
                        <th class="action-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(App\Models\User::orderBy('created_at', 'desc')->take(10)->get() as $user)
                    <tr>
                        <td class="id-col">
                            <span>#{{ $user->id }}</span>
                        </td>
                        <td class="name-col">
                            <strong>{{ $user->name }}</strong>
                        </td>
                        <td class="email-col">
                            <a href="mailto:{{ $user->email }}" class="email-link">
                                {{ $user->email }}
                            </a>
                        </td>
                        <td class="role-col">
                            @if($user->role == 'super_admin')
<<<<<<< HEAD
                                <span class="role-text role-super-admin">Super Admin</span>
                            @elseif($user->role == 'admin')
                                <span class="role-text role-admin">Admin</span>
                            @elseif($user->role == 'delivery')
                                <span class="role-text role-delivery">Delivery</span>
                            @elseif($user->role == 'stock_checker' || $user->role == 'checker')
                                <span class="role-text role-checker">Checker</span>
                            @else
                                <span class="role-text role-customer">Customer</span>
=======
                                <span class="badge-text badge-super-admin">Super Admin</span>
                            @elseif($user->role == 'admin')
                                <span class="badge-text badge-admin">Admin</span>
                            @elseif($user->role == 'delivery')
                                <span class="badge-text badge-delivery">Delivery</span>
                            @elseif($user->role == 'stock_checker' || $user->role == 'checker')
                                <span class="badge-text badge-checker">Checker</span>
                            @else
                                <span class="badge-text badge-customer">Customer</span>
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
                            @endif
                        </td>
                        <td class="status-col">
                            @if($user->is_active)
<<<<<<< HEAD
                                <span >Active</span>
                            @else
                                <span >Inactive</span>
=======
                                <span class="status-text status-active">Active</span>
                            @else
                                <span class="status-text status-inactive">Inactive</span>
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
                            @endif
                        </td>
                        <td class="date-col">
                            <span>{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="action-col">
                            <div class="action-buttons">
                                <a href="{{ route('superadmin.users.show', $user) }}" 
                                   class="action-btn-small btn-view-small" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('superadmin.users.edit', $user) }}" 
                                   class="action-btn-small btn-edit-small" title="Edit User">
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
</div>

<!-- System Status -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">System Overview</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-custom">
                    <div class="list-group-item">
                        <span class="d-flex align-items-center">
                            <i class="fas fa-users me-3" style="color: #28a745;"></i>
                            <span>Total Customers</span>
                        </span>
                        <span class="role-text role-customer">
                            {{ App\Models\User::where('role', 'customer')->count() }}
                        </span>
                    </div>
                    <div class="list-group-item">
                        <span class="d-flex align-items-center">
                            <i class="fas fa-truck me-3" style="color: #FBC02D;"></i>
                            <span>Delivery Staff</span>
                        </span>
                        <span class="role-text role-delivery">
                            {{ App\Models\User::where('role', 'delivery')->count() }}
                        </span>
                    </div>
                    <div class="list-group-item">
                        <span class="d-flex align-items-center">
                            <i class="fas fa-clipboard-check me-3" style="color: #1A5D1A;"></i>
                            <span>Stock Checkers</span>
                        </span>
                        <span class="role-text role-checker">
                            {{ App\Models\User::where('role', 'stock_checker')->orWhere('role', 'checker')->count() }}
                        </span>
                    </div>
                    <div class="list-group-item">
                        <span class="d-flex align-items-center">
                            <i class="fas fa-box me-3" style="color: #17a2b8;"></i>
                            <span>Total Products</span>
                        </span>
                        <span class="role-text role-customer">
                            {{ App\Models\Product::count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">Quick Statistics</h5>
            </div>
            <div class="card-body p-3">
                <div class="stats-grid">
                    <div class="stat-box">
                        <h4 class="text-green">{{ App\Models\User::where('is_active', true)->count() }}</h4>
                        <small>Active Users</small>
                    </div>
                    <div class="stat-box">
                        <h4 class="text-red">{{ App\Models\User::where('is_active', false)->count() }}</h4>
                        <small>Inactive Users</small>
                    </div>
                    <div class="stat-box">
                        <h4 class="text-blue">{{ App\Models\Order::count() }}</h4>
                        <small>Total Orders</small>
                    </div>
                    <div class="stat-box">
                        <h4 class="text-orange">{{ App\Models\Product::where('stock_quantity', '<', 10)->count() }}</h4>
                        <small>Low Stock Items</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection