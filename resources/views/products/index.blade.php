@extends('layouts.app')

@section('content')
<style>
    .hero-carousel-container {
        background: linear-gradient(rgba(0,0,0,0.3), rgba(255, 255, 255, 0.3)), 
                    url('{{ asset('images/background-pattern.jpg') }}');
        background-size: cover;
        background-position: center;
        padding: 20px 0;
        margin-bottom: 30px;
    }
    
    .carousel-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px hsla(0, 0%, 0%, 0.20);
        max-width: 1200px;
        margin: 0 auto;
        height: 300px; /* Fixed height for consistency */
    }
    
    .carousel-image {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Ensures image covers the container without distortion */
        object-position: center;
    }
    
    .carousel-caption-card {
        background: rgba(0, 0, 0, 0.7) !important;
        border-radius: 10px;
        padding: 15px !important;
        bottom: 20px !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        width: 90% !important;
        max-width: 500px;
    }

    .carousel-caption-card h1 {
        font-size: 1.5rem !important;
        margin-bottom: 5px;
    }

    .carousel-caption-card p {
        font-size: 0.9rem !important;
        margin-bottom: 0;
    }

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

    /* Category header styling */
    .category-header {
        border-bottom: 3px solid #2C8F0C;
        padding-bottom: 10px;
        margin: 30px 0 20px 0;
        color: #2C8F0C;
    }

    /* Loading indicator */
    #loading-indicator {
        display: none;
        text-align: center;
        padding: 20px;
    }

    /* End of results message */
    #end-of-results {
        display: none;
        text-align: center;
        padding: 20px;
        color: #6c757d;
    }

    /* No Products Found Modal Styling */
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

    /* Responsive adjustments for smaller screens */
    @media (max-width: 768px) {
        .carousel-card {
            height: 200px; /* Even smaller on mobile */
        }
        
        .carousel-caption-card {
            padding: 10px !important;
            bottom: 15px !important;
        }
        
        .carousel-caption-card h1 {
            font-size: 1.2rem !important;
        }
        
        .carousel-caption-card p {
            font-size: 0.8rem !important;
        }
        
        .hero-carousel-container {
            padding: 15px 0;
            margin-bottom: 20px;
        }
    }

    @media (max-width: 576px) {
        .carousel-card {
            height: 180px;
        }
        
        .carousel-caption-card {
            width: 95% !important;
            max-width: 300px;
        }
    }
</style>

<!-- No Products Found Modal -->
<div class="modal fade no-products-modal" id="noProductsModal" tabindex="-1" aria-labelledby="noProductsModalLabel" aria-hidden="true">    <div class="modal-dialog modal-dialog-centered modal-lg">
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

<!-- Smaller Hero Banner -->
<div class="hero-carousel-container">
    <div id="heroCarousel" class="carousel slide carousel-card" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner h-100">
            <div class="carousel-item active h-100">
                <img src="{{ asset('images/banner1.png') }}" class="d-block w-100 carousel-image" alt="Big Sale">
                <div class="carousel-caption carousel-caption-card">
                    <h1 class="fw-bold">🔥 Big Sale!</h1>
                    <p class="fs-5">Up to 50% off on selected products.</p>
                </div>
            </div>
            <div class="carousel-item h-100">
                <img src="{{ asset('images/NW.png') }}" class="d-block w-100 carousel-image" alt="New Arrivals">
                <div class="carousel-caption carousel-caption-card">
                    <h1 class="fw-bold">✨ New Arrivals</h1>
                    <p class="fs-5">Check out our latest collections.</p>
                </div>
            </div>
            <div class="carousel-item h-100">
                <img src="{{ asset('images/GO.jpeg') }}" class="d-block w-100 carousel-image" alt="Exclusive Deals">
                <div class="carousel-caption carousel-caption-card">
                    <h1 class="fw-bold">💎 Exclusive Deals</h1>
                    <p class="fs-5">Shop now before the offers end!</p>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</div>

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
            <span class="text-muted" id="products-count">{{ $products->count() }} products loaded</span>
        </div>

        @if($products->count() > 0)
            <!-- Products grouped by category -->
            @php
                $groupedProducts = $products->groupBy('category_id');
                $currentCategory = null;
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
    @foreach($products as $product)
    <div class="col-lg-3 col-md-6 mb-4">
    <div class="card product-card h-100 shadow-sm">
        @if($product->has_discount)
        <span class="badge bg-danger position-absolute top-0 end-0 m-2">{{ $product->discount_percentage }}% OFF</span>
        @endif
        
        <img src="{{ $product->image_url }}" class="card-img-top product-image" alt="{{ $product->name }}">
        
        <div class="card-body d-flex flex-column">
            <h6 class="card-title">{{ $product->name }}</h6>
            <p class="card-text text-muted small">{{ Str::limit($product->description, 60) }}</p>
            
            <!-- Display Available Variants (View Only) -->
            @if($product->has_variants && $product->variants->count() > 0)
            <div class="mb-2">
                <small class="text-muted">Available Options:</small>
                <div class="mt-1">
                    @foreach($product->variants as $variant)
                        @php
                            $variantName = $variant->size ?? $variant->variant_name ?? 'Option';
                            $variantPrice = $variant->current_price ?? $variant->price ?? $variant->sale_price ?? 0;
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
                <div class="d-flex justify-content-between align-items-center mb-2">
                    @if($product->has_discount)
                    <span class="text-danger fw-bold">₱{{ $product->sale_price }}</span>
                    <span class="text-muted text-decoration-line-through small">₱{{ $product->price }}</span>
                    @else
                    <span class="text-primary fw-bold">₱{{ $product->price }}</span>
                    @endif
                </div>
                
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
                                // Get the first IN STOCK variant, not just the first variant
                                $firstInStockVariant = $product->variants->where('stock_quantity', '>', 0)->first();
                            @endphp
                            @if($firstInStockVariant)
                                <input type="hidden" name="selected_size" value="{{ $firstInStockVariant->size ?? $firstInStockVariant->variant_name ?? 'Standard' }}">
                            @else
                                {{-- If no variants are in stock, don't show add to cart --}}
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
                
                // Group products by category from the new page
                const newProductsByCategory = {};
                const productCards = newProducts.querySelectorAll('.category-products');
                
                productCards.forEach(categoryGroup => {
                    const categoryId = categoryGroup.getAttribute('data-category-id');
                    if (!newProductsByCategory[categoryId]) {
                        newProductsByCategory[categoryId] = [];
                    }
                    
                    const productsInCategory = categoryGroup.querySelectorAll('.col-xl-3, .col-lg-4, .col-md-6, .col-sm-6');
                    productsInCategory.forEach(product => {
                        newProductsByCategory[categoryId].push(product.outerHTML);
                    });
                });
            
                Object.keys(newProductsByCategory).forEach(categoryId => {
                    const existingCategory = productsContainer.querySelector(`.category-products[data-category-id="${categoryId}"]`);
                    
                    if (existingCategory) {
                        // Append to existing category
                        newProductsByCategory[categoryId].forEach(productHtml => {
                            existingCategory.innerHTML += productHtml;
                        });
                    } else {
                        // Create new category header and container
                        const categoryHeader = document.createElement('div');
                        categoryHeader.className = 'category-header';
                        
                        // Find category name from the response
                        const categoryName = tempContainer.querySelector(`.category-products[data-category-id="${categoryId}"]`)?.closest('.category-header')?.querySelector('h3')?.textContent || 'Uncategorized';
                        
                        categoryHeader.innerHTML = `<h3 class="mb-0">${categoryName}</h3>`;
                        
                        const categoryProductsContainer = document.createElement('div');
                        categoryProductsContainer.className = 'row category-products';
                        categoryProductsContainer.setAttribute('data-category-id', categoryId);
                        categoryProductsContainer.innerHTML = newProductsByCategory[categoryId].join('');
                        
                        // Append to the main container
                        productsContainer.appendChild(categoryHeader);
                        productsContainer.appendChild(categoryProductsContainer);
                    }
                });
                
                // Update products count
                const newCount = tempContainer.querySelector('#products-count');
                if (newCount) {
                    productsCount.textContent = newCount.textContent;
                }
                
                isLoading = false;
                loadingIndicator.style.display = 'none';
                
                // Check if we've reached the end
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
});
</script>
@endsection