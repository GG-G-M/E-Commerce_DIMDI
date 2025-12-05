@extends('layouts.admin')

@section('content')
<style>
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
    }

    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
    }

    .btn-outline-success {
        border-color: #2C8F0C;
        color: #2C8F0C;
        font-weight: 500;
    }

    .btn-outline-success:hover {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
        color: white;
        transform: translateY(-2px);
    }

    .btn-outline-warning {
        border-color: #FBC02D;
        color: #FBC02D;
        font-weight: 500;
    }

    .btn-outline-warning:hover {
        background-color: #FBC02D;
        border-color: #FBC02D;
        color: white;
        transform: translateY(-2px);
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

    /* Modal Styles */
    .modal-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1.25rem;
    }

    .modal-header-custom .modal-title {
        font-weight: 700;
    }

    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .form-label {
        font-weight: 600;
        color: #2C8F0C;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #C8E6C9;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.15rem rgba(44,143,12,0.2);
    }

    .badge-active {
        background-color: #E8F5E6;
        color: #2C8F0C;
        padding: 0.35em 0.65em;
        border-radius: 0.25rem;
        font-size: 0.75em;
        font-weight: 600;
    }

    .badge-archived {
        background-color: #FFF3E0;
        color: #F57C00;
        padding: 0.35em 0.65em;
        border-radius: 0.25rem;
        font-size: 0.75em;
        font-weight: 600;
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

    .warehouse-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .tips-box {
        background-color: #F8FDF8;
        border-left: 4px solid #2C8F0C;
        border-radius: 8px;
        padding: 1rem;
        font-size: 0.9rem;
        color: #2C8F0C;
    }

    .tips-box i {
        color: #2C8F0C;
        margin-right: 5px;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #C8E6C9;
        margin-bottom: 1rem;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .warehouse-name-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
</style>

<!-- Filters -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.warehouses.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Warehouse</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Search by name...">
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
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
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

<!-- Warehouse Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Warehouse Management</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
            <i class="fas fa-plus me-2"></i> Add Warehouse
        </button>
    </div>
    <div class="card-body">
        @if($warehouses->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Warehouse Name</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($warehouses as $warehouse)
                        <tr>
                            <td class="fw-semibold">#{{ $warehouse->id }}</td>
                            <td>
                                <div class="warehouse-name-cell">
                                    <div class="warehouse-icon">
                                        <i class="fas fa-warehouse"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $warehouse->name }}</strong>
                                        @if($warehouse->description)
                                            <br>
                                            <small class="text-muted">{{ Str::limit($warehouse->description, 100) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="{{ $warehouse->is_archived ? 'badge-archived' : 'badge-active' }}">
                                    {{ $warehouse->is_archived ? 'Archived' : 'Active' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-success edit-warehouse-btn"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editWarehouseModal" 
                                        data-id="{{ $warehouse->id }}"
                                        data-name="{{ $warehouse->name }}"
                                        data-description="{{ $warehouse->description }}"
                                        data-is_archived="{{ $warehouse->is_archived }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    @if (!$warehouse->is_archived)
                                        <button class="btn btn-sm btn-outline-warning toggle-status-btn" 
                                            data-id="{{ $warehouse->id }}" data-action="archive">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-success toggle-status-btn" 
                                            data-id="{{ $warehouse->id }}" data-action="unarchive">
                                            <i class="fas fa-box-open"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $warehouses->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-warehouse"></i>
                <h5 class="text-muted">No Warehouses Found</h5>
                <p class="text-muted mb-4">Add your first warehouse to get started</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
                    <i class="fas fa-plus me-2"></i> Add First Warehouse
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Warehouse Modal -->
<div class="modal fade" id="addWarehouseModal" tabindex="-1" aria-labelledby="addWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="addWarehouseModalLabel">
                    <i class="fas fa-plus-circle me-2"></i> Add New Warehouse
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addWarehouseForm" action="{{ route('admin.warehouses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_name" class="form-label">Warehouse Name *</label>
                        <input type="text" id="add_name" name="name" class="form-control" 
                               placeholder="Enter warehouse name" required>
                    </div>

                    <div class="mb-3">
                        <label for="add_description" class="form-label">Description</label>
                        <textarea id="add_description" name="description" class="form-control" rows="3"
                                  placeholder="Enter warehouse description (optional)"></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_address" class="form-label">Address</label>
                                <input type="text" id="add_address" name="address" class="form-control" 
                                       placeholder="Enter warehouse address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_contact" class="form-label">Contact Number</label>
                                <input type="text" id="add_contact" name="contact" class="form-control" 
                                       placeholder="Enter contact number">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" 
                                   id="add_is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="add_is_active">Active Warehouse</label>
                        </div>
                    </div>

                    <div class="tips-box mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Tips:</strong> Warehouses help organize your inventory. Give each warehouse a clear, descriptive name for easy identification.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Warehouse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Warehouse Modal -->
<div class="modal fade" id="editWarehouseModal" tabindex="-1" aria-labelledby="editWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="editWarehouseModalLabel">
                    <i class="fas fa-edit me-2"></i> Edit Warehouse
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editWarehouseForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Warehouse Name *</label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea id="edit_description" name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_address" class="form-label">Address</label>
                                <input type="text" id="edit_address" name="address" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_contact" class="form-label">Contact Number</label>
                                <input type="text" id="edit_contact" name="contact" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" 
                                   id="edit_is_active" name="is_active" value="1">
                            <label class="form-check-label" for="edit_is_active">Active Warehouse</label>
                        </div>
                    </div>

                    <div class="tips-box mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Note:</strong> Archiving a warehouse will hide it from selection but won't delete its inventory records.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Warehouse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const perPageSelect = document.getElementById('per_page');
    const searchLoading = document.getElementById('searchLoading');
    
    let searchTimeout;

    // Auto-submit search with delay
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchLoading.style.display = 'block';
        
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 800);
    });

    // Auto-submit status filter immediately
    statusSelect.addEventListener('change', function() {
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

    // Edit warehouse modal handling
    const editWarehouseButtons = document.querySelectorAll('.edit-warehouse-btn');
    const editWarehouseForm = document.getElementById('editWarehouseForm');
    const editIdInput = editWarehouseForm.querySelector('[name="id"]');
    const editNameInput = document.getElementById('edit_name');
    const editDescriptionInput = document.getElementById('edit_description');
    const editAddressInput = document.getElementById('edit_address');
    const editContactInput = document.getElementById('edit_contact');
    const editIsActiveInput = document.getElementById('edit_is_active');

    editWarehouseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const description = this.getAttribute('data-description');
            const isArchived = this.getAttribute('data-is_archived') === '1';

            // Set form action URL
            editWarehouseForm.action = `/admin/warehouses/${id}`;
            
            // Populate form fields
            editIdInput.value = id;
            editNameInput.value = name;
            editDescriptionInput.value = description || '';
            editIsActiveInput.checked = !isArchived;

            // Note: You might need to fetch additional data like address and contact
            // if they exist in your database
        });
    });

    // ADD WAREHOUSE
    document.getElementById('addWarehouseForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: new FormData(form)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addWarehouseModal'));
                modal.hide();
                // Reset form
                form.reset();
                // Reload page to show new warehouse
                location.reload();
            } else {
                alert('Error: Could not add warehouse');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            alert('Network error. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // UPDATE WAREHOUSE
    document.getElementById('editWarehouseForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: new FormData(form)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editWarehouseModal'));
                modal.hide();
                // Reload page to show updated warehouse
                location.reload();
            } else {
                alert('Error updating warehouse');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            alert('Network error. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // ARCHIVE / UNARCHIVE
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const action = btn.getAttribute('data-action');
            const actionText = action === 'archive' ? 'archive' : 'activate';
            
            if (!confirm(`Are you sure you want to ${actionText} this warehouse?`)) {
                return;
            }
            
            const id = btn.getAttribute('data-id');
            const originalText = btn.innerHTML;
            
            // Show loading on button
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            try {
                const response = await fetch(`/admin/warehouses/${id}/${action}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    location.reload();
                } else {
                    alert('Failed to update status');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                alert('Network error. Please try again.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
    });

    // Form validation
    const addForm = document.getElementById('addWarehouseForm');
    const editForm = document.getElementById('editWarehouseForm');
    
    [addForm, editForm].forEach(form => {
        if (form) {
            form.addEventListener('submit', function(e) {
                const nameInput = form.querySelector('[name="name"]');
                
                if (!nameInput.value.trim()) {
                    e.preventDefault();
                    alert('Warehouse name is required.');
                    nameInput.focus();
                    return false;
                }
                
                return true;
            });
        }
    });

    // Clear add form when modal closes
    const addModal = document.getElementById('addWarehouseModal');
    addModal.addEventListener('hidden.bs.modal', function () {
        const form = this.querySelector('form');
        if (form) {
            form.reset();
        }
    });
});
</script>
@endpush
@endsection