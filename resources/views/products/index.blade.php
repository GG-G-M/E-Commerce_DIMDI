@extends('layouts.app')

@section('content')
<style>
    .category-slider-full {
        background-color: #2C8F0C !important;
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
    }

    .category-slider-full .nav-pills .nav-link {
        color: white !important;
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        white-space: nowrap;
        margin: 0 8px;
    }

    .category-slider-full .nav-pills .nav-link.active {
        background-color: white !important;
        color: black !important;
        font-weight: bold;
        border-color: white;
    }

    .category-slider-full .nav-pills .nav-link:hover:not(.active) {
        background-color: #2C8F0C !important;
        color: white !important;
    }

    .discount-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 1;
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }

    .product-image {
        height: 250px;
        object-fit: cover;
    }
    
    .btn {
        background-color: #2C8F0C !important;
        color: white !important;
        border: none;
    }

    .btn:hover {
        background-color: #247a0a !important;
        color: white !important;
    }

    .btn-outline-primary {
        background-color: transparent !important;
        color: #2C8F0C !important;
        border: 1px solid #2C8F0C !important;
    }

    .btn-outline-primary:hover {
        background-color: #2C8F0C !important;
        color: white !important;
    }

    .category-header {
        border-bottom: 3px solid #2C8F0C;
        padding-bottom: 10px;
        margin: 30px 0 20px 0;
        color: #2C8F0C;
    }

    #loading-indicator {
        display: none;
        text-align: center;
        padding: 20px;
    }

    #end-of-results {
        display: none;
        text-align: center;
        padding: 20px;
        color: #6c757d;
    }

    .no-products-modal .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .no-products-modal .modal-header {
        border-bottom: 2px solid #E8F5E6;
        background: #F8FDF8;
        border-radius: 15px 15px 0 0;
        padding: 1.5rem;
    }

    .no-products-modal .modal-title {
        color: #2C8F0C;
        font-weight: 700;
        font-size: 1.5rem;
    }

    .no-products-modal .modal-body {
        padding: 2rem;
        text-align: center;
    }

    .no-products-image {
        max-width: 200px;
        height: auto;
        margin-bottom: 20px;
        border-radius: 10px;
    }

    .no-products-title {
        color: #2C8F0C;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .no-products-text {
        color: #6c757d;
        font-size: 1rem;
        margin-bottom: 25px;
        line-height: 1.6;
    }

    .search-suggestions {
        background: #F8FDF8;
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid #2C8F0C;
        margin: 20px 0;
    }

    .suggestion-title {
        color: #2C8F0C;
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 1.1rem;
    }

    .suggestion-list {
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: left;
    }

    .suggestion-list li {
        padding: 8px 0;
        border-bottom: 1px solid #E8F5E6;
    }

    .suggestion-list li:last-child {
        border-bottom: none;
    }

    .suggestion-list a {
        color: #495057;
        text-decoration: none;
        transition: color 0.3s ease;
        display: flex;
        align-items: center;
    }

    .suggestion-list a:hover {
        color: #2C8F0C;
    }

    .suggestion-list i {
        margin-right: 10px;
        color: #2C8F0C;
        width: 20px;
    }

    .no-products-modal .modal-footer {
        border-top: 2px solid #E8F5E6;
        background: #F8FDF8;
        border-radius: 0 0 15px 15px;
        padding: 1.5rem;
    }

    .btn-continue-shopping {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-continue-shopping:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
    }

    .star-rating {
        font-size: 0.7rem;
    }

    .star-rating .fas,
    .star-rating .far {
        color: #ffc107;
    }

    .product-rating {
        margin-bottom: 8px;
    }

    .floating-filter {
        position: fixed;
        top: 45%;
        right: 20px;
        transform: translateY(-50%);
        z-index: 1000;
    }

    .filter-bubble {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(44, 143, 12, 0.3);
        transition: all 0.3s ease;
        border: none;
    }

    .filter-bubble:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(44, 143, 12, 0.4);
    }

    .filter-panel {
        position: absolute;
        top: 0;
        right: 60px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        padding: 20px;
        width: 300px;
        display: none;
        z-index: 1001;
    }

    .filter-panel.show {
        display: block;
        animation: slideInRight 0.3s ease;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .filter-header {
        border-bottom: 2px solid #E8F5E6;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .filter-title {
        color: #2C8F0C;
        font-weight: 700;
        font-size: 1.2rem;
        margin: 0;
    }

    .filter-section {
        margin-bottom: 20px;
    }

    .filter-section-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-options {
        max-height: 150px;
        overflow-y: auto;
    }

    .filter-option {
        display: flex;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-option:hover {
        background-color: #F8FDF8;
        padding-left: 5px;
    }

    .filter-option:last-child {
        border-bottom: none;
    }

    .filter-checkbox {
        margin-right: 10px;
        width: 16px;
        height: 16px;
        border: 2px solid #2C8F0C;
        border-radius: 3px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .filter-checkbox.checked {
        background-color: #2C8F0C;
    }

    .filter-checkbox.checked::after {
        content: '✓';
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    .filter-label {
        flex: 1;
        font-size: 0.9rem;
        color: #555;
    }

    .filter-count {
        background: #E8F5E6;
        color: #2C8F0C;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .price-range {
        padding: 10px 0;
    }

   .price-inputs {
    display: flex;
    gap: 8px;
    margin-bottom: 15px;
    align-items: center;
}

.price-input {
    flex: 1;
    padding: 6px 8px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.8rem;
    text-align: center;
    max-width: 80px;
    min-width: 60px;
}

.price-input:focus {
    outline: none;
    border-color: #2C8F0C;
    box-shadow: 0 0 0 2px rgba(44, 143, 12, 0.1);
}

.price-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 5px;
    font-size: 0.7rem;
    color: #666;
}

    .price-slider {
        width: 100%;
        height: 4px;
        background: #ddd;
        border-radius: 2px;
        outline: none;
        -webkit-appearance: none;
    }

    .price-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 18px;
        height: 18px;
        background: #2C8F0C;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .price-slider::-moz-range-thumb {
        width: 18px;
        height: 18px;
        background: #2C8F0C;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-filter {
        flex: 1;
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-apply {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
    }

    .btn-apply:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    }

    .btn-clear {
        background: #f8f9fa;
        color: #6c757d;
        border: 1px solid #dee2e6;
    }

    .btn-clear:hover {
        background: #e9ecef;
    }

    /* Ultra Compact Banner Styles */
    .banner-card-compact {
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
        max-height: 300px !important;
        height: 300px !important;
        padding: 0 !important;
    }
    
    .carousel-compact {
        height: 350px !important;
        max-height: 350px !important;
    }
    
    .carousel-inner-compact {
        height: 350px !important;
        max-height: 350px !important;
    }
    
    .carousel-item-compact {
        height: 350px !important;
        max-height: 350px !important;
    }
    
    .carousel-image-compact {
        width: 100%;
        height: 350px !important;
        object-fit: cover;
        object-position: center;
        max-height: 350px !important;
    }
    
    /* Hide all captions and controls for ultra compact */
    .carousel-caption,
    .carousel-indicators,
    .carousel-control-prev,
    .carousel-control-next {
        display: none !important;
    }
    .brand-dropdown-container {
    margin-bottom: 10px;
}

.brand-dropdown-container {
    margin-bottom: 10px;
}

.brand-dropdown-menu {
    max-height: 200px;
    overflow-y: auto;
    width: 100%;
}

.brand-dropdown-menu .form-check {
    padding: 0.5rem 1rem;
    margin: 0;
}

.brand-dropdown-menu .form-check-input {
    margin-right: 8px;
}

.brand-dropdown-menu .form-check-label {
    cursor: pointer;
    font-size: 0.9rem;
}

.selected-brands {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    font-size: 0.8rem;
}

.brand-tag {
    background: #E8F5E6;
    color: #2C8F0C;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 4px;
}

.brand-tag .remove-brand {
    cursor: pointer;
    font-size: 0.7rem;
    padding: 0;
    background: none;
    border: none;
    color: #2C8F0C;
}

.brand-tag .remove-brand:hover {
    color: #1E6A08;
}
    @media (max-width: 768px) {
        .floating-filter {
            right: 10px;
            bottom: 80px;
            top: auto;
            transform: none;
        }

        .filter-panel {
            right: 60px;
            bottom: 0;
            top: auto;
            width: 280px;
        }

        .banner-card-compact {
            height: 35px !important;
            max-height: 35px !important;
        }
        
        .carousel-compact,
        .carousel-inner-compact,
        .carousel-item-compact {
            width: 100%;
            aspect-ratio: 16 / 9; /* Maintains 16:9 ratio */
            max-height: 400px;
        }

        .carousel-image-compact {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        /* Mobile adjustments */
        @media (max-width: 768px) {
            .carousel-compact,
            .carousel-inner-compact,
            .carousel-item-compact {
                aspect-ratio: 16 / 9;
                max-height: 250px;
            }
        }

        @media (max-width: 576px) {
            .carousel-compact,
            .carousel-inner-compact,
            .carousel-item-compact {
                aspect-ratio: 16 / 9;
                max-height: 180px;
            }
        }
            }
</style>

<!-- No Products Found Modal -->
<div class="modal fade no-products-modal" id="noProductsModal" tabindex="-1" aria-labelledby="noProductsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noProductsModalLabel">
                    <i class="fas fa-search me-2"></i>No Products Found
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('images/noproduct.png') }}" alt="No products found" class="no-products-image">
                <h3 class="no-products-title">Oops! No Matching Products</h3>
                <p class="no-products-text">
                    We couldn't find any products matching your search criteria. 
                    Don't worry, we have plenty of other amazing products for you to explore!
                </p>
                
                <div class="search-suggestions">
                    <h5 class="suggestion-title">💡 Try These Suggestions:</h5>
                    <ul class="suggestion-list">
                        <li>
                            <a href="{{ route('products.index') }}">
                                <i class="fas fa-store"></i>
                                Browse All Products
                            </a>
                        </li>
                        @foreach($categories->take(4) as $category)
                        <li>
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}">
                                <i class="fas fa-tag"></i>
                                Explore {{ $category->name }}
                            </a>
                        </li>
                        @endforeach
                        <li>
                            <a href="{{ route('products.index', ['sort' => 'featured']) }}">
                                <i class="fas fa-star"></i>
                                Check Featured Products
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index', ['sort' => 'newest']) }}">
                                <i class="fas fa-rocket"></i>
                                See New Arrivals
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('products.index') }}" class="btn btn-continue-shopping">
                    <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Floating Filter Bubble -->
<div class="floating-filter">
    <button class="filter-bubble" id="filterBubble">
        <i class="fas fa-filter"></i>
    </button>
    <div class="filter-panel" id="filterPanel">
        <div class="filter-header">
            <h4 class="filter-title"><i class="fas fa-sliders-h me-2"></i>Filters</h4>
        </div>
        
        <!-- Brand Filter -->
<div class="filter-section">
    <h6 class="filter-section-title">Brands</h6>
    <div class="brand-dropdown-container">
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" id="brandDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Select Brands
            </button>
            <ul class="dropdown-menu brand-dropdown-menu" aria-labelledby="brandDropdown">
                @foreach($brands as $brand)
                    <li>
                        <div class="form-check dropdown-item">
                            <input class="form-check-input brand-checkbox" type="checkbox" value="{{ $brand->name }}" id="brand{{ $brand->id }}"
                                {{ in_array($brand->name, explode(',', request('brands', ''))) ? 'checked' : '' }}>
                            <label class="form-check-label w-100" for="brand{{ $brand->id }}">
                                {{ $brand->name }}
                            </label>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div id="selectedBrands" class="selected-brands mt-2"></div>
    </div>
</div>

        <!-- Price Range Filter -->
        <div class="filter-section">
            <h6 class="filter-section-title">Price Range</h6>
            <div class="price-range">
                <div class="price-inputs">
                    <input type="number" class="price-input" id="minPrice" placeholder="Min" min="0">
                    <span class="align-self-center">-</span>
                    <input type="number" class="price-input" id="maxPrice" placeholder="Max" min="0">
                </div>
                <input type="range" class="price-slider" id="priceSlider" min="0" max="10000" step="100">
                <div class="price-labels">
                    <span>₱0</span>
                    <span>₱10,000</span>
                </div>
            </div>
        </div>

        <div class="filter-actions">
            <button class="btn-filter btn-clear" id="clearFilters">
                Clear
            </button>
            <button class="btn-filter btn-apply" id="applyFilters">
                Apply
            </button>
        </div>
    </div>
</div>

<!-- Clean Auto-sliding Carousel (No Visible Controls) -->
@if(isset($banners) && count($banners) > 0)
<div class="card banner-card-compact">
    <div id="heroCarousel" class="carousel slide carousel-compact" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-inner carousel-inner-compact">
            @foreach($banners as $index => $banner)
                <div class="carousel-item carousel-item-compact {{ $index === 0 ? 'active' : '' }}">
                    @if(!empty($banner['target_url']))
                        <a href="{{ $banner['target_url'] }}" target="_blank" class="banner-link">
                            <img src="{{ $banner['image'] }}" 
                                 class="d-block w-100 carousel-image-compact" 
                                 alt="{{ $banner['alt'] }}">
                        </a>
                    @else
                        <img src="{{ $banner['image'] }}" 
                             class="d-block w-100 carousel-image-compact" 
                             alt="{{ $banner['alt'] }}">
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Main Content -->
<div class="container py-4">
    <!-- Category Slider - Full Width -->
    <div class="category-slider-full py-3 mb-4">
        <div class="container-fluid px-0">
            <div class="nav nav-pills justify-content-center flex-nowrap overflow-auto">
                <!-- All Categories -->
                <a href="{{ route('products.index') }}" 
                   class="nav-link text-white fw-bold px-4 py-2 {{ request('category') ? '' : 'active' }}">
                    All
                </a>

                <!-- Dynamic Categories -->
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                       class="nav-link text-white px-4 py-2 {{ request('category') == $category->slug ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Products Container -->
    <div id="products-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Products</h2>
            <span class="text-muted" id="products-count">{{ $products->count() }} products found</span>
        </div>

        @if($products->count() > 0)
            <!-- Products grouped by category -->
            @php
                $groupedProducts = $products->groupBy('category_id');
            @endphp
            
            @foreach($groupedProducts as $categoryId => $categoryProducts)
                @php
                    $category = $categories->firstWhere('id', $categoryId);
                @endphp
                <!-- Category Header -->
                <div class="category-header">
                    <h3 class="mb-0">{{ $category ? $category->name : 'Uncategorized' }}</h3>
                </div>
                
                <!-- Products for this category -->
                <div class="row">
                    @foreach($categoryProducts as $product)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card product-card h-100 shadow-sm">
                            @if($product->has_discount)
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">{{ $product->discount_percentage }}% OFF</span>
                            @endif
                            
                            <img src="{{ $product->image_url }}" class="card-img-top product-image" alt="{{ $product->name }}">
                            
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">{{ $product->name }}</h6>
                                
                                <!-- Display Brand -->
                                @if($product->brand_id && $product->brand)
                                    <small class="text-muted d-block mb-2">
                                        <i class="fas fa-tag me-1"></i>{{ $product->brand->name }}
                                    </small>
                                @endif
                                
                                <p class="card-text text-muted small">{{ Str::limit($product->description, 60) }}</p>
                                
                                <!-- Display Available Variants (View Only) -->
                                @if($product->has_variants && $product->variants->count() > 0)
                                <div class="mb-2">
                                    <small class="text-muted">Available Options:</small>
                                    <div class="mt-1">
                                        @foreach($product->variants as $variant)
                                            @php
                                                $variantName = $variant->size ?? $variant->variant_name ?? 'Option';
                                                $variantStock = $variant->stock_quantity ?? 0;
                                                $isInStock = $variantStock > 0;
                                            @endphp
                                            <span class="badge {{ $isInStock ? 'bg-light text-dark border' : 'bg-secondary' }} me-1 mb-1 small">
                                                {{ $variantName }}
                                                @if(!$isInStock)
                                                <small class="text-muted">(OOS)</small>
                                                @endif
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                <div class="mt-auto">
                                    <!-- Price Display -->
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        @if($product->has_discount)
                                        <span class="text-danger fw-bold">₱{{ number_format($product->sale_price, 2) }}</span>
                                        <span class="text-muted text-decoration-line-through small">₱{{ number_format($product->price, 2) }}</span>
                                        @else
                                        <span class="text-primary fw-bold">₱{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>

                                    <!-- Rating Display -->
                                    <div class="product-rating mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="star-rating me-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($product->average_rating))
                                                        <i class="fas fa-star text-warning" style="font-size: 0.7rem;"></i>
                                                    @elseif($i == ceil($product->average_rating) && fmod($product->average_rating, 1) != 0)
                                                        <i class="fas fa-star-half-alt text-warning" style="font-size: 0.7rem;"></i>
                                                    @else
                                                        <i class="far fa-star text-warning" style="font-size: 0.7rem;"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <small class="text-muted">
                                                ({{ $product->total_ratings }})
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                            View Details
                                        </a>
                                        
                                        @if($product->in_stock)
                                        <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            
                                            @if($product->has_variants && $product->variants->count() > 0)
                                                @php
                                                    // Get the first IN STOCK variant
                                                    $firstInStockVariant = $product->variants->where('stock_quantity', '>', 0)->first();
                                                @endphp
                                                @if($firstInStockVariant)
                                                    <input type="hidden" name="selected_size" value="{{ $firstInStockVariant->size ?? $firstInStockVariant->variant_name ?? 'Standard' }}">
                                                @else
                                                    <input type="hidden" name="selected_size" value="">
                                                @endif
                                            @else
                                                <input type="hidden" name="selected_size" value="Standard">
                                            @endif
                                            
                                            <button type="submit" class="btn btn-primary btn-sm w-100 add-to-cart-btn" 
                                                    {{ $product->has_variants && !$firstInStockVariant ? 'disabled' : '' }}
                                                    title="{{ $product->has_variants && !$firstInStockVariant ? 'No variants in stock' : 'Add to Cart' }}">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                        @else
                                        <button class="btn btn-secondary btn-sm flex-fill" disabled>Out of Stock</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endforeach
        @else
            <!-- Show empty state with message -->
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No products available</h4>
                <p class="text-muted">Try browsing different categories or check back later for new arrivals.</p>
            </div>
        @endif
    </div>

    <!-- Loading indicator -->
    <div id="loading-indicator">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Loading more products...</p>
    </div>

    <!-- End of results message -->
    <div id="end-of-results">
        <p>No more products to load.</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let page = 1;
    let isLoading = false;
    let hasMore = true;
    const loadingIndicator = document.getElementById('loading-indicator');
    const endOfResults = document.getElementById('end-of-results');
    const productsContainer = document.getElementById('products-container');
    const productsCount = document.getElementById('products-count');
    
    // Get current category from URL
    const urlParams = new URLSearchParams(window.location.search);
    const currentCategory = urlParams.get('category') || '';
    const searchQuery = urlParams.get('search') || '';
    
    // Show modal if no products found and there was a search
    @if($products->count() === 0 && request()->has('search'))
        const noProductsModal = new bootstrap.Modal(document.getElementById('noProductsModal'));
        noProductsModal.show();
    @endif
    
    // Floating Filter Functionality
    const filterBubble = document.getElementById('filterBubble');
    const filterPanel = document.getElementById('filterPanel');
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');
    const priceSlider = document.getElementById('priceSlider');
    const applyFilters = document.getElementById('applyFilters');
    const clearFilters = document.getElementById('clearFilters');

    // Brand dropdown functionality
    function updateBrandDropdown() {
        const brandCheckboxes = document.querySelectorAll('.brand-checkbox:checked');
        const dropdownButton = document.getElementById('brandDropdown');
        const selectedBrandsContainer = document.getElementById('selectedBrands');
        
        const selectedBrands = Array.from(brandCheckboxes).map(cb => cb.value);
        
        // Update dropdown button text
        if (selectedBrands.length === 0) {
            dropdownButton.textContent = 'Select Brands';
        } else if (selectedBrands.length === 1) {
            dropdownButton.textContent = selectedBrands[0];
        } else {
            dropdownButton.textContent = `${selectedBrands.length} brands selected`;
        }
        
        // Update selected brands display
        selectedBrandsContainer.innerHTML = '';
        selectedBrands.forEach(brand => {
            const tag = document.createElement('span');
            tag.className = 'brand-tag';
            tag.innerHTML = `
                ${brand}
                <button type="button" class="remove-brand" data-brand="${brand}">
                    <i class="fas fa-times"></i>
                </button>
            `;
            selectedBrandsContainer.appendChild(tag);
        });
        
        // Add event listeners to remove buttons
        document.querySelectorAll('.remove-brand').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const brand = this.getAttribute('data-brand');
                const checkbox = document.querySelector(`.brand-checkbox[value="${brand}"]`);
                if (checkbox) {
                    checkbox.checked = false;
                    updateBrandDropdown();
                }
            });
        });
    }

    // Get selected brands
    function getSelectedBrands() {
        const brandCheckboxes = document.querySelectorAll('.brand-checkbox:checked');
        return Array.from(brandCheckboxes).map(cb => cb.value);
    }

    // Set current filters from URL
    function setCurrentFilters() {
        const urlBrands = urlParams.get('brands')?.split(',') || [];
        const urlMinPrice = urlParams.get('min_price') || '';
        const urlMaxPrice = urlParams.get('max_price') || '';
        
        // Set brand filters
        urlBrands.forEach(brand => {
            const checkbox = document.querySelector(`.brand-checkbox[value="${brand}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
        
        // Update dropdown display
        updateBrandDropdown();
        
        // Set price filters
        if (urlMinPrice) {
            minPriceInput.value = urlMinPrice;
        }
        if (urlMaxPrice) {
            maxPriceInput.value = urlMaxPrice;
            priceSlider.value = urlMaxPrice;
        }
    }

    // Apply filters
    applyFilters.addEventListener('click', function() {
        const selectedBrands = getSelectedBrands();
        const minPrice = minPriceInput.value || '';
        const maxPrice = maxPriceInput.value || '';
        
        let url = new URL(window.location.href);
        
        // Update URL parameters
        if (selectedBrands.length > 0) {
            url.searchParams.set('brands', selectedBrands.join(','));
        } else {
            url.searchParams.delete('brands');
        }
        
        if (minPrice) {
            url.searchParams.set('min_price', minPrice);
        } else {
            url.searchParams.delete('min_price');
        }
        
        if (maxPrice) {
            url.searchParams.set('max_price', maxPrice);
        } else {
            url.searchParams.delete('max_price');
        }
        
        // Reset to first page when applying filters
        url.searchParams.set('page', '1');
        
        window.location.href = url.toString();
    });

    // Clear filters
    clearFilters.addEventListener('click', function() {
        // Clear brand selection
        document.querySelectorAll('.brand-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        updateBrandDropdown();
        
        // Clear price inputs
        minPriceInput.value = '';
        maxPriceInput.value = '';
        priceSlider.value = 10000;
        
        let url = new URL(window.location.href);
        url.searchParams.delete('brands');
        url.searchParams.delete('min_price');
        url.searchParams.delete('max_price');
        url.searchParams.set('page', '1');
        
        window.location.href = url.toString();
    });

    // Price range functionality
    priceSlider.addEventListener('input', function() {
        const maxPrice = this.value;
        maxPriceInput.value = maxPrice;
    });

    maxPriceInput.addEventListener('input', function() {
        let value = parseInt(this.value) || 0;
        if (value > 10000) value = 10000;
        if (value < 0) value = 0;
        priceSlider.value = value;
        this.value = value;
    });

    minPriceInput.addEventListener('input', function() {
        let value = parseInt(this.value) || 0;
        if (value < 0) value = 0;
        this.value = value;
    });

    // Toggle filter panel
    filterBubble.addEventListener('click', function(e) {
        e.stopPropagation();
        filterPanel.classList.toggle('show');
    });

    // Close filter panel when clicking outside
    document.addEventListener('click', function(e) {
        if (!filterPanel.contains(e.target) && !filterBubble.contains(e.target)) {
            filterPanel.classList.remove('show');
        }
    });

    // Initialize brand dropdown
    document.querySelectorAll('.brand-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBrandDropdown);
    });

    // Set current filters on page load
    setCurrentFilters();

    const hasProducts = {{ $products->count() > 0 ? 'true' : 'false' }};
    
    if (hasProducts) {
        function loadMoreProducts() {
            if (isLoading || !hasMore) return;
            
            isLoading = true;
            loadingIndicator.style.display = 'block';
            page++;
            
            // Build the URL with parameters
            let url = `{{ route('products.index') }}?page=${page}`;
            if (currentCategory) {
                url += `&category=${currentCategory}`;
            }
            if (searchQuery) {
                url += `&search=${encodeURIComponent(searchQuery)}`;
            }
            
            // Add filter parameters
            const selectedBrands = getSelectedBrands();
            const minPrice = minPriceInput.value || '';
            const maxPrice = maxPriceInput.value || '';
            
            if (selectedBrands.length > 0) {
                url += `&brands=${selectedBrands.join(',')}`;
            }
            if (minPrice) {
                url += `&min_price=${minPrice}`;
            }
            if (maxPrice) {
                url += `&max_price=${maxPrice}`;
            }
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                if (html.trim() === '') {
                    hasMore = false;
                    endOfResults.style.display = 'block';
                    loadingIndicator.style.display = 'none';
                    return;
                }
                
                // Create a temporary container to parse the HTML
                const tempContainer = document.createElement('div');
                tempContainer.innerHTML = html;
                
                // Extract products from the response
                const newProducts = tempContainer.querySelector('#products-container');
                if (!newProducts) {
                    hasMore = false;
                    endOfResults.style.display = 'block';
                    loadingIndicator.style.display = 'none';
                    return;
                }
                
                // Append new products to existing container
                productsContainer.innerHTML += newProducts.innerHTML;
                
                // Update products count
                const newCount = tempContainer.querySelector('#products-count');
                if (newCount) {
                    productsCount.textContent = newCount.textContent;
                }
                
                isLoading = false;
                loadingIndicator.style.display = 'none';
                
                // Check if we've reached the end
                const productCards = newProducts.querySelectorAll('.col-lg-3, .col-md-6');
                if (productCards.length === 0) {
                    hasMore = false;
                    endOfResults.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error loading more products:', error);
                isLoading = false;
                loadingIndicator.style.display = 'none';
            });
        }
        
        // Infinite scroll event listener
        window.addEventListener('scroll', function() {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 1000) {
                loadMoreProducts();
            }
        });
    }

    // Add to cart functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-to-cart-btn')) {
            e.preventDefault();
            const form = e.target.closest('.add-to-cart-form');
            const submitBtn = form.querySelector('.add-to-cart-btn');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Product added to cart successfully!', 'success');
                    // Update cart count if needed
                    if (data.cart_count !== undefined) {
                        updateCartCount(data.cart_count);
                    }
                } else {
                    showToast(data.message || 'Error adding product to cart.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Unable to add product to cart. Please try again.', 'error');
            })
            .finally(() => {
                // Restore button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }
    });

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
                // Add fade out animation
                toast.style.transition = 'all 0.3s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50%) translateY(-20px)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }
        }, 3000);
    }

    // Update cart count function
    function updateCartCount(count) {
        const cartCountElements = document.querySelectorAll('#cartCount, #mobileCartCount');
        cartCountElements.forEach(element => {
            element.textContent = count;
        });
    }
});
</script>
@endsection