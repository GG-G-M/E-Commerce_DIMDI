@extends('layouts.app')

@section('content')
<style>
    .breadcrumb {
        background: #F8FDF8;
        border-radius: 8px;
        padding: 1rem 1.5rem;
        border-left: 4px solid #2C8F0C;
        margin-bottom: 2rem;
    }
    
    .breadcrumb-item a {
        color: #2C8F0C;
        text-decoration: none;
        font-weight: 500;
    }
    
    .breadcrumb-item.active {
        color: #6c757d;
        font-weight: 600;
    }
    
    .product-image {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        width: 100%;
        height: 500px;
        object-fit: cover;
    }
    
    .product-image:hover {
        transform: scale(1.02);
    }
    
    .product-title {
        color: #2C8F0C;
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.8rem;
    }
    
    .category-badge {
        background: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        display: inline-block;
        margin-bottom: 1rem;
    }
    
    .price-section {
        background: linear-gradient(135deg, #F8FDF8, #E8F5E6);
        padding: 1.2rem;
        border-radius: 10px;
        border-left: 4px solid #2C8F0C;
        margin: 1rem 0;
    }
    
    .current-price {
        color: #2C8F0C;
        font-weight: 700;
        font-size: 1.8rem;
    }
    
    .original-price {
        color: #6c757d;
        font-size: 1.1rem;
        text-decoration: line-through;
    }
    
    .discount-badge {
        background: linear-gradient(135deg, #DC3545, #C82333);
        color: white;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
    }
    
    .stock-badge {
        padding: 0.6rem 1.2rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
    }
    
    .stock-in {
        background: #E8F5E6;
        color: #2C8F0C;
        border: 2px solid #2C8F0C;
    }
    
    .stock-out {
        background: #F8D7DA;
        color: #721C24;
        border: 2px solid #DC3545;
    }
    
    .product-description {
        color: #495057;
        line-height: 1.7;
        font-size: 1.1rem;
        background: #F8FDF8;
        padding: 2rem;
        border-radius: 12px;
        border-left: 4px solid #2C8F0C;
        margin-top: 2rem;
    }
    
    .quantity-control {
        background: white;
        border: 2px solid #E8F5E6;
        border-radius: 8px;
        padding: 1.2rem;
        margin: 1rem 0;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        border-radius: 8px;
        padding: 0.8rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.3);
    }
    
    .btn-secondary {
        background: #6c757d;
        border: none;
        border-radius: 8px;
        padding: 0.8rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .details-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background: white;
        margin-top: 1.5rem;
    }
    
    .details-card .card-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border-radius: 10px 10px 0 0 !important;
        padding: 1rem 1.2rem;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .details-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .details-list li {
        padding: 0.8rem 0;
        border-bottom: 1px solid #E8F5E6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .details-list li:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        color: #2C8F0C;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .detail-value {
        color: #495057;
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    .related-products-section {
        margin-top: 4rem;
        padding-top: 2rem;
        border-top: 3px solid #E8F5E6;
    }
    
    .section-title {
        color: #2C8F0C;
        font-weight: 700;
        margin-bottom: 2rem;
        position: relative;
        display: inline-block;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 50px;
        height: 3px;
        background: #2C8F0C;
        border-radius: 2px;
    }
    
    .related-product-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .related-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .related-product-image {
        height: 180px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .related-product-card:hover .related-product-image {
        transform: scale(1.05);
    }
    
    .related-product-title {
        color: #2C8F0C;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .related-product-price {
        color: #2C8F0C;
        font-weight: 700;
        font-size: 1rem;
    }
    
    .btn-outline-primary {
        border: 2px solid #2C8F0C;
        color: #2C8F0C;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .btn-outline-primary:hover {
        background: #2C8F0C;
        border-color: #2C8F0C;
        transform: translateY(-1px);
    }
    
    .quantity-input {
        border: 2px solid #E8F5E6;
        border-radius: 6px;
        padding: 0.6rem;
        text-align: center;
        font-weight: 600;
        transition: border-color 0.3s ease;
        font-size: 0.9rem;
    }
    
    .quantity-input:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.1);
    }
    
    .product-info-sidebar {
        background: #F8FDF8;
        border-radius: 10px;
        padding: 1.5rem;
        border-left: 4px solid #2C8F0C;
        height: fit-content;
    }
</style>

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
            @if($product->available_sizes && count($product->available_sizes) > 0)
            <div class="mb-4">
                <label class="form-label fw-bold">Select Size:</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($product->available_sizes as $size)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="selected_size" id="size_{{ $size }}" value="{{ $size }}" {{ $loop->first ? 'checked' : '' }}>
                        <label class="form-check-label btn btn-outline-secondary" for="size_{{ $size }}">
                            {{ $size }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mb-4">
                <span class="badge bg-{{ $product->stock_quantity > 0 ? 'success' : 'danger' }}">
                    {{ $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                </span>
                <small class="text-muted ms-2">{{ $product->stock_quantity }} units available</small>
            </div>

            @if($product->stock_quantity > 0)
            <form action="{{ route('cart.store') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="selected_size" id="selected_size_input" value="{{ $product->available_sizes[0] ?? 'One Size' }}">
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
                        <li><strong>Availability:</strong> {{ $product->stock_quantity }} in stock</li>
                        @if($product->available_sizes && count($product->available_sizes) > 0)
                        <li><strong>Available Sizes:</strong> {{ implode(', ', $product->available_sizes) }}</li>
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
                        @if($relatedProduct->available_sizes && count($relatedProduct->available_sizes) > 0)
                        <div class="mb-2">
                            <small class="text-muted">Sizes: 
                                @foreach($relatedProduct->available_sizes as $size)
                                    <span class="badge bg-light text-dark border me-1">{{ $size }}</span>
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
    // Update selected size when user clicks on size options
    document.querySelectorAll('input[name="selected_size"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('selected_size_input').value = this.value;
        });
    });
</script>
@endsection