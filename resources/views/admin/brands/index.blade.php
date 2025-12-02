@extends('layouts.admin')

@section('content')
<style>
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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

    .status-active {
        color: #2C8F0C;
        font-weight: 600;
    }

    .status-inactive {
        color: #6c757d;
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

    .btn-outline-success {
        border-color: #2C8F0C;
        color: #2C8F0C;
    }

    .btn-outline-success:hover {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
        color: white;
    }

    .btn-outline-danger {
        border-color: #C62828;
        color: #C62828;
    }

    .btn-outline-danger:hover {
        background-color: #C62828;
        border-color: #C62828;
        color: white;
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
</style>

<!-- Search and Filters -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.brands.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Brands</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Search by name or description...">
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
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="sort_by" class="form-label fw-bold">Sort By</label>
                        <select class="form-select" id="sort_by" name="sort_by">
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="sort_order" {{ request('sort_by') == 'sort_order' ? 'selected' : '' }}>Sort Order</option>
                            <option value="products_count" {{ request('sort_by') == 'products_count' ? 'selected' : '' }}>Product Count</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
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

<!-- Brands Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Brand List</h5>
        <div class="d-flex align-items-center">
            <span class="text-white me-3 small d-none d-md-block">
                Total: {{ $brands->total() }} brands
            </span>
            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add Brand
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Sort Order</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($brands as $brand)
                    <tr>
                        <td>
                            <strong>{{ $brand->name }}</strong><br>
                            <small class="text-muted">Slug: {{ $brand->slug }}</small>
                        </td>
                        <td>
                            @if($brand->description)
                                <span class="text-muted">{{ Str::limit($brand->description, 50) }}</span>
                            @else
                                <span class="text-muted">No description</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-dark">
                                <i class="fas fa-box me-1"></i>{{ $brand->products->count() }} products
                            </span>
                        </td>
                        <td>
                            @if($brand->is_active)
                                <span class="status-active">
                                    <i class="fas fa-check-circle me-1"></i>Active
                                </span>
                            @else
                                <span class="status-inactive">
                                    <i class="fas fa-times-circle me-1"></i>Inactive
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">{{ $brand->sort_order }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.brands.edit', $brand) }}" 
                                   class="btn btn-sm btn-outline-success me-1" 
                                   title="Edit Brand">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this brand?')"
                                            title="Delete Brand">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $brands->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const searchInput = document.getElementById('search');
        const statusSelect = document.getElementById('status');
        const sortBySelect = document.getElementById('sort_by');
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

        // Auto-submit sort by selection immediately
        sortBySelect.addEventListener('change', function() {
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
    });
</script>
@endpush
@endsection