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

    /* Summary Cards - Consistent */
    .summary-card {
        background: linear-gradient(135deg, #E8F5E6, #F8FDF8);
        border: none;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .summary-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2C8F0C;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .summary-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
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

    /* Add Product Button */
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

    /* Status Styling - Text with pulsing dots */
    .status-text {
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-active {
        color: #2C8F0C;
    }
    
    .status-active::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #2C8F0C;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-inactive {
        color: #6c757d;
    }
    
    .status-inactive::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #6c757d;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-archived {
        color: #6c757d;
        font-style: italic;
    }
    
    .status-featured {
        color: #2C8F0C;
        font-weight: 600;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }

    /* Stock Level Styling */
    .stock-high {
        color: #2C8F0C;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .stock-low {
        color: #FBC02D;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .stock-out {
        color: #C62828;
        font-weight: 600;
        font-size: 0.9rem;
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

    /* Table Container */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        max-width: 100%;
    }

    /* Column widths */
    .image-col { width: 70px; min-width: 70px; }
    .name-col { width: 200px; min-width: 200px; }
    .brand-col { width: 120px; min-width: 120px; }
    .category-col { width: 120px; min-width: 120px; }
    .variants-col { width: 100px; min-width: 100px; }
    .price-col { width: 100px; min-width: 100px; }
    .stock-col { width: 100px; min-width: 100px; }
    .status-col { width: 100px; min-width: 100px; }
    .action-col { width: 100px; min-width: 100px; }

    /* Product Image */
    .product-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }
    
    .product-name {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        line-height: 1.2;
    }
    
    .product-sku {
        color: #6c757d;
        font-size: 0.75rem;
        margin-top: 2px;
    }

    /* Price Styling */
    .price-current {
        font-weight: 700;
        font-size: 0.9rem;
        color: #2C8F0C;
    }
    
    .price-original {
        color: #6c757d;
        font-size: 0.75rem;
        text-decoration: line-through;
    }
    
    .discount-badge {
        font-size: 0.7rem;
        background-color: #FFEBEE;
        color: #C62828;
        padding: 0.15rem 0.35rem;
        border-radius: 4px;
        font-weight: 600;
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

    /* Action Buttons - Consistent with other pages */
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
            width: 50px;
            height: 50px;
        }
        
        .summary-number {
            font-size: 1.5rem;
        }
        
        .summary-label {
            font-size: 0.8rem;
        }
        
        .status-text {
            font-size: 0.8rem;
        }
    }
</style>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->total() }}</div>
            <div class="summary-label">Total Products</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->where('is_active', true)->where('is_archived', false)->count() }}</div>
            <div class="summary-label">Active Products</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->where('is_archived', true)->count() }}</div>
            <div class="summary-label">Archived</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->where('is_featured', true)->where('is_archived', false)->count() }}</div>
            <div class="summary-label">Featured</div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Products</label>
                        <input type="text" class="form-control" id="search" name="search" 
                            value="{{ request('search') }}" placeholder="Search by name, description, or SKU...">
                        <div class="search-loading" id="searchLoading">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="brand" class="form-label fw-bold">Brand</label>
                        <input type="text" class="form-control" id="brand" name="brand" 
                            value="{{ request('brand') }}" placeholder="Filter by brand...">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
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
                <div class="col-md-2">
                    <div class="mb-3">
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

<!-- CSV Upload Modal -->
<div class="modal fade" id="csvUploadModal" tabindex="-1" aria-labelledby="csvUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-csv me-2"></i>Upload Products via CSV
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.products.import.csv') }}" method="POST" enctype="multipart/form-data" id="csvUploadForm">
                @csrf
                <div class="modal-body">
                    
                    <!-- Template Download Section -->
                    <div class="template-download" style="background: #e8f5e9; border: 1px dashed #2C8F0C; padding: 15px; text-align: center; border-radius: 8px; margin-bottom: 20px;">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-download me-2"></i>Download CSV Template
                        </h6>
                        <p class="text-muted mb-3">
                            Use our template to ensure your CSV file has the correct format.
                        </p>
                        <a href="{{ route('admin.products.csv.template') }}" class="btn btn-success">
                            <i class="fas fa-file-download me-2"></i>Download Template
                        </a>
                    </div>

                    <!-- Instructions -->
                    <div class="csv-instructions" style="background: #f8f9fa; border-left: 4px solid #2C8F0C; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
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
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                        <div class="form-text">
                            Only .csv files are allowed. Maximum file size: 10MB
                        </div>
                    </div>

                    <!-- Processing Options -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Duplicate Handling</label>
                                <select class="form-select" name="duplicate_handling">
                                    <option value="skip">Skip duplicates (keep existing)</option>
                                    <option value="update">Update existing products</option>
                                    <option value="overwrite">Overwrite existing products</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Default Status</label>
                                <select class="form-select" name="default_status">
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
                    <button type="submit" class="btn-import-csv" id="uploadCsvBtn">
                        <i class="fas fa-upload me-2"></i>Upload CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Product Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Product List</h5>
        <div class="header-buttons">
            <button class="btn-import-csv" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                <i class="fas fa-file-csv"></i> Import CSV
            </button>
            <a href="{{ route('admin.products.create') }}" class="btn-add-product">
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
                        <tr>
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
                                    <span>{{ Str::limit($product->brand->name, 15) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="category-col">
                                <span>{{ Str::limit($product->category->name, 15) }}</span>
                                @if(!$product->category->is_active)
                                    <div class="text-warning small mt-1">Inactive</div>
                                @endif
                            </td>
                            <td class="variants-col">
                                @if($product->has_variants && $product->variants && $product->variants->count() > 0)
                                    <span>{{ $product->variants->count() }}</span>
                                @else
                                    <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td class="price-col">
                                @if($product->has_discount)
                                    <div class="price-current">₱{{ number_format($product->sale_price, 2) }}</div>
                                    <div class="price-original">₱{{ number_format($product->price, 2) }}</div>
                                    <div class="discount-badge">{{ $product->discount_percentage }}% OFF</div>
                                @else
                                    <div class="price-current">₱{{ number_format($product->price, 2) }}</div>
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
                                    <span class="status-archived">Archived</span>
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
                            <td class="action-col">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="action-btn btn-edit" 
                                       title="Edit Product">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($product->is_archived)
                                        <form action="{{ route('admin.products.unarchive', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="action-btn btn-unarchive"
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
            <div class="d-flex justify-content-center p-3">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-box"></i>
                <h5 class="text-muted">No Products Found</h5>
                <p class="text-muted mb-4">Try adjusting your search or filter criteria</p>
                <div class="d-flex gap-3 justify-content-center">
                    <button class="btn-import-csv" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                        <i class="fas fa-file-csv"></i> Import CSV
                    </button>
                    <a href="{{ route('admin.products.create') }}" class="btn-add-product">
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
    const brandInput = document.getElementById('brand');
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

    // Auto-submit brand filter with delay
    brandInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchLoading.style.display = 'block';
        
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 800);
    });

    // Auto-submit other filters immediately
    categorySelect.addEventListener('change', function() {
        filterForm.submit();
    });

    statusSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    perPageSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    // Clear loading indicator when form submits
    filterForm.addEventListener('submit', function() {
        searchLoading.style.display = 'none';
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
            
            // Simulate progress (in real implementation, you'd use AJAX with progress events)
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
                    // Refresh the page after 2 seconds to show new products
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