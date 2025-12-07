<div class="modal-header" style="background: linear-gradient(135deg, #2C8F0C, #4CAF50); color: white;">
    <h5 class="modal-title">
        <i class="fas fa-box me-2"></i>Product Details: {{ $product->name }}
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-4 text-center mb-3 mb-md-0">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                 class="img-fluid rounded shadow-sm mb-3" style="max-height: 300px; object-fit: cover; width: 100%;">
            <div class="small text-muted mb-2">
                <strong>SKU:</strong> {{ $product->sku }}
            </div>
            <div class="mb-2">
                @if($product->has_discount)
                    <div class="h4 text-danger mb-1">₱{{ number_format($product->sale_price, 2) }}</div>
                    <div class="text-muted text-decoration-line-through small">₱{{ number_format($product->price, 2) }}</div>
                    <span class="badge bg-danger mt-1">{{ $product->discount_percentage }}% OFF</span>
                @else
                    <div class="h4 text-success">₱{{ number_format($product->price, 2) }}</div>
                @endif
            </div>
            <div class="mt-3">
                <strong>Stock:</strong>
                @if($product->has_variants)
                    <div class="text-success fw-bold">{{ $product->total_stock }} units</div>
                    <small class="text-muted">(sum of variants)</small>
                @else
                    @if($product->stock_quantity > 10)
                        <div class="text-success fw-bold">{{ $product->stock_quantity }} units</div>
                    @elseif($product->stock_quantity > 0)
                        <div class="text-warning fw-bold">{{ $product->stock_quantity }} units</div>
                    @else
                        <div class="text-danger fw-bold">{{ $product->stock_quantity }} units</div>
                    @endif
                @endif
            </div>
        </div>
        <div class="col-md-8">
            <div class="mb-3">
                <h6 class="text-success mb-2">
                    <i class="fas fa-info-circle me-1"></i>Main Information
                </h6>
                <div class="row mb-2">
                    <div class="col-4"><strong>Category:</strong></div>
                    <div class="col-8">
                        <span class="badge bg-success">{{ $product->category->name }}</span>
                        @if(!$product->category->is_active)
                            <span class="badge bg-warning ms-1">Inactive</span>
                        @endif
                    </div>
                </div>
                @if($product->brand)
                <div class="row mb-2">
                    <div class="col-4"><strong>Brand:</strong></div>
                    <div class="col-8">{{ $product->brand->name }}</div>
                </div>
                @endif
                <div class="row mb-2">
                    <div class="col-4"><strong>Status:</strong></div>
                    <div class="col-8">
                        @if($product->is_archived)
                            <span class="badge bg-secondary">Archived</span>
                        @elseif($product->is_effectively_inactive)
                            <span class="badge bg-warning">Inactive</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                        @if($product->is_featured)
                            <span class="badge bg-info ms-1">Featured</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <h6 class="text-success mb-2">
                    <i class="fas fa-align-left me-1"></i>Description
                </h6>
                <p class="text-muted">{{ $product->description }}</p>
            </div>
            
            <hr>
            
            <div>
                <h6 class="text-success mb-3">
                    <i class="fas fa-list me-1"></i>Variants
                </h6>
                @if($product->has_variants && $product->variants->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover">
                            <thead style="background-color: #E8F5E6;">
                                <tr>
                                    <th>Variant Name</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->variants as $variant)
                                <tr>
                                    <td>{{ $variant->variant_name ?? 'Standard' }}</td>
                                    <td><small class="text-muted">{{ $variant->sku }}</small></td>
                                    <td>
                                        @if($variant->has_discount)
                                            <span class="text-danger fw-bold">₱{{ number_format($variant->current_price, 2) }}</span>
                                            <div class="text-muted text-decoration-line-through small">₱{{ number_format($variant->price, 2) }}</div>
                                        @else
                                            <span class="text-success">₱{{ number_format($variant->current_price, 2) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($variant->stock_quantity > 10)
                                            <span class="text-success fw-bold">{{ $variant->stock_quantity }}</span>
                                        @elseif($variant->stock_quantity > 0)
                                            <span class="text-warning fw-bold">{{ $variant->stock_quantity }}</span>
                                        @else
                                            <span class="text-danger fw-bold">{{ $variant->stock_quantity }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.products.edit', $product) }}?variant_id={{ $variant->id }}" 
                                           class="btn btn-sm btn-outline-success" 
                                           title="Edit Variant">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-muted text-center py-3">
                        <i class="fas fa-info-circle me-1"></i>No variants for this product.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
        <i class="fas fa-edit me-1"></i> Edit Product
    </a>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times me-1"></i> Close
    </button>
</div>

