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

        .batch-info {
            font-size: 0.875em;
        }

        .batch-item {
            padding: 2px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .batch-item:last-child {
            border-bottom: none;
        }
    </style>

    <!-- Search -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.stock_out.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 position-relative">
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
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="per_page" class="form-label fw-bold">Items per page</label>
                            <select class="form-select" id="per_page" name="per_page">
                                @foreach ([5, 10, 15, 25, 50] as $option)
                                    <option value="{{ $option }}"
                                        {{ request('per_page', 10) == $option ? 'selected' : '' }}>
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
            <h5 class="mb-0">Stock-Out List</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stockOutModal">
                <i class="bi bi-plus-circle"></i> Stock-Out
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered align-middle w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product / Variant</th>
                        <th>Quantity</th>
                        <th>Stock-In Batches (FIFO)</th>
                        <th>Reason</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockOuts as $stock)
                        <tr>
                            <td>{{ $stock->id }}</td>
                            <td>
                                @if ($stock->product)
                                    {{ $stock->product->name }}
                                @elseif($stock->variant)
                                    {{ $stock->variant->product->name }} / {{ $stock->variant->variant_name }}
                                @endif
                            </td>
                            <td>{{ $stock->quantity }}</td>
                            <td>
                                @if ($stock->stockInBatches->count() > 0)
                                    <div class="batch-info">
                                        @foreach ($stock->stockInBatches as $batch)
                                            <div class="batch-item">
                                                Batch #{{ $batch->id }}: {{ $batch->pivot->deducted_quantity }} pcs
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No batch info</span>
                                @endif
                            </td>
                            <td>{{ $stock->reason }}</td>
                            <td>{{ $stock->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 d-flex justify-content-center">
                {{ $stockOuts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Stock-Out Modal (Add/Edit) -->
    <div class="modal fade" id="stockOutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="stockOutForm" method="POST" action="{{ route('admin.stock_out.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add Stock-Out</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <div class="mb-3 position-relative">
                            <label class="form-label">Product</label>
                            <input type="text" class="form-control" id="stockOutProductField"
                                placeholder="Click to select product" readonly>
                            <input type="hidden" name="product_id" id="stockOutProductId">
                        </div>

                        <div class="mb-3" id="stockOutVariantContainer">
                            <label class="form-label">Variant</label>
                            <select class="form-select" name="product_variant_id" id="stockOutVariantSelect">
                                <option value="">Select Variant</option>
                                @foreach ($variants as $variant)
                                    <option value="{{ $variant->id }}" data-product-id="{{ $variant->product_id }}">
                                        {{ $variant->product->name }} / {{ $variant->variant_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" id="quantityInput" min="1"
                                required>
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

    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Search and Filter -->
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
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="productTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Variants</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
                                @foreach ($products as $product)
                                    <tr data-archived="{{ $product->is_archived ? '1' : '0' }}">
                                        <td>
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                class="img-thumbnail"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($product->has_variants && $product->variants->count() > 0)
                                                {{ $product->variants->count() }} variants
                                            @else
                                                None
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="select-product-btn btn btn-sm btn-primary"
                                                data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                data-has-variants="{{ $product->variants->count() ? 1 : 0 }}">
                                                Select
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <nav>
                        <ul class="pagination" id="pagination"></ul>
                    </nav>

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

                searchInput?.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchLoading && (searchLoading.style.display = 'block');
                    searchTimeout = setTimeout(() => filterForm.submit(), 800);
                });

                perPageSelect?.addEventListener('change', () => filterForm.submit());

                filterForm?.addEventListener('submit', () => {
                    searchLoading && (searchLoading.style.display = 'none');
                });

                // --- Stock-Out Modal Elements ---
                const stockModal = new bootstrap.Modal(document.getElementById('stockOutModal'));
                const form = document.getElementById('stockOutForm');
                const modalTitle = document.getElementById('modalTitle');
                const formMethod = document.getElementById('formMethod');

                const productField = document.getElementById('stockOutProductField');
                const productIdInput = document.getElementById('stockOutProductId');
                const variantContainer = document.getElementById('stockOutVariantContainer');
                const variantSelect = document.getElementById('stockOutVariantSelect');

                if (variantContainer) variantContainer.style.display = 'none';

                // --- Product Modal Selection (Reusable for multiple fields) ---
                document.querySelectorAll('[id$="ProductField"]').forEach(input => {
                    input.addEventListener('click', function() {
                        document.querySelectorAll('[id$="ProductField"]').forEach(f => f.classList
                            .remove('product-active'));
                        this.classList.add('product-active');

                        const productModal = new bootstrap.Modal(document.getElementById(
                            'productModal'));
                        productModal.show();
                    });
                });

                document.querySelectorAll('.select-product-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.dataset.id.toString();
                        const productName = this.dataset.name;
                        const hasVariants = this.dataset.hasVariants === '1';

                        const activeField = document.querySelector('.product-active');
                        if (!activeField) return;

                        const target = activeField.id.replace('ProductField', '');
                        const productField = document.getElementById(target + 'ProductField');
                        const productIdInput = document.getElementById(target + 'ProductId');
                        const variantContainer = document.getElementById(target + 'VariantContainer');
                        const variantSelect = document.getElementById(target + 'VariantSelect');

                        productField.value = productName;
                        productIdInput.value = productId;

                        if (hasVariants && variantContainer && variantSelect) {
                            variantContainer.style.display = 'block';
                            Array.from(variantSelect.options).forEach(option => {
                                option.style.display = (option.value === "" || option.dataset
                                    .productId === productId) ? 'block' : 'none';
                            });
                            variantSelect.value = "";
                        } else if (variantContainer && variantSelect) {
                            variantContainer.style.display = 'none';
                            variantSelect.value = "";
                        }

                        const modalEl = document.getElementById('productModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();
                        activeField.classList.remove('product-active');
                    });
                });

                // --- Edit Stock-Out Functionality ---
                document.querySelectorAll('.editStockBtn').forEach(button => {
                    button.addEventListener('click', () => {
                        const id = button.dataset.id;

                        productField.value = button.dataset.productName;
                        productIdInput.value = button.dataset.productId;
                        variantSelect.value = button.dataset.variantId;
                        document.getElementById('quantityInput').value = button.dataset.quantity;
                        document.getElementById('reasonInput').value = button.dataset.reason;

                        form.action = `/admin/stock-outs/${id}`;
                        formMethod.value = 'PUT';
                        modalTitle.textContent = 'Edit Stock-Out';
                        stockModal.show();

                        if (variantSelect.value) variantContainer.style.display = 'block';
                    });
                });

                // --- Reset modal on close ---
                document.getElementById('stockOutModal').addEventListener('hidden.bs.modal', () => {
                    form.action = "{{ route('admin.stock_out.store') }}";
                    formMethod.value = 'POST';
                    modalTitle.textContent = 'Add Stock-Out';
                    form.reset();
                    if (variantContainer) variantContainer.style.display = 'none';
                });

                // --- Product Modal: Search, Filter & Pagination ---
                const tableBody = document.getElementById('productTableBody');
                const productSearch = document.getElementById('productSearch');
                const productFilter = document.getElementById('productFilter');
                const pagination = document.getElementById('pagination');
                const rowsPerPage = 5;
                let currentPage = 1;
                const allRows = Array.from(tableBody.querySelectorAll('tr'));

                function renderTable() {
                    let filteredRows = allRows;

                    // Search
                    const term = productSearch.value.toLowerCase();
                    if (term) {
                        filteredRows = filteredRows.filter(row => {
                            const name = row.children[1].textContent.toLowerCase();
                            const sku = row.children[2].textContent.toLowerCase();
                            return name.includes(term) || sku.includes(term);
                        });
                    }

                    // Filter
                    const filter = productFilter.value;
                    if (filter === 'active') filteredRows = filteredRows.filter(row => row.dataset.archived === '0');
                    else if (filter === 'archived') filteredRows = filteredRows.filter(row => row.dataset.archived ===
                        '1');

                    // Pagination
                    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                    const start = (currentPage - 1) * rowsPerPage;
                    const end = start + rowsPerPage;
                    const paginatedRows = filteredRows.slice(start, end);

                    tableBody.innerHTML = '';
                    paginatedRows.forEach(row => tableBody.appendChild(row));

                    renderPagination(totalPages);
                }

                function renderPagination(totalPages) {
                    pagination.innerHTML = '';
                    for (let i = 1; i <= totalPages; i++) {
                        const li = document.createElement('li');
                        li.className = 'page-item' + (i === currentPage ? ' active' : '');
                        const a = document.createElement('a');
                        a.className = 'page-link';
                        a.href = '#';
                        a.textContent = i;
                        a.addEventListener('click', e => {
                            e.preventDefault();
                            currentPage = i;
                            renderTable();
                        });
                        li.appendChild(a);
                        pagination.appendChild(li);
                    }
                }

                productSearch.addEventListener('input', () => {
                    currentPage = 1;
                    renderTable();
                });
                productFilter.addEventListener('change', () => {
                    currentPage = 1;
                    renderTable();
                });

                renderTable();

            });
        </script>
    @endpush
@endsection
