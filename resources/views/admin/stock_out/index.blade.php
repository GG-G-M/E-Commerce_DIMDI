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
    .product-col { width: 200px; min-width: 200px; }
    .quantity-col { width: 90px; min-width: 90px; }
    .batch-col { width: 180px; min-width: 180px; }
    .reason-col { width: 150px; min-width: 150px; }
    .date-col { width: 120px; min-width: 120px; }
    .action-col { width: 100px; min-width: 100px; }

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
    
    .variant-name {
        color: #6c757d;
        font-size: 0.8rem;
        font-style: italic;
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
        <div class="header-buttons">
            <button class="btn btn-add-stock-out" data-bs-toggle="modal" data-bs-target="#stockOutModal">
                <i class="fas fa-minus-circle"></i> Stock-Out
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @if($stockOuts->count())
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
                            <th class="product-col">Product / Variant</th>
                            <th class="quantity-col">Quantity</th>
                            <th class="batch-col">Stock-In Batches (FIFO)</th>
                            <th class="reason-col">Reason</th>
                            <th class="date-col">Date</th>
                            <th class="action-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockOuts as $stock)
                            <tr data-id="{{ $stock->id }}">
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
                                            @if($stock->product)
                                                <div class="product-name">{{ Str::limit($stock->product->name, 25) }}</div>
                                            @elseif($stock->variant)
                                                <div class="product-name">{{ Str::limit($stock->variant->product->name, 20) }}</div>
                                                <div class="variant-name">{{ Str::limit($stock->variant->variant_name, 15) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="quantity-col">
                                    <span class="fw-bold text-danger">{{ $stock->quantity }}</span>
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
                                    <div class="date-text">{{ $stock->created_at->format('Y-m-d H:i') }}</div>
                                </td>
                                <td class="action-col">
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

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
                    <button class="btn btn-add-stock-out" data-bs-toggle="modal" data-bs-target="#stockOutModal">
                        <i class="fas fa-minus-circle"></i> Add Stock-Out
                    </button>
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
                        <input type="text" class="form-control" id="stockOutProductField"
                            placeholder="Click to select product" readonly>
                        <input type="hidden" name="product_id" id="stockOutProductId">
                    </div>

                    <div class="mb-3" id="stockOutVariantContainer" style="display: none;">
                        <label class="form-label">Variant</label>
                        <select class="form-select" name="product_variant_id" id="stockOutVariantSelect">
                            <option value="">Select Variant</option>
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}" data-product-id="{{ $variant->product_id }}">
                                    {{ $variant->product->name }} / {{ $variant->variant_name }}
                                </option>
                            @endforeach
                        </select>
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
                    <button type="submit" class="btn btn-add-stock-out" id="saveBtn">
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

    // --- Stock-Out Modal Elements ---
    const stockModal = new bootstrap.Modal(document.getElementById('stockOutModal'));
    const form = document.getElementById('stockOutForm');
    const modalTitle = document.getElementById('modalTitle');
    const formMethod = document.getElementById('formMethod');

    const productField = document.getElementById('stockOutProductField');
    const productIdInput = document.getElementById('stockOutProductId');
    const variantContainer = document.getElementById('stockOutVariantContainer');
    const variantSelect = document.getElementById('stockOutVariantSelect');

    // --- Product Modal Selection ---
    if (productField) {
        productField.addEventListener('click', function() {
            const productModal = new bootstrap.Modal(document.getElementById('productModal'));
            productModal.show();
        });
    }

    // Product selection in modal
    document.querySelectorAll('.select-product-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            const productName = this.dataset.name;
            const hasVariants = this.dataset.hasVariants === '1';

            productField.value = productName;
            productIdInput.value = productId;

            if (hasVariants && variantContainer && variantSelect) {
                variantContainer.style.display = 'block';
                Array.from(variantSelect.options).forEach(option => {
                    option.style.display = (option.value === "" || option.dataset.productId === productId) ? 'block' : 'none';
                });
                variantSelect.value = "";
            } else if (variantContainer && variantSelect) {
                variantContainer.style.display = 'none';
                variantSelect.value = "";
            }

            const modalEl = document.getElementById('productModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        });
    });

    // --- Edit Stock-Out Functionality ---
    document.querySelectorAll('.editStockBtn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const productName = this.dataset.productName || '';

            productField.value = productName;
            productIdInput.value = this.dataset.productId || '';
            
            if (variantSelect && this.dataset.variantId) {
                variantSelect.value = this.dataset.variantId;
                variantContainer.style.display = 'block';
            } else if (variantContainer) {
                variantContainer.style.display = 'none';
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
    });

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
    if (form) {
        form.addEventListener('submit', function(e) {
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