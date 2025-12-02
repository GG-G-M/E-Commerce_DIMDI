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

        .btn-success {
            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
            border: none;
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #1E6A08, #2C8F0C);
            color: white;
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

        .modal-header {
            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
            color: white;
        }

        .form-label {
            font-weight: 600;
            color: #2C8F0C;
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

        .stock-high {
            color: #2C8F0C;
            font-weight: 600;
        }

        .stock-medium {
            color: #FBC02D;
            font-weight: 600;
        }

        .stock-low {
            color: #C62828;
            font-weight: 600;
        }

        .badge-success {
            background-color: #2C8F0C !important;
        }

        .badge-warning {
            background-color: #FBC02D !important;
            color: #000;
        }

        .badge-info {
            background-color: #17a2b8 !important;
        }

        .product-name {
            font-weight: 600;
            color: #2C8F0C;
        }

        .variant-name {
            color: #6c757d;
            font-size: 0.875em;
        }

        .quantity-badge {
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
            font-weight: 600;
            font-size: 0.75em;
        }

        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #2C8F0C;
        }

        .summary-card {
            background: linear-gradient(135deg, #E8F5E6, #F8FDF8);
            border: 1px solid #2C8F0C;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .summary-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2C8F0C;
            line-height: 1;
        }

        .summary-label {
            font-size: 0.875rem;
            color: #6c757d;
            font-weight: 600;
        }

        .csv-template-section {
            background: #e8f5e9;
            border: 1px dashed #2C8F0C;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .csv-instructions {
            background: #f8f9fa;
            border-left: 4px solid #2C8F0C;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="summary-card text-center">
                <div class="summary-number">{{ $stockIns->total() }}</div>
                <div class="summary-label">Total Stock-Ins</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card text-center">
                <div class="summary-number">{{ $stockIns->sum('quantity') }}</div>
                <div class="summary-label">Total Quantity</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card text-center">
                <div class="summary-number">{{ $stockIns->sum('remaining_quantity') }}</div>
                <div class="summary-label">Remaining Stock</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card text-center">
                <div class="summary-number">{{ $stockIns->groupBy('warehouse_id')->count() }}</div>
                <div class="summary-label">Warehouses Used</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.stock_in.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3 position-relative">
                            <label for="search" class="form-label fw-bold">Search Stock-Ins</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Search by product, reason...">
                            <div class="search-loading" id="searchLoading">
                                <div class="spinner-border spinner-border-sm text-success" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="warehouse_id" class="form-label fw-bold">Warehouse</label>
                            <select class="form-select" id="warehouse_id" name="warehouse_id">
                                <option value="">All Warehouses</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label fw-bold">Supplier</label>
                            <select class="form-select" id="supplier_id" name="supplier_id">
                                <option value="">All Suppliers</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="date_range" class="form-label fw-bold">Date Range</label>
                            <select class="form-select" id="date_range" name="date_range">
                                <option value="">All Time</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                                <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="per_page" class="form-label fw-bold">Items per page</label>
                            <select class="form-select" id="per_page" name="per_page">
                                @foreach ([5, 10, 15, 25, 50] as $option)
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
    <div class="modal fade" id="csvUploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.stock_in.import.csv') }}" method="POST" enctype="multipart/form-data"
                    id="csvUploadForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-file-csv me-2"></i>Upload Stock-In via CSV</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Template Download -->
                        <div class="csv-template-section">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-download me-2"></i>Download CSV Template
                            </h6>
                            <p class="text-muted mb-3">
                                Use our template to ensure your CSV file has the correct format.
                            </p>
                            <a href="{{ route('admin.stock_in.csv.template') }}" class="btn btn-success">
                                <i class="fas fa-file-download me-2"></i>Download Template
                            </a>
                        </div>

                        <!-- Instructions -->
                        <div class="csv-instructions">
                            <h6><i class="fas fa-info-circle me-2"></i>CSV Format Instructions</h6>
                            <ul class="small mb-0">
                                <li>File must be CSV format</li>
                                <li>Required columns: <code>product_id</code>, <code>variant_id</code>,
                                    <code>warehouse_id</code>, <code>supplier_id</code>, <code>stock_checker_id</code>,
                                    <code>quantity</code>, <code>reason</code>
                                </li>
                                <li>Ensure IDs correspond to existing products, variants, warehouses, suppliers, and stock
                                    checkers</li>
                            </ul>
                        </div>

                        <!-- File Input -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select CSV File</label>
                            <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                            <div class="form-text">Only CSV files allowed, max 10MB</div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress mb-3" style="height: 20px; display: none;" id="uploadProgress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                <span class="progress-text">0%</span>
                            </div>
                        </div>

                        <div id="uploadStatus" class="alert" style="display: none;"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="uploadCsvBtn">
                            <i class="fas fa-upload me-2"></i>Upload CSV
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Stock-In Table -->
    <div class="card card-custom">
        <div class="card-header card-header-custom">
            <h5 class="mb-0">Stock-In List</h5>
            <div class="btn-group">
                <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                    <i class="fas fa-file-csv me-2"></i> Import CSV
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stockInModal">
                    <i class="bi bi-plus-circle"></i> Add Stock-In
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($stockIns->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product / Variant</th>
                                <th>Warehouse</th>
                                <th>Supplier</th>
                                <th>Stock Checker</th>
                                <th>Quantity</th>
                                <th>Remaining</th>
                                <th>Status</th>
                                <th>Reason</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stockIns as $stock)
                                @php
                                    $usagePercentage = $stock->quantity > 0 ? 
                                        (($stock->quantity - $stock->remaining_quantity) / $stock->quantity) * 100 : 0;
                                    
                                    if ($usagePercentage >= 80) {
                                        $statusClass = 'stock-low';
                                        $statusBadge = 'badge-danger';
                                        $statusText = 'High Usage';
                                    } elseif ($usagePercentage >= 50) {
                                        $statusClass = 'stock-medium';
                                        $statusBadge = 'badge-warning';
                                        $statusText = 'Medium Usage';
                                    } else {
                                        $statusClass = 'stock-high';
                                        $statusBadge = 'badge-success';
                                        $statusText = 'Low Usage';
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <span class="text-muted">#{{ $stock->id }}</span>
                                    </td>
                                    <td>
                                        @if ($stock->product)
                                            <div class="product-name">{{ $stock->product->name }}</div>
                                            <div class="text-muted small">Main Product</div>
                                        @elseif($stock->variant)
                                            <div class="product-name">{{ $stock->variant->product->name }}</div>
                                            <div class="variant-name">{{ $stock->variant->variant_name }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $stock->warehouse->name }}</span>
                                    </td>
                                    <td>
                                        @if($stock->supplier)
                                            <span class="text-dark">{{ $stock->supplier->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($stock->checker)
                                            <span class="text-dark">
                                                {{ $stock->checker->firstname }} {{ $stock->checker->lastname }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="quantity-badge bg-primary">{{ $stock->quantity }}</span>
                                    </td>
                                    <td>
                                        <span class="{{ $statusClass }}">{{ $stock->remaining_quantity }}</span>
                                        <div class="text-muted small">
                                            {{ number_format($usagePercentage, 1) }}% used
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusBadge }}">{{ $statusText }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ Str::limit($stock->reason, 30) }}</span>
                                    </td>
                                    <td>
                                        <div class="text-muted small">
                                            {{ $stock->created_at->format('M j, Y') }}
                                        </div>
                                        <div class="text-muted smaller">
                                            {{ $stock->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $stockIns->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-boxes"></i>
                    <h4 class="text-success">No Stock-In Records</h4>
                    <p class="text-muted mb-4">Start by adding your first stock-in record or importing via CSV.</p>
                    <div class="btn-group">
                        <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                            <i class="fas fa-file-csv me-2"></i> Import CSV
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stockInModal">
                            <i class="bi bi-plus-circle"></i> Add First Stock-In
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Stock-In Modal (Add/Edit) -->
    <div class="modal fade" id="stockInModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="stockInForm" method="POST" action="{{ route('admin.stock_in.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add Stock-In</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <select class="form-select" name="product_id" id="productSelect">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Variant</label>
                            <select class="form-select" name="product_variant_id" id="variantSelect">
                                <option value="">Select Variant</option>
                                @foreach ($variants as $variant)
                                    <option value="{{ $variant->id }}">{{ $variant->product->name }} /
                                        {{ $variant->variant_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Warehouse</label>
                            <select class="form-select" name="warehouse_id" id="warehouseSelect" required>
                                <option value="">Select Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Supplier</label>
                            <select class="form-select" name="supplier_id" id="supplierSelect" required>
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stock Checker</label>
                            <select class="form-select" name="stock_checker_id" id="checkerSelect" required>
                                <option value="">Select Stock Checker</option>
                                @foreach ($stockCheckers as $checker)
                                    <option value="{{ $checker->id }}">
                                        {{ $checker->firstname }} {{ $checker->middlename }} {{ $checker->lastname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" id="quantityInput"
                                min="1" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <input type="text" class="form-control" name="reason" id="reasonInput">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" id="saveBtn">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-filter functionality
            const filterForm = document.getElementById('filterForm');
            const searchInput = document.getElementById('search');
            const warehouseSelect = document.getElementById('warehouse_id');
            const supplierSelect = document.getElementById('supplier_id');
            const dateRangeSelect = document.getElementById('date_range');
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

            // Auto-submit other filters immediately
            warehouseSelect.addEventListener('change', function() {
                filterForm.submit();
            });

            supplierSelect.addEventListener('change', function() {
                filterForm.submit();
            });

            dateRangeSelect.addEventListener('change', function() {
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

                    uploadProgress.style.display = 'block';
                    uploadStatus.style.display = 'none';
                    uploadCsvBtn.disabled = true;
                    uploadCsvBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';

                    let progress = 0;
                    const progressInterval = setInterval(() => {
                        progress += 5;
                        if (progress <= 100) {
                            progressBar.style.width = progress + '%';
                            progressBar.setAttribute('aria-valuenow', progress);
                            progressText.textContent = progress + '%';
                        }
                    }, 100);

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
                                showUploadStatus('danger', data.message || 'Upload failed.');
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