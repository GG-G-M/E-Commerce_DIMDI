@extends('layouts.superadmin')

@section('content')
<style>
    /* === Custom Styles === */
    :root {
        --super-green: #1A5D1A;
        --light-green: #2C8F0C;
        --dark-green: #0D3B0D;
        --border-color: #e9ecef;
        --header-green: #E8F5E9;
    }

    /* Page Header */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid var(--light-green);
    }

    .page-header h1 {
        color: var(--dark-green);
        font-weight: 700;
        margin: 0;
    }

    .btn-green {
        background: linear-gradient(135deg, var(--light-green), var(--super-green));
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-green:hover {
        background: linear-gradient(135deg, var(--dark-green), var(--super-green));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.2);
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }

    .form-control, .form-select {
        border: 2px solid var(--border-color);
        border-radius: 8px;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--light-green);
        box-shadow: 0 0 0 0.25rem rgba(44, 143, 12, 0.1);
    }

    /* Bulk Actions Card */
    .bulk-actions-card {
        background: #f8fdf8;
        border: 2px dashed var(--border-color);
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .btn-outline-green {
        color: var(--light-green);
        border: 2px solid var(--light-green);
        background: white;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-green:hover {
        background: var(--light-green);
        color: white;
        border-color: var(--light-green);
    }

    /* Table Styling */
    .table-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .table-custom {
        margin: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-custom thead th {
        background: var(--header-green);
        color: var(--dark-green);
        font-weight: 600;
        padding: 1rem;
        border-bottom: 2px solid #d4edda;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .table-custom tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        transition: background-color 0.2s ease;
    }

    .table-custom tbody tr:hover {
        background-color: #f8fdf8;
    }

    /* Checkbox styling */
    .checkbox-cell {
        width: 40px;
        text-align: center;
    }

    .user-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--light-green);
    }

    /* Role text */
    .role-text {
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .role-super-admin { color: #dc3545; }
    .role-admin { color: #007bff; }
    .role-delivery { color: #fd7e14; }
    .role-customer { color: #6c757d; }

    /* Status text */
    .status-text {
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .status-active { color: var(--light-green); }
    .status-inactive { color: #6c757d; }

    /* Action buttons - consistent styling */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: nowrap;
    }

    .btn-action {
        padding: 0.5rem;
        border-radius: 6px;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
    }

    /* Eye button with green outline and green icon */
    .btn-view {
        background: white;
        color: var(--light-green);
        border: 2px solid var(--light-green);
    }

    .btn-view:hover {
        background: var(--light-green);
        color: white;
        border-color: var(--light-green);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.2);
    }

    /* Edit button with grey outline */
    .btn-edit {
        background: white;
        color: #6c757d;
        border: 2px solid #dee2e6;
    }

    .btn-edit:hover {
        background: #f8f9fa;
        color: var(--dark-green);
        border-color: var(--light-green);
    }

    /* Delete button */
    .btn-delete {
        background: white;
        color: #dc3545;
        border: 2px solid #f5c6cb;
    }

    .btn-delete:hover {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-top: 2rem;
    }

    .stat-card {
        background: white;
        border: none;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border-top: 4px solid var(--light-green);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .stat-card h6 {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .stat-card h3 {
        color: var(--dark-green);
        font-weight: 700;
        margin: 0;
        font-size: 2rem;
    }

    /* Empty state */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--light-green);
    }

    /* Pagination */
    .pagination-custom .page-item .page-link {
        color: var(--light-green);
        border: 2px solid var(--border-color);
        margin: 0 2px;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 600;
    }
    
    .pagination-custom .page-item.active .page-link {
        background: linear-gradient(135deg, var(--light-green), var(--super-green));
        border-color: var(--light-green);
        color: white;
    }
    
    .pagination-custom .page-item:not(.disabled) .page-link:hover {
        background-color: #f8fdf8;
        border-color: var(--light-green);
        color: var(--light-green);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .table-custom {
            font-size: 0.9rem;
        }
        
        .action-buttons {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .table-custom thead th, 
        .table-custom tbody td {
            padding: 0.75rem 0.5rem;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-users me-2"></i>User Management
        </h1>
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-green">
            <i class="fas fa-user-plus me-1"></i> Create New User
        </a>
    </div>
</div>

<!-- Filters -->
<div class="filter-card">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold small text-muted">Search Users</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Search by name or email..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-muted">Filter by Role</label>
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold small text-muted">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold small text-muted">Sort By</label>
                <select name="sort" class="form-select">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                </select>
            </div>
            <div class="col-md-2 d-flex flex-column gap-2">
                <button type="submit" class="btn btn-green mt-auto">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                @if(request()->has('search') || request()->has('role') || request()->has('status') || request()->has('sort'))
                    <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-green">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions -->
@if($users->count() > 0)
<div class="bulk-actions-card">
    <div class="card-body">
        <form id="bulk-action-form" method="POST">
            @csrf
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted mb-2">Bulk Actions</label>
                    <div class="d-flex gap-2">
                        <select name="bulk_action" class="form-select" id="bulkActionSelect">
                            <option value="">Select Action</option>
                            <option value="activate">Activate Selected</option>
                            <option value="deactivate">Deactivate Selected</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                        <button type="button" class="btn btn-outline-green" id="applyBulkAction">
                            Apply
                        </button>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <span class="text-muted small">
                            <i class="fas fa-check-circle me-1"></i>
                            <span id="selectedCount">0</span> users selected
                        </span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="selectAll">
                                <i class="fas fa-check-double me-1"></i>Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                <i class="fas fa-times me-1"></i>Deselect All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Users Table -->
<div class="table-card">
    <div class="card-body p-0">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th class="checkbox-cell">
                            <input type="checkbox" id="selectAllCheckbox">
                        </th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="checkbox-cell">
                            <input type="checkbox" class="user-checkbox" name="user_ids[]" 
                                   value="{{ $user->id }}">
                        </td>
                        <td>
                            <span class="text-muted">#{{ $user->id }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-info ms-2 small">You</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                {{ $user->email }}
                            </a>
                        </td>
                        <td>
                            @if($user->role == 'super_admin')
                                <span class="role-text role-super-admin">Super Admin</span>
                            @elseif($user->role == 'admin')
                                <span class="role-text role-admin">Admin</span>
                            @elseif($user->role == 'delivery')
                                <span class="role-text role-delivery">Delivery</span>
                            @else
                                <span class="role-text role-customer">Customer</span>
                            @endif
                        </td>
                        <td>
                            @if($user->phone)
                                <span class="text-dark">{{ $user->phone }}</span>
                            @else
                                <span class="text-muted">-</span>
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
                            <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('superadmin.users.show', $user) }}" 
                                   class="btn-action btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('superadmin.users.edit', $user) }}" 
                                   class="btn-action btn-edit" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
                                <form action="{{ route('superadmin.users.destroy', $user) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-action btn-delete"
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

        <div class="d-flex justify-content-center p-4">
            <nav class="pagination-custom">
                {{ $users->links('pagination::bootstrap-5') }}
            </nav>
        </div>

        @else
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <h4 class="text-success">No Users Found</h4>
            <p class="text-muted mb-3">No users match your search criteria</p>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-green">
                <i class="fas fa-times me-1"></i> Clear Filters
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <h6>Total Users</h6>
        <h3>{{ $totalUsers }}</h3>
    </div>
    <div class="stat-card">
        <h6>Admins</h6>
        <h3>{{ $adminCount }}</h3>
    </div>
    <div class="stat-card">
        <h6>Delivery Staff</h6>
        <h3>{{ $deliveryCount }}</h3>
    </div>
    <div class="stat-card">
        <h6>Active Users</h6>
        <h3>{{ $activeCount }}</h3>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const selectAllBtn = document.getElementById('selectAll');
    const deselectAllBtn = document.getElementById('deselectAll');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkActionSelect = document.getElementById('bulkActionSelect');
    const applyBulkActionBtn = document.getElementById('applyBulkAction');

    function updateSelectedCount() {
        const selected = document.querySelectorAll('.user-checkbox:checked').length;
        selectedCountSpan.textContent = selected;
        
        // Update select all checkbox state
        selectAllCheckbox.checked = selected === userCheckboxes.length && selected > 0;
        selectAllCheckbox.indeterminate = selected > 0 && selected < userCheckboxes.length;
    }

    selectAllCheckbox.addEventListener('change', function() {
        userCheckboxes.forEach(cb => cb.checked = this.checked);
        updateSelectedCount();
    });

    selectAllBtn.addEventListener('click', function() {
        userCheckboxes.forEach(cb => cb.checked = true);
        updateSelectedCount();
    });

    deselectAllBtn.addEventListener('click', function() {
        userCheckboxes.forEach(cb => cb.checked = false);
        updateSelectedCount();
    });

    userCheckboxes.forEach(cb => cb.addEventListener('change', updateSelectedCount));

    applyBulkActionBtn.addEventListener('click', function() {
        const action = bulkActionSelect.value;
        const selectedIds = [...document.querySelectorAll('.user-checkbox:checked')].map(cb => cb.value);

        if (!action) {
            alert('Please select a bulk action');
            return;
        }
        
        if (selectedIds.length === 0) {
            alert('Please select at least one user');
            return;
        }

        let confirmMessage = '';
        if (action === 'delete') {
            confirmMessage = `Are you sure you want to delete ${selectedIds.length} user(s)? This action cannot be undone.`;
        } else if (action === 'activate') {
            confirmMessage = `Activate ${selectedIds.length} user(s)?`;
        } else if (action === 'deactivate') {
            confirmMessage = `Deactivate ${selectedIds.length} user(s)?`;
        }

        if (!confirm(confirmMessage)) return;

        const form = document.getElementById('bulk-action-form');
        const input = document.createElement('input');

        input.type = 'hidden';
        input.name = 'user_ids';
        input.value = JSON.stringify(selectedIds);

        form.appendChild(input);

        if (action === 'activate') form.action = '{{ route("superadmin.users.bulk-activate") }}';
        if (action === 'deactivate') form.action = '{{ route("superadmin.users.bulk-deactivate") }}';
        if (action === 'delete') form.action = '{{ route("superadmin.users.bulk-delete") }}';

        form.submit();
    });
});
</script>

@endsection