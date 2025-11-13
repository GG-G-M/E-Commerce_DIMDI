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
    .star-rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .star-rating-input input {
        display: none;
    }

    .star-rating-input label {
        cursor: pointer;
        font-size: 1.5rem;
        color: #ddd;
        transition: color 0.2s;
        margin-right: 5px;
    }

    .star-rating-input input:checked ~ label,
    .star-rating-input label:hover,
    .star-rating-input label:hover ~ label {
        color: #ffc107;
    }

    .star-rating-input input:checked + label {
        color: #ffc107;
    }

    .star-rating {
        font-size: 1rem;
    }

    .star-rating .fas,
    .star-rating .far {
        color: #ffc107;
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
            
            <!-- Display Brand - FIXED -->
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
                    <span class="h3 text-danger me-2" id="product-price">₱{{ number_format($product->sale_price, 2) }}</span>
                    <span class="h5 text-muted text-decoration-line-through" id="product-original-price">₱{{ number_format($product->price, 2) }}</span>
                    <span class="badge bg-danger ms-2">{{ $product->discount_percentage }}% OFF</span>
                @else
                    <span class="h3 text-success fw-bold" id="product-price">₱{{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <p class="mb-4">{{ $product->description }}</p>

            <!-- Variant Selection -->
            @if($product->has_variants && $product->variants->count() > 0)
            <div class="mb-4">
                <label class="form-label fw-bold text-success">Select Option:</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($product->variants as $variant)
                        @php
                            $variantName = $variant->size ?? $variant->variant_name ?? 'Option';
                            $variantPrice = $variant->current_price;
                            $variantStock = $variant->stock_quantity ?? 0;
                            $isInStock = $variantStock > 0;
                            $isFirstInStock = $loop->first && $isInStock;
                            $hasVariantDiscount = !is_null($variant->sale_price) && $variant->sale_price < $variant->price;
                            $variantDiscountPercent = $hasVariantDiscount ? round((($variant->price - $variant->sale_price) / $variant->price) * 100) : 0;
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
                                   {{ $isFirstInStock ? 'checked' : '' }}
                                   {{ !$isInStock ? 'disabled' : '' }}>
                            <label class="form-check-label variant-option {{ $isFirstInStock ? 'selected' : '' }} {{ !$isInStock ? 'disabled' : '' }}" 
                                   for="variant_{{ $loop->index }}">
                                <div class="text-center">
                                    <div class="fw-semibold">{{ $variantName }}</div>
                                    
                                    @if($hasVariantDiscount)
                                        <div class="text-danger fw-bold">₱{{ number_format($variant->sale_price, 2) }}</div>
                                        <div class="text-muted text-decoration-line-through small">₱{{ number_format($variant->price, 2) }}</div>
                                        <span class="badge bg-danger small">{{ $variantDiscountPercent }}% OFF</span>
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
                <span class="badge bg-{{ $product->in_stock ? 'success' : 'danger' }}">
                    {{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}
                </span>
                <small class="text-muted ms-2">Total: {{ $product->total_stock }} units available</small>
            </div>

            @if($product->in_stock)
            <form action="{{ route('cart.store') }}" method="POST" class="mb-4" id="add-to-cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1" id="quantity-input">
                <input type="hidden" name="selected_size" id="selected_variant_input" 
                       value="{{ $product->has_variants && $product->variants->count() > 0 ? ($product->variants->where('stock_quantity', '>', 0)->first()->size ?? $product->variants->where('stock_quantity', '>', 0)->first()->variant_name ?? 'Standard') : 'Standard' }}">

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg add-to-cart-btn" id="add-to-cart-btn">
                        <i class="fas fa-cart-plus me-2"></i>Add to Cart
                    </button>
                </div>
            </form>

            {{-- <!-- Message Button -->
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

            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title text-success fw-bold">Product Details</h6>
                    <ul class="list-unstyled mb-0">
                        <li><strong>SKU:</strong> {{ $product->sku }}</li>
                        <li><strong>Category:</strong> {{ $product->category->name }}</li>
                        <!-- Brand Display - FIXED -->
                        @if($product->brand_id && $product->brand)
                        <li><strong>Brand:</strong> {{ $product->brand->name }}</li>
                        @endif
                        <li><strong>Availability:</strong> {{ $product->total_stock }} in stock</li>
                        @if($product->has_variants && $product->variants->count() > 0)
                        <li><strong>Available Options:</strong> 
                            @foreach($product->variants as $variant)
                                @php
                                    $variantName = $variant->size ?? $variant->variant_name ?? 'Option';
                                    $variantStock = $variant->stock_quantity ?? 0;
                                @endphp
                                <span class="badge bg-{{ $variantStock > 0 ? 'primary' : 'secondary' }} me-1">
                                    {{ $variantName }} ({{ $variantStock }})
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
        <h3 class="mb-4 text-success fw-bold">Related Products</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card product-card h-100">
                    <img src="{{ $relatedProduct->image_url }}" class="card-img-top product-image" alt="{{ $relatedProduct->name }}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-semibold">{{ $relatedProduct->name }}</h6>
                        
                        <!-- Display Brand for Related Products - FIXED -->
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
                                        $variantName = $variant->size ?? $variant->variant_name ?? 'Option';
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
</div>
    <!-- Rating Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-star me-2"></i>Product Reviews & Ratings</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Average Rating Display -->
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="display-4 text-success fw-bold">{{ number_format($product->average_rating, 1) }}</div>
                            <div class="star-rating mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->average_rating))
                                        <i class="fas fa-star text-warning"></i>
                                    @elseif($i == ceil($product->average_rating) && fmod($product->average_rating, 1) != 0)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="text-muted">Based on {{ $product->total_ratings }} reviews</p>
                        </div>
                        <div class="col-md-8">
                            <!-- User Rating Form (if purchased and not rated) -->
                            @auth
                                @if($product->purchasedBy(auth()->user()) && !$product->ratedBy(auth()->user()))
                                    <div class="user-rating-form">
                                        <h5 class="text-success">Rate this product</h5>
                                        <p class="text-muted">Share your experience with this product</p>
                                        <form action="{{ route('ratings.store', $product) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Your Rating</label>
                                                <div class="star-rating-input">
                                                    @for($i = 5; $i >= 1; $i--)
                                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                                                        <label for="star{{ $i }}"><i class="far fa-star"></i></label>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="review" class="form-label fw-semibold">Review (Optional)</label>
                                                <textarea name="review" id="review" class="form-control" rows="3" placeholder="Share your experience with this product..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-paper-plane me-2"></i>Submit Rating
                                            </button>
                                        </form>
                                    </div>
                                @elseif($product->ratedBy(auth()->user()))
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle me-2"></i>You have already rated this product. Thank you!
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-info-circle me-2"></i>You can rate this product after purchase and delivery.
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    <a href="{{ route('login') }}" class="alert-link">Login</a> to rate this product if you've purchased it.
                                </div>
                            @endauth
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="reviews-list">
                        <h5 class="text-success mb-4">Customer Reviews</h5>
                        @if($product->ratings->count() > 0)
                            @foreach($product->ratings()->with('user')->latest()->get() as $rating)
                                <div class="review-item border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong class="text-success">{{ $rating->user->name }}</strong>
                                            <div class="star-rating mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $rating->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-2 text-muted">{{ $rating->rating }}/5</span>
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $rating->created_at->format('M d, Y') }}</small>
                                    </div>
                                    @if($rating->review)
                                        <p class="mt-2 mb-0">{{ $rating->review }}</p>
                                    @else
                                        <p class="mt-2 mb-0 text-muted"><em>No review text provided</em></p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                            </div>
                        @endif
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
    const variantInput = document.getElementById('selected_variant_input');
    const variantRadios = document.querySelectorAll('input[name="selected_variant"]');
    
    // Update selected variant when user clicks on a variant option
    variantRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (!this.disabled) {
                const variantImage = this.getAttribute('data-variant-image');
                const variantPrice = this.getAttribute('data-variant-price');
                const variantOriginalPrice = this.getAttribute('data-variant-original-price');
                const hasDiscount = this.getAttribute('data-variant-has-discount') === 'true';
                const discountPercent = this.getAttribute('data-variant-discount-percent');
                
                // Update selected variant value
                variantInput.value = this.value;
                
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
                    productPrice.className = 'h3 text-danger me-2';
                    
                    if (productOriginalPrice) {
                        productOriginalPrice.textContent = '₱' + parseFloat(variantOriginalPrice).toFixed(2);
                        productOriginalPrice.style.display = 'inline';
                    }
                    
                    // Update image discount badge (the one in position-absolute)
                    let imageDiscountBadge = document.querySelector('.position-absolute .badge.bg-danger');
                    if (hasDiscount) {
                        if (!imageDiscountBadge) {
                            imageDiscountBadge = document.createElement('span');
                            imageDiscountBadge.className = 'badge bg-danger fs-6';
                            document.querySelector('.position-absolute').appendChild(imageDiscountBadge);
                        }
                        imageDiscountBadge.textContent = discountPercent + '% OFF';
                    } else if (imageDiscountBadge) {
                        imageDiscountBadge.remove();
                    }

                    // Update price discount badge (the one next to price)
                    let priceDiscountBadge = document.querySelector('.mb-3 .badge.bg-danger');
                    if (hasDiscount) {
                        if (!priceDiscountBadge) {
                            priceDiscountBadge = document.createElement('span');
                            priceDiscountBadge.className = 'badge bg-danger ms-2';
                            productPrice.parentNode.appendChild(priceDiscountBadge);
                        }
                        priceDiscountBadge.textContent = discountPercent + '% OFF';
                    } else if (priceDiscountBadge) {
                        priceDiscountBadge.remove();
                    }
                    
                    // Remove discount badge if exists
                    const discountBadge = document.querySelector('.badge.bg-danger');
                    if (discountBadge && !discountBadge.closest('.position-absolute')) {
                        discountBadge.remove();
                    }
                } else {
                    productPrice.textContent = '₱' + parseFloat(variantPrice).toFixed(2);
                    productPrice.className = 'h3 text-success fw-bold';
                    
                    if (productOriginalPrice) {
                        productOriginalPrice.style.display = 'none';
                    }
                    
                    // Remove discount badges
                    const imageDiscountBadge = document.querySelector('.position-absolute .badge.bg-danger');
                    if (imageDiscountBadge) {
                        imageDiscountBadge.remove();
                    }
                    
                    const priceDiscountBadge = document.querySelector('.mb-3 .badge.bg-danger');
                    if (priceDiscountBadge) {
                        priceDiscountBadge.remove();
                    }
                }
                
                // Update selected style
                document.querySelectorAll('.variant-option').forEach(option => {
                    option.classList.remove('selected');
                });
                this.closest('.form-check').querySelector('.variant-option').classList.add('selected');
                
                // Update add to cart button state
                updateAddToCartButton();
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

    function updateAddToCartButton() {
        const selectedVariant = document.querySelector('input[name="selected_variant"]:checked');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        
        if (selectedVariant && selectedVariant.disabled) {
            addToCartBtn.disabled = true;
            addToCartBtn.innerHTML = '<i class="fas fa-times me-2"></i>Out of Stock';
            addToCartBtn.classList.remove('btn-primary');
            addToCartBtn.classList.add('btn-secondary');
        } else {
            addToCartBtn.disabled = false;
            addToCartBtn.innerHTML = '<i class="fas fa-cart-plus me-2"></i>Add to Cart';
            addToCartBtn.classList.remove('btn-secondary');
            addToCartBtn.classList.add('btn-primary');
        }
    }

    // Initialize button state
    updateAddToCartButton();

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
                    showToast('Product added to cart successfully! 🎉', 'success');
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
    
    // Upper middle toast notification function
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
    
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star-rating-input input');
        stars.forEach(star => {
            star.addEventListener('change', function() {
                const rating = this.value;
            
            });
        });
    });
});
</script>
@endpush
@endsection