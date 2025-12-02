@extends('layouts.app')

@section('content')
<style>
    /* 🌿 Enhanced Green Theme */
    .category-slider-full {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50) !important;
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.2);
    }

    .category-slider-full .nav-pills .nav-link {
        color: white !important;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 25px;
        transition: all 0.3s ease;
        white-space: nowrap;
        margin: 0 6px;
        padding: 8px 20px;
        font-weight: 500;
    }

    .category-slider-full .nav-pills .nav-link.active {
        background: white !important;
        color: #2C8F0C !important;
        font-weight: 600;
        border-color: white;
        box-shadow: 0 4px 8px rgba(255, 255, 255, 0.3);
    }

    .category-slider-full .nav-pills .nav-link:hover:not(.active) {
        background: rgba(255, 255, 255, 0.1) !important;
        border-color: rgba(255, 255, 255, 0.6);
        transform: translateY(-2px);
    }

    /* Enhanced Product Cards */
    .product-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        background: white;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .product-image {
        height: 220px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .discount-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 2;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 12px;
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    .product-card-body {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .product-title {
        font-weight: 600;
        color: #2d3748;
        font-size: 0.95rem;
        line-height: 1.4;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-brand {
        color: #2C8F0C;
        font-size: 0.8rem;
        font-weight: 500;
        margin-bottom: 0.75rem;
    }

    .product-description {
        color: #718096;
        font-size: 0.8rem;
        line-height: 1.4;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .variant-badge {
        background: #f7fafc;
        border: 1px solid #e2e8f0;
        color: #4a5568;
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 8px;
        margin: 2px;
        display: inline-block;
    }

    .variant-badge.out-of-stock {
        background: #fed7d7;
        border-color: #feb2b2;
        color: #c53030;
    }

    .price-section {
        margin-bottom: 1rem;
    }

    .current-price {
        font-weight: 700;
        font-size: 1.1rem;
        color: #2C8F0C;
    }

    .original-price {
        font-size: 0.85rem;
        color: #a0aec0;
        text-decoration: line-through;
        margin-left: 0.5rem;
    }

    .rating-section {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .star-rating {
        font-size: 0.75rem;
        margin-right: 0.5rem;
    }

    .rating-count {
        font-size: 0.8rem;
        color: #718096;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: auto;
    }

    .btn-view-details {
        flex: 2;
        background: transparent;
        color: #2C8F0C;
        border: 2px solid #2C8F0C;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-view-details:hover {
        background: #2C8F0C;
        color: white;
        transform: translateY(-1px);
    }

    .btn-add-cart {
        flex: 1;
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 8px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-add-cart:hover:not(:disabled) {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
    }

    .btn-add-cart:disabled {
        background: #cbd5e0;
        cursor: not-allowed;
        transform: none;
    }

    /* Enhanced Category Headers */
    .category-header {
        border-bottom: 3px solid #2C8F0C;
        padding-bottom: 12px;
        margin: 40px 0 25px 0;
        position: relative;
    }

    .category-header::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 60px;
        height: 3px;
        background: #4CAF50;
    }

    .category-title {
        color: #2C8F0C;
        font-weight: 700;
        font-size: 1.5rem;
        margin: 0;
    }

    /* Enhanced Filter Panel - Updated */
    .floating-filter {
        position: fixed;
        top: 15%;
        right: 20px;
        transform: translateY(-50%);
        z-index: 1000;
    }

    .filter-bubble {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(44, 143, 12, 0.4);
        transition: all 0.3s ease;
        border: none;
        position: relative;
    }

    .filter-bubble:hover {
        transform: scale(1.1) rotate(15deg);
        box-shadow: 0 8px 25px rgba(44, 143, 12, 0.5);
    }

    .filter-bubble::after {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-radius: 50%;
        z-index: -1;
        opacity: 0.4;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.4; }
        50% { transform: scale(1.1); opacity: 0.2; }
        100% { transform: scale(1); opacity: 0.4; }
    }

    .filter-panel {
        position: absolute;
        top: 0;
        right: 70px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        padding: 25px;
        width: 320px; /* Slightly reduced width */
        display: none;
        z-index: 1001;
        border: 1px solid #e2e8f0;
        max-height: 80vh;
        overflow-y: auto;
    }

    .filter-panel.show {
        display: block;
        animation: slideInRight 0.4s ease;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Brand Dropdown Filter - Replaced Search */
    .brand-dropdown-container {
        margin-bottom: 1rem;
        position: relative;
    }

    .brand-dropdown-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        background: #f8f9fa;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .brand-dropdown-header:hover {
        border-color: #2C8F0C;
        background: #f8fff8;
    }

    .brand-dropdown-header.active {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 3px rgba(44, 143, 12, 0.1);
    }

    .dropdown-placeholder {
        color: #718096;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dropdown-arrow {
        color: #2C8F0C;
        transition: transform 0.3s ease;
    }

    .brand-dropdown-options {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-top: 5px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1002;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        display: none;
    }

    .brand-dropdown-options.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .brand-dropdown-item {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f1f1;
    }

    .brand-dropdown-item:last-child {
        border-bottom: none;
    }

    .brand-dropdown-item:hover {
        background: rgba(44, 143, 12, 0.05);
    }

    .brand-dropdown-item input[type="checkbox"] {
        margin-right: 10px;
        width: 18px;
        height: 18px;
        cursor: pointer;
        border-radius: 4px;
        border: 2px solid #cbd5e0;
    }

    .brand-dropdown-item input[type="checkbox"]:checked {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
    }

    .brand-dropdown-label {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        color: #4a5568;
    }

    .brand-dropdown-name {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .brand-dropdown-count {
        background: #f7fafc;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        color: #718096;
        min-width: 30px;
        text-align: center;
    }

    /* Compact Price Range Filter */
    .price-range-container {
        padding: 10px 0;
    }

    .price-inputs {
        display: flex;
        align-items: center;
        gap: 8px; /* Reduced gap */
        margin-bottom: 1rem;
    }

    .price-input {
        flex: 1;
        max-width: 130px; /* Limited max width */
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 10px; /* Reduced padding */
        font-size: 0.85rem; /* Smaller font */
        transition: all 0.3s ease;
    }

    .price-input:focus {
        outline: none;
        border-color: #2C8F0C;
        box-shadow: 0 0 0 3px rgba(44, 143, 12, 0.1);
    }

    .price-separator {
        color: #718096;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .price-slider-container {
        margin: 1.5rem 0;
        padding: 0 5px;
    }

    .price-slider {
        width: 100%;
        height: 6px;
        -webkit-appearance: none;
        appearance: none;
        background: linear-gradient(to right, #2C8F0C, #4CAF50);
        border-radius: 3px;
        outline: none;
    }

    .price-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px; /* Slightly smaller */
        height: 18px;
        border-radius: 50%;
        background: white;
        border: 2px solid #2C8F0C;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .price-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 8px;
        font-size: 0.8rem; /* Smaller font */
        color: #718096;
    }

    /* Selected Brands Display - Compact */
    .selected-brands-container {
        background: #f8fff8;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px; /* Reduced padding */
        margin-top: 1rem;
    }

    .selected-brands-title {
        font-size: 0.8rem; /* Smaller font */
        color: #2C8F0C;
        font-weight: 600;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .selected-brands-list {
        display: flex;
        flex-wrap: wrap;
        gap: 4px; /* Reduced gap */
    }

    .brand-tag {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        padding: 3px 8px; /* Reduced padding */
        border-radius: 12px;
        font-size: 0.75rem; /* Smaller font */
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: 0 1px 3px rgba(44, 143, 12, 0.2);
    }

    .remove-brand-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 14px; /* Smaller */
        height: 14px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0;
        line-height: 1;
    }

    /* Filter Actions */
    .filter-actions {
        display: flex;
        gap: 10px;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .btn-filter {
        flex: 1;
        padding: 10px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-clear {
        background: #f8f9fa;
        color: #718096;
        border: 1px solid #e2e8f0;
    }

    .btn-clear:hover {
        background: #e9ecef;
        color: #4a5568;
        transform: translateY(-1px);
    }

    .btn-apply {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
    }

    .btn-apply:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.3);
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        background: linear-gradient(135deg, #f8fff8, #f0f9f0);
        border-radius: 20px;
        margin: 2rem 0;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #2C8F0C;
        margin-bottom: 1.5rem;
        opacity: 0.7;
    }

    .empty-state-title {
        color: #2C8F0C;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .empty-state-text {
        color: #718096;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    /* Banner */
    .banner-container {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        border: 1px solid #e2e8f0;
    }

    .carousel-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        object-position: center;
    }

    /* Loading Animation */
    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Custom Scrollbar */
    .brand-dropdown-options::-webkit-scrollbar,
    .filter-panel::-webkit-scrollbar {
        width: 6px;
    }

    .brand-dropdown-options::-webkit-scrollbar-track,
    .filter-panel::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .brand-dropdown-options::-webkit-scrollbar-thumb,
    .filter-panel::-webkit-scrollbar-thumb {
        background: #2C8F0C;
        border-radius: 3px;
    }

    .brand-dropdown-options::-webkit-scrollbar-thumb:hover,
    .filter-panel::-webkit-scrollbar-thumb:hover {
        background: #1E6A08;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .filter-panel {
            width: 300px;
            right: 60px;
            padding: 20px;
        }
        
        .filter-bubble {
            width: 50px;
            height: 50px;
            font-size: 1.1rem;
        }
        
        .carousel-image {
            height: 200px;
        }
        
        .price-input {
            max-width: 110px;
        }
    }

    @media (max-width: 576px) {
        .filter-panel {
            width: 280px;
            right: 50px;
            padding: 15px;
        }
        
        .filter-bubble {
            width: 45px;
            height: 45px;
            font-size: 1rem;
            right: 10px;
        }
        
        .price-input {
            max-width: 100px;
            padding: 6px 8px;
            font-size: 0.8rem;
        }
        
        .price-inputs {
            gap: 5px;
        }
    }

    /* No Products Modal Styling */
    .no-products-image {
        max-width: 200px;
        margin: 0 auto 20px;
        display: block;
    }

    .no-products-title {
        color: #2C8F0C;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .no-products-text {
        color: #718096;
        margin-bottom: 1.5rem;
    }

    .search-suggestions {
        background: #f8fff8;
        border-radius: 12px;
        padding: 15px;
        margin-top: 20px;
    }

    .suggestion-title {
        color: #2C8F0C;
        margin-bottom: 10px;
    }

    .suggestion-list {
        list-style: none;
        padding: 0;
    }

    .suggestion-list li {
        margin-bottom: 8px;
    }

    .suggestion-list a {
        color: #4a5568;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: color 0.3s ease;
    }

    .suggestion-list a:hover {
        color: #2C8F0C;
    }

    .btn-continue-shopping {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border: none;
    }

    .btn-continue-shopping:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        color: white;
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

<!-- Floating Filter Panel with Dropdown Brand Filter -->
<div class="floating-filter">
    <button class="filter-bubble" id="filterBubble">
        <i class="fas fa-filter"></i>
    </button>
    <div class="filter-panel" id="filterPanel">
        <div class="filter-header mb-4">
            <h4 class="filter-title text-success mb-0">
                <i class="fas fa-sliders-h me-2"></i>Filters
                <span class="badge bg-success ms-2" id="activeFilterCount">0</span>
            </h4>
        </div>
        
        <!-- Brand Filter Section - UPDATED with Dropdown -->
        <div class="filter-section">
            <h6 class="filter-section-title">
                <i class="fas fa-tags"></i>Brands
            </h6>
            
            <!-- Brand Dropdown -->
            <div class="brand-dropdown-container">
                <div class="brand-dropdown-header" id="brandDropdownHeader">
                    <div class="dropdown-placeholder" id="dropdownPlaceholder">
                        <i class="fas fa-tag text-success"></i>
                        <span>Select brands...</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-arrow" id="dropdownArrow"></i>
                </div>
                
                <div class="brand-dropdown-options" id="brandDropdownOptions">
                    @foreach($brands as $brand)
                    <div class="brand-dropdown-item" data-brand="{{ $brand->name }}">
                        <input type="checkbox" 
                               class="brand-checkbox" 
                               value="{{ $brand->name }}" 
                               id="brand{{ $brand->id }}"
                               {{ in_array($brand->name, explode(',', request('brands', ''))) ? 'checked' : '' }}>
                        <label for="brand{{ $brand->id }}" class="brand-dropdown-label">
                            <span class="brand-dropdown-name">
                                {{ $brand->name }}
                            </span>
                            <span class="brand-dropdown-count">{{ $brand->products_count ?? 0 }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Selected Brands Display -->
            <div class="selected-brands-container" id="selectedBrandsContainer" style="display: none;">
                <div class="selected-brands-title">
                    <i class="fas fa-check-circle"></i>
                    Selected Brands:
                </div>
                <div class="selected-brands-list" id="selectedBrandsList"></div>
            </div>
        </div>
        
        <!-- Price Range Filter - COMPACT -->
        <div class="filter-section">
            <h6 class="filter-section-title">
                <i class="fas fa-money-bill-wave"></i>Price Range
            </h6>
            
            <div class="price-range-container">
                <div class="price-inputs">
                    <input type="number" 
                           class="price-input" 
                           id="minPrice" 
                           placeholder="Min ₱" 
                           min="0"
                           value="{{ request('min_price', '') }}">
                    <span class="price-separator">to</span>
                    <input type="number" 
                           class="price-input" 
                           id="maxPrice" 
                           placeholder="Max ₱" 
                           min="0"
                           value="{{ request('max_price', '') }}">
                </div>
                
                <div class="price-slider-container">
                    <input type="range" 
                           class="price-slider" 
                           id="priceSlider" 
                           min="0" 
                           max="10000" 
                           step="100"
                           value="{{ request('max_price', 10000) }}">
                    <div class="price-labels">
                        <span>₱0</span>
                        <span>₱10,000</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Actions -->
        <div class="filter-actions" style="position: sticky; bottom: 0; background: white; padding-top: 15px;">
            <button class="btn-filter btn-clear" id="clearFilters">
                <i class="fas fa-times"></i>Clear
            </button>
            <button class="btn-filter btn-apply" id="applyFilters">
                <i class="fas fa-check"></i>Apply
            </button>
        </div>
    </div>
</div>

<!-- Clean Auto-sliding Carousel -->
@if(isset($banners) && count($banners) > 0)
<div class="banner-container">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-inner">
            @foreach($banners as $index => $banner)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    @if(!empty($banner['target_url']))
                        <a href="{{ $banner['target_url'] }}" target="_blank" class="banner-link">
                            <img src="{{ $banner['image'] }}" 
                                 class="d-block w-100 carousel-image" 
                                 alt="{{ $banner['alt'] }}">
                        </a>
                    @else
                        <img src="{{ $banner['image'] }}" 
                             class="d-block w-100 carousel-image" 
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
            <h2 class="category-title">Featured Products</h2>
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
                    <h3 class="category-title">{{ $category ? $category->name : 'Uncategorized' }}</h3>
                </div>
                
                <!-- Products for this category -->
                <div class="row">
                    @foreach($categoryProducts as $product)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card product-card h-100">
                            @if($product->has_discount)
                            <span class="discount-badge">{{ $product->discount_percentage }}% OFF</span>
                            @endif
                            
                            <img src="{{ $product->image_url }}" class="card-img-top product-image" alt="{{ $product->name }}">
                            
                            <div class="product-card-body">
                                <h6 class="product-title">{{ $product->name }}</h6>
                                
                                <!-- Display Brand -->
                                @if($product->brand_id && $product->brand)
                                    <div class="product-brand">
                                        <i class="fas fa-tag me-1"></i>{{ $product->brand->name }}
                                    </div>
                                @endif
                                
                                <p class="product-description">{{ Str::limit($product->description, 60) }}</p>
                                
                                <!-- Display Available Variants -->
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
                                            <span class="variant-badge {{ !$isInStock ? 'out-of-stock' : '' }}">
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
                                    <div class="price-section">
                                        @if($product->has_discount)
                                        <span class="current-price">₱{{ number_format($product->sale_price, 2) }}</span>
                                        <span class="original-price">₱{{ number_format($product->price, 2) }}</span>
                                        @else
                                        <span class="current-price">₱{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>

                                    <!-- Rating Display -->
                                    <div class="rating-section">
                                        <div class="star-rating">
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
                                        <span class="rating-count">({{ $product->total_ratings }})</span>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="action-buttons">
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-view-details">
                                            View Details
                                        </a>
                                        
                                        @if($product->in_stock)
                                        <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form m-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            
                                            @if($product->has_variants && $product->variants->count() > 0)
                                                @php
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
                                            
                                            <button type="submit" class="btn btn-add-cart" 
                                                    {{ $product->has_variants && !$firstInStockVariant ? 'disabled' : '' }}
                                                    title="{{ $product->has_variants && !$firstInStockVariant ? 'No variants in stock' : 'Add to Cart' }}">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                        @else
                                        <button class="btn btn-add-cart" disabled>
                                            <i class="fas fa-times"></i>
                                        </button>
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
            <!-- Enhanced Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="empty-state-title">No Products Available</h3>
                <p class="empty-state-text">
                    We're currently updating our inventory. Please check back later for new arrivals 
                    or try browsing different categories.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="fas fa-store me-2"></i>Browse All Categories
                </a>
            </div>
        @endif
    </div>

    <!-- Loading indicator -->
    <div id="loading-indicator" class="text-center py-4" style="display: none;">
        <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Loading more amazing products...</p>
    </div>

    <!-- End of results message -->
    <div id="end-of-results" class="text-center py-4" style="display: none;">
        <div class="text-muted">
            <i class="fas fa-check-circle fa-2x mb-3 text-success"></i>
            <p class="mb-0">You've reached the end of our product collection!</p>
        </div>
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
    
    // Filter elements
    const filterBubble = document.getElementById('filterBubble');
    const filterPanel = document.getElementById('filterPanel');
    const brandDropdownHeader = document.getElementById('brandDropdownHeader');
    const brandDropdownOptions = document.getElementById('brandDropdownOptions');
    const dropdownPlaceholder = document.getElementById('dropdownPlaceholder');
    const dropdownArrow = document.getElementById('dropdownArrow');
    const selectedBrandsContainer = document.getElementById('selectedBrandsContainer');
    const selectedBrandsList = document.getElementById('selectedBrandsList');
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');
    const priceSlider = document.getElementById('priceSlider');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const activeFilterCount = document.getElementById('activeFilterCount');
    
    // Get current filters from URL
    const urlParams = new URLSearchParams(window.location.search);
    const currentCategory = urlParams.get('category') || '';
    const searchQuery = urlParams.get('search') || '';
    
    // Initialize price slider
    if (priceSlider && maxPriceInput) {
        const maxPriceValue = urlParams.get('max_price') || 10000;
        priceSlider.value = maxPriceValue;
        maxPriceInput.value = maxPriceValue;
    }
    
    // Initialize brand dropdown placeholder
    updateBrandDropdownPlaceholder();
    updateSelectedBrands();
    updateActiveFilterCount();
    
    // Show modal if no products found and there was a search
    @if($products->count() === 0 && request()->has('search'))
        const noProductsModal = new bootstrap.Modal(document.getElementById('noProductsModal'));
        noProductsModal.show();
    @endif
    
    // Brand dropdown functionality
    if (brandDropdownHeader) {
        brandDropdownHeader.addEventListener('click', function(e) {
            e.stopPropagation();
            brandDropdownOptions.classList.toggle('show');
            brandDropdownHeader.classList.toggle('active');
            dropdownArrow.style.transform = brandDropdownOptions.classList.contains('show') 
                ? 'rotate(180deg)' 
                : 'rotate(0deg)';
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!brandDropdownHeader.contains(e.target) && !brandDropdownOptions.contains(e.target)) {
                brandDropdownOptions.classList.remove('show');
                brandDropdownHeader.classList.remove('active');
                dropdownArrow.style.transform = 'rotate(0deg)';
            }
        });
        
        // Prevent closing when clicking inside dropdown
        brandDropdownOptions.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Brand selection management
    document.querySelectorAll('.brand-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBrandDropdownPlaceholder();
            updateSelectedBrands();
            updateActiveFilterCount();
        });
    });
    
    // Price slider sync
    if (priceSlider && maxPriceInput) {
        priceSlider.addEventListener('input', function() {
            maxPriceInput.value = this.value;
            updateActiveFilterCount();
        });
        
        maxPriceInput.addEventListener('input', function() {
            let value = parseInt(this.value) || 0;
            if (value > 10000) value = 10000;
            if (value < 0) value = 0;
            priceSlider.value = value;
            updateActiveFilterCount();
        });
        
        minPriceInput.addEventListener('input', function() {
            let value = parseInt(this.value) || 0;
            if (value < 0) value = 0;
            this.value = value;
            updateActiveFilterCount();
        });
    }
    
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
    
    // Apply filters
    applyFiltersBtn.addEventListener('click', function() {
        applyBrandFilters();
    });
    
    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        clearAllFilters();
    });
    
    // Functions
    function updateBrandDropdownPlaceholder() {
        const selectedBrands = getSelectedBrands();
        const placeholderSpan = dropdownPlaceholder.querySelector('span');
        
        if (selectedBrands.length === 0) {
            placeholderSpan.innerHTML = 'Select brands...';
        } else if (selectedBrands.length === 1) {
            placeholderSpan.innerHTML = `${selectedBrands[0]}`;
        } else {
            placeholderSpan.innerHTML = `${selectedBrands.length} brands selected`;
        }
    }
    
    function updateSelectedBrands() {
        const selectedBrands = getSelectedBrands();
        
        if (selectedBrandsList) {
            selectedBrandsList.innerHTML = '';
            
            selectedBrands.forEach(brand => {
                const brandTag = document.createElement('span');
                brandTag.className = 'brand-tag';
                brandTag.innerHTML = `
                    ${brand}
                    <button type="button" class="remove-brand-btn" data-brand="${brand}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                selectedBrandsList.appendChild(brandTag);
            });
            
            // Add event listeners to remove buttons
            document.querySelectorAll('.remove-brand-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const brand = this.getAttribute('data-brand');
                    const checkbox = document.querySelector(`.brand-checkbox[value="${brand}"]`);
                    if (checkbox) {
                        checkbox.checked = false;
                        updateBrandDropdownPlaceholder();
                        updateSelectedBrands();
                        updateActiveFilterCount();
                    }
                });
            });
        }
        
        // Show/hide container
        if (selectedBrandsContainer) {
            if (selectedBrands.length > 0) {
                selectedBrandsContainer.style.display = 'block';
            } else {
                selectedBrandsContainer.style.display = 'none';
            }
        }
    }
    
    function updateActiveFilterCount() {
        const selectedBrands = getSelectedBrands();
        const minPrice = minPriceInput ? minPriceInput.value : '';
        const maxPrice = maxPriceInput ? maxPriceInput.value : '';
        
        let count = selectedBrands.length;
        if (minPrice || maxPrice) {
            count += 1;
        }
        
        if (activeFilterCount) {
            activeFilterCount.textContent = count;
            
            // Add/remove badge from filter bubble
            const filterBubble = document.getElementById('filterBubble');
            if (count > 0) {
                filterBubble.innerHTML = '<i class="fas fa-filter"></i>';
                filterBubble.innerHTML += `<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">${count}</span>`;
            } else {
                filterBubble.innerHTML = '<i class="fas fa-filter"></i>';
            }
        }
    }
    
    function getSelectedBrands() {
        const brandCheckboxes = document.querySelectorAll('.brand-checkbox:checked');
        return Array.from(brandCheckboxes).map(cb => cb.value);
    }
    
    function applyBrandFilters() {
        const selectedBrands = getSelectedBrands();
        const minPrice = minPriceInput ? minPriceInput.value : '';
        const maxPrice = maxPriceInput ? maxPriceInput.value : '';
        
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
        
        // Close filter panel
        filterPanel.classList.remove('show');
        
        // Navigate to new URL
        window.location.href = url.toString();
    }
    
    function clearAllFilters() {
        // Clear brand selection
        document.querySelectorAll('.brand-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Clear price inputs
        if (minPriceInput) minPriceInput.value = '';
        if (maxPriceInput) maxPriceInput.value = '';
        if (priceSlider) priceSlider.value = 10000;
        
        // Update displays
        updateBrandDropdownPlaceholder();
        updateSelectedBrands();
        updateActiveFilterCount();
        
        // Close dropdown
        brandDropdownOptions.classList.remove('show');
        brandDropdownHeader.classList.remove('active');
        dropdownArrow.style.transform = 'rotate(0deg)';
        
        // Apply cleared filters
        applyBrandFilters();
    }
    
    // Infinite scroll functionality
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
            const minPrice = minPriceInput ? minPriceInput.value : '';
            const maxPrice = maxPriceInput ? maxPriceInput.value : '';
            
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