@extends('layouts.admin')

@section('content')
<style>
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #2C8F0C;
    }
    .page-header h1 { color: #2C8F0C; font-weight: 700; }
    .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.3s ease; }
    .card-custom:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.15); }
    .card-header-custom { background: linear-gradient(135deg, #2C8F0C, #4CAF50); color: white; font-weight: 600; border-top-left-radius: 12px; border-top-right-radius: 12px; }
    .btn-primary { background: linear-gradient(135deg, #2C8F0C, #4CAF50); border: none; }
    .btn-primary:hover { background: linear-gradient(135deg, #1E6A08, #2C8F0C); }
    .btn-warning { background: #FBC02D; border: none; color: #fff; }
    .btn-danger { background: #C62828; border: none; }
    .table th { background-color: #E8F5E6; color: #2C8F0C; font-weight: 600; border-bottom: 2px solid #2C8F0C; }
    .table tbody tr:hover { background-color: #F8FDF8; transition: background-color 0.2s ease; }
    .modal-header { background: linear-gradient(135deg, #2C8F0C, #4CAF50); color: white; }
    .form-label { font-weight: 600; color: #2C8F0C; }
</style>

<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Stock-In Management</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stockInModal">
        <i class="bi bi-plus-circle"></i> Add Stock-In
    </button>
</div>

{{-- <!-- Filters -->
<div class="card card-custom mb-4">
    <div class="card-header card-header-custom"><i class="fas fa-filter me-2"></i> Stock-In Filters</div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.stock_in.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Search Product/Variant</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search...">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Filter by Warehouse</label>
                    <select class="form-select" name="warehouse_id">
                        <option value="">All</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block w-100"><i class="fas fa-check me-1"></i> Apply</button>
                </div>
            </div>
        </form>
    </div>
</div> --}}

<!-- Stock-In Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">Stock-In List</div>
    <div class="card-body">
        <table class="table table-bordered align-middle w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product / Variant</th>
                    <th>Warehouse</th>
                    <th>Quantity</th>
                    <th>Reason</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stockIns as $stock)
                <tr>
                    <td>{{ $stock->id }}</td>
                    <td>
                        @if($stock->product)
                            {{ $stock->product->name }}
                        @elseif($stock->variant)
                            {{ $stock->variant->product->name }} / {{ $stock->variant->variant_name }}
                        @endif
                    </td>
                    <td>{{ $stock->warehouse->name }}</td>
                    <td>{{ $stock->quantity }}</td>
                    <td>{{ $stock->reason }}</td>
                    <td>{{ $stock->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <button 
                            class="btn btn-warning btn-sm me-1 editStockBtn"
                            data-id="{{ $stock->id }}"
                            data-product-id="{{ $stock->product_id }}"
                            data-variant-id="{{ $stock->product_variant_id }}"
                            data-warehouse-id="{{ $stock->warehouse_id }}"
                            data-quantity="{{ $stock->quantity }}"
                            data-reason="{{ $stock->reason }}"
                        >
                            <i class="fas fa-edit"></i>
                        </button>
                        {{-- <form action="{{ route('admin.stock_in.destroy', $stock) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form> --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3 d-flex justify-content-center">
            {{ $stockIns->links('pagination::bootstrap-5') }}
        </div>
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
                        <label class="form-label">Warehouse</label>
                        <select class="form-select" name="warehouse_id" id="warehouseSelect" required>
                            <option value="">Select Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
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

<!-- JavaScript for editing on same page -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stockModal = new bootstrap.Modal(document.getElementById('stockInModal'));
        const form = document.getElementById('stockInForm');
        const modalTitle = document.getElementById('modalTitle');
        const formMethod = document.getElementById('formMethod');

        document.querySelectorAll('.editStockBtn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;

                // Fill modal fields
                document.getElementById('productSelect').value = button.dataset.productId;
                document.getElementById('variantSelect').value = button.dataset.variantId;
                document.getElementById('warehouseSelect').value = button.dataset.warehouseId;
                document.getElementById('quantityInput').value = button.dataset.quantity;
                document.getElementById('reasonInput').value = button.dataset.reason;

                // Change form action and method
                form.action = `/admin/stock-ins/${id}`;
                formMethod.value = 'PUT';
                modalTitle.textContent = 'Edit Stock-In';

                // Show modal
                stockModal.show();
            });
        });

        // Reset modal when hidden (for Add Stock-In)
        document.getElementById('stockInModal').addEventListener('hidden.bs.modal', () => {
            form.action = "{{ route('admin.stock_in.store') }}";
            formMethod.value = 'POST';
            modalTitle.textContent = 'Add Stock-In';
            form.reset();
        });
    });
</script>
@endsection
