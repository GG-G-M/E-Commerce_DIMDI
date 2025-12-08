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

    /* Button Styles - Consistent */
    .btn-success-custom {
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
    }
    
    .btn-success-custom:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
        color: white;
    }

    .btn-outline-success-custom {
        background: white;
        border: 2px solid #2C8F0C;
        color: #2C8F0C;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-success-custom:hover {
        background: #2C8F0C;
        color: white;
        transform: translateY(-2px);
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

    /* Badges - Compact */
    .badge-text {
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        display: inline-block;
        text-align: center;
        min-width: 100px;
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
        min-width: 70px;
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
    
    .action-btn {
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
    
    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    
    .btn-view {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-view:hover {
        background-color: #2C8F0C;
        color: white;
    }
    
    .btn-edit {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-edit:hover {
        background-color: #2C8F0C;
        color: white;
    }
    
    .btn-delete {
        background-color: white;
        border-color: #C62828;
        color: #C62828;
    }
    
    .btn-delete:hover {
        background-color: #C62828;
        color: white;
    }

    /* Statistics Cards */
    .stats-card {
        background: linear-gradient(135deg, #E8F5E6, #F8FDF8);
        border: none;
        border-radius: 10px;
        padding: 1.25rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .stats-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2C8F0C;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .stats-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
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
    .id-col { width: 70px; min-width: 70px; }
    .name-col { width: 180px; min-width: 180px; }
    .email-col { width: 200px; min-width: 200px; }
    .role-col { width: 120px; min-width: 120px; }
    .phone-col { width: 120px; min-width: 120px; }
    .status-col { width: 100px; min-width: 100px; }
    .date-col { width: 100px; min-width: 100px; }
    .action-col { width: 120px; min-width: 120px; }

    /* User Info */
    .user-name {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
        line-height: 1.2;
    }
    
    .you-badge {
        background-color: #D1ECF1;
        color: #0C5460;
        border: 1px solid #BEE5EB;
        padding: 0.1rem 0.4rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-left: 0.25rem;
    }

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

    /* Phone Text */
    .phone-text {
        font-size: 0.85rem;
        color: #495057;
    }

    /* Date Styling */
    .date-text {
        font-size: 0.85rem;
        color: #6c757d;
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

    /* Card body padding fix */
    .card-body {
        padding: 0 !important;
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

    /* Form Styling */
    .form-label {
        font-weight: 600;
        color: #2C8F0C;
        font-size: 0.9rem;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
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
        
        .badge-text {
            min-width: 80px;
            font-size: 0.7rem;
        }
        
        .status-text {
            min-width: 60px;
            font-size: 0.7rem;
        }
        
        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }
        
        .user-name {
            font-size: 0.8rem;
        }
        
        .btn-outline-success-custom,
        .btn-success-custom {
            padding: 0.4rem 0.7rem;
            font-size: 0.8rem;
        }
        
        .stats-card {
            padding: 1rem;
        }
        
        .stats-number {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Dashboard Header -->
<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">Users Management</h1>
            <p class="mb-0 text-muted">Manage all system users including admins, staff, and customers</p>
        </div>
        <div class="text-end">
            <small class="text-muted fw-bold">Total Users: {{ $totalUsers }}</small>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="filter-card">
    <div class="card-body">
        <form method="GET" class="row g-2" id="filterForm">
            <div class="col-md-3">
                <div class="mb-2">
                    <label class="form-label fw-bold">Search Users</label>
                    <input type="text" name="search" class="form-control search-box" 
                           placeholder="Search by name or email..." 
                           value="{{ request('search') }}" id="searchInput">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-2">
                    <label class="form-label fw-bold">Filter by Role</label>
                    <select name="role" class="form-select search-box" id="roleSelect">
                        <option value="">All Roles</option>
                        @foreach($roles as $key => $label)
                            <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-2">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select search-box" id="statusSelect">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-2">
                    <label class="form-label fw-bold">Sort By</label>
                    <select name="sort" class="form-select search-box" id="sortSelect">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="mb-2 w-100">
                    @if(request()->hasAny(['search', 'role', 'status', 'sort']))
                        <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-success-custom w-100">
                            <i class="fas fa-times me-1"></i> Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">All System Users</h5>
        <div class="header-buttons">
            <a href="{{ route('superadmin.users.create') }}" class="btn btn-success-custom">
                <i class="fas fa-user-plus"></i> New User
            </a>
        </div>
    </div>
    
    <div class="card-body p-0">
        @if($users->count() > 0)
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
                            <th class="name-col">Name</th>
                            <th class="email-col">Email</th>
                            <th class="role-col">Role</th>
                            <th class="phone-col">Phone</th>
                            <th class="status-col">Status</th>
                            <th class="date-col">Created</th>
                            <th class="action-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="id-col">
                                <span>#{{ $user->id }}</span>
                            </td>
                            <td class="name-col">
                                <div class="user-name">{{ $user->name }}</div>
                                @if($user->id === auth()->id())
                                    <span class="you-badge">You</span>
                                @endif
                            </td>
                            <td class="email-col">
                                <a href="mailto:{{ $user->email }}" class="email-link">
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td class="role-col">
                                @if($user->role == 'super_admin')
                                    <span class="badge-text badge-super-admin">Super Admin</span>
                                @elseif($user->role == 'admin')
                                    <span class="badge-text badge-admin">Admin</span>
                                @elseif($user->role == 'delivery')
                                    <span class="badge-text badge-delivery">Delivery</span>
                                @elseif($user->role == 'stock_checker' || $user->role == 'checker')
                                    <span class="badge-text badge-checker">Checker</span>
                                @else
                                    <span class="badge-text badge-customer">Customer</span>
                                @endif
                            </td>
                            <td class="phone-col">
                                @if($user->phone)
                                    <span class="phone-text">{{ $user->phone }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="status-col">
                                @if($user->is_active)
                                    <span class="status-text status-active">Active</span>
                                @else
                                    <span class="status-text status-inactive">Inactive</span>
                                @endif
                            </td>
                            <td class="date-col">
                                <span class="date-text">{{ $user->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="action-col">
                                <div class="action-buttons">
                                    <a href="{{ route('superadmin.users.show', $user) }}" 
                                       class="action-btn btn-view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('superadmin.users.edit', $user) }}" 
                                       class="action-btn btn-edit" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
                                    <form action="{{ route('superadmin.users.destroy', $user) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="action-btn btn-delete"
                                                onclick="return confirm('Are you sure you want to delete this user?')"
                                                title="Delete User">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="d-flex justify-content-between align-items-center p-3">
                <div>
                    <small class="text-muted">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                    </small>
                </div>
                <div>
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-users"></i>
                <h5 class="text-muted">No Users Found</h5>
                <p class="text-muted mb-4">No users match your search criteria</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('superadmin.users.index') }}" class="btn btn-success-custom">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                    <a href="{{ route('superadmin.users.create') }}" class="btn btn-outline-success-custom">
                        <i class="fas fa-user-plus me-1"></i> Create User
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Statistics -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $totalUsers }}</div>
            <div class="stats-label">Total Users</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $adminCount }}</div>
            <div class="stats-label">Admins</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $deliveryCount }}</div>
            <div class="stats-label">Delivery Staff</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-number">{{ $activeCount }}</div>
            <div class="stats-label">Active Users</div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const roleSelect = document.getElementById('roleSelect');
    const statusSelect = document.getElementById('statusSelect');
    const sortSelect = document.getElementById('sortSelect');

    let searchTimeout;

    // Auto-submit on search input with delay
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500);
    });

    // Auto-submit on select changes
    roleSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    statusSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    sortSelect.addEventListener('change', function() {
        filterForm.submit();
    });
});
</script>

@endsection