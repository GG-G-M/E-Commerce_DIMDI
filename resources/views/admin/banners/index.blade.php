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

    .image-preview-container {
        text-align: center;
        margin-bottom: 1rem;
        padding: 1rem;
        border: 2px dashed #C8E6C9;
        border-radius: 8px;
        background-color: #F8FDF8;
    }

    .image-preview {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        object-fit: contain;
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

    .banner-image-cell {
        width: 120px;
    }

    .banner-image-small {
        width: 100px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        padding: 3px;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
        flex-wrap: nowrap;
    }

    /* Enhanced Action Buttons - Consistent with suppliers */
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
    
    .btn-delete {
        background-color: white;
        border-color: #C62828;
        color: #C62828;
    }
    
    .btn-delete:hover {
        background-color: #C62828;
        color: white;
    }

    /* Tooltip styling for buttons */
    .action-btn {
        position: relative;
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

    .status-active {
        color: #2C8F0C;
        font-weight: 600;
    }

    .status-inactive {
        color: #6c757d;
        font-weight: 600;
    }

    /* Status styling - Consistent with suppliers */
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
    
    .status-text-inactive {
        color: #6c757d;
    }

    .status-text-inactive::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #6c757d;
        border-radius: 50%;
        opacity: 0.8;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    .target-url {
        font-size: 0.85rem;
        color: #6c757d;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block;
    }

    .image-placeholder-small {
        width: 100px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        color: #6c757d;
    }

    /* Order indicator */
    .order-indicator {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        margin: 0 auto;
    }
</style>

<!-- Search and Filters -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.banners.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-6">
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
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Filter by Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="sort_by" class="form-label fw-bold">Sort By</label>
                        <select class="form-select" id="sort_by" name="sort_by">
                            <option value="order" {{ request('sort_by') == 'order' ? 'selected' : '' }}>Display Order</option>
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
        <h5 class="mb-0">Banner Management</h5>
        <div class="d-flex align-items-center">
            <span class="text-white me-3 small d-none d-md-block">
                Total: {{ $banners->total() }} banners
            </span>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBannerModal">
                 Add Banner
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($banners->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 80px;">Order</th>
                            <th class="banner-image-cell">Image</th>
                            <th>Title & Description</th>
                            <th>Target URL</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($banners as $banner)
                        <tr>
                            <td class="text-center">
                                <div class="order-indicator">{{ $banner->order }}</div>
                            </td>
                            <td class="banner-image-cell">
                                @if($banner->image_path)
                                    <img src="{{ asset($banner->image_path) }}" 
                                         alt="{{ $banner->alt_text }}" 
                                         class="banner-image-small"
                                         data-bs-toggle="tooltip" 
                                         data-bs-title="{{ $banner->alt_text }}">
                                @else
                                    <div class="image-placeholder-small">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $banner->title }}</strong><br>
                                @if($banner->description)
                                    <small class="text-muted">{{ Str::limit($banner->description, 80) }}</small>
                                @else
                                    <small class="text-muted">No description</small>
                                @endif
                            </td>
                            <td>
                                @if($banner->target_url)
                                    <a href="{{ $banner->target_url }}" target="_blank" class="target-url" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-title="{{ $banner->target_url }}">
                                        {{ Str::limit($banner->target_url, 40) }}
                                    </a>
                                @else
                                    <span class="text-muted">No link</span>
                                @endif
                            </td>
                            <td>
                                @if($banner->is_active)
                                    <span class="status-text status-text-active">
                                        Active
                                    </span>
                                @else
                                    <span class="status-text status-text-inactive">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="action-buttons justify-content-center">
                                    <button type="button" class="action-btn btn-edit edit-banner-btn"
                                            data-bs-toggle="modal" data-bs-target="#editBannerModal"
                                            data-id="{{ $banner->id }}"
                                            data-title="{{ $banner->title }}"
                                            data-description="{{ $banner->description }}"
                                            data-alt_text="{{ $banner->alt_text }}"
                                            data-target_url="{{ $banner->target_url }}"
                                            data-order="{{ $banner->order }}"
                                            data-is_active="{{ $banner->is_active }}"
                                            data-image="{{ $banner->image_path ? asset($banner->image_path) : '' }}"
                                            data-title="Edit Banner">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete"
                                                onclick="return confirm('Are you sure you want to delete this banner?')"
                                                data-title="Delete Banner">
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
                {{ $banners->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-images"></i>
                <h5 class="text-muted">No Banners Found</h5>
                <p class="text-muted mb-4">Add your first banner to get started</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBannerModal">
                    <i class="fas fa-plus me-2"></i> Add First Banner
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Banner Modal -->
<div class="modal fade" id="addBannerModal" tabindex="-1" aria-labelledby="addBannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="addBannerModalLabel">
                    <i class="fas fa-plus-circle me-2"></i> Add New Banner
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="add_title" class="form-label">Banner Title *</label>
                                <input type="text" id="add_title" name="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="Enter banner title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="add_order" class="form-label">Display Order *</label>
                                <input type="number" id="add_order" name="order"
                                    class="form-control @error('order') is-invalid @enderror"
                                    placeholder="1" value="{{ old('order', 1) }}" min="1" required>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="add_description" class="form-label">Description</label>
                        <textarea id="add_description" name="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Enter banner description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_target_url" class="form-label">Target URL</label>
                                <input type="url" id="add_target_url" name="target_url"
                                    class="form-control @error('target_url') is-invalid @enderror"
                                    placeholder="https://example.com/page" value="{{ old('target_url') }}">
                                @error('target_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_alt_text" class="form-label">Alt Text</label>
                                <input type="text" id="add_alt_text" name="alt_text"
                                    class="form-control @error('alt_text') is-invalid @enderror"
                                    placeholder="Banner description for accessibility" value="{{ old('alt_text') }}">
                                @error('alt_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="add_image" class="form-label">Banner Image *</label>
                                <input type="file" id="add_image" name="image"
                                    class="form-control @error('image') is-invalid @enderror"
                                    accept="image/*" required>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Recommended size: 1920x600px (landscape format)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="add_is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="add_is_active">Active Banner</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="add_image_preview" class="image-preview-container mt-2" style="display: none;">
                        <img id="add_image_preview_img" class="image-preview" src="#" alt="Banner preview">
                    </div>

                    <div class="tips-box mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Tips:</strong> 
                        <ul class="mb-0">
                            <li>Use high-quality images for better display</li>
                            <li>Recommended image format: JPG or PNG</li>
                            <li>Keep banner content relevant to target audience</li>
                            <li>Test target URLs to ensure they work properly</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Banner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Banner Modal -->
<div class="modal fade" id="editBannerModal" tabindex="-1" aria-labelledby="editBannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="editBannerModalLabel">
                    <i class="fas fa-edit me-2"></i> Edit Banner
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBannerForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Banner Title *</label>
                                <input type="text" id="edit_title" name="title"
                                    class="form-control @error('title') is-invalid @enderror" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_order" class="form-label">Display Order *</label>
                                <input type="number" id="edit_order" name="order"
                                    class="form-control @error('order') is-invalid @enderror" min="1" required>
                                @error('order')
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
                                <label for="edit_target_url" class="form-label">Target URL</label>
                                <input type="url" id="edit_target_url" name="target_url"
                                    class="form-control @error('target_url') is-invalid @enderror">
                                @error('target_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_alt_text" class="form-label">Alt Text</label>
                                <input type="text" id="edit_alt_text" name="alt_text"
                                    class="form-control @error('alt_text') is-invalid @enderror">
                                @error('alt_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit_image" class="form-label">Banner Image</label>
                                <input type="file" id="edit_image" name="image"
                                    class="form-control @error('image') is-invalid @enderror"
                                    accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty to keep current image</small>
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="remove_image" id="edit_remove_image" class="form-check-input" value="1">
                                    <label for="edit_remove_image" class="form-check-label text-danger">Remove current image</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="edit_is_active" name="is_active" value="1">
                                    <label class="form-check-label" for="edit_is_active">Active Banner</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="edit_image_preview" class="image-preview-container mt-2">
                        <img id="edit_image_preview_img" class="image-preview" src="#" alt="Current banner image">
                    </div>

                    <div class="tips-box mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Note:</strong> Banners with lower order numbers appear first. Active banners will be displayed on the website.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Banner
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

    // Image preview for Add Banner
    const addImageInput = document.getElementById('add_image');
    const addImagePreview = document.getElementById('add_image_preview');
    const addImagePreviewImg = document.getElementById('add_image_preview_img');
    
    addImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) { // 5MB limit
                alert('Image size must be less than 5MB');
                this.value = '';
                addImagePreview.style.display = 'none';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                addImagePreviewImg.src = e.target.result;
                addImagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            addImagePreview.style.display = 'none';
        }
    });

    // Image preview for Edit Banner
    const editImageInput = document.getElementById('edit_image');
    const editImagePreview = document.getElementById('edit_image_preview');
    const editImagePreviewImg = document.getElementById('edit_image_preview_img');
    
    editImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) { // 5MB limit
                alert('Image size must be less than 5MB');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                editImagePreviewImg.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Edit banner modal handling
    const editBannerButtons = document.querySelectorAll('.edit-banner-btn');
    const editBannerForm = document.getElementById('editBannerForm');
    const editIdInput = editBannerForm.querySelector('[name="id"]');
    const editTitleInput = document.getElementById('edit_title');
    const editDescriptionInput = document.getElementById('edit_description');
    const editAltTextInput = document.getElementById('edit_alt_text');
    const editTargetUrlInput = document.getElementById('edit_target_url');
    const editOrderInput = document.getElementById('edit_order');
    const editIsActiveInput = document.getElementById('edit_is_active');
    const editRemoveImageCheckbox = document.getElementById('edit_remove_image');

    editBannerButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const description = this.getAttribute('data-description');
            const altText = this.getAttribute('data-alt_text');
            const targetUrl = this.getAttribute('data-target_url');
            const order = this.getAttribute('data-order');
            const isActive = this.getAttribute('data-is_active') === '1';
            const image = this.getAttribute('data-image');

            // Set form action URL
            editBannerForm.action = `/admin/banners/${id}`;
            
            // Populate form fields
            editIdInput.value = id;
            editTitleInput.value = title;
            editDescriptionInput.value = description || '';
            editAltTextInput.value = altText || '';
            editTargetUrlInput.value = targetUrl || '';
            editOrderInput.value = order;
            editIsActiveInput.checked = isActive;
            editRemoveImageCheckbox.checked = false;

            // Handle image preview
            if (image) {
                editImagePreviewImg.src = image;
                editImagePreview.style.display = 'block';
            } else {
                editImagePreview.style.display = 'none';
            }
        });
    });

    // Handle remove image checkbox
    editRemoveImageCheckbox.addEventListener('change', function() {
        if (this.checked) {
            editImagePreview.style.display = 'none';
            editImageInput.disabled = true;
        } else {
            editImagePreview.style.display = 'block';
            editImageInput.disabled = false;
        }
    });

    // Handle modal form validation
    const addBannerForm = document.querySelector('#addBannerModal form');
    const editBannerFormElement = document.querySelector('#editBannerModal form');

    [addBannerForm, editBannerFormElement].forEach(form => {
        if (form) {
            form.addEventListener('submit', function(e) {
                const titleInput = this.querySelector('[name="title"]');
                const orderInput = this.querySelector('[name="order"]');
                
                if (!titleInput.value.trim()) {
                    e.preventDefault();
                    titleInput.classList.add('is-invalid');
                    titleInput.focus();
                    
                    titleInput.addEventListener('input', function() {
                        this.classList.remove('is-invalid');
                    }, { once: true });
                    return false;
                }
                
                if (!orderInput.value.trim() || parseInt(orderInput.value) < 1) {
                    e.preventDefault();
                    orderInput.classList.add('is-invalid');
                    orderInput.focus();
                    
                    orderInput.addEventListener('input', function() {
                        this.classList.remove('is-invalid');
                    }, { once: true });
                    return false;
                }
                
                return true;
            });
        }
    });

    // Clear add form when modal is closed
    const addBannerModal = document.getElementById('addBannerModal');
    addBannerModal.addEventListener('hidden.bs.modal', function () {
        const form = this.querySelector('form');
        if (form) {
            form.reset();
            addImagePreview.style.display = 'none';
            const invalidFields = form.querySelectorAll('.is-invalid');
            invalidFields.forEach(field => {
                field.classList.remove('is-invalid');
            });
        }
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Show modal if there are validation errors
    @if($errors->any())
        @if($errors->has('title') || $errors->has('order'))
            @if(request()->routeIs('admin.banners.index') || request()->routeIs('admin.banners.store'))
                const addModal = new bootstrap.Modal(document.getElementById('addBannerModal'));
                addModal.show();
            @elseif(request()->routeIs('admin.banners.update'))
                const editModal = new bootstrap.Modal(document.getElementById('editBannerModal'));
                editModal.show();
            @endif
        @endif
    @endif
});
</script>
@endpush
@endsection