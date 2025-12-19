@extends('layouts.admin')

@section('content')
<style>
    /* === Consistent Green Theme === */
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
    .btn-add-category {
       background: white;
    color: #2C8F0C;
    border: 2px solid rgba(44, 143, 12, 0.3);
    padding: 0.5rem 1.25rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    text-decoration: none;
    white-space: nowrap;
    min-width: fit-content;
    height: auto;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    
    
    .btn-add-category:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
    }
    
    .btn-add-category:active {
        transform: translateY(0);
    }

    /* Improved Add Button */
    .btn-add-category {
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
    
    
    .btn-add-category:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
    }
    
    .btn-add-category:active {
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
        position: relative;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .action-btn::after {
        content: attr(data-title);
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #333;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease;
        z-index: 1000;
    }
    
    .action-btn:hover::after {
        opacity: 1;
        visibility: visible;
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
    
    .btn-delete {
        background-color: white;
        border-color: #C62828;
        color: #C62828;
    }
    
    .btn-delete:hover {
        background-color: #C62828;
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
        position: sticky;
        top: 0;
        z-index: 10;
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

    /* Status styling - Consistent with other pages */
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

    .status-text-inactive {
        color: #dc3545;
    }

    .status-text-inactive::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #dc3545;
        border-radius: 50%;
        opacity: 0.8;
    }

    .status-text-archived {
        color: #6c757d;
    }

    .status-text-archived::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #6c757d;
        border-radius: 50%;
        opacity: 0.6;
    }

    /* Modal Styling - Consistent */
    .modal-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
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

    /* Category Icon - Fixed Size */
    .category-icon {
        width: 40px;
        height: 40px;
        min-height: 40px;
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
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
    .id-col { min-width: 40px; width: 40px; }
    .name-col { min-width: 200px; width: 250px; }
    .slug-col { min-width: 150px; width: 150px; }
    .products-col { min-width: 100px; width: 100px; }
    .status-col { min-width: 80px; width: 80px; }
    .action-col { min-width: 80px; width: 80px; }

    /* Category Info Cell */
    /* Category Info Cell */
    .category-info-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .category-name {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
        line-height: 1.3;
    }
    
    .category-slug {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 2px;
    }
    
    .category-description {
        color: #6c757d;
        font-size: 0.85rem;
        line-height: 1.3;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Products Count */
    .products-count {
        font-weight: 600;
        color: #2C8F0C;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 5px;
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
    .badge-inactive {
        display: none;
    }


</style>

<!-- Filters and Search - Consistent -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.categories.index') }}" id="filterForm">
            <div class="row">
                <!-- Search by Name or Description -->
                <div class="col-md-7">
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

                <!-- Filter by Status -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Filter by Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                </div>

                <!-- Items per page selection -->
                <div class="col-md-2">
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

<!-- Categories Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Category Management</h5>
        <button class="btn btn-add-category" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
             Add Category
        </button>
    </div>
    <div class="card-body p-0">
        @if($categories->count())
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
                            <th class="name-col">Name</th>
                            <th class="slug-col">Slug</th>
                            <th class="products-col">Products</th>
                            <th class="status-col">Status</th>
                            <th class="action-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr data-id="{{ $category->id }}">
                            <td class="id-col">
                                <span class="text-muted">#{{ $category->id }}</span>
                            </td>
                            <td class="name-col">
                                <div class="category-info-cell">
                                    <div class="category-icon">
                                        <i class="fas fa-folder"></i>
                                    </div>
                                    <div>
                                        <div class="category-name">{{ $category->name }}</div>
                                        @if($category->description)
                                            <div class="category-description" title="{{ $category->description }}">
                                                {{ Str::limit($category->description, 40) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="slug-col">
                                <code class="category-slug">{{ $category->slug }}</code>
                            </td>
                            <td class="products-col">
                                <span class="products-count">
                                    {{-- <i class="fas fa-box"></i> --}}
                                    {{ $category->products_count ?? $category->products->count() }}
                                </span>
                            </td>
                            <td class="status-col">
                                @if($category->is_archived)
                                    <span class="status-text status-text-archived">Archived</span>
                                @elseif($category->is_active)
                                    <span class="status-text status-text-active">Active</span>
                                @else
                                    <span class="status-text status-text-inactive">Inactive</span>
                                @endif
                            </td>
                            <td class="action-col">
                                <div class="action-buttons">
                                    <button class="action-btn btn-edit editBtn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editCategoryModal"
                                            data-category='@json($category)'
                                            data-title="Edit Category">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    @if($category->is_archived)
                                        <button type="button" class="action-btn btn-unarchive unarchiveBtn" 
                                                data-title="Unarchive Category">
                                            <i class="fas fa-box-open"></i>
                                        </button>
                                    @else
                                        <button type="button" class="action-btn btn-archive archiveBtn" 
                                                data-title="Archive Category">
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

            @if($categories->hasPages())
            <div class="d-flex justify-content-center p-4">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-folder-open"></i>
                <h5 class="text-muted">No Categories Found</h5>
                <p class="text-muted mb-4">Add your first category to get started</p>
                <button class="btn btn-add-category" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-user-plus"></i> Add First Category
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="addCategoryForm" action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="add_name" class="form-label">Category Name *</label>
                            <input type="text" id="add_name" name="name" class="form-control" 
                                   placeholder="Enter category name" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="add_description" class="form-label">Description</label>
                            <textarea id="add_description" name="description" class="form-control" rows="4"
                                      placeholder="Write a short description (optional)"></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="add_is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="add_is_active">Active Category</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="tips-box mt-3">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Tips:</strong> Keep category names simple and clear. Use descriptions to help users identify what products belong here.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Category Name *</label>
                            <input type="text" id="edit_name" name="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea id="edit_description" name="description" class="form-control" rows="4"></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="edit_is_active" name="is_active" value="1">
                                <label class="form-check-label" for="edit_is_active">Active Category</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
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

    /* === Add Category === */
    document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
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
            // Note: If success, the page might reload or redirect. 
            // The controller redirects, so we might need to handle it.
            // Since the controller returns a redirect, fetch might follow it.
            // But if we want JSON, we should have updated the controller to return JSON or handle the redirect.
            // For now, let's assume standard form submission behavior or handle redirect.
            if (data.redirect || (res.redirected && res.url)) {
                 window.location.href = data.redirect || res.url;
            } else {
                 // Fallback if controller returns HTML (it does redirect)
                 window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // If it's a redirect, it might have been followed and returned HTML, causing JSON parse error
            window.location.reload();
        });
    });

    /* === Edit Category === */
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const category = JSON.parse(this.dataset.category);
            const form = document.getElementById('editCategoryForm');
            
            // Set form action
            form.action = `/admin/categories/${category.slug}`;
            
            // Fill form fields
            document.getElementById('edit_name').value = category.name || '';
            document.getElementById('edit_description').value = category.description || '';
            document.getElementById('edit_is_active').checked = category.is_active;
        });
    });

    /* === Archive Category === */
    document.querySelectorAll('.archiveBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Are you sure you want to archive this category? It will be hidden from the public lists.')) return;
            
            const row = this.closest('tr');
            const categoryId = row.dataset.id;
            const categorySlug = row.querySelector('.category-slug').textContent.trim();
            
            // Show loading state
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/admin/categories/${categorySlug}/archive`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to archive category: ' + (data.message || 'Unknown error'));
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-archive"></i>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-archive"></i>';
            });
        });
    });

    /* === Unarchive Category === */
    document.querySelectorAll('.unarchiveBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Are you sure you want to restore this category?')) return;
            
            const row = this.closest('tr');
            const categoryId = row.dataset.id;
            const categorySlug = row.querySelector('.category-slug').textContent.trim();
            
            // Show loading state
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/admin/categories/${categorySlug}/unarchive`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to restore category: ' + (data.message || 'Unknown error'));
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-box-open"></i>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-box-open"></i>';
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
