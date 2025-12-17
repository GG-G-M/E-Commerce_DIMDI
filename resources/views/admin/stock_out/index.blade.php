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

    /* Add Stock-Out Button - Matching Add Product Button */
    .btn-add-stock-out {
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
    
    .btn-add-stock-out:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
    }

    .btn-add-stock-out:active {
        transform: translateY(0);
    }

    /* Action Buttons - Consistent with products page */
    .action-buttons {
        display: flex;
        gap: 4px;
        flex-wrap: nowrap;
        justify-content: center;
        align-items: center;
        padding: 2px 0;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        border: 1.5px solid;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        flex-shrink: 0;
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
        transform: translateY(-1px);
    }
    
    .btn-delete {
        background-color: white;
        border-color: #C62828;
        color: #C62828;
    }
    
    .btn-delete:hover {
        background-color: #C62828;
        color: white;
        transform: translateY(-1px);
    }
    
    /* Ensure forms don't interfere with button layout */
    .action-buttons form {
        display: inline;
        margin: 0;
        padding: 0;
    }
    
    .action-buttons form button {
        margin: 0;
        padding: 0;
    }
    
    /* Prevent action column from wrapping */
    .action-col {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Ensure table cells don't expand */
    .table td.action-col {
        overflow: hidden;
    }
    
    /* Fix table layout for better action button display */
    .table {
        table-layout: fixed;
        word-wrap: break-word;
    }

    /* Table Styling - Compact */
    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
    }
    
    /* Center align all table headers and cells */
    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
    }
    
    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 0.75rem 0.5rem;
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
    }

    .table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        text-align: center;
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

    /* Modal Styling - Matching products page */
    .modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(44, 143, 12, 0.15);
    }
    
    .modal-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1rem 1.5rem;
    }

    .modal-title {
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .btn-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        opacity: 0.8;
    }
    
    .btn-close:hover {
        opacity: 1;
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

    /* Clickable field styling */
    .clickable-field {
        cursor: pointer;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .clickable-field:hover {
        background-color: #e9ecef;
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.15rem rgba(44,143,12,0.2);
    }

    .clickable-field:focus {
        background-color: #fff;
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

    /* Empty State - Matching products page */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #C8E6C9;
        margin-bottom: 1rem;
    }

    /* Table styling for no scroll bars - Matching products page */
    .table {
        width: 100%;
        max-width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    /* Prevent any scroll bars in the table card */
    .card-custom .card-body {
        overflow-x: hidden;
        overflow-y: hidden;
    }

    .card-custom {
        overflow: hidden;
    }

    /* Responsive table - always fixed layout for better fit */
    .table {
        table-layout: fixed;
    }

    /* Column widths - Consistent with products page */
    .id-col { width: 70px; min-width: 70px; text-align: center; }
    .image-col { width: 70px; min-width: 70px; text-align: center; }
    .product-col { width: 160px; min-width: 160px; text-align: center; }
    .quantity-col { width: 90px; min-width: 90px; text-align: center; }
    .batch-col { width: 180px; min-width: 180px; text-align: center; }
    .reason-col { width: 150px; min-width: 150px; text-align: center; }
    .date-col { width: 120px; min-width: 120px; text-align: center; }
    .action-col { width: 100px; min-width: 100px; text-align: center; }

    /* Product Info Cell - Matching products page */
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
        margin: 0 auto;
        display: block;
    }
    
    .product-name {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
        line-height: 1.2;
        text-align: center;
    }
    
    .variant-name {
        color: #6c757d;
        font-size: 0.8rem;
        font-style: italic;
        text-align: center;
    }

    /* Quantity Styling - No badges */
    .quantity-text {
        font-weight: 700;
        font-size: 1rem;
        color: #333;
    }
    
    .stock-out-quantity {
        font-weight: 700;
        font-size: 1rem;
        color: #C62828;
    }

    /* Batch Info */
    .batch-info {
        max-height: 100px;
        overflow-y: auto;
        font-size: 0.8rem;
    }
    
    .batch-item {
        padding: 4px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .batch-item:last-child {
        border-bottom: none;
    }
    
    .batch-id {
        font-weight: 600;
        color: #2C8F0C;
    }
    
    .batch-quantity {
        color: #6c757d;
        font-weight: 600;
    }

    /* Reason Styling */
    .reason-text {
        font-size: 0.85rem;
        color: #495057;
        max-width: 200px;
        word-wrap: break-word;
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

    /* Stock Availability Styling */
    .stock-info {
        background-color: #F8F9FA;
        border: 1px solid #E9ECEF;
        border-radius: 6px;
        padding: 0.75rem;
        margin-top: 0.5rem;
        font-size: 0.85rem;
    }
    
    .stock-info .stock-label {
        font-weight: 600;
        color: #2C8F0C;
        margin-bottom: 0.25rem;
    }
    
    .stock-info .stock-available {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .stock-info.stock-high {
        border-color: #C8E6C9;
        background-color: #F8FDF8;
    }
    
    .stock-info.stock-high .stock-available {
        color: #2C8F0C;
    }
    
    .stock-info.stock-medium {
        border-color: #FFEAA7;
        background-color: #FFFBF0;
    }
    
    .stock-info.stock-medium .stock-available {
        color: #FBC02D;
    }
    
    .stock-info.stock-low {
        border-color: #FFCDD2;
        background-color: #FFF5F5;
    }
    
    .stock-info.stock-low .stock-available {
        color: #C62828;
    }
    
    .stock-info.stock-out {
        border-color: #FFCDD2;
        background-color: #FFF5F5;
    }
    
    .stock-info.stock-out .stock-available {
        color: #C62828;
        font-size: 1.2rem;
    }

    /* Error Styling */
    .error-message {
        background-color: #FFEBEE;
        border: 1px solid #FFCDD2;
        border-radius: 6px;
        padding: 0.75rem;
        margin-top: 0.5rem;
        color: #C62828;
        font-size: 0.85rem;
        display: none;
    }
    
    .error-message i {
        margin-right: 0.5rem;
    }
    
    .form-control.is-invalid {
        border-color: #C62828;
        box-shadow: 0 0 0 0.15rem rgba(198, 40, 40, 0.2);
    }
    
    .quantity-warning {
        border: 2px solid #FBC02D;
        background-color: #FFFBF0;
    }
    
    .quantity-error {
        border: 2px solid #C62828;
        background-color: #FFF5F5;
    }

    /* Loading Spinner */
    .stock-loading {
        display: none;
        color: #2C8F0C;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
    
    .stock-loading.show {
        display: block;
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
    
    /* Header button group - Consistent with products page */
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
        
        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }
        
        .product-img {
            width: 40px;
            height: 40px;
        }
        
        .batch-info {
            max-height: 80px;
            font-size: 0.75rem;
        }
        
        .stock-out-quantity {
            font-size: 0.9rem;
        }
    }
</style>

<!-- Search and Filters -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.stock_out.index') }}" id="filterForm">
            <div class="row g-2">
                <!-- Search -->
                <div class="col-md-6">
                    <div class="mb-2 position-relative">
                        <label for="search" class="form-label fw-bold">Search Stock-Out</label>
                        <input type="text" class="form-control" id="search" name="search" 
                            value="{{ request('search') }}" placeholder="Search by product, variant, or reason...">
                        <div class="search-loading" id="searchLoading">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items per page selection -->
                <div class="col-md-3">
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

<!-- Stock-Out Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Stock-Out Management</h5>
        <div class="ms-auto d-flex gap-2">
            <button class="btn btn-add-stock-out" data-bs-toggle="modal" data-bs-target="#stockOutModal">
                {{-- <i class="fas fa-minus-circle"></i> --}}
                Stock-Out
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @if($stockOuts->count())
            <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
                            <th class="image-col">Image</th>
                            <th class="product-col">Product / Variant</th>
                            <th class="quantity-col">Quantity</th>
                            <th class="batch-col">Stock-In Batches (FIFO)</th>
                            <th class="reason-col">Reason</th>
                            <th class="date-col">Date</th>
                            {{-- <th class="action-col">Actions</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockOuts as $stock)
                            <tr data-id="{{ $stock->id }}">
                                <td class="id-col">
                                    <small class="text-muted">#{{ $stock->id }}</small>
                                </td>
                                <td class="image-col">
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
                                </td>
                                <td class="product-col">
                                    <div class="text-center">
                                        @if($stock->product)
                                            <div class="product-name">{{ Str::limit($stock->product->name, 25) }}</div>
                                        @elseif($stock->variant)
                                            <div class="product-name">{{ Str::limit($stock->variant->product->name, 20) }}</div>
                                            <div class="variant-name">{{ Str::limit($stock->variant->variant_name, 15) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="quantity-col">
                                    <div class="stock-out-quantity">{{ $stock->quantity }}</div>
                                </td>
                                <td class="batch-col">
                                    @if($stock->stockInBatches->count() > 0)
                                        <div class="batch-info">
                                            @foreach($stock->stockInBatches as $batch)
                                                <div class="batch-item">
                                                    <span class="batch-id">Batch #{{ $batch->id }}</span>: 
                                                    <span class="batch-quantity">{{ $batch->pivot->deducted_quantity }} pcs</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">No batch info</span>
                                    @endif
                                </td>
                                <td class="reason-col">
                                    <div class="reason-text">{{ Str::limit($stock->reason, 30) }}</div>
                                </td>
                                <td class="date-col">
                                    <div class="date-text">{{ $stock->created_at->format('M j, Y') }}</div>
                                    <div class="time-text">{{ $stock->created_at->format('h:i A') }}</div>
                                </td>
                                {{-- <td class="action-col">
                                    <div class="action-buttons">
                                        <button class="action-btn btn-edit editStockBtn" 
                                                data-id="{{ $stock->id }}"
                                                data-product-id="{{ $stock->product_id }}"
                                                data-product-name="{{ $stock->product ? $stock->product->name : '' }}"
                                                data-variant-id="{{ $stock->product_variant_id }}"
                                                data-quantity="{{ $stock->quantity }}"
                                                data-reason="{{ $stock->reason }}"
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.stock_out.destroy', $stock) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn btn-delete" 
                                                    onclick="return confirm('Are you sure you want to delete this stock-out record?')"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @if($stockOuts->hasPages())
            <div class="d-flex justify-content-center p-3">
                {{ $stockOuts->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-minus-circle"></i>
                <h5 class="text-muted">No Stock-Out Records Found</h5>
                <p class="text-muted mb-4">Add your first stock-out record to get started</p>
                <div class="d-flex gap-3 justify-content-center">
                    {{-- <button class="btn btn-add-stock-out" data-bs-toggle="modal" data-bs-target="#stockOutModal">
                        <i class="fas fa-minus-circle"></i>
                        Add First Stock-Out
                    </button> --}}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Stock-Out Modal (Add/Edit) -->
<div class="modal fade" id="stockOutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Stock-Out</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockOutForm" method="POST" action="{{ route('admin.stock_out.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="modal-body">
                    <div class="mb-3 position-relative">
                        <label class="form-label">Product</label>
                        <div class="position-relative">
                            <input type="text" class="form-control clickable-field" id="stockOutProductField"
                                placeholder="Click to select product" readonly>
                            <div class="position-absolute end-0 top-50 translate-middle-y me-3" style="pointer-events: none;">
                                <i class="fas fa-search text-muted"></i>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" id="stockOutProductId">
                    </div>

                    <div class="mb-3" id="stockOutVariantContainer" style="display: none;">
                        <label class="form-label">Variant</label>
                        <div class="position-relative">
                            <input type="text" class="form-control clickable-field" id="stockOutVariantField"
                                placeholder="Click to select variant" readonly>
                            <div class="position-absolute end-0 top-50 translate-middle-y me-3" style="pointer-events: none;">
                                <i class="fas fa-search text-muted"></i>
                            </div>
                            <input type="hidden" name="product_variant_id" id="stockOutVariantId">
                        </div>
                        <div class="form-text">Select a specific variant of the chosen product</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantityInput" min="1" required>
                        <div class="stock-loading" id="stockLoading">
                            <i class="fas fa-spinner fa-spin me-1"></i> Checking stock availability...
                        </div>
                        <div class="stock-info" id="stockInfo" style="display: none;">
                            <div class="stock-label">Available Stock:</div>
                            <div class="stock-available" id="stockAvailable">0</div>
                            <div class="stock-status" id="stockStatus">-</div>
                        </div>
                        <div class="error-message" id="quantityError">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span id="errorText"></span>
                        </div>
                        <input type="hidden" name="product_id" id="stockOutProductId">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <input type="text" class="form-control" name="reason" id="reasonInput" placeholder="e.g., Sold, Damaged, Returned">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantityInput" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <input type="text" class="form-control" name="reason" id="reasonInput" placeholder="e.g., Sold, Damaged, Returned">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-add-stock-out" id="saveBtn">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-add-stock-out" id="saveBtn">
                        <i class="fas fa-save me-1"></i> Save Stock-Out
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
                    <div class="col-md-6">
                        <input type="text" id="productSearch" class="form-control"
                            placeholder="Search by name or SKU">
                    </div>
                    <div class="col-md-3">
                        <select id="productFilter" class="form-select">
                            <option value="active" selected>Active</option>
                            <option value="archived">Archived</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="productPerPage" class="form-select">
                            <option value="5" {{ request('product_per_page', 10) == '5' ? 'selected' : '' }}>5 per page</option>
                            <option value="10" {{ request('product_per_page', 10) == '10' ? 'selected' : '' }}>10 per page</option>
                            <option value="15" {{ request('product_per_page', 10) == '15' ? 'selected' : '' }}>15 per page</option>
                            <option value="25" {{ request('product_per_page', 10) == '25' ? 'selected' : '' }}>25 per page</option>
                            <option value="50" {{ request('product_per_page', 10) == '50' ? 'selected' : '' }}>50 per page</option>
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
                                <th>Variants</th>
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
                                        @if($product->has_variants && $product->variants && $product->variants->count() > 0)
                                            <span class="badge bg-info">
                                                <i class="fas fa-layer-group me-1"></i>
                                                {{ $product->variants->count() }} variant(s)
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-box me-1"></i>
                                                No variants
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->has_variants)
                                            <span class="fw-bold text-info">{{ $product->total_stock }}</span>
                                        @else
                                            @if($product->stock_quantity > 10)
                                                <span class="fw-bold text-success">{{ $product->stock_quantity }}</span>
                                            @elseif($product->stock_quantity > 0)
                                                <span class="fw-bold text-warning">{{ $product->stock_quantity }}</span>
                                            @else
                                                <span class="fw-bold text-danger">{{ $product->stock_quantity }}</span>
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
                
                <!-- JavaScript-based Pagination -->
                <div id="productPagination" class="d-flex justify-content-center mt-3" style="display: none;">
                    <nav aria-label="Product selection pagination">
                        <ul class="pagination pagination-sm" id="productPaginationList">
                            <!-- Pagination will be populated by JavaScript -->
                        </ul>
                    </nav>
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted" id="productPaginationInfo">
                        Showing 1 to {{ $products->count() }} of {{ $products->count() }} products
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Variant Selection Modal -->
<div class="modal fade" id="variantModal" tabindex="-1" aria-labelledby="variantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Variant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="variantSearch" class="form-control"
                            placeholder="Search by variant name">
                    </div>
                    <div class="col-md-6">
                        <select id="variantPerPage" class="form-select">
                            <option value="5" {{ request('variant_per_page', 10) == '5' ? 'selected' : '' }}>5 per page</option>
                            <option value="10" {{ request('variant_per_page', 10) == '10' ? 'selected' : '' }}>10 per page</option>
                            <option value="15" {{ request('variant_per_page', 10) == '15' ? 'selected' : '' }}>15 per page</option>
                            <option value="25" {{ request('variant_per_page', 10) == '25' ? 'selected' : '' }}>25 per page</option>
                            <option value="50" {{ request('variant_per_page', 10) == '50' ? 'selected' : '' }}>50 per page</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover align-middle" id="variantTable">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Variant Name</th>
                                <th>SKU</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="variantTableBody">
                            <!-- Variants will be loaded here -->
                        </tbody>
                    </table>
                </div>
                
                <!-- JavaScript-based Pagination -->
                <div id="variantPagination" class="d-flex justify-content-center mt-3" style="display: none;">
                    <nav aria-label="Variant selection pagination">
                        <ul class="pagination pagination-sm" id="variantPaginationList">
                            <!-- Pagination will be populated by JavaScript -->
                        </ul>
                    </nav>
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted" id="variantPaginationInfo">
                        <!-- Pagination info will be updated by JavaScript -->
                    </small>
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

    if (perPageSelect) {
        perPageSelect.addEventListener('change', () => filterForm.submit());
    }

    if (filterForm) {
        filterForm.addEventListener('submit', () => {
            if (searchLoading) searchLoading.style.display = 'none';
        });
    }

    // --- Variant Loading Functions ---
    async function loadVariants(page = 1, search = '', perPage = 10, productId = null) {
        const tableBody = document.getElementById('variantTableBody');
        const pagination = document.getElementById('variantPagination');
        const paginationInfo = document.getElementById('variantPaginationInfo');
        
        if (!tableBody) return;
        
        // Show loading state
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading variants...</td></tr>';
        
        try {
            const params = new URLSearchParams({
                page: page,
                search: search,
                per_page: perPage,
                product_id: productId
            });
            
            const response = await fetch(`{{ route('admin.stock_in.variants') }}?${params}`);
            const data = await response.json();
            
            if (data.success) {
                // Update table
                updateVariantTable(data.variants);
                
                // Update pagination
                updateVariantPagination(data.pagination);
                
                // Update pagination info
                if (paginationInfo) {
                    paginationInfo.textContent = `Showing ${data.pagination.first_item} to ${data.pagination.last_item} of ${data.pagination.total} variants`;
                }
                
                // Show pagination if there are multiple pages
                if (pagination && data.pagination.last_page > 1) {
                    pagination.style.display = 'flex';
                } else if (pagination) {
                    pagination.style.display = 'none';
                }
            } else {
                throw new Error(data.message || 'Failed to load variants');
            }
        } catch (error) {
            console.error('Error loading variants:', error);
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error loading variants. Please try again.</td></tr>';
        }
    }
    
    // Function to update variant table
    function updateVariantTable(variants) {
        const tableBody = document.getElementById('variantTableBody');
        if (!tableBody) return;
        
        let html = '';
        variants.forEach(variant => {
            const imageHtml = variant.product && variant.product.image_url
                ? `<img src="${variant.product.image_url}" alt="${variant.product.name}" class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;">`
                : `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-box text-muted"></i></div>`;
            
            const stockHtml = variant.stock_quantity > 10
                ? `<span class="text-success">${variant.stock_quantity}</span>`
                : variant.stock_quantity > 0
                    ? `<span class="text-warning">${variant.stock_quantity}</span>`
                    : `<span class="text-danger">${variant.stock_quantity}</span>`;
            
            const priceHtml = variant.price
                ? `<span class="text-success fw-bold">₱${variant.price}</span>`
                : `<span class="text-muted">-</span>`;
            
            html += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            ${imageHtml}
                            <div>
                                <div class="fw-bold">${variant.product.name}</div>
                                <small class="text-muted">${variant.product.sku}</small>
                            </div>
                        </div>
                    </td>
                    <td>${variant.variant_name}</td>
                    <td><small class="text-muted">${variant.sku}</small></td>
                    <td>${stockHtml}</td>
                    <td>${priceHtml}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success select-variant-btn"
                            data-id="${variant.id}" 
                            data-name="${variant.variant_name}"
                            data-product-name="${variant.product.name}">
                            Select
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
        
        // Re-attach event listeners to new buttons
        document.querySelectorAll('.select-variant-btn').forEach(button => {
            button.addEventListener('click', handleVariantSelection);
        });
    }
    
    // Function to update variant pagination controls
    function updateVariantPagination(paginationData) {
        const paginationList = document.getElementById('variantPaginationList');
        if (!paginationList) return;
        
        let html = '';
        
        // Previous button
        if (paginationData.current_page > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="changeVariantPage(${paginationData.current_page - 1})">‹</a></li>`;
        } else {
            html += `<li class="page-item disabled"><span class="page-link">‹</span></li>`;
        }
        
        // Page numbers
        const start = Math.max(1, paginationData.current_page - 2);
        const end = Math.min(paginationData.last_page, paginationData.current_page + 2);
        
        if (start > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="changeVariantPage(1)">1</a></li>`;
            if (start > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }
        
        for (let i = start; i <= end; i++) {
            const activeClass = i === paginationData.current_page ? 'active' : '';
            html += `<li class="page-item ${activeClass}"><a class="page-link" href="#" onclick="changeVariantPage(${i})">${i}</a></li>`;
        }
        
        if (end < paginationData.last_page) {
            if (end < paginationData.last_page - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            html += `<li class="page-item"><a class="page-link" href="#" onclick="changeVariantPage(${paginationData.last_page})">${paginationData.last_page}</a></li>`;
        }
        
        // Next button
        if (paginationData.current_page < paginationData.last_page) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="changeVariantPage(${paginationData.current_page + 1})">›</a></li>`;
        } else {
            html += `<li class="page-item disabled"><span class="page-link">›</span></li>`;
        }
        
        paginationList.innerHTML = html;
        
        // Update current page
        currentVariantPage = paginationData.current_page;
    }
    
    // Global function for changing variant pages
    window.changeVariantPage = function(page) {
        loadVariants(page, currentVariantSearch, currentVariantPerPage, selectedProductId);
    }
    
    // Handle variant selection
    function handleVariantSelection() {
        const variantId = this.dataset.id;
        const variantName = this.dataset.name;
        const productName = this.dataset.productName;
        
        // Set selected variant
        variantField.value = `${productName} / ${variantName}`;
        variantIdInput.value = variantId;
        
        // Close modal
        const modalEl = document.getElementById('variantModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
        
        // Check stock for selected variant
        getStockForProduct(selectedProductId, variantId);
    }

    // --- Stock-Out Modal Elements ---
    const stockModal = new bootstrap.Modal(document.getElementById('stockOutModal'));
    const form = document.getElementById('stockOutForm');
    const modalTitle = document.getElementById('modalTitle');
    const formMethod = document.getElementById('formMethod');

    const productField = document.getElementById('stockOutProductField');
    const productIdInput = document.getElementById('stockOutProductId');
    const variantField = document.getElementById('stockOutVariantField');
    const variantIdInput = document.getElementById('stockOutVariantId');
    const variantContainer = document.getElementById('stockOutVariantContainer');
    
    let selectedProductId = null;
    let currentVariantPage = 1;
    let currentVariantPerPage = 10;
    let currentVariantSearch = '';

    // Hide variant field initially
    if (variantContainer) variantContainer.style.display = 'none';

    // Show product modal when product field is clicked
    if (productField) {
        productField.addEventListener('click', function() {
            const productModal = new bootstrap.Modal(document.getElementById('productModal'));
            productModal.show();
        });
    }

    // Show variant modal when variant field is clicked
    if (variantField) {
        variantField.addEventListener('click', function() {
            if (!selectedProductId) {
                alert('Please select a product first.');
                return;
            }
            const variantModal = new bootstrap.Modal(document.getElementById('variantModal'));
            variantModal.show();
            // Load variants for the selected product
            loadVariants(1, '', currentVariantPerPage, selectedProductId);
        });
    }

    // Attach product selection handlers (will be re-attached when products load)
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('select-product-btn')) {
            handleProductSelection.call(e.target, e);
        }
    });

    // --- Edit Stock-Out Functionality ---
    document.querySelectorAll('.editStockBtn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const productName = this.dataset.productName || '';
            const productId = this.dataset.productId || '';
            const variantId = this.dataset.variantId || '';

            productField.value = productName;
            productIdInput.value = productId;
            selectedProductId = productId;
            
            if (variantId) {
                variantContainer.style.display = 'block';
                variantField.value = 'Variant selected';
                variantIdInput.value = variantId;
                // Check stock for variant
                getStockForProduct(productId, variantId);
            } else if (variantContainer) {
                variantContainer.style.display = 'none';
                variantField.value = '';
                variantIdInput.value = '';
                // Check stock for product
                getStockForProduct(productId);
            }
            
            document.getElementById('quantityInput').value = this.dataset.quantity || '';
            document.getElementById('reasonInput').value = this.dataset.reason || '';

            form.action = `/admin/stock-out/${id}`;
            formMethod.value = 'PUT';
            modalTitle.textContent = 'Edit Stock-Out';
            stockModal.show();
        });
    });

    // --- Reset modal on close ---
    document.getElementById('stockOutModal').addEventListener('hidden.bs.modal', function() {
        form.action = "{{ route('admin.stock_out.store') }}";
        formMethod.value = 'POST';
        modalTitle.textContent = 'Add Stock-Out';
        form.reset();
        if (variantContainer) variantContainer.style.display = 'none';
        // Reset stock info
        const stockInfo = document.getElementById('stockInfo');
        if (stockInfo) stockInfo.style.display = 'none';
        hideError();
    });

    // JavaScript-only pagination for product modal
    let currentProductPage = 1;
    let currentProductPerPage = 10;
    let currentProductSearch = '';
    let currentProductFilter = 'active';

    // Function to fetch and display products
    async function loadProducts(page = 1, search = '', filter = 'active', perPage = 10) {
        const tableBody = document.getElementById('productTableBody');
        const pagination = document.getElementById('productPagination');
        const paginationInfo = document.getElementById('productPaginationInfo');
        
        if (!tableBody) return;
        
        // Show loading state
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading products...</td></tr>';
        
        try {
            const params = new URLSearchParams({
                page: page,
                search: search,
                filter: filter,
                per_page: perPage
            });
            
            const response = await fetch(`{{ route('admin.stock_in.products') }}?${params}`);
            const data = await response.json();
            
            if (data.success) {
                // Update table
                updateProductTable(data.products);
                
                // Update pagination
                updatePagination(data.pagination);
                
                // Update pagination info
                if (paginationInfo) {
                    paginationInfo.textContent = `Showing ${data.pagination.first_item} to ${data.pagination.last_item} of ${data.pagination.total} products`;
                }
                
                // Show pagination if there are multiple pages
                if (pagination && data.pagination.last_page > 1) {
                    pagination.style.display = 'flex';
                } else if (pagination) {
                    pagination.style.display = 'none';
                }
            } else {
                throw new Error(data.message || 'Failed to load products');
            }
        } catch (error) {
            console.error('Error loading products:', error);
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error loading products. Please try again.</td></tr>';
        }
    }
    
    // Function to update product table
    function updateProductTable(products) {
        const tableBody = document.getElementById('productTableBody');
        if (!tableBody) return;
        
        let html = '';
        products.forEach(product => {
            const hasVariants = product.has_variants && product.variants_count > 0;
            const imageHtml = product.image_url 
                ? `<img src="${product.image_url}" alt="${product.name}" class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;">`
                : `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-box text-muted"></i></div>`;
            
            const variantBadge = hasVariants 
                ? `<span class="badge bg-info"><i class="fas fa-layer-group me-1"></i> ${product.variants_count} variant(s)</span>`
                : `<span class="badge bg-secondary"><i class="fas fa-box me-1"></i> No variants</span>`;
            
            const stockHtml = hasVariants 
                ? `<span class="badge bg-info">${product.total_stock}</span>`
                : product.stock_quantity > 10 
                    ? `<span class="text-success">${product.stock_quantity}</span>`
                    : product.stock_quantity > 0 
                        ? `<span class="text-warning">${product.stock_quantity}</span>`
                        : `<span class="text-danger">${product.stock_quantity}</span>`;
            
            html += `
                <tr data-archived="${product.is_archived ? '1' : '0'}">
                    <td>${imageHtml}</td>
                    <td>${product.name}</td>
                    <td><small class="text-muted">${product.sku}</small></td>
                    <td>${product.category_name}</td>
                    <td>${variantBadge}</td>
                    <td>${stockHtml}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success select-product-btn"
                            data-id="${product.id}" 
                            data-name="${product.name}"
                            data-has-variants="${hasVariants ? '1' : '0'}">
                            Select
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
        
        // Re-attach event listeners to new buttons
        document.querySelectorAll('.select-product-btn').forEach(button => {
            button.addEventListener('click', handleProductSelection);
        });
    }
    
    // Function to update pagination controls
    function updatePagination(paginationData) {
        const paginationList = document.getElementById('productPaginationList');
        if (!paginationList) return;
        
        let html = '';
        
        // Previous button
        if (paginationData.current_page > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="changeProductPage(${paginationData.current_page - 1})">‹</a></li>`;
        } else {
            html += `<li class="page-item disabled"><span class="page-link">‹</span></li>`;
        }
        
        // Page numbers
        const start = Math.max(1, paginationData.current_page - 2);
        const end = Math.min(paginationData.last_page, paginationData.current_page + 2);
        
        if (start > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="changeProductPage(1)">1</a></li>`;
            if (start > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }
        
        for (let i = start; i <= end; i++) {
            const activeClass = i === paginationData.current_page ? 'active' : '';
            html += `<li class="page-item ${activeClass}"><a class="page-link" href="#" onclick="changeProductPage(${i})">${i}</a></li>`;
        }
        
        if (end < paginationData.last_page) {
            if (end < paginationData.last_page - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            html += `<li class="page-item"><a class="page-link" href="#" onclick="changeProductPage(${paginationData.last_page})">${paginationData.last_page}</a></li>`;
        }
        
        // Next button
        if (paginationData.current_page < paginationData.last_page) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="changeProductPage(${paginationData.current_page + 1})">›</a></li>`;
        } else {
            html += `<li class="page-item disabled"><span class="page-link">›</span></li>`;
        }
        
        paginationList.innerHTML = html;
        
        // Update current page
        currentProductPage = paginationData.current_page;
    }
    
    // Global function for changing pages
    window.changeProductPage = function(page) {
        loadProducts(page, currentProductSearch, currentProductFilter, currentProductPerPage);
    }
    
    // Handle product selection
    function handleProductSelection() {
        const productId = this.dataset.id;
        const productName = this.dataset.name;
        const hasVariants = this.dataset.hasVariants === '1';
        
        // Set selected product
        productField.value = productName;
        productIdInput.value = productId;
        selectedProductId = productId; // Track for variant selection
        
        if (hasVariants && variantContainer && variantField) {
            variantContainer.style.display = 'block';
            // Reset variant field
            variantField.value = '';
            if (variantIdInput) variantIdInput.value = '';
        } else if (variantContainer && variantField) {
            variantContainer.style.display = 'none';
            // Reset variant field
            variantField.value = '';
            if (variantIdInput) variantIdInput.value = '';
        }
        
        // Close modal
        const modalEl = document.getElementById('productModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
        
        // Check stock for selected product
        getStockForProduct(productId);
    }

    // --- Product Modal: Search & Filter with AJAX ---
    const productSearch = document.getElementById('productSearch');
    const productFilter = document.getElementById('productFilter');
    const productPerPageSelect = document.getElementById('productPerPage');
    
    if (productSearch) {
        let searchTimeout;
        productSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentProductSearch = this.value;
                loadProducts(1, currentProductSearch, currentProductFilter, currentProductPerPage);
            }, 300);
        });
    }
    
    if (productFilter) {
        productFilter.addEventListener('change', function() {
            currentProductFilter = this.value;
            loadProducts(1, currentProductSearch, currentProductFilter, currentProductPerPage);
        });
    }
    
    if (productPerPageSelect) {
        productPerPageSelect.addEventListener('change', function() {
            currentProductPerPage = this.value;
            loadProducts(1, currentProductSearch, currentProductFilter, currentProductPerPage);
        });
    }
    
    // --- Variant Modal: Search & Filter with AJAX ---
    const variantSearch = document.getElementById('variantSearch');
    const variantPerPageSelect = document.getElementById('variantPerPage');
    
    if (variantSearch) {
        let variantSearchTimeout;
        variantSearch.addEventListener('input', function() {
            clearTimeout(variantSearchTimeout);
            variantSearchTimeout = setTimeout(() => {
                currentVariantSearch = this.value;
                loadVariants(1, currentVariantSearch, currentVariantPerPage, selectedProductId);
            }, 300);
        });
    }
    
    if (variantPerPageSelect) {
        variantPerPageSelect.addEventListener('change', function() {
            currentVariantPerPage = this.value;
            loadVariants(1, currentVariantSearch, currentVariantPerPage, selectedProductId);
        });
    }
    
    // Initialize product modal with current data
    const productModal = document.getElementById('productModal');
    if (productModal) {
        productModal.addEventListener('shown.bs.modal', function() {
            // Load products on modal open if table is empty or shows loading message
            const tableBody = document.getElementById('productTableBody');
            if (!tableBody.children.length || 
                (tableBody.children[0].children.length === 1 && tableBody.children[0].children[0].textContent.includes('Loading'))) {
                loadProducts(currentProductPage, currentProductSearch, currentProductFilter, currentProductPerPage);
            }
        });
    }

    // --- Stock Validation Functions ---
    let currentStock = 0;
    let isCheckingStock = false;

    function getStockForProduct(productId, variantId = null) {
        if (!productId || isCheckingStock) return;
        
        isCheckingStock = true;
        const stockLoading = document.getElementById('stockLoading');
        const stockInfo = document.getElementById('stockInfo');
        const stockAvailable = document.getElementById('stockAvailable');
        const stockStatus = document.getElementById('stockStatus');
        
        if (stockLoading) stockLoading.classList.add('show');
        if (stockInfo) stockInfo.style.display = 'none';
        hideError(); // Clear any previous errors
        
        const formData = new FormData();
        formData.append('product_id', productId);
        if (variantId) formData.append('variant_id', variantId);
        
        fetch('{{ route("admin.stock_out.check-stock") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            currentStock = data.available_stock || 0;
            
            if (stockLoading) stockLoading.classList.remove('show');
            if (stockInfo) stockInfo.style.display = 'block';
            if (stockAvailable) stockAvailable.textContent = currentStock;
            
            // Set stock status and styling
            let statusClass = '';
            let statusText = '';
            
            if (currentStock === 0) {
                statusClass = 'stock-out';
                statusText = 'OUT OF STOCK';
            } else if (currentStock <= 10) {
                statusClass = 'stock-low';
                statusText = 'Low Stock';
            } else if (currentStock <= 50) {
                statusClass = 'stock-medium';
                statusText = 'Medium Stock';
            } else {
                statusClass = 'stock-high';
                statusText = 'Good Stock';
            }
            
            stockInfo.className = `stock-info ${statusClass}`;
            if (stockStatus) stockStatus.textContent = statusText;
            
            // Trigger quantity validation
            validateQuantity();
        })
        .catch(error => {
            console.error('Error checking stock:', error);
            if (stockLoading) stockLoading.classList.remove('show');
            showError('Failed to check stock availability');
        })
        .finally(() => {
            isCheckingStock = false;
        });
    }

    function validateQuantity() {
        const quantityInput = document.getElementById('quantityInput');
        const quantityError = document.getElementById('quantityError');
        const errorText = document.getElementById('errorText');
        
        if (!quantityInput) return;
        
        const quantity = parseInt(quantityInput.value);
        
        // Remove previous validation classes
        quantityInput.classList.remove('is-invalid', 'quantity-warning', 'quantity-error');
        hideError();
        
        if (!quantity || quantity <= 0) {
            return;
        }
        
        if (quantity > currentStock) {
            quantityInput.classList.add('quantity-error');
            showError(`Quantity (${quantity}) exceeds available stock (${currentStock})`);
            return false;
        } else if (quantity > currentStock * 0.8) {
            quantityInput.classList.add('quantity-warning');
            // Warning but don't prevent - user can still proceed
            return true;
        }
        
        return true;
    }
    
    function showError(message) {
        const quantityError = document.getElementById('quantityError');
        const errorText = document.getElementById('errorText');
        
        if (quantityError) {
            quantityError.style.display = 'block';
        }
        if (errorText) {
            errorText.textContent = message;
        }
    }
    
    function hideError() {
        const quantityError = document.getElementById('quantityError');
        if (quantityError) {
            quantityError.style.display = 'none';
        }
    }

    // --- Quantity Input Validation ---
    const quantityInput = document.getElementById('quantityInput');
    if (quantityInput) {
        let validationTimeout;
        
        quantityInput.addEventListener('input', function() {
            clearTimeout(validationTimeout);
            validationTimeout = setTimeout(validateQuantity, 300);
        });
        
        quantityInput.addEventListener('blur', validateQuantity);
    }

    // Form submission loading state
    if (form) {
        form.addEventListener('submit', function(e) {
            const quantityInput = document.getElementById('quantityInput');
            
            // Final validation before submission
            if (quantityInput && !validateQuantity()) {
                e.preventDefault();
                showError('Please fix the quantity error before submitting');
                return false;
            }
            
            const saveBtn = document.getElementById('saveBtn');
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
            }
        });
    }
});

// Toast notification function - Matching products page
function showToast(message, type = 'success') {
    // Remove existing toasts
    document.querySelectorAll('.upper-middle-toast').forEach(toast => toast.remove());
    
    const bgColors = {
        'success': '#2C8F0C',
        'error': '#dc3545',
        'warning': '#ffc107',
        'info': '#17a2b8'
    };
    
    const icons = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-triangle',
        'warning': 'fa-exclamation-circle',
        'info': 'fa-info-circle'
    };
    
    const bgColor = bgColors[type] || bgColors.success;
    const icon = icons[type] || icons.success;
    const textColor = type === 'warning' ? 'text-dark' : 'text-white';
    
    const toast = document.createElement('div');
    toast.className = 'upper-middle-toast position-fixed start-50 translate-middle-x p-3';
    toast.style.cssText = `
        top: 100px;
        z-index: 9999;
        min-width: 300px;
        text-align: center;
    `;
    
    toast.innerHTML = `
        <div class="toast align-items-center border-0 show shadow-lg" role="alert" style="background-color: ${bgColor}; border-radius: 10px;">
            <div class="d-flex justify-content-center align-items-center p-3">
                <div class="toast-body ${textColor} d-flex align-items-center">
                    <i class="fas ${icon} me-2 fs-5"></i>
                    <span class="fw-semibold">${message}</span>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}
</script>
@endpush
@endsection