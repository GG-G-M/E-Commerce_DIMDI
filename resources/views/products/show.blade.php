@extends('layouts.app')

@section('content')
<style>
    .breadcrumb a {
        color: #2C8F0C;
        text-decoration: none;
    }
    .breadcrumb a:hover {
        text-decoration: underline;
    }
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(44, 143, 12, 0.15);
        transition: transform 0.2s ease;
    }
    .card:hover {
        transform: translateY(-3px);
    }
    .card-header, .btn-primary {
        background-color: #2C8F0C !important;
        border: none;
    }
    .btn-primary:hover {
        background-color: #25750A !important;
    }
    .btn-success {
        background-color: #198754 !important;
        border: none;
    }
    .btn-success:hover {
        background-color: #157347 !important;
    }
    .btn-outline-primary {
        color: #2C8F0C;
        border-color: #2C8F0C;
    }
    .btn-outline-primary:hover {
        background-color: #2C8F0C;
        color: white;
    }
    .badge.bg-primary {
        background-color: #2C8F0C !important;
    }
    .text-primary {
        color: #2C8F0C !important;
    }
    .product-card {
        border-radius: 12px;
        transition: transform 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(44, 143, 12, 0.2);
    }
    .product-image {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        transition: opacity 0.3s ease;
    }
    .variant-option {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        padding: 10px 15px;
        margin: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .variant-option:hover {
        border-color: #2C8F0C;
    }
    .variant-option.selected {
        border-color: #2C8F0C;
        background-color: #E8F5E6;
    }
    .variant-option.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: #f8f9fa;
    }
    .breadcrumb-item.active {
        color: #25750A;
        font-weight: 600;
    }
    .image-loading {
        opacity: 0.7;
    }
    .btn-buy-now {
        background-color: white !important;
        border: 2px solid #2C8F0C !important;
        color: #2C8F0C !important;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-buy-now:hover {
        background-color: #2C8F0C !important;
        color: white !important;
        border-color: #25750A !important;
    }
    .details-section {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 2rem;
        margin-top: 2rem;
    }
    .details-section h3 {
        color: #2C8F0C;
        border-bottom: 2px solid #2C8F0C;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    /* =================== IMPROVED RATINGS STYLES =================== */
    /* Keep original design for product info, only improve ratings */
    
    /* Enhanced Star Rating Styles */
    .star-rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-start;
        gap: 5px;
    }

    .star-rating-input input {
        display: none;
    }

    .star-rating-input label {
        cursor: pointer;
        font-size: 1.8rem;
        color: #e0e0e0;
        transition: all 0.2s ease;
        padding: 0 2px;
    }

    .star-rating-input label:hover,
    .star-rating-input label:hover ~ label,
    .star-rating-input input:checked ~ label {
        color: #FFD700;
        transform: scale(1.1);
    }

    .star-rating-input input:checked + label {
        color: #FFD700;
        animation: starBounce 0.3s ease;
    }

    @keyframes starBounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.3); }
    }

    .star-rating-display {
        font-size: 1.1rem;
    }

    .star-rating-display .fas {
        color: #FFD700;
        text-shadow: 0 0 3px rgba(255, 215, 0, 0.3);
    }

    .star-rating-display .far {
        color: #e0e0e0;
    }
    
    /* Review Card Styles */
    .review-card {
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(44, 143, 12, 0.1);
    }
    
    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(44, 143, 12, 0.15);
    }
    
    .review-header {
        background: linear-gradient(135deg, #2C8F0C 0%, #25750A 100%);
        color: white;
        padding: 1.25rem;
        border-radius: 12px 12px 0 0;
    }
    
    .review-body {
        padding: 1.5rem;
    }
    
    .review-text {
        font-size: 1rem;
        line-height: 1.6;
        color: #495057;
        position: relative;
        padding-left: 1.5rem;
    }
    
    .review-text::before {
        content: "❝";
        position: absolute;
        left: 0;
        top: -0.5rem;
        font-size: 2rem;
        color: #2C8F0C;
        opacity: 0.3;
    }
    
    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .reviewer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2C8F0C, #25750A);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
    }
    
    /* Rating Distribution */
    .rating-distribution {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(44, 143, 12, 0.1);
    }
    
    .distribution-bar {
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
        margin: 5px 0;
    }
    
    .distribution-fill {
        height: 100%;
        background: linear-gradient(90deg, #2C8F0C, #4CAF50);
        border-radius: 4px;
        transition: width 1s ease;
    }
    
    /* Average Rating Display */
    .rating-summary {
        background: linear-gradient(135deg, #2C8F0C 0%, #4CAF50 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 10px 20px rgba(44, 143, 12, 0.2);
    }
    
    .rating-number {
        font-size: 4.5rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .rating-total {
        font-size: 1rem;
        opacity: 0.9;
    }
    
    /* Form Styles */
    .review-form-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 2px dashed #2C8F0C;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .review-form-header {
        color: #2C8F0C;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }
    
    .form-control:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.25rem rgba(44, 143, 12, 0.25);
    }
    
    /* Badge Styles */
    .badge-reviewed {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
    }
    
    .badge-pending {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
    }
    
    /* Action Buttons */
    .btn-review-action {
        border-radius: 25px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-review-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    
    /* Review Actions */
    .review-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    /* Order Badge */
    .order-badge {
        background: rgba(44, 143, 12, 0.1);
        color: #2C8F0C;
        border: 1px solid rgba(44, 143, 12, 0.2);
        border-radius: 6px;
        padding: 0.25rem 0.75rem;
        font-size: 0.85rem;
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
            <div class="position-relative">
                <img src="{{ $product->image_url }}" 
                     class="img-fluid rounded shadow-sm product-main-image" 
                     alt="{{ $product->name }}"
                     id="product-main-image"
                     style="width: 100%; height: 400px; object-fit: cover;">
                <div class="position-absolute top-0 start-0 mt-2 ms-2">
                    @if($product->has_discount)
                    <span class="badge bg-danger fs-6">{{ $product->discount_percentage }}% OFF</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <h1 class="h2 fw-bold text-success">{{ $product->name }}</h1>
            
            <!-- Display Brand -->
            @if($product->brand_id && $product->brand)
                <div class="mb-2">
                    <span class="badge bg-light text-dark border px-3 py-2">
                        <i class="fas fa-tag me-1 text-success"></i>{{ $product->brand->name }}
                    </span>
                </div>
            @endif
            
            <p class="text-muted mb-2">Category: {{ $product->category->name }}</p>
            
            <div class="mb-3">
                @if($product->has_discount)
                    <span class="h3 text-success me-2" id="product-price">₱{{ number_format($product->sale_price, 2) }}</span>
                    <span class="h5 text-muted text-decoration-line-through" id="product-original-price">₱{{ number_format($product->price, 2) }}</span>
                @else
                    <span class="h3 text-success fw-bold" id="product-price">₱{{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <!-- Variant Selection -->
            @if($product->has_variants && $product->variants->count() > 0)
            <div class="mb-4">
                <label class="form-label fw-bold text-success">Select Option:</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($product->variants as $variant)
                        @php
                            $variantName = $variant->display_name;
                            $variantPrice = $variant->current_price;
                            $variantStock = $variant->stock_quantity ?? 0;
                            $isInStock = $variantStock > 0;
                            $isFirstInStock = $loop->first && $isInStock;
                            $hasVariantDiscount = $variant->has_discount;
                            $variantDiscountPercent = $variant->discount_percentage;
                            $variantDescription = $variant->variant_description;
                        @endphp
                        <div class="form-check p-0">
                            <input class="form-check-input d-none" type="radio" name="selected_variant" 
                                   id="variant_{{ $loop->index }}" value="{{ $variantName }}"
                                   data-variant-id="{{ $variant->id }}"
                                   data-variant-image="{{ $variant->image_url }}"
                                   data-variant-price="{{ $variantPrice }}"
                                   data-variant-original-price="{{ $variant->price }}"
                                   data-variant-has-discount="{{ $hasVariantDiscount ? 'true' : 'false' }}"
                                   data-variant-discount-percent="{{ $variantDiscountPercent }}"
                                   data-variant-sku="{{ $variant->sku }}"
                                   data-variant-stock="{{ $variantStock }}"
                                   data-variant-in-stock="{{ $isInStock ? 'true' : 'false' }}"
                                   data-variant-description="{{ $variantDescription }}"
                                   {{ $isFirstInStock ? 'checked' : '' }}
                                   {{ !$isInStock ? 'disabled' : '' }}>
                            <label class="form-check-label variant-option {{ $isFirstInStock ? 'selected' : '' }} {{ !$isInStock ? 'disabled' : '' }}" 
                                   for="variant_{{ $loop->index }}">
                                <div class="text-center">
                                    <div class="fw-semibold">{{ $variantName }}</div>
                                    
                                    @if($hasVariantDiscount)
                                        <div class="text-success fw-bold">₱{{ number_format($variant->sale_price, 2) }}</div>
                                        <div class="text-muted text-decoration-line-through small">₱{{ number_format($variant->price, 2) }}</div>
                                    @else
                                        <div class="text-success fw-bold">₱{{ number_format($variant->price, 2) }}</div>
                                    @endif
                                    
                                    @if(!$isInStock)
                                    <small class="text-danger">Out of Stock</small>
                                    @else
                                    <small class="text-muted">{{ $variantStock }} available</small>
                                    @endif
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mb-4">
                <span class="badge bg-{{ $product->in_stock ? 'success' : 'danger' }}" id="stock-badge">
                    {{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}
                </span>
                <small class="text-muted ms-2" id="stock-text">Total: {{ $product->total_stock }} units available</small>
            </div>

            @if($product->in_stock)
            <!-- Action Buttons - KEEP ORIGINAL DESIGN -->
            <div class="row g-3 mb-4">
                <!-- Buy Now Button - ORIGINAL STYLE -->
                <div class="col-md-6">
                    <form action="{{ route('orders.create') }}" method="GET" id="buy-now-form">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1" id="buy-now-quantity">
                        <input type="hidden" name="selected_size" id="buy-now-variant-input" 
                               value="{{ $product->has_variants && $product->variants->count() > 0 ? ($product->variants->where('stock_quantity', '>', 0)->first()->display_name ?? 'Standard') : 'Standard' }}">
                        <input type="hidden" name="direct_checkout" value="true">
                        <button type="submit" class="btn btn-buy-now btn-lg w-100" id="buy-now-btn">
                            <i class="fas fa-bolt me-2"></i>Buy Now
                        </button>
                    </form>
                </div>
                
                <!-- Add to Cart Button - ORIGINAL STYLE -->
                <div class="col-md-6">
                    <form action="{{ route('cart.store') }}" method="POST" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1" id="quantity-input">
                        <input type="hidden" name="selected_size" id="selected_variant_input" 
                               value="{{ $product->has_variants && $product->variants->count() > 0 ? ($product->variants->where('stock_quantity', '>', 0)->first()->display_name ?? 'Standard') : 'Standard' }}">
                        <button type="submit" class="btn btn-primary btn-lg w-100 add-to-cart-btn" id="add-to-cart-btn">
                            <i class="fas fa-cart-plus me-2"></i>Add to Cart
                        </button>
                    </form>
                </div>
            </div>

            {{-- <!-- Message Button - ORIGINAL COMMENTED OUT -->
            <div class="d-grid mb-4">
                @auth
                    @php
                        $adminUser = App\Models\User::where('role', 'admin')->first();
                    @endphp
                    @if(auth()->user()->id != $adminUser->id)
                        <a href="{{ route('messages.show', ['product' => $product->id, 'user' => $adminUser->id]) }}" 
                           class="btn btn-outline-success btn-lg">
                            <i class="fas fa-comment-dots me-2"></i>Message Seller
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg">
                        <i class="fas fa-comment-dots me-2"></i>Login to Message Seller
                    </a>
                @endauth
            </div> --}}
            @else
            <button class="btn btn-secondary btn-lg w-100" disabled>Out of Stock</button>
            @endif
        </div>
    </div>

    <!-- Product Description & Details Section - KEEP ORIGINAL DESIGN -->
    <div class="details-section">
        <h3><i class="fas fa-info-circle me-2"></i>Product Information</h3>
        
        <div class="row">
            <!-- Description Column -->
            <div class="col-lg-6">
                <h5 class="text-success mb-3">Description</h5>
                <p class="mb-0" id="product-description">{{ $product->description }}</p>
            </div>
            
            <!-- Details Column -->
            <div class="col-lg-6">
                <h5 class="text-success mb-3">Product Details</h5>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th width="35%">SKU:</th>
                                <td id="product-sku">{{ $product->sku }}</td>
                            </tr>
                            <tr>
                                <th>Category:</th>
                                <td>{{ $product->category->name }}</td>
                            </tr>
                            @if($product->brand_id && $product->brand)
                            <tr>
                                <th>Brand:</th>
                                <td>{{ $product->brand->name }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Availability:</th>
                                <td>
                                    <span class="badge bg-{{ $product->in_stock ? 'success' : 'danger' }}" id="availability-badge">
                                        {{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                    <span class="ms-2" id="availability-text">{{ $product->total_stock }} units available</span>
                                </td>
                            </tr>
                            @if($product->has_variants && $product->variants->count() > 0)
                            <tr>
                                <th>Selected Option:</th>
                                <td id="selected-option">
                                    @php
                                        $firstAvailableVariant = $product->variants->where('stock_quantity', '>', 0)->first();
                                    @endphp
                                    @if($firstAvailableVariant)
                                        {{ $firstAvailableVariant->display_name }}
                                    @else
                                        {{ $product->variants->first()->display_name ?? 'Standard' }}
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products - KEEP ORIGINAL DESIGN -->
    @if($relatedProducts->count() > 0)
    <section class="mt-5">
        <h3 class="mb-4 text-success fw-bold">Related Products</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card product-card h-100">
                    <img src="{{ $relatedProduct->image_url }}" class="card-img-top product-image" alt="{{ $relatedProduct->name }}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-semibold">{{ $relatedProduct->name }}</h6>
                        
                        <!-- Display Brand for Related Products -->
                        @if($relatedProduct->brand_id && $relatedProduct->brand)
                            <small class="text-muted d-block mb-2">
                                <i class="fas fa-tag me-1"></i>{{ $relatedProduct->brand->name }}
                            </small>
                        @endif
                        
                        <!-- Display Available Variants -->
                        @if($relatedProduct->has_variants && $relatedProduct->variants->count() > 0)
                        <div class="mb-2">
                            <small class="text-muted">Options: 
                                @foreach($relatedProduct->variants as $variant)
                                    @php
                                        $variantName = $variant->display_name;
                                        $variantStock = $variant->stock_quantity ?? 0;
                                    @endphp
                                    <span class="badge bg-light text-dark border me-1 {{ $variantStock <= 0 ? 'text-decoration-line-through text-muted' : '' }}">
                                        {{ $variantName }}
                                    </span>
                                @endforeach
                            </small>
                        </div>
                        @endif
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-success fw-bold">₱{{ $relatedProduct->current_price }}</span>
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

    <!-- ================= IMPROVED RATINGS SECTION ONLY ================= -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-star me-2"></i>Product Reviews & Ratings</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Average Rating Display -->
                    <div class="row mb-4">
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <div class="rating-summary">
                                <div class="rating-number">{{ number_format($product->average_rating, 1) }}</div>
                                <div class="star-rating-display mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->average_rating))
                                            <i class="fas fa-star"></i>
                                        @elseif($i == ceil($product->average_rating) && fmod($product->average_rating, 1) != 0)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="rating-total">Based on {{ $product->total_ratings }} reviews</div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <!-- User Rating Form -->
                            @auth
                                @if($product->canUserReview(auth()->user()))
                                    <div class="user-rating-form">
                                        <h5 class="text-success mb-3">Share Your Experience</h5>
                                        <p class="text-muted mb-4">
                                            You can leave a review for each delivered order containing this product.
                                        </p>
                                        
                                        @php
                                            $userOrders = $product->getUserDeliveredOrders(auth()->user());
                                            $userRatings = [];
                                            
                                            foreach ($userOrders as $order) {
                                                $rating = $product->getUserRatingForOrder($order->id, auth()->user());
                                                if ($rating) {
                                                    $userRatings[$order->id] = $rating;
                                                }
                                            }
                                        @endphp
                                        
                                        @if($userOrders->count() > 0)
                                            @foreach($userOrders as $order)
                                                @php
                                                    $existingRating = $userRatings[$order->id] ?? null;
                                                @endphp
                                                
                                                <div class="review-card mb-4">
                                                    <div class="review-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span>
                                                                <i class="fas fa-shopping-bag me-1"></i>
                                                                Order #{{ $order->order_number }} 
                                                                <small class="opacity-75">({{ $order->created_at->format('M d, Y') }})</small>
                                                            </span>
                                                            @if($existingRating)
                                                                <span class="badge-reviewed">
                                                                    <i class="fas fa-check me-1"></i>Reviewed
                                                                </span>
                                                            @else
                                                                <span class="badge-pending">
                                                                    <i class="fas fa-clock me-1"></i>Pending Review
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="review-body">
                                                        @if($existingRating)
                                                            <!-- Display Existing Review -->
                                                            <div class="existing-review">
                                                                <div class="reviewer-info">
                                                                    <div class="reviewer-avatar">
                                                                        {{ substr(auth()->user()->name, 0, 1) }}
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="mb-0 fw-bold">{{ auth()->user()->name }}</h6>
                                                                        <small class="text-muted">
                                                                            Reviewed on {{ $existingRating->created_at->format('M d, Y') }}
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="star-rating-display mb-3">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        @if($i <= $existingRating->rating)
                                                                            <i class="fas fa-star"></i>
                                                                        @else
                                                                            <i class="far fa-star"></i>
                                                                        @endif
                                                                    @endfor
                                                                    <span class="ms-2 fw-bold text-success">{{ $existingRating->rating }}/5</span>
                                                                </div>
                                                                
                                                                <div class="review-text mb-3">
                                                                    {{ $existingRating->review }}
                                                                </div>
                                                                
                                                                @if($existingRating->updated_at != $existingRating->created_at)
                                                                    <small class="text-muted">
                                                                        <i class="fas fa-history me-1"></i>
                                                                        Updated on {{ $existingRating->updated_at->format('M d, Y') }}
                                                                    </small>
                                                                @endif
                                                                
                                                                <!-- Edit/Delete Actions -->
                                                                <div class="review-actions">
                                                                    <button class="btn btn-outline-success btn-review-action" 
                                                                            data-bs-toggle="collapse" 
                                                                            data-bs-target="#editReview{{ $existingRating->id }}">
                                                                        <i class="fas fa-edit me-1"></i>Edit Review
                                                                    </button>
                                                                    
                                                                    <form action="{{ route('ratings.destroy', $existingRating) }}" 
                                                                          method="POST" 
                                                                          class="d-inline"
                                                                          onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-outline-danger btn-review-action">
                                                                            <i class="fas fa-trash me-1"></i>Delete
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Edit Form (Collapsed) -->
                                                            <div class="collapse mt-4" id="editReview{{ $existingRating->id }}">
                                                                <div class="review-form-card">
                                                                    <h5 class="review-form-header mb-4">
                                                                        <i class="fas fa-edit me-2"></i>Edit Your Review
                                                                    </h5>
                                                                    <form action="{{ route('ratings.update', $existingRating) }}" method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                                        
                                                                        <div class="mb-4">
                                                                            <label class="form-label fw-semibold text-success mb-3">Update Your Rating</label>
                                                                            <div class="star-rating-input">
                                                                                @for($i = 5; $i >= 1; $i--)
                                                                                    <input type="radio" id="edit_star{{ $order->id }}_{{ $i }}" 
                                                                                           name="rating" value="{{ $i }}"
                                                                                           {{ $existingRating->rating == $i ? 'checked' : '' }} required>
                                                                                    <label for="edit_star{{ $order->id }}_{{ $i }}">
                                                                                        <i class="fas fa-star"></i>
                                                                                    </label>
                                                                                @endfor
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="mb-4">
                                                                            <label for="edit_review{{ $order->id }}" class="form-label fw-semibold text-success">Your Review</label>
                                                                            <textarea name="review" id="edit_review{{ $order->id }}" 
                                                                                      class="form-control" rows="4" required>{{ $existingRating->review }}</textarea>
                                                                            <div class="form-text">Your review helps other customers make better decisions.</div>
                                                                        </div>
                                                                        
                                                                        <div class="d-flex gap-2">
                                                                            <button type="submit" class="btn btn-success px-4 py-2">
                                                                                <i class="fas fa-save me-2"></i>Save Changes
                                                                            </button>
                                                                            <button type="button" class="btn btn-outline-secondary px-4 py-2" 
                                                                                    data-bs-toggle="collapse" 
                                                                                    data-bs-target="#editReview{{ $existingRating->id }}">
                                                                                Cancel
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <!-- New Review Form -->
                                                            <div class="review-form-card">
                                                                <h5 class="review-form-header mb-4">
                                                                    <i class="fas fa-comment-medical me-2"></i>Review Order #{{ $order->order_number }}
                                                                </h5>
                                                                <form action="{{ route('ratings.store', $product) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                                    
                                                                    <div class="mb-4">
                                                                        <label class="form-label fw-semibold text-success mb-3">Rate Your Experience</label>
                                                                        <div class="star-rating-input">
                                                                            @for($i = 5; $i >= 1; $i--)
                                                                                <input type="radio" id="star{{ $order->id }}_{{ $i }}" 
                                                                                       name="rating" value="{{ $i }}" required>
                                                                                <label for="star{{ $order->id }}_{{ $i }}">
                                                                                    <i class="fas fa-star"></i>
                                                                                </label>
                                                                            @endfor
                                                                        </div>
                                                                        <div class="form-text mt-2">Click on a star to rate this product</div>
                                                                    </div>
                                                                    
                                                                    <div class="mb-4">
                                                                        <label for="review{{ $order->id }}" class="form-label fw-semibold text-success">Your Review</label>
                                                                        <textarea name="review" id="review{{ $order->id }}" 
                                                                                  class="form-control" rows="4" 
                                                                                  placeholder="Share your experience with this product from order #{{ $order->order_number }}..." 
                                                                                  required></textarea>
                                                                        <div class="form-text">Be specific about what you liked or didn't like.</div>
                                                                    </div>
                                                                    
                                                                    <button type="submit" class="btn btn-success px-4 py-2">
                                                                        <i class="fas fa-paper-plane me-2"></i>Submit Review
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                You haven't received any orders containing this product yet.
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        You can review this product after purchase and delivery.
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    <a href="{{ route('login') }}" class="alert-link">Login</a> to review this product if you've purchased it.
                                </div>
                            @endauth
                        </div>
                    </div>

                    <!-- All Reviews List -->
                    <div class="reviews-list mt-5">
                        <h5 class="text-success mb-4">Customer Reviews ({{ $product->ratings->count() }})</h5>
                        @if($product->ratings->count() > 0)
                            @foreach($product->ratings()->with('user')->latest()->get() as $rating)
                                <div class="review-card">
                                    <div class="review-body">
                                        <div class="reviewer-info">
                                            <div class="reviewer-avatar">
                                                {{ substr($rating->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $rating->user->name }}</h6>
                                                <div class="d-flex flex-wrap gap-2 mt-1">
                                                    <small class="text-muted">
                                                        {{ $rating->created_at->format('F d, Y') }}
                                                    </small>
                                                    @if($rating->order)
                                                        <span class="order-badge">
                                                            <i class="fas fa-receipt me-1"></i>Order #{{ $rating->order->order_number }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="star-rating-display mb-3">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rating->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-2 fw-bold text-success">{{ $rating->rating }}/5</span>
                                        </div>
                                        
                                        <div class="review-text">
                                            {{ $rating->review }}
                                        </div>
                                        
                                        @if($rating->updated_at != $rating->created_at)
                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-history me-1"></i>
                                                    Updated on {{ $rating->updated_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-comment-slash"></i>
                                </div>
                                <h5 class="text-muted mb-3">No Reviews Yet</h5>
                                <p class="text-muted">Be the first to share your experience with this product!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.getElementById('product-main-image');
    const productPrice = document.getElementById('product-price');
    const productOriginalPrice = document.getElementById('product-original-price');
    const productSku = document.getElementById('product-sku');
    const availabilityBadge = document.getElementById('availability-badge');
    const availabilityText = document.getElementById('availability-text');
    const selectedOption = document.getElementById('selected-option');
    const stockBadge = document.getElementById('stock-badge');
    const stockText = document.getElementById('stock-text');
    const productDescription = document.getElementById('product-description');
    const variantInput = document.getElementById('selected_variant_input');
    const buyNowVariantInput = document.getElementById('buy-now-variant-input');
    const variantRadios = document.querySelectorAll('input[name="selected_variant"]');
    
    // Store original product data
    const originalProductData = {
        sku: productSku ? productSku.textContent : '{{ $product->sku }}',
        description: productDescription ? productDescription.textContent : '{{ $product->description }}',
        inStock: {{ $product->in_stock ? 'true' : 'false' }},
        totalStock: {{ $product->total_stock }}
    };
    
    // Update selected variant when user clicks on a variant option
    variantRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (!this.disabled) {
                const variantImage = this.getAttribute('data-variant-image');
                const variantPrice = this.getAttribute('data-variant-price');
                const variantOriginalPrice = this.getAttribute('data-variant-original-price');
                const hasDiscount = this.getAttribute('data-variant-has-discount') === 'true';
                const discountPercent = this.getAttribute('data-variant-discount-percent');
                const variantSku = this.getAttribute('data-variant-sku');
                const variantStock = parseInt(this.getAttribute('data-variant-stock'));
                const variantInStock = this.getAttribute('data-variant-in-stock') === 'true';
                const variantDescription = this.getAttribute('data-variant-description');
                
                // Update selected variant value for both forms
                variantInput.value = this.value;
                buyNowVariantInput.value = this.value;
                
                // Update image with smooth transition
                if (variantImage && variantImage !== mainImage.src) {
                    mainImage.classList.add('image-loading');
                    setTimeout(() => {
                        mainImage.src = variantImage;
                        mainImage.classList.remove('image-loading');
                    }, 150);
                }
                
                // Update price display
                if (hasDiscount) {
                    productPrice.textContent = '₱' + parseFloat(variantPrice).toFixed(2);
                    productPrice.className = 'h3 text-success me-2';
                    
                    if (productOriginalPrice) {
                        productOriginalPrice.textContent = '₱' + parseFloat(variantOriginalPrice).toFixed(2);
                        productOriginalPrice.style.display = 'inline';
                    }
                    
                    // Update image discount badge
                    let imageDiscountBadge = document.querySelector('.position-absolute .badge.bg-success');
                    if (hasDiscount) {
                        if (!imageDiscountBadge) {
                            imageDiscountBadge = document.createElement('span');
                            imageDiscountBadge.className = 'badge bg-success fs-6';
                            document.querySelector('.position-absolute').appendChild(imageDiscountBadge);
                        }
                        imageDiscountBadge.textContent = discountPercent + '% OFF';
                    } else if (imageDiscountBadge) {
                        imageDiscountBadge.remove();
                    }
                } else {
                    productPrice.textContent = '₱' + parseFloat(variantPrice).toFixed(2);
                    productPrice.className = 'h3 text-success fw-bold';
                    
                    if (productOriginalPrice) {
                        productOriginalPrice.style.display = 'none';
                    }
                    
                    // Remove image discount badge if no discount
                    const imageDiscountBadge = document.querySelector('.position-absolute .badge.bg-success');
                    if (imageDiscountBadge) {
                        imageDiscountBadge.remove();
                    }
                }
                
                // Update product information
                if (productSku) {
                    productSku.textContent = variantSku || originalProductData.sku;
                }
                
                if (productDescription && variantDescription) {
                    productDescription.textContent = variantDescription || originalProductData.description;
                }
                
                if (availabilityBadge) {
                    const inStock = variantInStock;
                    availabilityBadge.textContent = inStock ? 'In Stock' : 'Out of Stock';
                    availabilityBadge.className = `badge bg-${inStock ? 'success' : 'danger'}`;
                }
                
                if (availabilityText) {
                    availabilityText.textContent = variantStock > 0 ? `${variantStock} units available` : 'Out of stock';
                }
                
                if (selectedOption) {
                    selectedOption.textContent = this.value;
                }
                
                if (stockBadge) {
                    stockBadge.textContent = variantInStock ? 'In Stock' : 'Out of Stock';
                    stockBadge.className = `badge bg-${variantInStock ? 'success' : 'danger'}`;
                }
                
                if (stockText) {
                    stockText.textContent = variantStock > 0 ? `${variantStock} available` : 'Out of stock';
                }
                
                // Update selected style
                document.querySelectorAll('.variant-option').forEach(option => {
                    option.classList.remove('selected');
                });
                this.closest('.form-check').querySelector('.variant-option').classList.add('selected');
                
                // Update add to cart button state
                updateButtonStates();
            }
        });
    });

    // Add click handler for variant options
    document.querySelectorAll('.variant-option:not(.disabled)').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.closest('.form-check').querySelector('input[type="radio"]');
            if (!radio.disabled) {
                radio.checked = true;
                radio.dispatchEvent(new Event('change'));
            }
        });
    });

    function updateButtonStates() {
        const selectedVariant = document.querySelector('input[name="selected_variant"]:checked');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const buyNowBtn = document.getElementById('buy-now-btn');
        
        if (selectedVariant && selectedVariant.disabled) {
            addToCartBtn.disabled = true;
            addToCartBtn.innerHTML = '<i class="fas fa-times me-2"></i>Out of Stock';
            addToCartBtn.classList.remove('btn-primary');
            addToCartBtn.classList.add('btn-secondary');
            
            buyNowBtn.disabled = true;
            buyNowBtn.innerHTML = '<i class="fas fa-times me-2"></i>Out of Stock';
            buyNowBtn.classList.remove('btn-buy-now');
            buyNowBtn.classList.add('btn-secondary');
        } else {
            addToCartBtn.disabled = false;
            addToCartBtn.innerHTML = '<i class="fas fa-cart-plus me-2"></i>Add to Cart';
            addToCartBtn.classList.remove('btn-secondary');
            addToCartBtn.classList.add('btn-primary');
            
            buyNowBtn.disabled = false;
            buyNowBtn.innerHTML = '<i class="fas fa-bolt me-2"></i>Buy Now';
            buyNowBtn.classList.remove('btn-secondary');
            buyNowBtn.classList.add('btn-buy-now');
        }
    }

    // Initialize button state
    updateButtonStates();

    // Add to cart form handling
    const addToCartForm = document.getElementById('add-to-cart-form');
    
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('.add-to-cart-btn');
            const originalText = submitBtn.innerHTML;
            
            // Validate variant selection only if product has variants
            if (variantRadios.length > 0) {
                const selectedVariant = document.querySelector('input[name="selected_variant"]:checked');
                if (!selectedVariant || selectedVariant.disabled) {
                    showToast('Please select an available option before adding to cart.', 'warning');
                    return;
                }
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding to Cart...';
            
            // Submit via AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('Product added to cart successfully!', 'success');
                    if (data.cart_count !== undefined) {
                        updateCartCount(data.cart_count);
                    }
                } else {
                    showToast(data.message || 'Error adding product to cart.', 'error');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showToast('Unable to add product to cart. Please try again.', 'error');
            })
            .finally(() => {
                // Restore button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }

    // Buy Now form handling
    const buyNowForm = document.getElementById('buy-now-form');
    
    if (buyNowForm) {
        buyNowForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('#buy-now-btn');
            const originalText = submitBtn.innerHTML;
            
            // Validate variant selection only if product has variants
            if (variantRadios.length > 0) {
                const selectedVariant = document.querySelector('input[name="selected_variant"]:checked');
                if (!selectedVariant || selectedVariant.disabled) {
                    e.preventDefault();
                    showToast('Please select an available option before purchasing.', 'warning');
                    return;
                }
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            
            // Allow form to submit normally (redirect to orders create page)
        });
    }
    
    // Toast notification function
    function showToast(message, type = 'success') {
        // Remove existing toasts
        document.querySelectorAll('.upper-middle-toast').forEach(toast => toast.remove());
        
        const bgColors = {
            'success': '#2C8F0C',
            'error': '#dc3545',
            'warning': '#ffc107',
            'info': '#17a2b8'
        };
        
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-triangle',
            'warning': 'fa-exclamation-circle',
            'info': 'fa-info-circle'
        };
        
        const bgColor = bgColors[type] || bgColors.success;
        const icon = icons[type] || icons.success;
        const textColor = type === 'warning' ? 'text-dark' : 'text-white';
        
        const toast = document.createElement('div');
        toast.className = 'upper-middle-toast position-fixed start-50 translate-middle-x p-3';
        toast.style.cssText = `
            top: 100px;
            z-index: 9999;
            min-width: 300px;
            text-align: center;
        `;
        
        toast.innerHTML = `
            <div class="toast align-items-center border-0 show shadow-lg" role="alert" style="background-color: ${bgColor}; border-radius: 10px;">
                <div class="d-flex justify-content-center align-items-center p-3">
                    <div class="toast-body ${textColor} d-flex align-items-center">
                        <i class="fas ${icon} me-2 fs-5"></i>
                        <span class="fw-semibold">${message}</span>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 3000);
    }
    
    // Update cart count
    function updateCartCount(count) {
        const cartCountElements = document.querySelectorAll('.cart-count, .cart-badge');
        cartCountElements.forEach(element => {
            element.textContent = count;
            element.style.display = count > 0 ? 'inline-block' : 'none';
        });
    }
});
</script>
@endpush
@endsection