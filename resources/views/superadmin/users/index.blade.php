@extends('layouts.superadmin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-users text-green me-2"></i>User Management
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-green">
            <i class="fas fa-user-plus me-1"></i> Create New User
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search name/email" 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-control">
                    <option value="">All Roles</option>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-control">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-green w-100">Filter</button>
                @if(request()->has('search') || request()->has('role') || request()->has('status') || request()->has('sort'))
                    <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary w-100 mt-2">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions -->
@if($users->count() > 0)
<div class="card mb-4">
    <div class="card-body">
        <form id="bulk-action-form" method="POST">
            @csrf
            <div class="row align-items-center">
                <div class="col-md-3">
                    <select name="bulk_action" class="form-control" id="bulkActionSelect">
                        <option value="">Bulk Actions</option>
                        <option value="activate">Activate Selected</option>
                        <option value="deactivate">Deactivate Selected</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-green w-100" id="applyBulkAction">Apply</button>
                </div>
                <div class="col-md-7 text-end">
                    <span class="text-muted">Selected: <span id="selectedCount">0</span> users</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" id="selectAll">Select All</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">Deselect All</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Users Table -->
<div class="card">
    <div class="card-body">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">
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
                        <td>
                            <input type="checkbox" class="user-checkbox" name="user_ids[]" value="{{ $user->id }}" 
                                   data-user-id="{{ $user->id }}">
                        </td>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-green text-white me-2" style="width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-info ms-1">You</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'super_admin')
                                <span class="badge bg-danger"><i class="fas fa-crown me-1"></i> Super Admin</span>
                            @elseif($user->role == 'admin')
                                <span class="badge bg-primary"><i class="fas fa-user-shield me-1"></i> Admin</span>
                            @elseif($user->role == 'delivery')
                                <span class="badge bg-warning"><i class="fas fa-truck me-1"></i> Delivery</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-user me-1"></i> Customer</span>
                            @endif
                        </td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-info" 
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-warning"
                                   title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
                                <form action="{{ route('superadmin.users.destroy', $user) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
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
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $users->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h4>No users found</h4>
            <p class="text-muted">No users match your search criteria.</p>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-green">Clear Filters</a>
        </div>
        @endif
    </div>
</div>

<!-- Statistics -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-green">
            <div class="card-body text-center">
                <h6 class="card-title">Total Users</h6>
                <h3>{{ $totalUsers }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body text-center">
                <h6 class="card-title">Admins</h6>
                <h3>{{ $adminCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body text-center">
                <h6 class="card-title">Delivery Staff</h6>
                <h3>{{ $deliveryCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <h6 class="card-title">Active Users</h6>
                <h3>{{ $activeCount }}</h3>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select/Deselect all functionality
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const selectAllBtn = document.getElementById('selectAll');
    const deselectAllBtn = document.getElementById('deselectAll');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkActionSelect = document.getElementById('bulkActionSelect');
    const applyBulkActionBtn = document.getElementById('applyBulkAction');
    
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.user-checkbox:checked').length;
        selectedCountSpan.textContent = checked;
    }
    
    selectAllCheckbox.addEventListener('change', function() {
        userCheckboxes.forEach(cb => cb.checked = this.checked);
        updateSelectedCount();
    });
    
    selectAllBtn.addEventListener('click', function() {
        userCheckboxes.forEach(cb => cb.checked = true);
        selectAllCheckbox.checked = true;
        updateSelectedCount();
    });
    
    deselectAllBtn.addEventListener('click', function() {
        userCheckboxes.forEach(cb => cb.checked = false);
        selectAllCheckbox.checked = false;
        updateSelectedCount();
    });
    
    userCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });
    
    // Bulk actions
    applyBulkActionBtn.addEventListener('click', function() {
        const action = bulkActionSelect.value;
        if (!action) {
            alert('Please select a bulk action');
            return;
        }
        
        const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            alert('Please select at least one user');
            return;
        }
        
        if (action === 'delete') {
            if (!confirm(`Are you sure you want to delete ${selectedIds.length} user(s)? This action cannot be undone.`)) {
                return;
            }
        }
        
        // Create form and submit
        const form = document.getElementById('bulk-action-form');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'user_ids';
        input.value = JSON.stringify(selectedIds);
        form.appendChild(input);
        
        if (action === 'activate') {
            form.action = '{{ route("superadmin.users.bulk-activate") }}';
        } else if (action === 'deactivate') {
            form.action = '{{ route("superadmin.users.bulk-deactivate") }}';
        } else if (action === 'delete') {
            form.action = '{{ route("superadmin.users.bulk-delete") }}';
        }
        
        form.method = 'POST';
        form.submit();
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection