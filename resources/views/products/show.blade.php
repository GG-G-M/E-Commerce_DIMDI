@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <img src="{{ $product->image_url }}" class="img-fluid rounded" alt="{{ $product->name }}">
        </div>
        <div class="col-lg-6">
            <h1 class="h2">{{ $product->name }}</h1>
            <p class="text-muted">Category: {{ $product->category->name }}</p>
            
            <div class="mb-3">
                @if($product->has_discount)
                <span class="h3 text-danger me-2">${{ $product->sale_price }}</span>
                <span class="h5 text-muted text-decoration-line-through">${{ $product->price }}</span>
                <span class="badge bg-danger ms-2">{{ $product->discount_percentage }}% OFF</span>
                @else
                <span class="h3 text-primary">${{ $product->price }}</span>
                @endif
            </div>

            <p class="mb-4">{{ $product->description }}</p>

            <!-- Size Selection -->
            @if($product->all_sizes && count($product->all_sizes) > 0)
            <div class="mb-4">
                <label class="form-label fw-bold">Select Size:</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($product->all_sizes as $size)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="selected_size" id="size_{{ $size }}" value="{{ $size }}" 
                               {{ $loop->first && $product->isSizeInStock($size) ? 'checked' : '' }}
                               {{ !$product->isSizeInStock($size) ? 'disabled' : '' }}>
                        <label class="form-check-label btn btn-outline-{{ $product->isSizeInStock($size) ? 'primary' : 'secondary' }}" for="size_{{ $size }}">
                            {{ $size }}
                            @if(!$product->isSizeInStock($size))
                            <small class="d-block text-muted">(Out of Stock)</small>
                            @elseif($product->getStockForSize($size) < 10)
                            <small class="d-block text-warning">(Only {{ $product->getStockForSize($size) }} left)</small>
                            @endif
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mb-4">
                <span class="badge bg-{{ $product->in_stock ? 'success' : 'danger' }}">
                    {{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}
                </span>
                <small class="text-muted ms-2">Total: {{ $product->total_stock }} units available across all sizes</small>
            </div>

            @if($product->in_stock)
            <form action="{{ route('cart.store') }}" method="POST" class="mb-4" id="add-to-cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="selected_size" id="selected_size_input" value="{{ $product->all_sizes[0] ?? 'One Size' }}">
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-cart-plus me-2"></i>Add to Cart
                    </button>
                </div>
            </form>
            @else
            <button class="btn btn-secondary btn-lg w-100" disabled>Out of Stock</button>
            @endif

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Product Details</h6>
                    <ul class="list-unstyled">
                        <li><strong>SKU:</strong> {{ $product->sku }}</li>
                        <li><strong>Category:</strong> {{ $product->category->name }}</li>
                        <li><strong>Availability:</strong> {{ $product->total_stock }} in stock</li>
                        @if($product->all_sizes && count($product->all_sizes) > 0)
                        <li><strong>Available Sizes:</strong> 
                            @foreach($product->all_sizes as $size)
                            <span class="badge bg-{{ $product->isSizeInStock($size) ? 'primary' : 'secondary' }}">
                                {{ $size }} ({{ $product->getStockForSize($size) }})
                            </span>
                            @endforeach
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="mt-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card product-card h-100">
                    <img src="{{ $relatedProduct->image_url }}" class="card-img-top product-image" alt="{{ $relatedProduct->name }}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                        
                        <!-- Display Sizes for Related Products -->
                        @if($relatedProduct->all_sizes && count($relatedProduct->all_sizes) > 0)
                        <div class="mb-2">
                            <small class="text-muted">Sizes: 
                                @foreach($relatedProduct->all_sizes as $size)
                                    <span class="badge bg-light text-dark border me-1 {{ !$relatedProduct->isSizeInStock($size) ? 'text-decoration-line-through text-muted' : '' }}">
                                        {{ $size }}
                                    </span>
                                @endforeach
                            </small>
                        </div>
                        @endif
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-primary fw-bold">${{ $relatedProduct->current_price }}</span>
                            </div>
                            <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-outline-primary btn-sm w-100">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

@push('scripts')
<script>

    // Add to cart form handling
document.addEventListener('DOMContentLoaded', function() {
    // Handle add to cart forms with size selection
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const sizeSelect = this.querySelector('select[name="selected_size"]');
            const sizeInput = this.querySelector('input[name="selected_size"]');
            
            // Validate size selection for dropdowns
            if (sizeSelect && !sizeSelect.value) {
                e.preventDefault();
                showToast('Please select a size before adding to cart.', 'warning');
                sizeSelect.focus();
                return;
            }
            
            // Validate size selection for radio buttons
            if (!sizeInput && !sizeSelect) {
                const selectedSize = document.querySelector('input[name="selected_size"]:checked');
                if (!selectedSize || selectedSize.disabled) {
                    e.preventDefault();
                    showToast('Please select an available size before adding to cart.', 'warning');
                    return;
                }
            }
        });
    });
    
    function showToast(message, type = 'info') {
        // Simple toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} position-fixed`;
        toast.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
    // Update selected size when user clicks on size options
    document.querySelectorAll('input[name="selected_size"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (!this.disabled) {
                document.getElementById('selected_size_input').value = this.value;
            }
        });
    });

    // Form validation for home page
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const sizeSelect = this.querySelector('select[name="selected_size"]');
            if (sizeSelect && !sizeSelect.value) {
                e.preventDefault();
                alert('Please select a size before adding to cart.');
                sizeSelect.focus();
            }
        });
    });
</script>
@endpush
@endsection