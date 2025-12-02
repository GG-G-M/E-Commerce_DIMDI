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

    .btn-success {
        background: linear-gradient(135deg, #48b036ff, #ffffffff);
        border: none;
        color: black;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
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

    .btn-outline-warning {
        border-color: #FBC02D;
        color: #FBC02D;
    }

    .btn-outline-warning:hover {
        background-color: #FBC02D;
        border-color: #FBC02D;
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

    .badge-success {
        background-color: #2C8F0C !important;
    }

    .badge-secondary {
        background-color: #6c757d !important;
    }

    .banner-image {
        width: 100px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #E8F5E6;
    }

    .image-placeholder {
        width: 100px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 4px;
        border: 1px solid #E8F5E6;
    }
</style>

<!-- Search and Filters -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.banners.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Banners</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Search by title or description...">
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
                            <option value="order" {{ request('sort_by') == 'order' ? 'selected' : '' }}>Order</option>
                            <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title</option>
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
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

<!-- Banners Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Active Banners</h5>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Add New Banner
        </a>
    </div>
    <div class="card-body">
        @if($banners->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($banners as $banner)
                            <tr>
                                <td>{{ $banner->order }}</td>
                                <td>
                                    @if($banner->image_path)
                                        <img src="{{ asset($banner->image_path) }}" 
                                             alt="{{ $banner->alt_text }}" 
                                             class="banner-image">
                                    @else
                                        <div class="image-placeholder">
                                            <i class="fas fa-image fa-lg text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $banner->title }}</td>
                                <td>{{ Str::limit($banner->description, 50) }}</td>
                                <td>
                                    <span class="badge bg-{{ $banner->is_active ? 'success' : 'secondary' }}">
                                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <form action="{{ route('admin.banners.toggle-status', $banner) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $banner->is_active ? 'warning' : 'success' }}" title="{{ $banner->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $banner->is_active ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-outline-success" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this banner?')" title="Delete">
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
        @else
            <div class="text-center py-5">
                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Banners Created</h4>
                <p class="text-muted">Create your first banner to get started.</p>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Create First Banner
                </a>
            </div>
        @endif
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