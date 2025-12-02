@extends('layouts.admin')

@section('content')
<style>
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
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
        font-weight: 500;
    }

    .btn-outline-success:hover {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
        color: white;
        transform: translateY(-2px);
    }

    .btn-outline-danger {
        border-color: #C62828;
        color: #C62828;
        font-weight: 500;
    }

    .btn-outline-danger:hover {
        background-color: #C62828;
        border-color: #C62828;
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

    .form-check-input:checked {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
    }

    .logo-preview-container {
        text-align: center;
        margin-bottom: 1rem;
    }

    .logo-preview {
        max-width: 150px;
        max-height: 100px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 5px;
        background-color: white;
    }

    .remove-logo-btn {
        margin-top: 10px;
        display: block;
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

    .brand-logo-cell {
        width: 80px;
    }

    .brand-logo-small {
        width: 60px;
        height: 40px;
        object-fit: contain;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        padding: 3px;
        background-color: white;
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
        <h5 class="mb-0">Brand Management</h5>
        <div class="d-flex align-items-center">
            <span class="text-white me-3 small d-none d-md-block">
                Total: {{ $brands->total() }} brands
            </span>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                <i class="fas fa-plus me-1"></i> Add Brand
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($brands->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="brand-logo-cell">Logo</th>
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
                            <td class="brand-logo-cell">
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="brand-logo-small">
                                @else
                                    <div class="brand-logo-small d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-tag text-muted"></i>
                                    </div>
                                @endif
                            </td>
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
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-sm btn-outline-success edit-brand-btn"
                                            data-bs-toggle="modal" data-bs-target="#editBrandModal"
                                            data-id="{{ $brand->id }}"
                                            data-name="{{ $brand->name }}"
                                            data-slug="{{ $brand->slug }}"
                                            data-description="{{ $brand->description }}"
                                            data-sort_order="{{ $brand->sort_order }}"
                                            data-is_active="{{ $brand->is_active }}"
                                            data-logo="{{ $brand->logo ? asset('storage/' . $brand->logo) : '' }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
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
        @else
            <div class="empty-state">
                <i class="fas fa-tag"></i>
                <h5 class="text-muted">No Brands Found</h5>
                <p class="text-muted mb-4">Add your first brand to get started</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                    <i class="fas fa-plus me-2"></i> Add First Brand
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="addBrandModalLabel">
                    <i class="fas fa-plus-circle me-2"></i> Add New Brand
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_name" class="form-label">Brand Name *</label>
                                <input type="text" id="add_name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter brand name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_slug" class="form-label">Slug *</label>
                                <input type="text" id="add_slug" name="slug"
                                    class="form-control @error('slug') is-invalid @enderror"
                                    placeholder="brand-slug" value="{{ old('slug') }}" required>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="add_description" class="form-label">Description</label>
                        <textarea id="add_description" name="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Enter brand description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_logo" class="form-label">Brand Logo</label>
                                <input type="file" id="add_logo" name="logo"
                                    class="form-control @error('logo') is-invalid @enderror"
                                    accept="image/*">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="add_logo_preview" class="logo-preview-container mt-2" style="display: none;">
                                    <img id="add_logo_preview_img" class="logo-preview" src="#" alt="Logo preview">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="add_sort_order" class="form-label">Sort Order</label>
                                <input type="number" id="add_sort_order" name="sort_order" class="form-control" 
                                       value="{{ old('sort_order', 0) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="add_is_active" class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="add_is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="add_is_active">Active Brand</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tips-box mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Tips:</strong> Brand logos should be high-quality images. Recommended size is 200x100 pixels for best display.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Brand
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Brand Modal -->
<div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="editBrandModalLabel">
                    <i class="fas fa-edit me-2"></i> Edit Brand
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBrandForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Brand Name *</label>
                                <input type="text" id="edit_name" name="name"
                                    class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_slug" class="form-label">Slug *</label>
                                <input type="text" id="edit_slug" name="slug"
                                    class="form-control @error('slug') is-invalid @enderror" required>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea id="edit_description" name="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_logo" class="form-label">Brand Logo</label>
                                <input type="file" id="edit_logo" name="logo"
                                    class="form-control @error('logo') is-invalid @enderror"
                                    accept="image/*">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="edit_logo_preview" class="logo-preview-container mt-2">
                                    <img id="edit_logo_preview_img" class="logo-preview" src="#" alt="Current logo">
                                </div>
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="remove_logo" id="edit_remove_logo" class="form-check-input" value="1">
                                    <label for="edit_remove_logo" class="form-check-label text-danger">Remove current logo</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="edit_sort_order" class="form-label">Sort Order</label>
                                <input type="number" id="edit_sort_order" name="sort_order" class="form-control" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="edit_is_active" class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="edit_is_active" name="is_active" value="1">
                                    <label class="form-check-label" for="edit_is_active">Active Brand</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tips-box mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Note:</strong> Updating brand information will affect all products under this brand.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Brand
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
        }, 800);
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

    // Auto-generate slug from name (Add Brand)
    const addNameInput = document.getElementById('add_name');
    const addSlugInput = document.getElementById('add_slug');
    
    addNameInput.addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        
        addSlugInput.value = slug;
    });

    // Auto-generate slug from name (Edit Brand)
    const editNameInput = document.getElementById('edit_name');
    const editSlugInput = document.getElementById('edit_slug');
    
    editNameInput.addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        
        editSlugInput.value = slug;
    });

    // Logo preview for Add Brand
    const addLogoInput = document.getElementById('add_logo');
    const addLogoPreview = document.getElementById('add_logo_preview');
    const addLogoPreviewImg = document.getElementById('add_logo_preview_img');
    
    addLogoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                addLogoPreviewImg.src = e.target.result;
                addLogoPreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            addLogoPreview.style.display = 'none';
        }
    });

    // Logo preview for Edit Brand
    const editLogoInput = document.getElementById('edit_logo');
    const editLogoPreview = document.getElementById('edit_logo_preview');
    const editLogoPreviewImg = document.getElementById('edit_logo_preview_img');
    
    editLogoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                editLogoPreviewImg.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Edit brand modal handling
    const editBrandButtons = document.querySelectorAll('.edit-brand-btn');
    const editBrandForm = document.getElementById('editBrandForm');
    const editIdInput = editBrandForm.querySelector('[name="id"]');
    const editNameInput = document.getElementById('edit_name');
    const editSlugInput = document.getElementById('edit_slug');
    const editDescriptionInput = document.getElementById('edit_description');
    const editSortOrderInput = document.getElementById('edit_sort_order');
    const editIsActiveInput = document.getElementById('edit_is_active');
    const editRemoveLogoCheckbox = document.getElementById('edit_remove_logo');

    editBrandButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const slug = this.getAttribute('data-slug');
            const description = this.getAttribute('data-description');
            const sortOrder = this.getAttribute('data-sort_order');
            const isActive = this.getAttribute('data-is_active') === '1';
            const logo = this.getAttribute('data-logo');

            // Set form action URL
            editBrandForm.action = `/admin/brands/${id}`;
            
            // Populate form fields
            editIdInput.value = id;
            editNameInput.value = name;
            editSlugInput.value = slug;
            editDescriptionInput.value = description;
            editSortOrderInput.value = sortOrder;
            editIsActiveInput.checked = isActive;
            editRemoveLogoCheckbox.checked = false;

            // Handle logo preview
            if (logo) {
                editLogoPreviewImg.src = logo;
                editLogoPreview.style.display = 'block';
            } else {
                editLogoPreview.style.display = 'none';
            }
        });
    });

    // Handle modal form validation
    const addBrandForm = document.querySelector('#addBrandModal form');
    const editBrandFormElement = document.querySelector('#editBrandModal form');

    [addBrandForm, editBrandFormElement].forEach(form => {
        if (form) {
            form.addEventListener('submit', function(e) {
                const nameInput = this.querySelector('[name="name"]');
                const slugInput = this.querySelector('[name="slug"]');
                
                if (!nameInput.value.trim()) {
                    e.preventDefault();
                    nameInput.classList.add('is-invalid');
                    nameInput.focus();
                    
                    nameInput.addEventListener('input', function() {
                        this.classList.remove('is-invalid');
                    }, { once: true });
                    return false;
                }
                
                if (!slugInput.value.trim()) {
                    e.preventDefault();
                    slugInput.classList.add('is-invalid');
                    slugInput.focus();
                    
                    slugInput.addEventListener('input', function() {
                        this.classList.remove('is-invalid');
                    }, { once: true });
                    return false;
                }
                
                return true;
            });
        }
    });

    // Clear add form when modal is closed
    const addBrandModal = document.getElementById('addBrandModal');
    addBrandModal.addEventListener('hidden.bs.modal', function () {
        const form = this.querySelector('form');
        if (form) {
            form.reset();
            addLogoPreview.style.display = 'none';
            const invalidFields = form.querySelectorAll('.is-invalid');
            invalidFields.forEach(field => {
                field.classList.remove('is-invalid');
            });
        }
    });

    // Handle remove logo checkbox
    editRemoveLogoCheckbox.addEventListener('change', function() {
        if (this.checked) {
            editLogoPreview.style.display = 'none';
            editLogoInput.disabled = true;
        } else {
            editLogoPreview.style.display = 'block';
            editLogoInput.disabled = false;
        }
    });

    // Show modal if there are validation errors
    @if($errors->any())
        @if($errors->has('name') || $errors->has('slug'))
            @if(request()->routeIs('admin.brands.index') || request()->routeIs('admin.brands.store'))
                const addModal = new bootstrap.Modal(document.getElementById('addBrandModal'));
                addModal.show();
            @elseif(request()->routeIs('admin.brands.update'))
                const editModal = new bootstrap.Modal(document.getElementById('editBrandModal'));
                editModal.show();
            @endif
        @endif
    @endif
});
</script>
@endpush
@endsection