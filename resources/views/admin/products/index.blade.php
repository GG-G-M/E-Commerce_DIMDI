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

    /* Dashboard Header */
    .dashboard-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-left: 4px solid #2C8F0C;
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

    /* Button Styles - Consistent */
    .btn-success-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(44, 143, 12, 0.2);
    }
    
    .btn-success-custom:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
        color: white;
    }

    .btn-outline-success-custom {
        background: white;
        border: 2px solid #2C8F0C;
        color: #2C8F0C;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-success-custom:hover {
        background: #2C8F0C;
        color: white;
        transform: translateY(-1px);
    }

    .btn-info-custom {
        background: linear-gradient(135deg, #17a2b8, #6f42c1);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(23, 162, 184, 0.2);
    }
    
    .btn-info-custom:hover {
        background: linear-gradient(135deg, #138496, #5a32a3);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
        color: white;
    }

    /* Search Box */
    .search-box {
        border-radius: 8px;
        border: 1px solid #C8E6C9;
        transition: border-color 0.3s ease;
        font-size: 0.9rem;
    }

    .search-box:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.15rem rgba(44,143,12,0.2);
    }

    /* Status Text Styles */
    .status-text {
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .status-active {
        color: #2C8F0C;
    }
    
    .status-inactive {
        color: #6c757d;
    }
    
    .status-archived {
        color: #6c757d;
        font-style: italic;
    }
    
    .status-featured {
        color: #2C8F0C;
    }

    /* Stock Text Styles */
    .stock-high {
        color: #2C8F0C;
        font-weight: 600;
    }
    
    .stock-low {
        color: #FBC02D;
        font-weight: 600;
    }
    
    .stock-out {
        color: #C62828;
        font-weight: 600;
    }

    /* Action Buttons */
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

    /* Product Image */
    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }

    /* Product Info */
    .product-name {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
        line-height: 1.2;
    }
    
    .product-sku {
        color: #6c757d;
        font-size: 0.75rem;
    }

    /* Price Styling */
    .price-current {
        font-weight: 700;
        color: #2C8F0C;
        font-size: 0.9rem;
    }
    
    .price-original {
        font-size: 0.75rem;
        color: #6c757d;
        text-decoration: line-through;
    }
    
    .discount-badge {
        font-size: 0.7rem;
        font-weight: 600;
        color: #C62828;
        background-color: #FFEBEE;
        padding: 0.1rem 0.3rem;
        border-radius: 3px;
        margin-left: 0.25rem;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    /* Header Stats */
    .header-stats {
        font-size: 0.9rem;
        font-weight: 600;
        opacity: 0.9;
    }

    /* Category Text */
    .category-text {
        color: #495057;
        font-size: 0.85rem;
    }
    
    .category-warning {
        color: #FBC02D;
        font-size: 0.7rem;
    }

    /* Brand Text */
    .brand-text {
        color: #495057;
        font-size: 0.85rem;
    }

    /* Variants Info */
    .variants-info {
        color: #495057;
        font-size: 0.85rem;
    }

    /* Pagination styling - Consistent */
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

    /* Modal Styling */
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

    /* Template Download Section */
    .template-download {
        background: #E8F5E6;
        border: 1px dashed #2C8F0C;
        padding: 1rem;
        text-align: center;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    /* Instructions */
    .csv-instructions {
        background: #f8f9fa;
        border-left: 4px solid #2C8F0C;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
        font-size: 0.9rem;
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
        
        .product-image {
            width: 50px;
            height: 50px;
        }
        
        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }
        
        .btn-outline-success-custom,
        .btn-success-custom,
        .btn-info-custom {
            padding: 0.4rem 0.7rem;
            font-size: 0.8rem;
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
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.products.import.csv') }}" method="POST" enctype="multipart/form-data" id="csvUploadForm">
                @csrf
                <div class="modal-body">
                    
                    <!-- Template Download Section -->
                    <div class="template-download">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-download me-2"></i>Download CSV Template
                        </h6>
                        <p class="text-muted mb-3">
                            Use our template to ensure your CSV file has the correct format.
                        </p>
                        <a href="{{ route('admin.products.csv.template') }}" class="btn btn-success-custom btn-sm">
                            <i class="fas fa-file-download me-2"></i>Download Template
                        </a>
                    </div>

                    <!-- Instructions -->
                    <div class="csv-instructions">
                        <h6><i class="fas fa-info-circle me-2"></i>CSV Format Instructions</h6>
                        <ul class="small mb-0">
                            <li>File must be in CSV format (Comma Separated Values)</li>
                            <li>First row must contain column headers</li>
                            <li>Required columns: <code>name</code>, <code>sku</code>, <code>price</code>, <code>category_id</code></li>
                            <li>Optional columns: <code>description</code>, <code>brand_id</code>, <code>stock_quantity</code>, <code>image_url</code></li>
                            <li>Make sure SKU values are unique</li>
                            <li>Category ID must exist in your categories table</li>
                        </ul>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="csv_file" class="form-label fw-bold">Select CSV File</label>
                        <input type="file" class="form-control search-box" id="csv_file" name="csv_file" accept=".csv" required>
                        <div class="form-text">
                            Only .csv files are allowed. Maximum file size: 10MB
                        </div>
                    </div>

                    <!-- Processing Options -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Duplicate Handling</label>
                                <select class="form-select search-box" name="duplicate_handling">
                                    <option value="skip">Skip duplicates (keep existing)</option>
                                    <option value="update">Update existing products</option>
                                    <option value="overwrite">Overwrite existing products</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Default Status</label>
                                <select class="form-select search-box" name="default_status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar (Hidden by default) -->
                    <div class="progress mb-3" style="height: 20px; display: none;" id="uploadProgress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <span class="progress-text">0%</span>
                        </div>
                    </div>

                    <!-- Upload Status Messages -->
                    <div id="uploadStatus" class="alert" style="display: none;"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success-custom" id="uploadCsvBtn">
                        <i class="fas fa-upload me-2"></i>Upload CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="filter-card">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm">
            <div class="row g-2">
                <div class="col-md-3">
                    <div class="mb-2">
                        <label for="search" class="form-label fw-bold">Search Products</label>
                        <input type="text" class="form-control search-box" id="search" name="search" 
                            value="{{ request('search') }}" placeholder="Search by name, SKU...">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="brand" class="form-label fw-bold">Brand</label>
                        <input type="text" class="form-control search-box" id="brand" name="brand" 
                            value="{{ request('brand') }}" placeholder="Filter by brand...">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="category_id" class="form-label fw-bold">Category</label>
                        <select class="form-select search-box" id="category_id" name="category_id">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select class="form-select search-box" id="status" name="status">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status', 'active') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="per_page" class="form-label fw-bold">Items per page</label>
                        <select class="form-select search-box" id="per_page" name="per_page">
                            @foreach([5, 10, 15, 25, 50] as $option)
                                <option value="{{ $option }}" {{ request('per_page', 10) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <div class="mb-2">
                        @if(request()->hasAny(['search', 'brand', 'category_id', 'status']))
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-success-custom w-100">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Product Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Product List</h5>
        <div class="header-buttons">
            <span class="header-stats text-white me-3 d-none d-md-block">
                Total: {{ $products->total() }}
            </span>
            <button type="button" class="btn btn-info-custom me-2" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                <i class="fas fa-file-csv me-1"></i> Import CSV
            </button>
            <a href="{{ route('admin.products.create') }}" class="btn btn-success-custom">
              Add Product
            </a>
        </div>
    </div>
    
    <div class="card-body p-0">
        @if($products->count() > 0)
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th width="70">Image</th>
                            <th width="180">Product</th>
                            <th width="120">Brand</th>
                            <th width="130">Category</th>
                            <th width="100">Variants</th>
                            <th width="110">Price</th>
                            <th width="80">Stock</th>
                            <th width="100">Status</th>
                            <th width="140" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <span class="text-muted">#{{ $product->id }}</span>
                            </td>
                            <td>
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="product-image">
                            </td>
                            <td>
                                <div class="product-name">{{ Str::limit($product->name, 25) }}</div>
                                <div class="product-sku">{{ $product->sku }}</div>
                            </td>
                            <td>
                                @if($product->brand_id && $product->brand)
                                    <span class="brand-text">{{ $product->brand->name }}</span>
                                @else
                                    <span class="text-muted">No Brand</span>
                                @endif
                            </td>
                            <td>
                                <span class="category-text">{{ $product->category->name }}</span>
                                @if(!$product->category->is_active)
                                    <div class="category-warning mt-1">
                                        Inactive Category
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($product->has_variants && $product->variants && $product->variants->count() > 0)
                                    <span class="variants-info">{{ $product->variants->count() }} variants</span>
                                @else
                                    <span class="text-muted">No Variants</span>
                                @endif
                            </td>
                            <td>
                                @if($product->has_discount)
                                    <div class="price-current">₱{{ number_format($product->sale_price, 2) }}</div>
                                    <div class="price-original">₱{{ number_format($product->price, 2) }}</div>
                                    <span class="discount-badge">{{ $product->discount_percentage }}% OFF</span>
                                @else
                                    <div class="price-current">₱{{ number_format($product->price, 2) }}</div>
                                @endif
                            </td>
                            <td>
                                @if($product->has_variants)
                                    <span class="stock-high">{{ $product->total_stock }} units</span>
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
                            <td>
                                @if($product->is_archived)
                                    <span class="status-text status-archived">Archived</span>
                                @else
                                    @if($product->is_effectively_inactive)
                                        <span class="status-text status-inactive">Inactive</span>
                                    @else
                                        <span class="status-text status-active">Active</span>
                                    @endif
                                    @if($product->is_featured)
                                        <div class="status-featured small mt-1">Featured</div>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.products.view', $product) }}" 
                                       class="action-btn btn-view" title="View Product">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="action-btn btn-edit" title="Edit Product">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($product->is_archived)
                                    <form action="{{ route('admin.products.unarchive', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn btn-edit"
                                                onclick="return confirm('Are you sure you want to unarchive this product?')"
                                                title="Unarchive Product">
                                            <i class="fas fa-box-open"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.products.archive', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn btn-archive"
                                                onclick="return confirm('Are you sure you want to archive this product?')"
                                                title="Archive Product">
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
            <div class="d-flex justify-content-between align-items-center p-3">
                <div>
                    <small class="text-muted">
                        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                    </small>
                </div>
                <div>
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-box-open"></i>
                <h5 class="text-muted">No Products Found</h5>
                <p class="text-muted mb-4">No products match your search criteria</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-success-custom">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-outline-success-custom">
                        <i class="fas fa-plus me-1"></i> Add Product
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
    const brandInput = document.getElementById('brand');
    const categorySelect = document.getElementById('category_id');
    const statusSelect = document.getElementById('status');
    const perPageSelect = document.getElementById('per_page');
    
    let searchTimeout;

    // Auto-submit search with delay
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500);
    });

    // Auto-submit brand filter with delay
    brandInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500);
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

    // CSV Upload functionality
    const csvUploadForm = document.getElementById('csvUploadForm');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadStatus = document.getElementById('uploadStatus');
    const uploadCsvBtn = document.getElementById('uploadCsvBtn');
    const progressBar = uploadProgress.querySelector('.progress-bar');
    const progressText = uploadProgress.querySelector('.progress-text');

    if (csvUploadForm) {
        csvUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Show progress bar
            uploadProgress.style.display = 'block';
            uploadStatus.style.display = 'none';
            uploadCsvBtn.disabled = true;
            uploadCsvBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
            
            // Simulate progress
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 5;
                if (progress <= 100) {
                    progressBar.style.width = progress + '%';
                    progressBar.setAttribute('aria-valuenow', progress);
                    progressText.textContent = progress + '%';
                }
            }, 100);
            
            // Submit the form
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                clearInterval(progressInterval);
                progressBar.style.width = '100%';
                progressText.textContent = '100%';
                
                if (data.success) {
                    showUploadStatus('success', data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showUploadStatus('danger', data.message || 'Upload failed. Please check your CSV file.');
                }
            })
            .catch(error => {
                clearInterval(progressInterval);
                showUploadStatus('danger', 'Upload failed: ' + error.message);
            })
            .finally(() => {
                uploadCsvBtn.disabled = false;
                uploadCsvBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload CSV';
            });
        });
        
        function showUploadStatus(type, message) {
            uploadStatus.className = `alert alert-${type}`;
            uploadStatus.innerHTML = message;
            uploadStatus.style.display = 'block';
        }
        
        // Reset form when modal is closed
        document.getElementById('csvUploadModal').addEventListener('hidden.bs.modal', function() {
            csvUploadForm.reset();
            uploadProgress.style.display = 'none';
            uploadStatus.style.display = 'none';
            progressBar.style.width = '0%';
            progressText.textContent = '0%';
        });
    }
});
</script>
@endpush
@endsection