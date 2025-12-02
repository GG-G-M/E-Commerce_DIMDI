@extends('layouts.admin')

@section('content')
<style>
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
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

    .btn-warning {
        background: #FBC02D;
        border: none;
        color: #fff;
        font-weight: 600;
    }

    .btn-warning:hover {
        background: #F57C00;
        transform: translateY(-2px);
    }

    .btn-outline-success {
        border: 2px solid #2C8F0C;
        color: #2C8F0C;
        font-weight: 500;
    }

    .btn-outline-success:hover {
        background-color: #2C8F0C;
        color: white;
        transform: translateY(-2px);
    }

    .btn-outline-warning {
        border: 2px solid #FBC02D;
        color: #FBC02D;
        font-weight: 500;
    }

    .btn-outline-warning:hover {
        background-color: #FBC02D;
        color: white;
        transform: translateY(-2px);
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 1rem;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
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

    .avatar-placeholder {
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

    .action-buttons {
        display: flex;
        gap: 8px;
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

    .fullname-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
</style>

<!-- Filters -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.stock_checkers.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-5">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Stock Checker</label>
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
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="per_page" class="form-label fw-bold">Items per page</label>
                        <select class="form-select" id="per_page" name="per_page">
                            @foreach ([5, 10, 15, 25, 50] as $option)
                                <option value="{{ $option }}"
                                    {{ request('per_page', 10) == $option ? 'selected' : '' }}>
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

<!-- Stock Checker Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Stock Checker Management</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockCheckerModal">
            Add Stock Checker
        </button>
    </div>
    <div class="card-body">
        @if($stockCheckers->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockCheckers as $checker)
                            <tr>
                                <td class="fw-semibold">#{{ $checker->id }}</td>
                                <td>
                                    <div class="fullname-cell">
                                        <div class="avatar-placeholder">
                                            {{ substr($checker->firstname, 0, 1) }}{{ substr($checker->lastname, 0, 1) }}
                                        </div>
                                        <div>
                                            <strong>{{ $checker->firstname }} {{ $checker->middlename ? $checker->middlename . ' ' : '' }}{{ $checker->lastname }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($checker->contact)
                                        <span class="text-dark">{{ $checker->contact }}</span>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                                <td>
                                    @if($checker->address)
                                        <small class="text-muted">{{ Str::limit($checker->address, 50) }}</small>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="{{ $checker->is_archived ? 'badge-archived' : 'badge-active' }}">
                                        {{ $checker->is_archived ? 'Archived' : 'Active' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-success editBtn" data-bs-toggle="modal"
                                            data-bs-target="#editStockCheckerModal" data-checker='@json($checker)'>
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        @if (!$checker->is_archived)
                                            <button class="btn btn-sm btn-outline-warning toggleStatusBtn"
                                                data-id="{{ $checker->id }}" data-action="archive">
                                                <i class="fas fa-archive"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-success toggleStatusBtn"
                                                data-id="{{ $checker->id }}" data-action="unarchive">
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

            <div class="mt-4 d-flex justify-content-center">
                {{ $stockCheckers->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-clipboard-check"></i>
                <h5 class="text-muted">No Stock Checkers Found</h5>
                <p class="text-muted mb-4">Add your first stock checker to get started</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockCheckerModal">
                    <i class="fas fa-plus me-2"></i> Add First Stock Checker
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Stock Checker Modal -->
<div class="modal fade" id="addStockCheckerModal" tabindex="-1" aria-labelledby="addStockCheckerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="addStockCheckerModalLabel">
                    <i class="fas fa-user-plus me-2"></i> Add New Stock Checker
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addStockCheckerForm" action="{{ route('admin.stock_checkers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="add_firstname" class="form-label">First Name *</label>
                                <input type="text" id="add_firstname" name="firstname" class="form-control" 
                                       placeholder="Enter first name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="add_middlename" class="form-label">Middle Name</label>
                                <input type="text" id="add_middlename" name="middlename" class="form-control" 
                                       placeholder="Enter middle name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="add_lastname" class="form-label">Last Name *</label>
                                <input type="text" id="add_lastname" name="lastname" class="form-control" 
                                       placeholder="Enter last name" required>
                            </div>
                        </div>
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
                                <label for="add_address" class="form-label">Address</label>
                                <input type="text" id="add_address" name="address" class="form-control" 
                                       placeholder="Enter complete address">
                            </div>
                        </div>
                    </div>

                    <div class="tips-box mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Tips:</strong> Stock checkers are responsible for inventory management. Make sure to provide accurate contact information for easy communication.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Stock Checker
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Stock Checker Modal -->
<div class="modal fade" id="editStockCheckerModal" tabindex="-1" aria-labelledby="editStockCheckerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="editStockCheckerModalLabel">
                    <i class="fas fa-edit me-2"></i> Edit Stock Checker
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStockCheckerForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_firstname" class="form-label">First Name *</label>
                                <input type="text" id="edit_firstname" name="firstname" class="form-control" 
                                       placeholder="Enter first name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_middlename" class="form-label">Middle Name</label>
                                <input type="text" id="edit_middlename" name="middlename" class="form-control" 
                                       placeholder="Enter middle name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_lastname" class="form-label">Last Name *</label>
                                <input type="text" id="edit_lastname" name="lastname" class="form-control" 
                                       placeholder="Enter last name" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_contact" class="form-label">Contact Number</label>
                                <input type="text" id="edit_contact" name="contact" class="form-control" 
                                       placeholder="Enter contact number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_address" class="form-label">Address</label>
                                <input type="text" id="edit_address" name="address" class="form-control" 
                                       placeholder="Enter complete address">
                            </div>
                        </div>
                    </div>

                    <div class="tips-box mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Note:</strong> Updating stock checker information will not affect their previous inventory records.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Stock Checker
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

            // ADD STOCK CHECKER
            document.getElementById('addStockCheckerForm').addEventListener('submit', async function(e) {
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
                        location.reload();
                    } else {
                        alert('Error: Could not add stock checker');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                } catch (error) {
                    alert('Network error. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });

            // FILL EDIT MODAL
            document.querySelectorAll('.editBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const checker = JSON.parse(btn.dataset.checker);
                    const form = document.getElementById('editStockCheckerForm');
                    
                    // Set form action
                    form.action = `/admin/stock_checkers/${checker.id}`;
                    
                    // Fill form fields
                    form.querySelector('[name="id"]').value = checker.id;
                    form.querySelector('[name="firstname"]').value = checker.firstname;
                    form.querySelector('[name="middlename"]').value = checker.middlename || '';
                    form.querySelector('[name="lastname"]').value = checker.lastname;
                    form.querySelector('[name="contact"]').value = checker.contact || '';
                    form.querySelector('[name="address"]').value = checker.address || '';
                });
            });

            // UPDATE STOCK CHECKER
            document.getElementById('editStockCheckerForm').addEventListener('submit', async function(e) {
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
                        location.reload();
                    } else {
                        alert('Error updating stock checker');
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
            document.querySelectorAll('.toggleStatusBtn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    if (!confirm('Are you sure you want to change the status of this stock checker?')) {
                        return;
                    }
                    
                    const id = btn.dataset.id;
                    const action = btn.dataset.action;
                    const originalText = btn.innerHTML;
                    
                    // Show loading on button
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    
                    try {
                        const response = await fetch(`/admin/stock_checkers/${id}/${action}`, {
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
            const forms = document.querySelectorAll('#addStockCheckerForm, #editStockCheckerForm');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const firstName = form.querySelector('[name="firstname"]');
                    const lastName = form.querySelector('[name="lastname"]');
                    
                    if (!firstName.value.trim() || !lastName.value.trim()) {
                        e.preventDefault();
                        alert('First name and last name are required.');
                        return false;
                    }
                    
                    return true;
                });
            });

            // Clear add form when modal closes
            const addModal = document.getElementById('addStockCheckerModal');
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