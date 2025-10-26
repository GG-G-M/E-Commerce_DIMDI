@extends('layouts.admin')

@section('content')
<style>
    /* === Green Theme and Card Styling === */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #2C8F0C;
    }

    .page-header h1 {
        color: #2C8F0C;
        font-weight: 700;
    }

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

    .badge-success { background-color: #2C8F0C; }
    .badge-danger { background-color: #C62828; }
    .badge-warning { background-color: #FBC02D; }
    .badge-info { background-color: #0288D1; }
</style>

<!-- Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Product Management</h1>
        <p class="text-muted mb-0">Manage your products, categories, and inventory here.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Product
    </a>
</div>

<!-- Filters and Search -->
<div class="card card-custom mb-4">
    <div class="card-header card-header-custom">
        <i class="fas fa-filter me-2"></i> Product Filters
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="search" class="form-label fw-bold">Search Products</label>
                        <input type="text" class="form-control" id="search" name="search" 
                            value="{{ request('search') }}" placeholder="Search by name, description, or SKU...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-bold">Filter by Category</label>
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
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Filter by Status</label>
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
                        <label class="form-label fw-bold">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-1"></i> Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Product Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <i class="fas fa-boxes me-2"></i> Product List
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
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
                            {{ $product->category->name }}
                            @if(!$product->category->is_active)
                                <span class="badge bg-warning ms-1">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($product->has_variants && $product->variants && $product->variants->count() > 0)
                                @foreach($product->variants->take(3) as $variant)
                                    <span class="badge bg-secondary me-1 mb-1" title="{{ $variant->variant_description }}">
                                        {{ $variant->variant_name }}
                                    </span>
                                @endforeach
                                @if($product->variants->count() > 3)
                                    <span class="badge bg-light text-dark">+{{ $product->variants->count() - 3 }} more</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">No Variants</span>
                            @endif
                        </td>
                        <td>₱{{ number_format($product->price, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $product->stock_quantity > 10 ? 'success' : ($product->stock_quantity > 0 ? 'warning' : 'danger') }}">
                                {{ $product->stock_quantity }}
                            </span>
                        </td>
                        <td>
                            @if($product->is_archived)
                                <span class="badge bg-dark">Archived</span>
                            @else
                                @if($product->is_effectively_inactive)
                                    <span class="badge bg-danger">Inactive</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                                @if($product->is_featured)
                                    <span class="badge bg-info mt-1">Featured</span>
                                @endif
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-success me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($product->is_archived)
                                <form action="{{ route('admin.products.unarchive', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success"
                                            onclick="return confirm('Are you sure you want to unarchive this product?')">
                                        <i class="fas fa-box-open"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.products.archive', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning"
                                            onclick="return confirm('Are you sure you want to archive this product?')">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                </form>
                            @endif
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
@endsection
