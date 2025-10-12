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
        <p class="text-muted mb-0">Manage your products and organize them by category.</p>
    </div>
    <button type="button" class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#productModal"
        data-mode="create"
        data-action="{{ route('admin.products.store') }}">
        <i class="fas fa-plus me-1"></i> Add Product
    </button>
</div>

<!-- Filters and Search -->
<div class="card card-custom mb-4">
    <div class="card-header card-header-custom">
        <i class="fas fa-filter me-2"></i> Filters
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}">
            <div class="row">
                <!-- Search -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="search" class="form-label fw-bold">Search Products</label>
                        <input type="text" class="form-control" id="search" name="search" 
                            value="{{ request('search') }}" placeholder="Search by name">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-bold">Filter by Category</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Filter by Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                </div>

                <!-- Search Button -->
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i>
                    </button>
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
        @if($products->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        {{-- <th>Slug</th> --}}
                        <th>Category</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th>Archived</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td><strong>{{ $product->name }}</strong></td>
                        {{-- <td>{{ $product->slug }}</td> --}}
                        <td>{{ $product->category->name ?? '—' }}</td>
                        <td>
                            @if($product->is_featured)
                                <span class="badge bg-info">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_archived)
                                <span class="badge bg-dark">Archived</span>
                            @else
                                <span class="badge bg-light text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button"
                                class="btn btn-sm btn-outline-success"
                                data-bs-toggle="modal"
                                data-bs-target="#productModal"
                                data-mode="edit"
                                data-action="{{ route('admin.products.update', $product->id) }}"
                                data-product='@json($product)'>
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $products->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-3x text-success mb-3"></i>
            <p class="text-muted mb-0">No products found. Try adding one!</p>
        </div>
        @endif
    </div>
</div>

<!-- Single Modal for Create/Edit -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="productForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        {{-- <div class="col-md-6">
                            <label class="form-label fw-bold">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug">
                        </div> --}}

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Featured</label>
                            <select class="form-select" id="is_featured" name="is_featured">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Active</label>
                            <select class="form-select" id="is_active" name="is_active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveProductBtn">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const productModal = document.getElementById('productModal');
    const form = document.getElementById('productForm');
    const modalTitle = document.getElementById('productModalLabel');
    const saveBtn = document.getElementById('saveProductBtn');

    productModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const mode = button.dataset.mode;

        // Reset form before showing modal
        form.reset();
        const existingMethod = form.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();

        if (mode === 'create') {
            modalTitle.textContent = 'Add Product';
            saveBtn.textContent = 'Save';
            form.action = button.dataset.action;
            form.method = 'POST';
        } else if (mode === 'edit') {
            const product = JSON.parse(button.dataset.product);
            modalTitle.textContent = 'Edit Product';
            saveBtn.textContent = 'Update';
            form.action = button.dataset.action;
            form.method = 'POST';

            // Add PUT method
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);

            // Fill inputs
            document.getElementById('name').value = product.name;
            // document.getElementById('slug').value = product.slug;
            document.getElementById('description').value = product.description ?? '';
            document.getElementById('category_id').value = product.category_id ?? '';
            document.getElementById('is_featured').value = product.is_featured ? 1 : 0;
            document.getElementById('is_active').value = product.is_active ? 1 : 0;
        }
    });
});
</script>
@endpush




@endsection


