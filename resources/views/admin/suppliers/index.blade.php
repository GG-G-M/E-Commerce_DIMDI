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

    .supplier-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
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

    .supplier-info-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    /* Status styling - Exactly like customer page */
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
</style>

<!-- Filters -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.suppliers.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Supplier</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Search by name, contact, or address...">
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

<!-- Supplier Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Supplier Management</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
            <i class="fas fa-plus me-2"></i> Add Supplier
        </button>
    </div>
    <div class="card-body">
        @if($suppliers->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Supplier</th>
                            <th>Contact</th>
                            <th>Contact Person</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($suppliers as $supplier)
                        <tr>
                            <td class="fw-semibold">#{{ $supplier->id }}</td>
                            <td>
                                <div class="supplier-info-cell">
                                    <div class="supplier-icon">
                                        {{ substr($supplier->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <strong>{{ $supplier->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($supplier->contact)
                                    <span class="text-dark">{{ $supplier->contact }}</span>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </td>
                            <td>
                                @if($supplier->contact_person)
                                    <span class="text-dark">{{ $supplier->contact_person }}</span>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </td>
                            <td>
                                @if($supplier->address)
                                    <small class="text-muted">{{ Str::limit($supplier->address, 50) }}</small>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </td>
                           <td class="status-col">
    @if ($supplier->is_archived)
        <span class="status-badge-archived">Archived</span>
    @else
        <span class="status-text status-text-active">Active</span>
    @endif
</td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-success edit-supplier-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editSupplierModal"
                                        data-id="{{ $supplier->id }}"
                                        data-name="{{ $supplier->name }}"
                                        data-contact="{{ $supplier->contact }}"
                                        data-contact_person="{{ $supplier->contact_person }}"
                                        data-address="{{ $supplier->address }}"
                                        data-is_archived="{{ $supplier->is_archived }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    @if (!$supplier->is_archived)
                                        <button class="btn btn-sm btn-outline-warning toggle-status-btn"
                                            data-id="{{ $supplier->id }}" data-action="archive">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-success toggle-status-btn"
                                            data-id="{{ $supplier->id }}" data-action="unarchive">
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

            <div class="mt-3 d-flex justify-content-center">
                {{ $suppliers->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-truck-loading"></i>
                <h5 class="text-muted">No Suppliers Found</h5>
                <p class="text-muted mb-4">Add your first supplier to get started</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                    <i class="fas fa-plus me-2"></i> Add First Supplier
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="addSupplierModalLabel">
                    <i class="fas fa-plus-circle me-2"></i> Add New Supplier
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSupplierForm" action="{{ route('admin.suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_name" class="form-label">Supplier Name *</label>
                        <input type="text" id="add_name" name="name" class="form-control" 
                               placeholder="Enter supplier company name" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_contact" class="form-label">Contact Number</label>
                                <input type="text" id="add_contact" name="contact" class="form-control" 
                                       placeholder="Enter contact number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_contact_person" class="form-label">Contact Person</label>
                                <input type="text" id="add_contact_person" name="contact_person" class="form-control" 
                                       placeholder="Enter contact person name">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="add_address" class="form-label">Address</label>
                        <textarea id="add_address" name="address" class="form-control" rows="3"
                                  placeholder="Enter complete address"></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" 
                                   id="add_is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="add_is_active">Active Supplier</label>
                        </div>
                    </div>

                    <div class="tips-box mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Tips:</strong> Make sure to provide accurate contact information for better communication with suppliers. Active suppliers will be available for purchase orders.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="editSupplierModalLabel">
                    <i class="fas fa-edit me-2"></i> Edit Supplier
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSupplierForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Supplier Name *</label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_contact" class="form-label">Contact Number</label>
                                <input type="text" id="edit_contact" name="contact" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_contact_person" class="form-label">Contact Person</label>
                                <input type="text" id="edit_contact_person" name="contact_person" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Address</label>
                        <textarea id="edit_address" name="address" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" 
                                   id="edit_is_active" name="is_active" value="1">
                            <label class="form-check-label" for="edit_is_active">Active Supplier</label>
                        </div>
                    </div>

                    <div class="tips-box mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Note:</strong> Archiving a supplier will hide it from selection but won't delete its purchase order history.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Supplier
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

    // Edit supplier modal handling
    const editSupplierButtons = document.querySelectorAll('.edit-supplier-btn');
    const editSupplierForm = document.getElementById('editSupplierForm');
    const editIdInput = editSupplierForm.querySelector('[name="id"]');
    const editNameInput = document.getElementById('edit_name');
    const editContactInput = document.getElementById('edit_contact');
    const editContactPersonInput = document.getElementById('edit_contact_person');
    const editAddressInput = document.getElementById('edit_address');
    const editIsActiveInput = document.getElementById('edit_is_active');

    editSupplierButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const contact = this.getAttribute('data-contact');
            const contactPerson = this.getAttribute('data-contact_person');
            const address = this.getAttribute('data-address');
            const isArchived = this.getAttribute('data-is_archived') === '1';

            // Set form action URL
            editSupplierForm.action = `/admin/suppliers/${id}`;
            
            // Populate form fields
            editIdInput.value = id;
            editNameInput.value = name;
            editContactInput.value = contact || '';
            editContactPersonInput.value = contactPerson || '';
            editAddressInput.value = address || '';
            editIsActiveInput.checked = !isArchived;
        });
    });

    // ADD SUPPLIER
    document.getElementById('addSupplierForm').addEventListener('submit', async function(e) {
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                modal.hide();
                // Reset form
                form.reset();
                // Reload page to show new supplier
                location.reload();
            } else {
                alert('Error: Could not add supplier');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            alert('Network error. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // UPDATE SUPPLIER
    document.getElementById('editSupplierForm').addEventListener('submit', async function(e) {
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('editSupplierModal'));
                modal.hide();
                // Reload page to show updated supplier
                location.reload();
            } else {
                alert('Error updating supplier');
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
            
            if (!confirm(`Are you sure you want to ${actionText} this supplier?`)) {
                return;
            }
            
            const id = btn.getAttribute('data-id');
            const originalText = btn.innerHTML;
            
            // Show loading on button
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            try {
                const response = await fetch(`/admin/suppliers/${id}/${action}`, {
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
    const addForm = document.getElementById('addSupplierForm');
    const editForm = document.getElementById('editSupplierForm');
    
    [addForm, editForm].forEach(form => {
        if (form) {
            form.addEventListener('submit', function(e) {
                const nameInput = form.querySelector('[name="name"]');
                
                if (!nameInput.value.trim()) {
                    e.preventDefault();
                    alert('Supplier name is required.');
                    nameInput.focus();
                    return false;
                }
                
                return true;
            });
        }
    });

    // Clear add form when modal closes
    const addModal = document.getElementById('addSupplierModal');
    addModal.addEventListener('hidden.bs.modal', function () {
        const form = this.querySelector('form');
        if (form) {
            form.reset();
        }
    });
});
</script>
@endpush

@endsection@extends('layouts.admin')

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

    /* Improved Add Button - Consistent with previous */
    .btn-add-supplier {
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
    
    .btn-add-supplier:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
    }
    
    .btn-add-supplier:active {
        transform: translateY(0);
    }

    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    }

    /* Enhanced Action Buttons - Consistent with customer page */
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

    /* Status styling - Consistent with customer page */
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

    /* Supplier Icon */
    .supplier-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
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
    .name-col { min-width: 180px; width: 200px; }
    .contact-col { min-width: 150px; width: 180px; }
    .person-col { min-width: 150px; width: 180px; }
    .address-col { min-width: 200px; max-width: 250px; width: 250px; }
    .status-col { min-width: 100px; width: 120px; }
    .action-col { min-width: 120px; width: 140px; }

    /* Supplier Info Cell */
    .supplier-info-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .supplier-name {
        font-weight: 600;
        color: #333;
    }
    
    .supplier-contact {
        color: #495057;
        font-size: 0.9rem;
    }
    
    .supplier-address {
        color: #6c757d;
        font-size: 0.85rem;
        max-width: 250px;
        word-break: break-word;
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
</style>

<!-- Filters and Search - Consistent -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.suppliers.index') }}" id="filterForm">
            <div class="row">
                <!-- Search by Name or Contact -->
                <div class="col-md-5">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Suppliers</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Search by name, contact, or address...">
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

<!-- Supplier Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Supplier Management</h5>
        <button class="btn btn-add-supplier" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
            <i class="fas fa-user-plus"></i> Add Supplier
        </button>
    </div>
    <div class="card-body p-0">
        @if($suppliers->count())
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
                            <th class="name-col">Supplier</th>
                            <th class="contact-col">Contact</th>
                            <th class="person-col">Contact Person</th>
                            <th class="address-col">Address</th>
                            <th class="status-col">Status</th>
                            <th class="action-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                        <tr data-id="{{ $supplier->id }}">
                            <td class="id-col">
                                <span class="text-muted">#{{ $supplier->id }}</span>
                            </td>
                            <td class="name-col">
                                <div class="supplier-info-cell">
                                    <div class="supplier-icon">
                                        {{ substr($supplier->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="supplier-name">{{ $supplier->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="contact-col">
                                @if($supplier->contact)
                                    <div class="supplier-contact">{{ $supplier->contact }}</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="person-col">
                                @if($supplier->contact_person)
                                    <div class="supplier-contact">{{ $supplier->contact_person }}</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="address-col">
                                @if($supplier->address)
                                    <div class="supplier-address" title="{{ $supplier->address }}">
                                        {{ Str::limit($supplier->address, 40) }}
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="status-col">
                                @if ($supplier->is_archived)
                                    <span class="status-badge-archived">Archived</span>
                                @else
                                    <span class="status-text status-text-active">Active</span>
                                @endif
                            </td>
                            <td class="action-col">
                                <div class="action-buttons">
                                    <button class="action-btn btn-edit edit-supplier-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editSupplierModal"
                                            data-supplier='@json($supplier)'>
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    @if (!$supplier->is_archived)
                                        <button class="action-btn btn-archive archiveBtn" 
                                                data-id="{{ $supplier->id }}" 
                                                title="Archive Supplier">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    @else
                                        <button class="action-btn btn-unarchive unarchiveBtn" 
                                                data-id="{{ $supplier->id }}" 
                                                title="Unarchive Supplier">
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

            @if($suppliers->hasPages())
            <div class="d-flex justify-content-center p-4">
                {{ $suppliers->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-truck-loading"></i>
                <h5 class="text-muted">No Suppliers Found</h5>
                <p class="text-muted mb-4">Add your first supplier to get started</p>
                <button class="btn btn-add-supplier" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                    <i class="fas fa-user-plus"></i> Add First Supplier
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="addSupplierForm" action="{{ route('admin.suppliers.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="add_name" class="form-label">Supplier Name *</label>
                            <input type="text" id="add_name" name="name" class="form-control" 
                                   placeholder="Enter supplier company name" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="add_contact" class="form-label">Contact Number</label>
                            <input type="text" id="add_contact" name="contact" class="form-control" 
                                   placeholder="Enter contact number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="add_contact_person" class="form-label">Contact Person</label>
                            <input type="text" id="add_contact_person" name="contact_person" class="form-control" 
                                   placeholder="Enter contact person name">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="add_address" class="form-label">Address</label>
                            <textarea id="add_address" name="address" class="form-control" rows="3"
                                      placeholder="Enter complete address"></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="tips-box mt-3">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Tips:</strong> Make sure to provide accurate contact information for better communication with suppliers.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Supplier</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editSupplierForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Supplier Name *</label>
                            <input type="text" id="edit_name" name="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_contact" class="form-label">Contact Number</label>
                            <input type="text" id="edit_contact" name="contact" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_contact_person" class="form-label">Contact Person</label>
                            <input type="text" id="edit_contact_person" name="contact_person" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="edit_address" class="form-label">Address</label>
                            <textarea id="edit_address" name="address" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="tips-box mt-3">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Note:</strong> Archiving a supplier will hide it from selection but won't delete its purchase order history.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Supplier</button>
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

    /* === Add Supplier === */
    document.getElementById('addSupplierForm').addEventListener('submit', function(e) {
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                modal.hide();
                location.reload();
            } else {
                alert('Error adding supplier: ' + (data.message || 'Unknown error'));
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

    /* === Edit Supplier === */
    document.querySelectorAll('.edit-supplier-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const supplier = JSON.parse(this.dataset.supplier);
            const form = document.getElementById('editSupplierForm');
            
            // Set form action
            form.action = `/admin/suppliers/${supplier.id}`;
            
            // Fill form fields
            document.getElementById('edit_name').value = supplier.name || '';
            document.getElementById('edit_contact').value = supplier.contact || '';
            document.getElementById('edit_contact_person').value = supplier.contact_person || '';
            document.getElementById('edit_address').value = supplier.address || '';
        });
    });

    document.getElementById('editSupplierForm').addEventListener('submit', function(e) {
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('editSupplierModal'));
                modal.hide();
                location.reload();
            } else {
                alert('Error updating supplier: ' + (data.message || 'Unknown error'));
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

    /* === Archive Supplier === */
    document.querySelectorAll('.archiveBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Are you sure you want to archive this supplier? This will make them inactive but preserve their data.')) return;
            
            const id = this.dataset.id;
            const button = this;

            // Disable button during processing
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/admin/suppliers/${id}/archive`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to archive supplier: ' + (data.message || 'Unknown error'));
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

    /* === Unarchive Supplier === */
    document.querySelectorAll('.unarchiveBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Are you sure you want to unarchive this supplier? They will become active again.')) return;
            
            const id = this.dataset.id;
            const button = this;

            // Disable button during processing
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/admin/suppliers/${id}/unarchive`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to unarchive supplier: ' + (data.message || 'Unknown error'));
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