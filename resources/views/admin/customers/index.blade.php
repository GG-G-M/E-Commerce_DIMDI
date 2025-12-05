@extends('layouts.admin')

@section('content')
<style>
    /* === Green Theme and Card Styling === */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #2C8F0C;
    }

    .page-header h1 {
        color: #2C8F0C;
        font-weight: 700;
    }

    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-5px);
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
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    }

    .btn-warning {
        background: #FBC02D;
        border: none;
        color: #fff;
    }

    .btn-danger {
        background: #C62828;
        border: none;
    }

    .table {
        margin-bottom: 0;
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

    /* Alternating row colors for better readability */
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #F8FDF8;
    }

    .modal-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
    }

    .form-label {
        font-weight: 600;
        color: #2C8F0C;
    }

    /* Loading indicator for search */
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

    /* Improved table cell styling */
    .customer-name {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }

    .customer-email {
        color: #2C8F0C;
        font-size: 0.85rem;
        word-break: break-word;
    }

    .customer-phone {
        color: #495057;
        font-size: 0.9rem;
    }

    .customer-address {
        color: #6c757d;
        font-size: 0.85rem;
        max-width: 200px;
        word-break: break-word;
    }

    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 80px;
    }

    .status-active {
        background-color: #D4EDDA;
        color: #155724;
        border: 1px solid #C3E6CB;
    }

    .status-archived {
        background-color: #FFF3CD;
        color: #856404;
        border: 1px solid #FFEAA7;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-outline-success {
        color: #2C8F0C;
        border-color: #2C8F0C;
        border-radius: 6px;
    }

    .btn-outline-success:hover {
        background-color: #2C8F0C;
        color: white;
    }

    .btn-outline-warning {
        color: #FBC02D;
        border-color: #FBC02D;
        border-radius: 6px;
    }

    .btn-outline-warning:hover {
        background-color: #FBC02D;
        color: white;
    }

    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    /* Column width control */
    .id-col { min-width: 80px; }
    .name-col { min-width: 180px; }
    .email-col { min-width: 200px; }
    .phone-col { min-width: 120px; }
    .address-col { min-width: 200px; max-width: 250px; }
    .status-col { min-width: 100px; }
    .action-col { min-width: 140px; }

    /* Pagination styling */
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
        background-color: #E8F5E6;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
    }
</style>

<!-- Filters and Search -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.customers.index') }}" id="filterForm">
            <div class="row">
                <!-- Search by Name or Email -->
                <div class="col-md-5">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Customers</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by name or email...">
                        <div class="search-loading" id="searchLoading">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter by Status (Active / Archived) -->
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

<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Customer List</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            <i class="fas fa-user-plus me-1"></i> Add Customer
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="id-col">ID</th>
                        <th class="name-col">Full Name</th>
                        <th class="email-col">Email</th>
                        <th class="phone-col">Phone</th>
                        <th class="address-col">Address</th>
                        <th class="status-col">Status</th>
                        <th class="action-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                    <tr data-id="{{ $customer->id }}">
                        <td>
                            <span class="text-muted">#{{ $customer->id }}</span>
                        </td>
                        <td>
                            <div class="customer-name">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                        </td>
                        <td>
                            <a href="mailto:{{ $customer->email }}" class="customer-email" title="{{ $customer->email }}">
                                {{ $customer->email }}
                            </a>
                        </td>
                        <td>
                            @if($customer->phone)
                                <div class="customer-phone">{{ $customer->phone }}</div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($customer->address)
                                <div class="customer-address" title="{{ $customer->address }}">
                                    {{ Str::limit($customer->address, 40) }}
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($customer->is_archived)
                                <span class="status-badge status-archived">Archived</span>
                            @else
                                <span class="status-badge status-active">Active</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-success editBtn" data-bs-toggle="modal" data-bs-target="#editCustomerModal" data-customer='@json($customer)'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if ($customer->is_archived)
                                    <button class="btn btn-sm btn-outline-success unarchiveBtn" data-id="{{ $customer->id }}" title="Unarchive Customer">
                                       <i class="fas fa-box-open"></i>
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline-warning archiveBtn" data-id="{{ $customer->id }}" title="Archive Customer">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
        <div class="d-flex justify-content-center p-4">
            {{ $customers->links('pagination::bootstrap-5') }}
        </div>
        @endif

    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="addCustomerForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    @include('admin.customers.form-fields')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editCustomerForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    @include('admin.customers.form-fields')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Customer</button>
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
        }, 800); // 800ms delay after typing stops
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

    /* === Add Customer === */
    document.getElementById('addCustomerForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';

        fetch('{{ route("admin.customers.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Close modal and reload
                const modal = bootstrap.Modal.getInstance(document.getElementById('addCustomerModal'));
                modal.hide();
                location.reload();
            } else {
                alert('Error adding customer: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    /* === Edit Customer === */
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const customer = JSON.parse(this.dataset.customer);
            const form = document.getElementById('editCustomerForm');
            
            // Set form action
            form.action = `/admin/customers/${customer.id}`;
            
            // Fill form fields
            for (const key in customer) {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) {
                    if (input.type === 'checkbox') {
                        input.checked = customer[key];
                    } else {
                        input.value = customer[key];
                    }
                }
            }
        });
    });

    document.getElementById('editCustomerForm').addEventListener('submit', function(e) {
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('editCustomerModal'));
                modal.hide();
                location.reload();
            } else {
                alert('Error updating customer: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    /* === Archive Customer === */
    document.querySelectorAll('.archiveBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Are you sure you want to archive this customer? This will make them inactive but preserve their data.')) return;
            
            const id = this.dataset.id;
            const row = this.closest('tr');

            // Disable button during processing
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/admin/customers/${id}/archive`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to archive customer: ' + (data.message || 'Unknown error'));
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
                location.reload();
            });
        });
    });

    /* === Unarchive Customer === */
    document.querySelectorAll('.unarchiveBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Are you sure you want to unarchive this customer? They will become active again.')) return;
            
            const id = this.dataset.id;

            // Disable button during processing
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/admin/customers/${id}/unarchive`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to unarchive customer: ' + (data.message || 'Unknown error'));
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
                location.reload();
            });
        });
    });
});
</script>
@endpush

@endsection