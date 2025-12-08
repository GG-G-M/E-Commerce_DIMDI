@extends('layouts.admin')

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

    /* Improved Add Button */
    .btn-add-warehouse {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(44, 143, 12, 0.2);
        height: 46px;
    }
    
    .btn-add-warehouse:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
    }
    
    .btn-add-warehouse:active {
        transform: translateY(0);
    }

    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    }

    /* Enhanced Action Buttons - Consistent with other pages */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: nowrap;
        justify-content: center;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        border: 2px solid;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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
    
    .btn-archive {
        background-color: white;
        border-color: #FBC02D;
        color: #FBC02D;
    }
    
    .btn-archive:hover {
        background-color: #FBC02D;
        color: white;
    }
    
    .btn-unarchive {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-unarchive:hover {
        background-color: #2C8F0C;
        color: white;
    }

    /* Table Styling - Consistent */
    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 1rem 0.75rem;
        white-space: nowrap;
    }

    .table td {
        padding: 1rem 0.75rem;
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

    /* Status styling - Consistent with customer/supplier pages */
    .status-text {
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-text-active {
        color: #2C8F0C;
    }
    
    .status-text-active::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #2C8F0C;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }

    .status-badge-archived {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 80px;
        background-color: #FFF3CD;
        color: #856404;
        border: 1px solid #FFEAA7;
    }

    /* Modal Styling - Consistent */
    .modal-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1.25rem;
    }

    .modal-title {
        font-weight: 700;
    }

    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    /* Form Styling */
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

    /* Filter Section - Consistent */
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

    /* Warehouse Icon */
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

    /* Tips Box */
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

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #C8E6C9;
        margin-bottom: 1rem;
    }

    /* Table Container for consistency */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }
    
    /* Responsive adjustments */
    @media (min-width: 1200px) {
        .table-container {
            overflow-x: visible;
        }
        
        .table {
            table-layout: fixed;
        }
    }

    /* Column widths for consistency */
    .id-col { min-width: 80px; width: 80px; }
    .name-col { min-width: 250px; width: 300px; }
    .status-col { min-width: 100px; width: 120px; }
    .action-col { 
        min-width: 120px; 
        width: 140px; 
        /* ADD THIS LINE for explicit cell content centering */
        text-align: center; 
    }
    /* Warehouse Info Cell */
    .warehouse-info-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .warehouse-name {
        font-weight: 600;
        color: #333;
    }
    
    .warehouse-description {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 4px;
    }

    /* Pagination styling - Consistent */
    .pagination .page-item .page-link {
        color: #2C8F0C;
        border: 1px solid #dee2e6;
        margin: 0 2px;
        border-radius: 6px;
        transition: all 0.3s ease;
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

    /* Remove old badge styles */
    .badge-active,
    .badge-archived {
        display: none;
    }

</style>

<!-- Filters and Search - Consistent -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.warehouses.index') }}" id="filterForm">
            <div class="row">
                <!-- Search by Name -->
                <div class="col-md-5">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Warehouses</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Search by name or description...">
                        <div class="search-loading" id="searchLoading">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter by Status -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Filter by Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                </div>

                <!-- Items per page selection -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="per_page" class="form-label fw-bold">Items per page</label>
                        <select class="form-select" id="per_page" name="per_page">
                            @foreach([2, 5, 10, 15, 25, 50] as $option)
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
        <button class="btn btn-add-warehouse" data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
            {{-- <i class="fas fa-user-plus"></i>  --}}
            Add Warehouse
        </button>
    </div>
    <div class="card-body p-0">
        @if($warehouses->count())
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
                            <th class="name-col">Warehouse</th>
                            <th class="status-col">Status</th>
                            <th class="action-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($warehouses as $warehouse)
                        <tr data-id="{{ $warehouse->id }}">
                            <td class="id-col">
                                <span class="text-muted">#{{ $warehouse->id }}</span>
                            </td>
                            <td class="name-col">
                                <div class="warehouse-info-cell">
                                    <div class="warehouse-icon">
                                        <i class="fas fa-warehouse"></i>
                                    </div>
                                    <div>
                                        <div class="warehouse-name">{{ $warehouse->name }}</div>
                                        @if($warehouse->description)
                                            <div class="warehouse-description" title="{{ $warehouse->description }}">
                                                {{ Str::limit($warehouse->description, 60) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="status-col">
                                @if ($warehouse->is_archived)
                                    <span class="status-badge-archived">Archived</span>
                                @else
                                    <span class="status-text status-text-active">Active</span>
                                @endif
                            </td>
                            <td class="action-col">
                                <div class="action-buttons">
                                    <button class="action-btn btn-edit edit-warehouse-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editWarehouseModal"
                                            data-warehouse='@json($warehouse)'>
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    @if (!$warehouse->is_archived)
                                        <button class="action-btn btn-archive archiveBtn" 
                                                data-id="{{ $warehouse->id }}" 
                                                title="Archive Warehouse">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    @else
                                        <button class="action-btn btn-unarchive unarchiveBtn" 
                                                data-id="{{ $warehouse->id }}" 
                                                title="Unarchive Warehouse">
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

            @if($warehouses->hasPages())
            <div class="d-flex justify-content-center p-4">
                {{ $warehouses->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-warehouse"></i>
                <h5 class="text-muted">No Warehouses Found</h5>
                <p class="text-muted mb-4">Add your first warehouse to get started</p>
                <button class="btn btn-add-warehouse" data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
                    <i class="fas fa-user-plus"></i> Add First Warehouse
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Warehouse Modal -->
<div class="modal fade" id="addWarehouseModal" tabindex="-1" aria-labelledby="addWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="addWarehouseForm" action="{{ route('admin.warehouses.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addWarehouseModalLabel">Add New Warehouse</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="add_name" class="form-label">Warehouse Name *</label>
                            <input type="text" id="add_name" name="name" class="form-control" 
                                   placeholder="Enter warehouse name" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="add_description" class="form-label">Description</label>
                            <textarea id="add_description" name="description" class="form-control" rows="3"
                                      placeholder="Enter warehouse description (optional)"></textarea>
                        </div>
                    </div>

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

                    <div class="col-md-12">
                        <div class="tips-box mt-3">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Tips:</strong> Warehouses help organize your inventory. Give each warehouse a clear, descriptive name for easy identification.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Warehouse</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Warehouse Modal -->
<div class="modal fade" id="editWarehouseModal" tabindex="-1" aria-labelledby="editWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editWarehouseForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editWarehouseModalLabel">Edit Warehouse</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Warehouse Name *</label>
                            <input type="text" id="edit_name" name="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea id="edit_description" name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

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

                    <div class="col-md-12">
                        <div class="tips-box mt-3">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Note:</strong> Archiving a warehouse will hide it from selection but won't delete its inventory records.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Warehouse</button>
                </div>
            </div>
        </form>
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

    /* === Add Warehouse === */
    document.getElementById('addWarehouseForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Close modal and reload
                const modal = bootstrap.Modal.getInstance(document.getElementById('addWarehouseModal'));
                modal.hide();
                location.reload();
            } else {
                alert('Error adding warehouse: ' + (data.message || 'Unknown error'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    /* === Edit Warehouse === */
    document.querySelectorAll('.edit-warehouse-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const warehouse = JSON.parse(this.dataset.warehouse);
            const form = document.getElementById('editWarehouseForm');
            
            // Set form action
            form.action = `/admin/warehouses/${warehouse.id}`;
            
            // Fill form fields
            document.getElementById('edit_name').value = warehouse.name || '';
            document.getElementById('edit_description').value = warehouse.description || '';
            document.getElementById('edit_address').value = warehouse.address || '';
            document.getElementById('edit_contact').value = warehouse.contact || '';
        });
    });

    document.getElementById('editWarehouseForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';

        fetch(form.action, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'X-HTTP-Method-Override': 'PUT' 
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Close modal and reload
                const modal = bootstrap.Modal.getInstance(document.getElementById('editWarehouseModal'));
                modal.hide();
                location.reload();
            } else {
                alert('Error updating warehouse: ' + (data.message || 'Unknown error'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    /* === Archive Warehouse === */
    document.querySelectorAll('.archiveBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Are you sure you want to archive this warehouse? This will make it inactive but preserve its data.')) return;
            
            const id = this.dataset.id;
            const button = this;

            // Disable button during processing
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/admin/warehouses/${id}/archive`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to archive warehouse: ' + (data.message || 'Unknown error'));
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-archive"></i>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-archive"></i>';
            });
        });
    });

    /* === Unarchive Warehouse === */
    document.querySelectorAll('.unarchiveBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Are you sure you want to unarchive this warehouse? It will become active again.')) return;
            
            const id = this.dataset.id;
            const button = this;

            // Disable button during processing
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/admin/warehouses/${id}/unarchive`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to unarchive warehouse: ' + (data.message || 'Unknown error'));
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-box-open"></i>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-box-open"></i>';
            });
        });
    });

    // Fix table layout on window resize
    window.addEventListener('resize', function() {
        const tableContainer = document.querySelector('.table-container');
        const table = document.querySelector('.table');
        
        // Only apply horizontal scroll on mobile
        if (window.innerWidth < 1200) {
            tableContainer.style.overflowX = 'auto';
            table.style.tableLayout = 'auto';
        } else {
            tableContainer.style.overflowX = 'visible';
            table.style.tableLayout = 'fixed';
        }
    });
});
</script>
@endpush
@endsection