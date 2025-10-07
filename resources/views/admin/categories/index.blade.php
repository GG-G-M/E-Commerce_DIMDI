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
    
    .categories-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .categories-card .card-body {
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
    
    .category-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #E8F5E6;
        transition: all 0.3s ease;
    }
    
    .category-image:hover {
        border-color: #2C8F0C;
        transform: scale(1.05);
    }
    
    .category-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        background: #F8FDF8;
        border: 2px dashed #E8F5E6;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .category-placeholder:hover {
        border-color: #2C8F0C;
        background: #F0F9F0;
    }
    
    .category-name {
        font-weight: 600;
        color: #2C8F0C;
    }
    
    .category-slug {
        color: #6c757d;
        font-size: 0.875rem;
        font-family: monospace;
    }
    
    .badge-products {
        background-color: #E8F5E6 !important;
        color: #2C8F0C !important;
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
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">Category Management</h1>
            <p class="mb-0 text-muted">Organize your products with categories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Category
        </a>
    </div>
</div>

<div class="card categories-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>
                            @if($category->image)
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" 
                                    class="category-image">
                            @else
                                <div class="category-placeholder">
                                    <i class="fas fa-tag text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="category-name">{{ $category->name }}</div>
                        </td>
                        <td>
                            <div class="category-slug">{{ $category->slug }}</div>
                        </td>
                        <td>
                            <span class="badge badge-products">
                                <i class="fas fa-box me-1"></i>
                                {{ $category->products_count ?? $category->products->count() }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $category->is_active ? 'badge-status-active' : 'badge-status-inactive' }}">
                                <i class="fas fa-{{ $category->is_active ? 'check' : 'times' }} me-1"></i>
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-primary btn-sm"
                                   data-bs-toggle="tooltip" title="Edit Category">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this category?')"
                                            data-bs-toggle="tooltip" title="Delete Category">
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
            {{ $categories->links() }}
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