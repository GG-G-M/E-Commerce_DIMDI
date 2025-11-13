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
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockInModal">
        <i class="bi bi-plus-circle"></i> Add Stock-In
    </button>
</div>

<!-- Filters -->
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
</div>

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
                        <a href="{{ route('admin.stock_in.edit', $stock) }}" class="btn btn-warning btn-sm me-1"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.stock_in.destroy', $stock) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
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

<!-- Add Stock-In Modal -->
<div class="modal fade" id="addStockInModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.stock_in.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Stock-In</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <select class="form-select" name="product_id">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Variant</label>
                        <select class="form-select" name="product_variant_id">
                            <option value="">Select Variant</option>
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}">{{ $variant->product->name }} / {{ $variant->variant_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warehouse</label>
                        <select class="form-select" name="warehouse_id" required>
                            <option value="">Select Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <input type="text" class="form-control" name="reason">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Save Stock-In</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
