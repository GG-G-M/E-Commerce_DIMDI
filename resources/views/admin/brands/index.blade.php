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

    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .status-active {
        color: #2C8F0C;
        font-weight: 600;
    }

    .status-inactive {
        color: #6c757d;
        font-weight: 600;
    }
</style>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Brand Management</h1>
        <p class="text-muted mb-0">Manage your product brands here.</p>
    </div>
    <a href="{{ route('admin.brands.create') }}" style="background-color: #2C8F0C" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Brand
    </a>
</div>

<div class="card card-custom">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-tags me-2"></i> Brand List
        </div>
        <div class="text-muted small">
            Total: {{ $brands->total() }} brands
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Sort Order</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($brands as $brand)
                    <tr>
                        <td>
                            <strong>{{ $brand->name }}</strong><br>
                            <small class="text-muted">Slug: {{ $brand->slug }}</small>
                        </td>
                        <td>
                            @if($brand->description)
                                <span class="text-muted">{{ Str::limit($brand->description, 50) }}</span>
                            @else
                                <span class="text-muted">No description</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-dark">
                                <i class="fas fa-box me-1"></i>{{ $brand->products->count() }} products
                            </span>
                        </td>
                        <td>
                            @if($brand->is_active)
                                <span class="status-active">
                                    <i class="fas fa-check-circle me-1"></i>Active
                                </span>
                            @else
                                <span class="status-inactive">
                                    <i class="fas fa-times-circle me-1"></i>Inactive
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">{{ $brand->sort_order }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.brands.edit', $brand) }}" 
                                   class="btn btn-sm btn-outline-success me-1" 
                                   title="Edit Brand">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this brand?')"
                                            title="Delete Brand">
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

        <div class="d-flex justify-content-center mt-3">
            {{ $brands->links() }}
        </div>
    </div>
</div>
@endsection