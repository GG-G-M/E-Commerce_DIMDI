@extends('layouts.admin')

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

    /* Improved Add Button */
    .btn-add-product {
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
    
    .btn-add-product:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
    }
    
    .btn-add-product:active {
        transform: translateY(0);
    }

    /* CSV Import Button */
    .btn-import-csv {
        background: linear-gradient(135deg, #17a2b8, #6f42c1);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(23, 162, 184, 0.2);
        height: 46px;
    }
    
    .btn-import-csv:hover {
        background: linear-gradient(135deg, #138496, #5a32a3);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(23, 162, 184, 0.3);
        color: white;
    }

    /* Enhanced Action Buttons - Consistent with other pages */
    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        justify-content: center;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        border: 2px solid;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    
    .btn-view {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-view:hover {
        background-color: #2C8F0C;
        color: white;
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

    /* Table Styling - Compact */
    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 0.75rem 0.5rem;
        white-space: nowrap;
    }

    .table td {
        padding: 0.75rem 0.5rem;
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

    /* Status styling - Compact */
    .status-text {
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .status-text-active {
        color: #2C8F0C;
    }
    
    .status-text-active::before {
        content: "";
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: #2C8F0C;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }

    .status-badge-inactive {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 60px;
        background-color: #FFF3CD;
        color: #856404;
        border: 1px solid #FFEAA7;
    }

    .status-badge-archived {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 60px;
        background-color: #E0E0E0;
        color: #616161;
        border: 1px solid #BDBDBD;
        font-style: italic;
    }

    .status-badge-featured {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 60px;
        background-color: #E8F5E6;
        color: #2C8F0C;
        border: 1px solid #C8E6C9;
    }

    /* Stock Status */
    .stock-high {
        color: #2C8F0C;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .stock-low {
        color: #FBC02D;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .stock-out {
        color: #C62828;
        font-weight: 600;
        font-size: 0.85rem;
    }

    /* Modal Styling - Consistent */
    .modal-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1rem;
    }

    .modal-title {
        font-weight: 700;
        font-size: 1.1rem;
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
        font-size: 0.9rem;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #C8E6C9;
        transition: border-color 0.3s ease;
        font-size: 0.9rem;
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

    /* Product Image - Smaller */
    .product-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }

    /* Tips Box */
    .tips-box {
        background-color: #F8FDF8;
        border-left: 3px solid #2C8F0C;
        border-radius: 6px;
        padding: 0.75rem;
        font-size: 0.85rem;
        color: #2C8F0C;
    }

    .tips-box i {
        color: #2C8F0C;
        margin-right: 5px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #C8E6C9;
        margin-bottom: 1rem;
    }

    /* Table Container - No overflow unless absolutely necessary */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        max-width: 100%;
    }
    
    /* Responsive adjustments */
    @media (min-width: 1200px) {
        .table-container {
            overflow-x: visible;
        }
        
        .table {
            table-layout: auto;
            width: 100%;
        }
    }

    /* Column widths - More compact */
    .id-col { width: 70px; min-width: 70px; }
    .image-col { width: 70px; min-width: 70px; }
    .name-col { width: 180px; min-width: 180px; }
    .brand-col { width: 100px; min-width: 100px; }
    .category-col { width: 120px; min-width: 120px; }
    .variants-col { width: 90px; min-width: 90px; }
    .price-col { width: 110px; min-width: 110px; }
    .stock-col { width: 80px; min-width: 80px; }
    .status-col { width: 100px; min-width: 100px; }
    .action-col { width: 140px; min-width: 140px; }

    /* Product Info Cell - Compact */
    .product-info-cell {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .product-name {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
        line-height: 1.2;
    }
    
    .product-sku {
        color: #6c757d;
        font-size: 0.75rem;
        margin-top: 2px;
    }
    
    .product-brand {
        color: #495057;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .product-category {
        color: #495057;
        font-size: 0.85rem;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Price Styling - Compact */
    .product-price {
        font-weight: 700;
        color: #2C8F0C;
        font-size: 0.9rem;
    }
    
    .product-sale-price {
        font-weight: 700;
        color: #C62828;
        font-size: 0.9rem;
    }
    
    .product-original-price {
        text-decoration: line-through;
        color: #6c757d;
        font-size: 0.75rem;
    }
    
    .discount-badge {
        background-color: #C62828;
        color: white;
        padding: 1px 4px;
        border-radius: 3px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-left: 2px;
        display: inline-block;
    }

    /* Pagination styling - Compact */
    .pagination .page-item .page-link {
        color: #2C8F0C;
        border: 1px solid #dee2e6;
        margin: 0 1px;
        border-radius: 4px;
        transition: all 0.3s ease;
        padding: 0.4rem 0.7rem;
        font-size: 0.85rem;
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

    /* Remove old status styles */
    .status-active,
    .status-inactive,
    .status-archived,
    .status-featured {
        display: none;
    }

    /* Header button group */
    .header-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .header-buttons .btn {
        margin: 0;
        font-size: 0.9rem;
    }

    /* Warning for inactive category */
    .category-warning {
        color: #FBC02D;
        font-size: 0.7rem;
        margin-top: 2px;
        white-space: nowrap;
    }

    /* Make table more compact on mobile */
    @media (max-width: 768px) {
        .header-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
        }
        
        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }
        
        .product-img {
            width: 40px;
            height: 40px;
        }
        
        .status-badge-inactive,
        .status-badge-archived,
        .status-badge-featured {
            padding: 0.2rem 0.4rem;
            font-size: 0.7rem;
            min-width: 50px;
        }
    }
</style>

<!-- CSV Upload Modal -->
<div class="modal fade" id="csvUploadModal" tabindex="-1" aria-labelledby="csvUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="csvUploadModalLabel">
                    <i class="fas fa-file-csv me-2"></i>Upload Products via CSV
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.products.import.csv') }}" method="POST" enctype="multipart/form-data" id="csvUploadForm">
                @csrf
                <div class="modal-body">
                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="csv_file" class="form-label fw-bold">Select CSV File</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                        <div class="form-text">
                            Only .csv files are allowed. Maximum file size: 10MB
                        </div>
                    </div>

                    <div class="tips-box mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Note:</strong> Make sure your CSV file follows the correct format with required columns.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="uploadCsvBtn">
                        <i class="fas fa-upload me-2"></i>Upload CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Filters and Search - Compact -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm">
            <div class="row g-2">
                <!-- Search by Name or SKU -->
                <div class="col-md-5">
                    <div class="mb-2 position-relative">
                        <label for="search" class="form-label fw-bold">Search Products</label>
                        <input type="text" class="form-control" id="search" name="search" 
                            value="{{ request('search') }}" placeholder="Search by name or SKU...">
                        <div class="search-loading" id="searchLoading">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter by Category -->
                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="category_id" class="form-label fw-bold">Category</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Filter by Status -->
                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select class="form-select" id="status" name="status">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status', 'active') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Items per page selection -->
                <div class="col-md-2">
                    <div class="mb-2">
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

<!-- Product Table - Compact -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Product Management</h5>
        <div class="header-buttons">
            <button type="button" class="btn-import-csv" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                <i class="fas fa-file-csv"></i> Import CSV
            </button>
            <a href="{{ route('admin.products.create') }}" class="btn btn-add-product">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        @if($products->count())
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
                            <th class="image-col">Image</th>
                            <th class="name-col">Product</th>
                            <th class="brand-col">Brand</th>
                            <th class="category-col">Category</th>
                            <th class="variants-col">Variants</th>
                            <th class="price-col">Price</th>
                            <th class="stock-col">Stock</th>
                            <th class="status-col">Status</th>
                            <th class="action-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr data-id="{{ $product->id }}">
                            <td class="id-col">
                                <small class="text-muted">#{{ $product->id }}</small>
                            </td>
                            <td class="image-col">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="product-img">
                            </td>
                            <td class="name-col">
                                <div class="product-name">{{ Str::limit($product->name, 30) }}</div>
                                <div class="product-sku">{{ $product->sku }}</div>
                            </td>
                            <td class="brand-col">
                                @if($product->brand_id && $product->brand)
                                    <span class="product-brand">{{ Str::limit($product->brand->name, 15) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="category-col">
                                <span class="product-category">{{ Str::limit($product->category->name, 15) }}</span>
                                @if(!$product->category->is_active)
                                    <div class="category-warning" title="Inactive Category">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="variants-col">
                                @if($product->has_variants && $product->variants && $product->variants->count() > 0)
                                    <span class="text-dark">{{ $product->variants->count() }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="price-col">
                                @if($product->has_discount)
                                    <div class="product-sale-price">₱{{ number_format($product->sale_price, 2) }}</div>
                                    <div class="product-original-price">₱{{ number_format($product->price, 2) }}</div>
                                @else
                                    <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
                                @endif
                            </td>
                            <td class="stock-col">
                                @if($product->has_variants)
                                    <span class="stock-high">{{ $product->total_stock }}</span>
                                @else
                                    @if($product->stock_quantity > 10)
                                        <span class="stock-high">{{ $product->stock_quantity }}</span>
                                    @elseif($product->stock_quantity > 0)
                                        <span class="stock-low">{{ $product->stock_quantity }}</span>
                                    @else
                                        <span class="stock-out">{{ $product->stock_quantity }}</span>
                                    @endif
                                @endif
                            </td>
                            <td class="status-col">
                                @if($product->is_archived)
                                    <span class="status-badge-archived">Archived</span>
                                @else
                                    @if($product->is_effectively_inactive)
                                        <span class="status-badge-inactive">Inactive</span>
                                    @else
                                        <span class="status-text status-text-active">Active</span>
                                    @endif
                                    @if($product->is_featured)
                                        <div class="status-badge-featured">Featured</div>
                                    @endif
                                @endif
                            </td>
                            <td class="action-col">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.products.view', $product) }}" 
                                       class="action-btn btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="action-btn btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($product->is_archived)
                                        <form action="{{ route('admin.products.unarchive', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="action-btn btn-unarchive" title="Unarchive">
                                                <i class="fas fa-box-open"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.products.archive', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="action-btn btn-archive" title="Archive">
                                                <i class="fas fa-archive"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
            <div class="d-flex justify-content-center p-3">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-box"></i>
                <h5 class="text-muted">No Products Found</h5>
                <p class="text-muted mb-4">Add your first product to get started</p>
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn-import-csv" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                        <i class="fas fa-file-csv"></i> Import CSV
                    </button>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-add-product">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const categorySelect = document.getElementById('category_id');
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

    // Auto-submit category filter immediately
    categorySelect.addEventListener('change', function() {
        filterForm.submit();
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

    /* === CSV Upload === */
    const csvUploadForm = document.getElementById('csvUploadForm');
    if (csvUploadForm) {
        csvUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Uploading...';

            fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Close modal and reload
                    const modal = bootstrap.Modal.getInstance(document.getElementById('csvUploadModal'));
                    modal.hide();
                    location.reload();
                } else {
                    alert('Error uploading CSV: ' + (data.message || 'Unknown error'));
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
    }

    /* === Archive Product === */
    document.querySelectorAll('.btn-archive').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to archive this product? This will make it inactive but preserve its data.')) return;
            
            const form = this.closest('form');
            
            // Show loading state
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to archive product: ' + (data.message || 'Unknown error'));
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

    /* === Unarchive Product === */
    document.querySelectorAll('.btn-unarchive').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to unarchive this product? It will become active again.')) return;
            
            const form = this.closest('form');
            
            // Show loading state
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to unarchive product: ' + (data.message || 'Unknown error'));
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
});
</script>
@endpush
@endsection