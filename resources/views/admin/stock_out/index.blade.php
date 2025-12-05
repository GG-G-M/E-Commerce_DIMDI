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
                @foreach($stockOuts as $stock)
                <tr>
                    <td>{{ $stock->id }}</td>
                    <td>
                        @if($stock->product)
                            {{ $stock->product->name }}
                        @elseif($stock->variant)
                            {{ $stock->variant->product->name }} / {{ $stock->variant->variant_name }}
                        @endif
                    </td>
                    <td>{{ $stock->quantity }}</td>
                    <td>
                        @if($stock->stockInBatches->count() > 0)
                            <div class="batch-info">
                                @foreach($stock->stockInBatches as $batch)
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

                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <select class="form-select" name="product_id" id="productSelect">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Variant</label>
                        <select class="form-select" name="product_variant_id" id="variantSelect">
                            <option value="">Select Variant</option>
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}">{{ $variant->product->name }} / {{ $variant->variant_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantityInput" min="1" required>
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
        // Auto-search functionality
        const filterForm = document.getElementById('filterForm');
        const searchInput = document.getElementById('search');
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

        // Auto-submit per page selection immediately
        perPageSelect.addEventListener('change', function() {
            filterForm.submit();
        });

        // Clear loading indicator when form submits
        filterForm.addEventListener('submit', function() {
            searchLoading.style.display = 'none';
        });

        // Edit functionality
        const stockModal = new bootstrap.Modal(document.getElementById('stockOutModal'));
        const form = document.getElementById('stockOutForm');
        const modalTitle = document.getElementById('modalTitle');
        const formMethod = document.getElementById('formMethod');

        document.querySelectorAll('.editStockBtn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;

                // Fill modal fields
                document.getElementById('productSelect').value = button.dataset.productId;
                document.getElementById('variantSelect').value = button.dataset.variantId;
                document.getElementById('quantityInput').value = button.dataset.quantity;
                document.getElementById('reasonInput').value = button.dataset.reason;

                // Change form action and method
                form.action = `/admin/stock-outs/${id}`;
                formMethod.value = 'PUT';
                modalTitle.textContent = 'Edit Stock-Out';

                // Show modal
                stockModal.show();
            });
        });

        // Reset modal when hidden (for Add Stock-Out)
        document.getElementById('stockOutModal').addEventListener('hidden.bs.modal', () => {
            form.action = "{{ route('admin.stock_out.store') }}";
            formMethod.value = 'POST';
            modalTitle.textContent = 'Add Stock-Out';
            form.reset();
        });
    });
</script>
@endpush
@endsection