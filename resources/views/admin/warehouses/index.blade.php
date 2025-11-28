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

    .modal-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
    }

    .form-label {
        font-weight: 600;
        color: #2C8F0C;
    }
    
    .loading-spinner {
        display: none;
        text-align: center;
        padding: 20px;
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
                            <option value="">All</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                </div>
                <!-- Items per page selection -->
                <div class="col-md-3">
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
        <h5 class="mb-0">Warehouse List</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
            <i class="bi bi-plus-circle"></i> Add Warehouse
        </button>
    </div>
    <div class="card-body">
        <div class="loading-spinner" id="loadingSpinner">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading warehouses...</p>
        </div>
        <div id="warehouseTableContainer">
            <table class="table table-bordered align-middle w-100" id="warehouseTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Warehouse Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($warehouses as $warehouse)
                    <tr data-id="{{ $warehouse->id }}">
                        <td>{{ $warehouse->id }}</td>
                        <td>{{ $warehouse->name }}</td>
                        <td>
                            @if (!$warehouse->is_archived)
                                <span>Active</span>
                            @else
                                <span>Archived</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-outline-success btn-sm editBtn me-1" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editWarehouseModal" 
                                data-warehouse='@json($warehouse)'>
                                <i class="fas fa-edit"></i>
                            </button>

                            @if (!$warehouse->is_archived)
                                <button class="btn btn-outline-warning btn-sm toggleStatusBtn" 
                                    data-id="{{ $warehouse->id }}" data-action="archive">
                                    <i class="fas fa-archive"></i>
                                </button>
                            @else
                                <button class="btn btn-outline-success btn-sm toggleStatusBtn" 
                                    data-id="{{ $warehouse->id }}" data-action="unarchive">
                                    <i class="fas fa-box-open"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $warehouses->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addWarehouseModal" tabindex="-1" aria-labelledby="addWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addWarehouseForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Warehouse</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Warehouse Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Warehouse</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editWarehouseModal" tabindex="-1" aria-labelledby="editWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editWarehouseForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Warehouse</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="mb-3">
                        <label class="form-label">Warehouse Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Warehouse</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Debounce function to limit how often the filter function is called
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Function to apply filters automatically
function applyFilters() {
    const form = document.getElementById('filterForm');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const tableContainer = document.getElementById('warehouseTableContainer');
    const searchLoading = document.getElementById('searchLoading');
    
    // Show loading spinner
    loadingSpinner.style.display = 'block';
    tableContainer.style.display = 'none';
    searchLoading.style.display = 'none';
    
    // Submit the form via AJAX
    fetch(form.action + '?' + new URLSearchParams(new FormData(form)), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Create a temporary container to parse the response
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        // Extract the table and pagination from the response
        const newTableContainer = tempDiv.querySelector('#warehouseTableContainer');
        if (newTableContainer) {
            tableContainer.innerHTML = newTableContainer.innerHTML;
            
            // Reattach event listeners to the new buttons
            attachEventListeners();
        }
        
        // Hide loading spinner and show table
        loadingSpinner.style.display = 'none';
        tableContainer.style.display = 'block';
    })
    .catch(error => {
        console.error('Error:', error);
        loadingSpinner.style.display = 'none';
        tableContainer.style.display = 'block';
    });
}

// Attach event listeners to buttons in the table
function attachEventListeners() {
    // Edit buttons
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const w = JSON.parse(btn.dataset.warehouse);
            const form = document.getElementById('editWarehouseForm');
            form.action = `/admin/warehouses/${w.id}`;
            form.querySelector('[name="id"]').value = w.id;
            form.querySelector('[name="name"]').value = w.name;
        });
    });
    
    // Archive/Unarchive buttons
    document.querySelectorAll('.toggleStatusBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const action = btn.dataset.action; // 'archive' or 'unarchive'
            fetch(`/admin/warehouses/${id}/${action}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(r => r.json()).then(d => {
                if (d.success) applyFilters();
                else alert('Failed to update status');
            });
        });
    });
}

// Initialize automatic filters
document.addEventListener('DOMContentLoaded', function() {
    // Set up event listeners for filter inputs
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
            applyFilters();
        }, 800); // 800ms delay after typing stops
    });

    // Auto-submit status filter immediately
    statusSelect.addEventListener('change', function() {
        applyFilters();
    });

    // Auto-submit per page selection immediately
    perPageSelect.addEventListener('change', function() {
        applyFilters();
    });

    // Attach event listeners to initial buttons
    attachEventListeners();
});

// Add Warehouse
document.getElementById('addWarehouseForm').addEventListener('submit', e => {
    e.preventDefault();
    fetch('{{ route("admin.warehouses.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: new FormData(e.target)
    }).then(r => r.json()).then(d => {
        if (d.success) {
            // Close modal and refresh the table
            const modal = bootstrap.Modal.getInstance(document.getElementById('addWarehouseModal'));
            modal.hide();
            document.getElementById('addWarehouseForm').reset();
            applyFilters();
        } else {
            alert('Error adding warehouse');
        }
    });
});

// Update Warehouse
document.getElementById('editWarehouseForm').addEventListener('submit', e => {
    e.preventDefault();
    const form = e.target;
    fetch(form.action, {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-HTTP-Method-Override': 'PUT' 
        },
        body: new FormData(form)
    }).then(r => r.json()).then(d => {
        if (d.success) {
            // Close modal and refresh the table
            const modal = bootstrap.Modal.getInstance(document.getElementById('editWarehouseModal'));
            modal.hide();
            applyFilters();
        } else {
            alert('Error updating warehouse');
        }
    });
});
</script>
@endpush
@endsection