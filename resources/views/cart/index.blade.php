@extends('layouts.cart')

@section('content')
    <style>
        .cart-item {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background: white;
        }

        .cart-item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            border: 1px solid #2C8F0C;
            background: white;
            color: #2C8F0C;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
        }

        .btn-primary {
            background-color: #2C8F0C !important;
            border-color: #2C8F0C !important;
        }

        .btn-outline-primary {
            color: #2C8F0C !important;
            border-color: #2C8F0C !important;
        }

        .btn-outline-primary:hover {
            background-color: #2C8F0C !important;
            color: white !important;
        }

        .summary-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            position: sticky;
            top: 20px;
        }

        .variant-select {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 0.9rem;
            width: 100%;
        }

        .stock-warning {
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .loading-spinner {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }

        .cart-item-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #2C8F0C;
        }

        .cart-item.selected {
            border-color: #2C8F0C !important;
            background-color: #f0fdf4 !important;
        }

        .selection-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .selection-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .selected-badge {
            background: #2C8F0C;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Price styling */
        .sale-price {
            color: #2C8F0C;
            font-weight: bold;
        }

        .original-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .savings-text {
            color: #dc3545;
            font-weight: 600;
        }
    </style>

    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Shopping Cart</h2>
                    <span class="text-muted">{{ $cartItems->count() }} items</span>
                </div>

                @if ($cartItems->count() > 0)
                    <!-- Selection Controls -->
                    <div class="selection-controls">
                        <div class="selection-info">
                            <input type="checkbox" id="select-all" class="cart-item-checkbox">
                            <label for="select-all" class="mb-0"><strong>Select All</strong></label>
                            <span class="selected-badge"><span id="selected-count">0</span> selected</span>
                        </div>
                    </div>

                    @foreach ($cartItems as $item)
                        @php
                            // Check if product has variants
                            $hasVariants = $item->product->has_variants && $item->product->variants->count() > 0;

                            if ($hasVariants) {
                                // Get the actual variant for this cart item
                                $currentVariant = $item->product->variants->first(function ($variant) use ($item) {
                                    return $variant->size === $item->selected_size ||
                                        $variant->variant_name === $item->selected_size;
                                });

                                $currentStock = $currentVariant ? $currentVariant->stock_quantity : 0;
                                $variantName = $currentVariant
                                    ? $currentVariant->size ?? $currentVariant->variant_name
                                    : $item->selected_size;
                                $isVariantAvailable = $currentVariant && $currentStock > 0;
                                $maxQuantity = $currentStock;

                                // Get variant-specific image or fallback to product image
                                $displayImage =
                                    $currentVariant && $currentVariant->image_url
                                        ? $currentVariant->image_url
                                        : $item->product->image_url;

                                // Calculate price based on actual variant
                                $unitPrice = $currentVariant
                                    ? ($currentVariant->has_discount ? $currentVariant->sale_price : $currentVariant->current_price)
                                    : ($item->product->has_discount ? $item->product->sale_price : $item->product->current_price);
                                
                                // Store original price for summary calculation
                                $originalUnitPrice = $currentVariant
                                    ? $currentVariant->price
                                    : $item->product->price;
                                
                                $hasDiscount = $currentVariant
                                    ? $currentVariant->has_discount
                                    : $item->product->has_discount;
                                
                                $discountPercent = $currentVariant
                                    ? $currentVariant->discount_percentage
                                    : $item->product->discount_percentage;
                            } else {
                                // For products without variants, use product's own stock and price
                                $currentVariant = null;
                                $currentStock = $item->product->stock_quantity;
                                $variantName = 'Standard';
                                $isVariantAvailable = $currentStock > 0;
                                $maxQuantity = $currentStock;
                                $displayImage = $item->product->image_url;
                                $unitPrice = $item->product->has_discount ? $item->product->sale_price : $item->product->current_price;
                                $originalUnitPrice = $item->product->price;
                                $hasDiscount = $item->product->has_discount;
                                $discountPercent = $item->product->discount_percentage;
                            }

                            $itemTotalPrice = $unitPrice * $item->quantity;
                            $itemOriginalTotalPrice = $originalUnitPrice * $item->quantity;
                        @endphp

                        <div class="cart-item position-relative" id="cart-item-{{ $item->id }}" data-item-id="{{ $item->id }}"
                             data-unit-price="{{ $unitPrice }}"
                             data-original-unit-price="{{ $originalUnitPrice }}"
                             data-has-discount="{{ $hasDiscount ? '1' : '0' }}"
                             data-discount-percent="{{ $discountPercent }}"
                             data-quantity="{{ $item->quantity }}">
                            <div class="loading-spinner" id="loading-{{ $item->id }}">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-1 d-flex align-items-center">
                                    <input type="checkbox" class="cart-item-checkbox item-checkbox" data-item-id="{{ $item->id }}">
                                </div>
                                <div class="col-md-2">
                                    <img src="{{ $displayImage }}" alt="{{ $item->product->name }}" class="cart-item-image"
                                        id="item-image-{{ $item->id }}">
                                </div>
                                <div class="col-md-3">
                                    <h5 class="mb-1">{{ $item->product->name }}</h5>
                                    <p class="text-muted mb-1 small">{{ Str::limit($item->product->description, 50) }}</p>

                                    <!-- Variant Selection -->
                                    @if ($item->product->has_variants && $item->product->variants->count() > 0)
                                        <form action="{{ route('cart.update', $item) }}" method="POST"
                                            class="mb-2 variant-form" id="variant-form-{{ $item->id }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="quantity" value="{{ $item->quantity }}"
                                                id="quantity-{{ $item->id }}">
                                            <select name="selected_size" class="variant-select"
                                                data-item-id="{{ $item->id }}">
                                                @foreach ($item->product->variants as $variant)
                                                    @php
                                                        $variantNameOption =
                                                            $variant->size ?? ($variant->variant_name ?? 'Option');
                                                        $variantStock = $variant->stock_quantity ?? 0;
                                                        $variantPrice = $variant->has_discount ? $variant->sale_price : $variant->current_price;
                                                        $variantOriginalPrice = $variant->price;
                                                        $variantHasDiscount = $variant->has_discount;
                                                        $variantDiscountPercent = $variant->discount_percentage;
                                                        $variantImage = $variant->image_url;
                                                    @endphp
                                                    <option value="{{ $variantNameOption }}"
                                                        data-price="{{ $variantPrice }}"
                                                        data-original-price="{{ $variantOriginalPrice }}"
                                                        data-has-discount="{{ $variantHasDiscount ? 'true' : 'false' }}"
                                                        data-discount-percent="{{ $variantDiscountPercent }}"
                                                        data-image="{{ $variantImage }}"
                                                        data-stock="{{ $variantStock }}"
                                                        {{ $item->selected_size == $variantNameOption ? 'selected' : '' }}
                                                        {{ $variantStock <= 0 ? 'disabled' : '' }}>
                                                        {{ $variantNameOption }}
                                                        @if ($variantStock <= 0)
                                                            (Out of Stock)
                                                        @else
                                                            @if($variantHasDiscount)
                                                                - ₱{{ number_format($variantPrice, 2) }} <small class="text-muted"><del>₱{{ number_format($variantOriginalPrice, 2) }}</del></small>
                                                            @else
                                                                - ₱{{ number_format($variantPrice, 2) }}
                                                            @endif
                                                            ({{ $variantStock }} available)
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $variantName }}</span>
                                    @endif

                                    <!-- Stock Warning -->
                                    @if (!$isVariantAvailable)
                                        <div class="alert alert-warning py-1 mt-2 small" role="alert">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            @if ($hasVariants)
                                                Selected option is out of stock
                                            @else
                                                This product is out of stock
                                            @endif
                                        </div>
                                    @elseif($currentStock < $item->quantity)
                                        <div class="alert alert-warning py-1 mt-2 small" role="alert">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Only {{ $currentStock }} available
                                            @if ($hasVariants)
                                                in this option
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <div class="quantity-control">
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="selected_size" value="{{ $item->selected_size }}">
                                            <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}"
                                                class="quantity-btn"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                        </form>

                                        <span class="quantity-input">{{ $item->quantity }}</span>

                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="selected_size" value="{{ $item->selected_size }}">
                                            <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}"
                                                class="quantity-btn"
                                                {{ !$isVariantAvailable || $item->quantity >= $maxQuantity ? 'disabled' : '' }}>+</button>
                                        </form>
                                    </div>
                                    @if ($isVariantAvailable && $maxQuantity)
                                        <small class="text-muted stock-warning">Max: {{ $maxQuantity }}</small>
                                    @endif
                                </div>
                                <div class="col-md-2 text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <!-- Only show current price on product card -->
                                        <strong class="sale-price" id="item-total-{{ $item->id }}">
                                            ₱{{ number_format($itemTotalPrice, 2) }}
                                        </strong>
                                        
                                        @if($item->product->has_variants || $hasDiscount)
                                            <br>
                                            <small class="text-muted item-unit-price" id="item-unit-{{ $item->id }}">
                                                ₱{{ number_format($unitPrice, 2) }} each
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-1 text-end">
                                    <form action="{{ route('cart.destroy', $item) }}" method="POST"
                                        onsubmit = "return confirm('Are you sure you want to remove this item from the cart?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0" title="Remove item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST"
                            onsubmit = "return confirm('Are you sure you want to clear this cart? This action cannot be undone.');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i>Clear Cart
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                        <h3 class="text-muted">Your cart is empty</h3>
                        <p class="text-muted mb-4">Start shopping to add items to your cart</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                        </a>
                    </div>
                @endif
            </div>

            @if ($cartItems->count() > 0)
                <div class="col-lg-4">
                    <div class="summary-card">
                        <h4 class="mb-4">Order Summary</h4>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal (<span id="summary-quantity">0</span> items):</span>
                            <span id="summary-subtotal">₱0.00</span>
                        </div>

                        <!-- Original Total Price (if any discounts) -->
                        <div id="original-price-row" style="display: none;">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Original Price:</span>
                                <span class="original-price" id="summary-original">₱0.00</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount Savings:</span>
                                <span class="savings-text" id="summary-savings">-₱0.00</span>
                            </div>
                        </div>

                        <!-- Shipping Fee -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span id="summary-shipping">
                                ₱10.00
                            </span>
                        </div>
                        <small class="text-muted d-block mb-3" id="cart-shipping-note">
                            <i class="fas fa-info-circle me-1"></i>
                            Final shipping fee will be calculated at checkout based on your delivery address.
                        </small>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total:</strong>
                            <strong class="text-success" id="summary-total">
                                ₱0.00
                            </strong>
                        </div>

                        <div id="free-shipping-alert" class="alert alert-info" style="display: none;">
                            <small>
                                <i class="fas fa-info-circle me-2"></i>
                                Add ₱<span id="remaining-amount">0.00</span> more for free shipping!
                            </small>
                        </div>

                        <!-- Check if any items are out of stock -->
                        @php
                            $outOfStockItems = $cartItems->filter(function ($item) {
                                $hasVariants = $item->product->has_variants && $item->product->variants->count() > 0;

                                if ($hasVariants) {
                                    $variant = $item->product->variants->first(function ($v) use ($item) {
                                        return $v->size === $item->selected_size ||
                                            $v->variant_name === $item->selected_size;
                                    });
                                    return !$variant || ($variant->stock_quantity ?? 0) <= 0;
                                } else {
                                    return ($item->product->stock_quantity ?? 0) <= 0;
                                }
                            });
                        @endphp

                        @if ($outOfStockItems->count() > 0)
                            <div class="alert alert-warning">
                                <small>
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    {{ $outOfStockItems->count() }} item(s) in your cart are out of stock. Please update
                                    your cart before checkout.
                                </small>
                            </div>
                            <button class="btn btn-secondary w-100 btn-lg" disabled>
                                <i class="fas fa-lock me-2"></i>Update Cart to Checkout
                            </button>
                        @else
                            <button type="button" id="proceed-checkout-btn" class="btn btn-primary w-100 btn-lg" disabled>
                                <i class="fas fa-lock me-2"></i>Proceed to Checkout
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function confirmClear() {
            return confirm('Are you sure you want to clear this cart? This action cannot be undone.');
        }

        // Handle variant selection with AJAX
        document.querySelectorAll('.variant-select').forEach(select => {
            select.addEventListener('change', function() {
                const itemId = this.getAttribute('data-item-id');
                const selectedOption = this.options[this.selectedIndex];

                // Don't proceed if option is disabled (out of stock)
                if (selectedOption.disabled) {
                    this.value = this.querySelector('option[selected]').value;
                    return;
                }

                const variantPrice = parseFloat(selectedOption.getAttribute('data-price'));
                const variantOriginalPrice = parseFloat(selectedOption.getAttribute('data-original-price'));
                const variantHasDiscount = selectedOption.getAttribute('data-has-discount') === 'true';
                const variantDiscountPercent = selectedOption.getAttribute('data-discount-percent');
                const variantImage = selectedOption.getAttribute('data-image');
                const variantStock = parseInt(selectedOption.getAttribute('data-stock'));

                // Get current quantity
                const quantityElement = document.querySelector(`#cart-item-${itemId} .quantity-input`);
                const quantity = quantityElement ? parseInt(quantityElement.textContent) : 1;

                // Calculate totals
                const itemTotal = variantPrice * quantity;

                // Update price display immediately
                const itemTotalElement = document.getElementById(`item-total-${itemId}`);
                const unitPriceElement = document.getElementById(`item-unit-${itemId}`);

                // Update sale price
                itemTotalElement.textContent = `₱${itemTotal.toFixed(2)}`;
                
                // Update unit price
                if (unitPriceElement) {
                    unitPriceElement.textContent = `₱${variantPrice.toFixed(2)} each`;
                }

                // Update cart item data attributes
                const cartItem = document.getElementById(`cart-item-${itemId}`);
                cartItem.setAttribute('data-unit-price', variantPrice);
                cartItem.setAttribute('data-original-unit-price', variantOriginalPrice);
                cartItem.setAttribute('data-has-discount', variantHasDiscount ? '1' : '0');
                cartItem.setAttribute('data-discount-percent', variantDiscountPercent);
                cartItem.setAttribute('data-quantity', quantity);

                // Update image immediately if variant has specific image
                const itemImage = document.getElementById(`item-image-${itemId}`);
                if (variantImage && variantImage !== 'null' && itemImage) {
                    itemImage.src = variantImage;
                }

                // Show loading spinner
                const loadingSpinner = document.getElementById(`loading-${itemId}`);
                if (loadingSpinner) {
                    loadingSpinner.style.display = 'block';
                }

                // Submit the form via AJAX to avoid page reload
                const form = document.getElementById(`variant-form-${itemId}`);
                if (form) {
                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update cart count if provided
                                if (data.cart_count !== undefined) {
                                    const cartCountElements = document.querySelectorAll('.cart-count');
                                    cartCountElements.forEach(el => {
                                        el.textContent = data.cart_count;
                                    });
                                }

                                // Show success message
                                if (data.message) {
                                    showToast('success', data.message);
                                }
                            } else {
                                // Show error message
                                if (data.message) {
                                    showToast('error', data.message);
                                    location.reload();
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('error', 'An error occurred while updating the cart');
                            location.reload();
                        })
                        .finally(() => {
                            // Hide loading spinner
                            if (loadingSpinner) {
                                loadingSpinner.style.display = 'none';
                            }

                            // Reload the page to get updated totals and stock information
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        });
                }
            });
        });

        // Show loading when changing quantity
        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('form');
                const cartItem = form.closest('.cart-item');
                if (cartItem) {
                    const itemId = cartItem.id.replace('cart-item-', '');
                    const loadingSpinner = document.getElementById(`loading-${itemId}`);
                    if (loadingSpinner) {
                        loadingSpinner.style.display = 'block';
                    }
                }
            });
        });

        // Toast notification function
        function showToast(type, message) {
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }

            const toast = document.createElement('div');
            toast.className =
            `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');

            toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

            toastContainer.appendChild(toast);

            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
        }

        // Multi-select cart functionality
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const checkoutBtn = document.getElementById('proceed-checkout-btn');
            const selectedCountSpan = document.getElementById('selected-count');
            
            // Summary elements
            const summaryQuantity = document.getElementById('summary-quantity');
            const summarySubtotal = document.getElementById('summary-subtotal');
            const summaryOriginal = document.getElementById('summary-original');
            const summarySavings = document.getElementById('summary-savings');
            const summaryShipping = document.getElementById('summary-shipping');
            const summaryTotal = document.getElementById('summary-total');
            const originalPriceRow = document.getElementById('original-price-row');
            const freeShippingAlert = document.getElementById('free-shipping-alert');
            const remainingAmount = document.getElementById('remaining-amount');

            // Function to calculate summary for selected items
            function calculateSummary(selectedIds) {
                let subtotal = 0;
                let originalTotal = 0;
                let quantity = 0;
                let hasDiscount = false;

                // If no items selected, return all zeros
                if (selectedIds.length === 0) {
                    return {
                        subtotal: 0,
                        originalTotal: 0,
                        quantity: 0,
                        hasDiscount: false,
                        savings: 0,
                        shipping: 10,
                        total: 10
                    };
                }

                // Calculate based on selected items
                selectedIds.forEach(itemId => {
                    const cartItem = document.getElementById(`cart-item-${itemId}`);
                    if (cartItem) {
                        const unitPrice = parseFloat(cartItem.getAttribute('data-unit-price')) || 0;
                        const originalUnitPrice = parseFloat(cartItem.getAttribute('data-original-unit-price')) || 0;
                        const itemHasDiscount = cartItem.getAttribute('data-has-discount') === '1';
                        const itemQuantity = parseInt(cartItem.getAttribute('data-quantity')) || 1;
                        
                        quantity += itemQuantity;
                        subtotal += unitPrice * itemQuantity;
                        originalTotal += originalUnitPrice * itemQuantity;
                        
                        if (itemHasDiscount) {
                            hasDiscount = true;
                        }
                    }
                });

                const savings = originalTotal - subtotal;
                // Cart shows ESTIMATED shipping: ₱0 for orders ≥₱100, else ₱10
                // ACTUAL shipping will be calculated at checkout based on GPS coordinates from user's address
                const shipping = subtotal >= 100 ? 0 : 10;
                const total = subtotal + shipping;

                return {
                    subtotal,
                    originalTotal,
                    quantity,
                    hasDiscount,
                    savings,
                    shipping,
                    total
                };
            }

            // Function to update summary display
            function updateSummary(selectedIds) {
                const summary = calculateSummary(selectedIds);
                
                // Update display
                summaryQuantity.textContent = summary.quantity;
                summarySubtotal.textContent = '₱' + summary.subtotal.toFixed(2);
                
                // Show/hide original price and savings
                if (summary.hasDiscount && summary.savings > 0) {
                    originalPriceRow.style.display = 'block';
                    summaryOriginal.textContent = '₱' + summary.originalTotal.toFixed(2);
                    summarySavings.textContent = '-₱' + summary.savings.toFixed(2);
                } else {
                    originalPriceRow.style.display = 'none';
                }
                
                // Update shipping
                if (summary.subtotal >= 100) {
                    summaryShipping.innerHTML = '<span class="text-success">FREE</span>';
                    freeShippingAlert.style.display = 'none';
                } else {
                    summaryShipping.textContent = '₱10.00';
                    freeShippingAlert.style.display = 'block';
                    remainingAmount.textContent = (100 - summary.subtotal).toFixed(2);
                }
                
                // Update total
                summaryTotal.textContent = '₱' + summary.total.toFixed(2);
                
                // Update checkout button
                checkoutBtn.disabled = selectedIds.length === 0;
            }

            function updateSelection() {
                const checkedItems = document.querySelectorAll('.item-checkbox:checked');
                const checkedCount = checkedItems.length;
                const selectedIds = Array.from(checkedItems).map(cb => cb.getAttribute('data-item-id'));

                // Update count
                selectedCountSpan.textContent = checkedCount;

                // Update summary based on selected items
                updateSummary(selectedIds);

                // Update select all checkbox
                if (checkedCount === itemCheckboxes.length && itemCheckboxes.length > 0) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else if (checkedCount > 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                }

                // Highlight selected items
                itemCheckboxes.forEach(checkbox => {
                    const itemId = checkbox.getAttribute('data-item-id');
                    const cartItem = document.getElementById(`cart-item-${itemId}`);
                    if (checkbox.checked) {
                        cartItem.classList.add('selected');
                    } else {
                        cartItem.classList.remove('selected');
                    }
                });
            }

            // Select all handler - selects ALL items when checked
            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelection();
            });

            // Individual checkbox handlers
            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelection);
            });

            // Proceed to checkout handler
            checkoutBtn.addEventListener('click', function() {
                const selectedIds = Array.from(document.querySelectorAll('.item-checkbox:checked'))
                    .map(cb => cb.getAttribute('data-item-id'));

                if (selectedIds.length === 0) {
                    return;
                }

                // Send selected items to server
                checkoutBtn.disabled = true;
                checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

                fetch('{{ route("cart.checkout-selected") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ selected_items: selectedIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route("orders.create") }}';
                    } else {
                        showToast('error', data.message || 'Error processing selection');
                        checkoutBtn.disabled = false;
                        checkoutBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Proceed to Checkout';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'An error occurred');
                    checkoutBtn.disabled = false;
                    checkoutBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Proceed to Checkout';
                });
            });
            
            // Initialize with no items selected
            updateSelection();
        });
    </script>
@endsection