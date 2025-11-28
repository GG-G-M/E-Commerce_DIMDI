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
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        }

        .table th {
            background-color: #E8F5E6;
            color: #2C8F0C;
            font-weight: 600;
            border-bottom: 2px solid #2C8F0C;
        }

        .badge-active {
            background-color: #2C8F0C;
            color: white;
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
            font-size: 0.75em;
            font-weight: 600;
        }

        .badge-inactive {
            background-color: #C62828;
            color: white;
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
            font-size: 0.75em;
            font-weight: 600;
        }

        .search-box {
            border: 1px solid #E8F5E6;
            border-radius: 8px;
            padding: 1rem;
            background-color: #F8FDF8;
        }

        .table tbody tr:hover {
            background-color: #F8FDF8;
            transition: background-color 0.2s ease;
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
    </style>

    <!-- Search -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.categories.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 position-relative">
                            <label for="search" class="form-label fw-bold">Search Categories</label>
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

    <!-- Categories Table -->
    <div class="card card-custom">
        <div class="card-header card-header-custom">
            <h5 class="mb-0">Category List</h5>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add Category
            </a>
        </div>
        <div class="card-body">
            @if ($categories->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Products</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="fw-semibold">{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td>
                                        <span>
                                            {{ $category->products_count ?? $category->products->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="{{ $category->is_active ? 'badge-active' : 'badge-inactive' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="btn btn-sm btn-outline-success me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this category?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $categories->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-success mb-3"></i>
                    <p class="text-muted mb-0">No categories found. Try adding one!</p>
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
        });
    </script>
    @endpush
@endsection