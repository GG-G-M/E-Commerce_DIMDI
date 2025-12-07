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

    .page-header h1 {
        color: #2C8F0C;
        font-weight: 700;
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

    .variant-card {
        border: 2px solid #E8F5E6;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background: #F8FDF8;
    }

    .variant-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #C8E6C9;
    }

    .variant-number {
        background: #2C8F0C;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
    }

    .image-preview-container {
        border: 2px dashed #C8E6C9;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        background: #F8FDF8;
    }

    .variant-image-preview {
        max-height: 120px;
        max-width: 100%;
        border-radius: 6px;
    }

    .total-stock-summary {
        background: linear-gradient(135deg, #E8F5E6, #C8E6C9);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid #2C8F0C;
    }
</style>

<!-- Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Product Details</h1>
        <p class="text-muted mb-0">Read-only product information</p>
    </div>
    <div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back to list</a>
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">Edit Product</a>
    </div>
</div>

<!-- Product View -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <i class="fas fa-box me-2"></i> Product Overview
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded mb-2" />
                <div class="small text-muted">SKU: {{ $product->sku }}</div>
                <div class="mt-2">
                    @if($product->has_discount)
                        <strong class="text-danger">₱{{ number_format($product->sale_price,2) }}</strong>
                        <div class="text-muted text-decoration-line-through small">₱{{ number_format($product->price,2) }}</div>
                    @else
                        <strong class="text-success">₱{{ number_format($product->price,2) }}</strong>
                    @endif
                </div>
                <div class="mt-2">
                    <strong>Stock:</strong>
                    @if($product->has_variants)
                        <div>{{ $product->total_stock }} units (sum of variants)</div>
                    @else
                        <div>{{ $product->stock_quantity }} units</div>
                    @endif
                </div>
            </div>

            <div class="col-md-8">
                <h5>{{ $product->name }}</h5>
                <p class="text-muted">{{ $product->description }}</p>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Category</strong>
                        <div>{{ optional($product->category)->name ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Brand</strong>
                        <div>{{ optional($product->brand)->name ?? '—' }}</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status</strong>
                        <div>{{ $product->is_archived ? 'Archived' : ($product->is_effectively_inactive ? 'Inactive' : 'Active') }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Featured</strong>
                        <div>{{ $product->is_featured ? 'Yes' : 'No' }}</div>
                    </div>
                </div>

                <hr />

                <h6>Variants</h6>
                @if($product->has_variants && $variants->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Variant</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($variants as $variant)
                                <tr>
                                    <td><code>{{ $variant->id }}</code></td>
                                    <td>{{ $variant->variant_name ?? 'Standard' }}</td>
                                    <td>{{ $variant->sku }}</td>
                                    <td>₱{{ number_format($variant->current_price,2) }}</td>
                                    <td>{{ $variant->stock_quantity }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.products.edit', $product) }}?variant_id={{ $variant->id }}" class="btn btn-sm btn-outline-success">Edit Variant</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-muted">No variants for this product.</div>
                @endif

            </div>
        </div>

        <div class="mt-4 text-end">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>

@endsection
