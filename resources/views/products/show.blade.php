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
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image - Bigger -->
        <div class="col-lg-8 mb-4">
            <img src="{{ $product->image_url }}" class="product-image" alt="{{ $product->name }}">
        </div>
        
        <!-- Product Info Sidebar - Compact -->
        <div class="col-lg-4">
            <div class="product-info-sidebar">
                <!-- Title and Category -->
                <h1 class="product-title">{{ $product->name }}</h1>
                <span class="category-badge">
                    <i class="fas fa-tag me-1"></i>{{ $product->category->name }}
                </span>

                <!-- Price Section -->
                <div class="price-section">
                    <div class="d-flex align-items-center flex-wrap">
                        @if($product->has_discount)
                        <span class="current-price me-2">${{ $product->sale_price }}</span>
                        <span class="original-price me-2">${{ $product->price }}</span>
                        <span class="discount-badge">
                            <i class="fas fa-fire me-1"></i>{{ $product->discount_percentage }}% OFF
                        </span>
                        @else
                        <span class="current-price">${{ $product->price }}</span>
                        @endif
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="d-flex align-items-center mb-3">
                    <span class="stock-badge {{ $product->stock_quantity > 0 ? 'stock-in' : 'stock-out' }}">
                        <i class="fas {{ $product->stock_quantity > 0 ? 'fa-check' : 'fa-times' }} me-1"></i>
                        {{ $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                    </span>
                    <small class="text-muted ms-2">
                        <i class="fas fa-cube me-1"></i>{{ $product->stock_quantity }} available
                    </small>
                </div>

                <!-- Add to Cart Form -->
                @if($product->stock_quantity > 0)
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="quantity-control">
                        <div class="row g-2 align-items-center">
                            <div class="col-5">
                                <label for="quantity" class="form-label fw-bold text-dark mb-1">Quantity:</label>
                            </div>
                            <div class="col-7">
                                <input type="number" name="quantity" id="quantity" class="form-control quantity-input" 
                                       value="1" min="1" max="{{ $product->stock_quantity }}">
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                @else
                <button class="btn btn-secondary w-100 py-2" disabled>
                    <i class="fas fa-times-circle me-2"></i>Out of Stock
                </button>
                @endif
            </div>

            <!-- Product Details Card -->
            <div class="details-card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i>Product Details
                </div>
                <div class="card-body">
                    <ul class="details-list">
                        <li>
                            <span class="detail-label">SKU:</span>
                            <span class="detail-value">{{ $product->sku }}</span>
                        </li>
                        <li>
                            <span class="detail-label">Category:</span>
                            <span class="detail-value">{{ $product->category->name }}</span>
                        </li>
                        <li>
                            <span class="detail-label">Availability:</span>
                            <span class="detail-value">{{ $product->stock_quantity }} units</span>
                        </li>
                        <li>
                            <span class="detail-label">Status:</span>
                            <span class="detail-value">
                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description at the Bottom -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="product-description">
                <h4 class="mb-3" style="color: #2C8F0C;">
                    <i class="fas fa-align-left me-2"></i>Product Description
                </h4>
                <p class="mb-0">{{ $product->description }}</p>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="related-products-section">
        <h3 class="section-title">Related Products</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card related-product-card h-100">
                    <img src="{{ $relatedProduct->image_url }}" class="card-img-top related-product-image" alt="{{ $relatedProduct->name }}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="related-product-title">{{ $relatedProduct->name }}</h6>
                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($relatedProduct->description, 60) }}</p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="related-product-price">${{ $relatedProduct->current_price }}</span>
                                @if($relatedProduct->has_discount)
                                <small class="text-danger fw-bold">{{ $relatedProduct->discount_percentage }}% OFF</small>
                                @endif
                            </div>
                            <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity input validation
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            const max = parseInt(this.getAttribute('max'));
            const min = parseInt(this.getAttribute('min'));
            let value = parseInt(this.value);
            
            if (value > max) {
                this.value = max;
            } else if (value < min) {
                this.value = min;
            }
        });
    }
});
</script>
@endsection