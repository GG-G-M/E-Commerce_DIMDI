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
    
    .products-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .products-card .card-body {
        padding: 0;
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 1rem 0.75rem;
    }
    
    .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-color: #E8F5E6;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background-color: #F8FDF8;
        transform: translateY(-1px);
    }
    
    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #E8F5E6;
        transition: all 0.3s ease;
    }
    
    .product-image:hover {
        border-color: #2C8F0C;
        transform: scale(1.05);
    }
    
    .badge-stock-high {
        background-color: #E8F5E6 !important;
        color: #2C8F0C !important;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
    }
    
    .badge-stock-medium {
        background-color: #FFF3CD !important;
        color: #856404 !important;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
    }
    
    .badge-stock-low {
        background-color: #F8D7DA !important;
        color: #721C24 !important;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
    }
    
    .badge-status-active {
        background-color: #E8F5E6 !important;
        color: #2C8F0C !important;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
    }
    
    .badge-status-inactive {
        background-color: #F8D7DA !important;
        color: #721C24 !important;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
    }
    
    .btn-outline-primary {
        border-color: #2C8F0C;
        color: #2C8F0C;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
        transform: translateY(-1px);
    }
    
    .btn-outline-danger {
        border-color: #DC3545;
        color: #DC3545;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-danger:hover {
        background-color: #DC3545;
        border-color: #DC3545;
        transform: translateY(-1px);
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .pagination .page-link {
        color: #2C8F0C;
        border-color: #E8F5E6;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
    }
    
    .pagination .page-link:hover {
        background-color: #E8F5E6;
        border-color: #2C8F0C;
    }
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">Product Management</h1>
            <p class="mb-0 text-muted">Manage your store's product catalog and inventory</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Product
        </a>
    </div>
</div>

<div class="card products-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                class="product-image">
                        </td>
                        <td>
                            <div class="fw-bold" style="color: #2C8F0C;">{{ $product->name }}</div>
                            <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $product->category->name }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-success">${{ number_format($product->price, 2) }}</span>
                        </td>
                        <td>
                            @php
                                $stockClass = $product->stock_quantity > 10 ? 'badge-stock-high' : 
                                            ($product->stock_quantity > 0 ? 'badge-stock-medium' : 'badge-stock-low');
                            @endphp
                            <span class="badge {{ $stockClass }}">
                                <i class="fas fa-{{ $product->stock_quantity > 10 ? 'check' : ($product->stock_quantity > 0 ? 'exclamation' : 'times') }} me-1"></i>
                                {{ $product->stock_quantity }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product->is_active ? 'badge-status-active' : 'badge-status-inactive' }}">
                                <i class="fas fa-{{ $product->is_active ? 'check' : 'times' }} me-1"></i>
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary btn-sm"
                                   data-bs-toggle="tooltip" title="Edit Product">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this product?')"
                                            data-bs-toggle="tooltip" title="Delete Product">
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
        
        <div class="d-flex justify-content-center py-4">
            {{ $products->links() }}
        </div>
    </div>
</div>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection