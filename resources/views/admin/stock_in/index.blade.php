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
    .btn-add-stock-in {
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
    
    .btn-add-stock-in:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
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

    /* Stock Usage Styling */
    .stock-high {
        color: #2C8F0C;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .stock-medium {
        color: #FBC02D;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .stock-low {
        color: #C62828;
        font-weight: 600;
        font-size: 0.85rem;
    }

    /* Status Badges - Compact */
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 80px;
    }
    
    .badge-success {
        background-color: #E8F5E6;
        color: #2C8F0C;
        border: 1px solid #C8E6C9;
    }
    
    .badge-warning {
        background-color: #FFF3CD;
        color: #856404;
        border: 1px solid #FFEAA7;
    }
    
    .badge-danger {
        background-color: #FFEBEE;
        color: #C62828;
        border: 1px solid #FFCDD2;
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

    /* Table Container */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        max-width: 100%;
    }

    /* Summary Cards - Compact */
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

    /* Column widths - More compact */
    .id-col { width: 70px; min-width: 70px; }
    .product-col { width: 200px; min-width: 200px; }
    .warehouse-col { width: 120px; min-width: 120px; }
    .supplier-col { width: 120px; min-width: 120px; }
    .checker-col { width: 140px; min-width: 140px; }
    .quantity-col { width: 100px; min-width: 100px; }
    .remaining-col { width: 110px; min-width: 110px; }
    .status-col { width: 100px; min-width: 100px; }
    .reason-col { width: 150px; min-width: 150px; }
    .date-col { width: 120px; min-width: 120px; }

    /* Product Info Cell - Compact */
    .product-info-cell {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .product-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }
    
    .product-name {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
        line-height: 1.2;
    }
    
    .product-type {
        color: #6c757d;
        font-size: 0.75rem;
        font-style: italic;
    }
    
    .variant-name {
        color: #6c757d;
        font-size: 0.8rem;
    }

    /* Quantity Styling */
    .quantity-badge {
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        background-color: #E8F5E6;
        color: #2C8F0C;
        border: 1px solid #C8E6C9;
        display: inline-block;
    }

    /* Date Styling */
    .date-text {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .time-text {
        font-size: 0.75rem;
        color: #adb5bd;
    }

    /* Usage Percentage */
    .usage-text {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* CSV Template Section */
    .csv-template-section {
        background-color: #F8FDF8;
        border: 1px dashed #2C8F0C;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        margin-bottom: 1rem;
    }

    /* CSV Instructions */
    .csv-instructions {
        background-color: #F8F9FA;
        border-left: 3px solid #2C8F0C;
        border-radius: 6px;
        padding: 0.75rem;
        font-size: 0.85rem;
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
        
        .product-img {
            width: 40px;
            height: 40px;
        }
        
        .summary-number {
            font-size: 1.5rem;
        }
        
        .summary-label {
            font-size: 0.8rem;
        }
        
        .status-badge {
            min-width: 70px;
            font-size: 0.7rem;
        }
        
        .quantity-badge {
            font-size: 0.8rem;
            padding: 0.15rem 0.4rem;
        }
    }
</style>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $stockIns->total() }}</div>
            <div class="summary-label">Total Stock-Ins</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $stockIns->sum('quantity') }}</div>
            <div class="summary-label">Total Quantity</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $stockIns->sum('remaining_quantity') }}</div>
            <div class="summary-label">Remaining Stock</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $stockIns->groupBy('warehouse_id')->count() }}</div>
            <div class="summary-label">Warehouses Used</div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.stock_in.index') }}" id="filterForm">
            <div class="row g-2">
                <!-- Search -->
                <div class="col-md-3">
                    <div class="mb-2 position-relative">
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

                <!-- Warehouse Filter -->
                <div class="col-md-2">
                    <div class="mb-2">
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

                <!-- Supplier Filter -->
                <div class="col-md-2">
                    <div class="mb-2">
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

                <!-- Date Range Filter -->
                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="date_range" class="form-label fw-bold">Date Range</label>
                        <select class="form-select" id="date_range" name="date_range">
                            <option value="">All Time</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                        </select>
                    </div>
                </div>

                <!-- Items per page selection -->
                <div class="col-md-2">
                    <div class="mb-2">
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
<div class="modal fade" id="csvUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-csv me-2"></i>Upload Stock-In via CSV
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.stock_in.import.csv') }}" method="POST" enctype="multipart/form-data" id="csvUploadForm">
                @csrf
                <div class="modal-body">
                    <!-- Template Download -->
                    <div class="csv-template-section">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-download me-2"></i>Download CSV Template
                        </h6>
                        <p class="text-muted mb-3">
                            Use our template to ensure your CSV file has the correct format.
                        </p>
                        <a href="{{ route('admin.stock_in.csv.template') }}" class="btn-download-csv">
                            <i class="fas fa-file-download"></i> Download Template
                        </a>
                    </div>

                    <!-- Instructions -->
                    <div class="csv-instructions">
                        <h6><i class="fas fa-info-circle me-2"></i>CSV Format Instructions</h6>
                        <ul class="small mb-0">
                            <li>File must be CSV format</li>
                            <li>Required columns: <code>product_id</code>, <code>variant_id</code>, <code>warehouse_id</code>, <code>supplier_id</code>, <code>stock_checker_id</code>, <code>quantity</code>, <code>reason</code></li>
                            <li>Ensure IDs correspond to existing products, variants, warehouses, suppliers, and stock checkers</li>
                        </ul>
                    </div>

                    <!-- File Input -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select CSV File</label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                        <div class="form-text">Only CSV files allowed, max 10MB</div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress mb-3" style="height: 6px; display: none;" id="uploadProgress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                            role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>

                    <div id="uploadStatus" class="alert" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-import-csv" id="uploadCsvBtn">
                        <i class="fas fa-upload"></i> Upload CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Stock-In Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Stock-In Management</h5>
        <div class="header-buttons">
            <button class="btn-import-csv" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                <i class="fas fa-file-csv"></i> Import CSV
            </button>
            <button class="btn-add-stock-in" data-bs-toggle="modal" data-bs-target="#stockInModal">
                <i class="fas fa-plus"></i> Add Stock-In
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @if ($stockIns->count() > 0)
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
                            <th class="product-col">Product / Variant</th>
                            <th class="warehouse-col">Warehouse</th>
                            <th class="supplier-col">Supplier</th>
                            <th class="checker-col">Stock Checker</th>
                            <th class="quantity-col">Quantity</th>
                            <th class="remaining-col">Remaining</th>
                            <th class="status-col">Status</th>
                            <th class="reason-col">Reason</th>
                            <th class="date-col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockIns as $stock)
                            @php
                                $usagePercentage = $stock->quantity > 0
                                    ? (($stock->quantity - $stock->remaining_quantity) / $stock->quantity) * 100
                                    : 0;

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
                                <td class="id-col">
                                    <small class="text-muted">#{{ $stock->id }}</small>
                                </td>
                                <td class="product-col">
                                    <div class="product-info-cell">
                                        @if($stock->product && $stock->product->image_url)
                                            <img src="{{ $stock->product->image_url }}" alt="{{ $stock->product->name }}" 
                                                 class="product-img">
                                        @elseif($stock->variant && $stock->variant->product && $stock->variant->product->image_url)
                                            <img src="{{ $stock->variant->product->image_url }}" alt="{{ $stock->variant->product->name }}" 
                                                 class="product-img">
                                        @else
                                            <div class="product-img d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            @if($stock->product && !$stock->variant)
                                                <div class="product-name">{{ Str::limit($stock->product->name, 25) }}</div>
                                                <div class="product-type">Main Product</div>
                                            @elseif($stock->variant)
                                                <div class="product-name">{{ Str::limit($stock->variant->product->name, 20) }}</div>
                                                <div class="variant-name">{{ Str::limit($stock->variant->variant_name, 15) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="warehouse-col">
                                    <span class="fw-bold">{{ Str::limit($stock->warehouse->name, 15) }}</span>
                                </td>
                                <td class="supplier-col">
                                    @if($stock->supplier)
                                        <span>{{ Str::limit($stock->supplier->name, 15) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="checker-col">
                                    @if($stock->checker)
                                        <span>{{ Str::limit($stock->checker->firstname . ' ' . $stock->checker->lastname, 20) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="quantity-col">
                                    <span class="quantity-badge">{{ $stock->quantity }}</span>
                                </td>
                                <td class="remaining-col">
                                    <span class="{{ $statusClass }}">{{ $stock->remaining_quantity }}</span>
                                    <div class="usage-text">{{ number_format($usagePercentage, 1) }}% used</div>
                                </td>
                                <td class="status-col">
                                    <span class="status-badge {{ $statusBadge }}">{{ $statusText }}</span>
                                </td>
                                <td class="reason-col">
                                    <div class="reason-text">{{ Str::limit($stock->reason, 30) }}</div>
                                </td>
                                <td class="date-col">
                                    <div class="date-text">{{ $stock->created_at->format('M j, Y') }}</div>
                                    <div class="time-text">{{ $stock->created_at->format('H:i') }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($stockIns->hasPages())
            <div class="d-flex justify-content-center p-3">
                {{ $stockIns->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-boxes"></i>
                <h5 class="text-muted">No Stock-In Records</h5>
                <p class="text-muted mb-4">Start by adding your first stock-in record or importing via CSV.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <button class="btn-import-csv" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                        <i class="fas fa-file-csv"></i> Import CSV
                    </button>
                    <button class="btn-add-stock-in" data-bs-toggle="modal" data-bs-target="#stockInModal">
                        <i class="fas fa-plus"></i> Add First Stock-In
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Stock-In Modal (Add/Edit) -->
<div class="modal fade" id="stockInModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Stock-In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockInForm" method="POST" action="{{ route('admin.stock_in.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="modal-body">
                    <div class="mb-3 position-relative">
                        <label class="form-label">Product</label>
                        <input type="text" class="form-control" id="productField"
                            placeholder="Click to select product" readonly>
                        <input type="hidden" name="product_id" id="productId">
                    </div>

                    <div class="mb-3" id="variantContainer" style="display: none;">
                        <label class="form-label">Variant</label>
                        <select class="form-select" name="product_variant_id" id="variantSelect">
                            <option value="">Select Variant</option>
                            @foreach ($variants as $variant)
                                <option value="{{ $variant->id }}" data-product-id="{{ $variant->product_id }}">
                                    {{ Str::limit($variant->product->name, 20) }} / {{ Str::limit($variant->variant_name, 15) }}
                                </option>
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
                                    {{ $checker->firstname }} {{ $checker->lastname }}
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
                        <input type="text" class="form-control" name="reason" id="reasonInput" 
                               placeholder="e.g., New stock, Restock, Return">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-add-stock-in" id="saveBtn">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Product Selection Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <input type="text" id="productSearch" class="form-control"
                            placeholder="Search by name or SKU">
                    </div>
                    <div class="col-md-4">
                        <select id="productFilter" class="form-select">
                            <option value="active" selected>Active</option>
                            <option value="archived">Archived</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover align-middle" id="productTable">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            @foreach($products as $product)
                                <tr data-archived="{{ $product->is_archived ? '1' : '0' }}">
                                    <td>
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                class="img-thumbnail"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td><small class="text-muted">{{ $product->sku }}</small></td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($product->has_variants)
                                            <span class="badge bg-info">{{ $product->total_stock }}</span>
                                        @else
                                            @if($product->stock_quantity > 10)
                                                <span class="text-success">{{ $product->stock_quantity }}</span>
                                            @elseif($product->stock_quantity > 0)
                                                <span class="text-warning">{{ $product->stock_quantity }}</span>
                                            @else
                                                <span class="text-danger">{{ $product->stock_quantity }}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success select-product-btn"
                                            data-id="{{ $product->id }}" 
                                            data-name="{{ $product->name }}"
                                            data-has-variants="{{ $product->variants && $product->variants->count() ? '1' : '0' }}">
                                            Select
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Auto-search for main filter form ---
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const warehouseSelect = document.getElementById('warehouse_id');
    const supplierSelect = document.getElementById('supplier_id');
    const dateRangeSelect = document.getElementById('date_range');
    const perPageSelect = document.getElementById('per_page');
    const searchLoading = document.getElementById('searchLoading');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            if (searchLoading) searchLoading.style.display = 'block';
            searchTimeout = setTimeout(() => filterForm.submit(), 800);
        });
    }

    // Auto-submit other filters immediately
    [warehouseSelect, supplierSelect, dateRangeSelect, perPageSelect].forEach(el => {
        if (el) {
            el.addEventListener('change', () => filterForm.submit());
        }
    });

    if (filterForm) {
        filterForm.addEventListener('submit', () => {
            if (searchLoading) searchLoading.style.display = 'none';
        });
    }

    // --- Stock-In Form ---
    const productField = document.getElementById('productField');
    const productIdInput = document.getElementById('productId');
    const variantContainer = document.getElementById('variantContainer');
    const variantSelect = document.getElementById('variantSelect');

    // Hide variant field initially
    if (variantContainer) variantContainer.style.display = 'none';

    // Show product modal when product field is clicked
    if (productField) {
        productField.addEventListener('click', function() {
            const productModal = new bootstrap.Modal(document.getElementById('productModal'));
            productModal.show();
        });
    }

    // Handle product selection from modal
    document.querySelectorAll('.select-product-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            const productName = this.dataset.name;
            const hasVariants = this.dataset.hasVariants === '1';

            // Set selected product
            productField.value = productName;
            productIdInput.value = productId;

            if (hasVariants && variantContainer && variantSelect) {
                variantContainer.style.display = 'block';

                // Filter variant options to only the selected product
                Array.from(variantSelect.options).forEach(option => {
                    if (option.value === "" || option.dataset.productId === productId) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });

                // Reset selected variant
                variantSelect.value = "";
            } else if (variantContainer && variantSelect) {
                variantContainer.style.display = 'none';
                variantSelect.value = "";
            }

            // Close modal
            const modalEl = document.getElementById('productModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        });
    });

    // --- CSV Upload functionality ---
    const csvUploadForm = document.getElementById('csvUploadForm');
    if (csvUploadForm) {
        const uploadProgress = document.getElementById('uploadProgress');
        const uploadStatus = document.getElementById('uploadStatus');
        const uploadCsvBtn = document.getElementById('uploadCsvBtn');
        const progressBar = uploadProgress.querySelector('.progress-bar');

        csvUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            uploadProgress.style.display = 'block';
            uploadStatus.style.display = 'none';
            uploadCsvBtn.disabled = true;
            uploadCsvBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showUploadStatus('success', data.message);
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    showUploadStatus('danger', data.message || 'Upload failed.');
                }
            })
            .catch(err => {
                showUploadStatus('danger', 'Upload failed: ' + err.message);
            })
            .finally(() => {
                uploadCsvBtn.disabled = false;
                uploadCsvBtn.innerHTML = '<i class="fas fa-upload"></i> Upload CSV';
            });

            function showUploadStatus(type, message) {
                uploadStatus.className = `alert alert-${type}`;
                uploadStatus.innerHTML = message;
                uploadStatus.style.display = 'block';
                uploadProgress.style.display = 'none';
            }
        });

        document.getElementById('csvUploadModal').addEventListener('hidden.bs.modal', function() {
            csvUploadForm.reset();
            uploadProgress.style.display = 'none';
            uploadStatus.style.display = 'none';
        });
    }

    // --- Product Modal: Search & Filter ---
    const tableBody = document.getElementById('productTableBody');
    const productSearch = document.getElementById('productSearch');
    const productFilter = document.getElementById('productFilter');
    
    if (productSearch && tableBody) {
        const allRows = Array.from(tableBody.querySelectorAll('tr'));

        function filterProducts() {
            const searchTerm = productSearch.value.toLowerCase();
            const filterValue = productFilter.value;

            allRows.forEach(row => {
                const name = row.children[1].textContent.toLowerCase();
                const sku = row.children[2].textContent.toLowerCase();
                const isArchived = row.dataset.archived === '1';
                const matchesSearch = searchTerm === '' || name.includes(searchTerm) || sku.includes(searchTerm);
                const matchesFilter = filterValue === 'all' || 
                                    (filterValue === 'active' && !isArchived) || 
                                    (filterValue === 'archived' && isArchived);

                row.style.display = matchesSearch && matchesFilter ? '' : 'none';
            });
        }

        productSearch.addEventListener('input', filterProducts);
        productFilter.addEventListener('change', filterProducts);
    }

    // Form submission loading state
    const stockInForm = document.getElementById('stockInForm');
    if (stockInForm) {
        stockInForm.addEventListener('submit', function(e) {
            const saveBtn = document.getElementById('saveBtn');
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
            }
        });
    }
});
</script>
@endpush
@endsection