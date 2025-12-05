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

    .btn-info {
        background: linear-gradient(135deg, #17a2b8, #6f42c1);
        border: none;
    }

    .btn-info:hover {
        background: linear-gradient(135deg, #138496, #5a32a3);
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

    .status-active {
        color: #2C8F0C;
        font-weight: 600;
    }

    .status-inactive {
        color: #6c757d;
        font-weight: 600;
    }

    .status-archived {
        color: #6c757d;
        font-style: italic;
    }

    .status-featured {
        color: #2C8F0C;
        font-weight: 600;
    }

    .stock-high {
        color: #2C8F0C;
        font-weight: 600;
    }

    .stock-low {
        color: #ffc107;
        font-weight: 600;
    }

    .stock-out {
        color: #dc3545;
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

    .btn-outline-warning {
        border-color: #FBC02D;
        color: #FBC02D;
    }

    .btn-outline-warning:hover {
        background-color: #FBC02D;
        border-color: #FBC02D;
        color: white;
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
                    <button type="submit" class="btn btn-primary" id="uploadCsvBtn">
                        <i class="fas fa-upload me-2"></i>Upload CSV
                    </button>
                </div>
            </form>
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

<!-- Product Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Product List</h5>
        <div class="d-flex align-items-center">
            <span class="text-white me-3 small d-none d-md-block">
                Total: {{ $products->total() }} products
            </span>
            <div class="btn-group">
                <button type="button" class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                    <i class="fas fa-file-csv me-1"></i> Import CSV
                </button>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Product
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Variants</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                 class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong><br>
                            <small class="text-muted">SKU: {{ $product->sku }}</small>
                        </td>
                        <td>
                            @if($product->brand_id && $product->brand)
                                <span class="text-dark">
                                    {{ $product->brand->name }}
                                </span>
                            @else
                                <span class="text-muted">
                                    No Brand
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="text-dark">
                                {{ $product->category->name }}
                            </span>
                            @if(!$product->category->is_active)
                                <div class="text-warning small mt-1">
                                    Inactive Category
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($product->has_variants && $product->variants && $product->variants->count() > 0)
                                <span class="text-dark">
                                    {{ $product->variants->count() }} variants
                                </span>
                            @else
                                <span class="text-muted">
                                    No Variants
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($product->has_discount)
                                <strong class="text-danger">₱{{ number_format($product->sale_price, 2) }}</strong>
                                <div class="text-muted text-decoration-line-through small">
                                    ₱{{ number_format($product->price, 2) }}
                                </div>
                                <span class="badge bg-danger small">{{ $product->discount_percentage }}% OFF</span>
                            @else
                                <strong class="text-success">₱{{ number_format($product->price, 2) }}</strong>
                            @endif
                        </td>
                        <td>
                            @if($product->has_variants)
                                <span class="stock-high">
                                    {{ $product->total_stock }} units
                                </span>
                            @else
                                @if($product->stock_quantity > 10)
                                    <span class="stock-high">
                                        {{ $product->stock_quantity }}
                                    </span>
                                @elseif($product->stock_quantity > 0)
                                    <span class="stock-low">
                                        {{ $product->stock_quantity }}
                                    </span>
                                @else
                                    <span class="stock-out">
                                        {{ $product->stock_quantity }}
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($product->is_archived)
                                <span class="status-archived">
                                    Archived
                                </span>
                            @else
                                @if($product->is_effectively_inactive)
                                    <span class="status-inactive">
                                        Inactive
                                    </span>
                                @else
                                    <span class="status-active">
                                        Active
                                    </span>
                                @endif
                                @if($product->is_featured)
                                    <div class="status-featured small mt-1">
                                        Featured
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="btn btn-sm btn-outline-success me-1" 
                                   title="Edit Product">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($product->is_archived)
                                    <form action="{{ route('admin.products.unarchive', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                onclick="return confirm('Are you sure you want to unarchive this product?')"
                                                title="Unarchive Product">
                                            <i class="fas fa-box-open"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.products.archive', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning"
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

        <div class="d-flex justify-content-center mt-3">
            {{ $products->links() }}
        </div>
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
        }, 800); // 800ms delay after typing stops
    });

    // Auto-submit brand filter with delay
    brandInput.addEventListener('input', function() {
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