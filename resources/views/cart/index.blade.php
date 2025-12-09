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

        .quantity-input.error {
            border-color: #dc3545;
            background-color: #fff5f5;
        }

        .quantity-error {
            color: #dc3545;
            font-size: 0.75rem;
            margin-top: 5px;
            display: none;
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
                    <h3 class="fw-bold text-success" style="color: #2C8F0C;">Shopping Cart</h3>
                    <span class="text-muted">{{ $cartItems->count() }} items</span>
                </div>

                @if ($cartItems->count() > 0)
                    <!-- Selection Controls -->
                    <div class="selection-controls d-flex justify-content-between align-items-center w-100">

                        <!-- Left side -->
                        <div class="selection-left d-flex align-items-center gap-2">
                            <input type="checkbox" id="select-all" class="cart-item-checkbox">
                            <label for="select-all" class="mb-0"><strong>Select All</strong></label>
                        </div>

                        <!-- Right side -->
                        <div class="selection-right">
                            <span><span id="selected-count">0</span> selected</span>
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
                                    ? ($currentVariant->has_discount
                                        ? $currentVariant->sale_price
                                        : $currentVariant->current_price)
                                    : ($item->product->has_discount
                                        ? $item->product->sale_price
                                        : $item->product->current_price);

                                // Store original price for summary calculation
                                $originalUnitPrice = $currentVariant ? $currentVariant->price : $item->product->price;

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
                                $unitPrice = $item->product->has_discount
                                    ? $item->product->sale_price
                                    : $item->product->current_price;
                                $originalUnitPrice = $item->product->price;
                                $hasDiscount = $item->product->has_discount;
                                $discountPercent = $item->product->discount_percentage;
                            }

                            $itemTotalPrice = $unitPrice * $item->quantity;
                            $itemOriginalTotalPrice = $originalUnitPrice * $item->quantity;
                        @endphp

                        <div class="cart-item position-relative" id="cart-item-{{ $item->id }}"
                            data-item-id="{{ $item->id }}" data-unit-price="{{ $unitPrice }}"
                            data-original-unit-price="{{ $originalUnitPrice }}"
                            data-has-discount="{{ $hasDiscount ? '1' : '0' }}"
                            data-discount-percent="{{ $discountPercent }}" data-quantity="{{ $item->quantity }}"
                            data-max-quantity="{{ $maxQuantity }}">
                            <div class="loading-spinner" id="loading-{{ $item->id }}">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-1 d-flex align-items-center">
                                    <input type="checkbox" class="cart-item-checkbox item-checkbox"
                                        data-item-id="{{ $item->id }}">
                                </div>
                                <div class="col-md-2">
                                    <img src="{{ $displayImage }}" alt="{{ $item->product->name }}"
                                        class="cart-item-image" id="item-image-{{ $item->id }}">
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
                                                        $variantPrice = $variant->has_discount
                                                            ? $variant->sale_price
                                                            : $variant->current_price;
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
                                                        data-image="{{ $variantImage }}" data-stock="{{ $variantStock }}"
                                                        {{ $item->selected_size == $variantNameOption ? 'selected' : '' }}
                                                        {{ $variantStock <= 0 ? 'disabled' : '' }}>
                                                        {{ $variantNameOption }}
                                                        @if ($variantStock <= 0)
                                                            (Out of Stock)
                                                        @else
                                                            @if ($variantHasDiscount)
                                                                - ₱{{ number_format($variantPrice, 2) }} <small
                                                                    class="text-muted"><del>₱{{ number_format($variantOriginalPrice, 2) }}</del></small>
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
                                    <div class="quantity-control" style="position: relative;">
                                        <!-- Completely isolated quantity controls -->
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <button type="button" 
                                                    class="quantity-btn"
                                                    onclick="decrementQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>

                                            <input type="number" 
                                                   class="quantity-input" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $maxQuantity }}"
                                                   data-item-id="{{ $item->id }}"
                                                   id="quantity-input-{{ $item->id }}"
                                                   name=""
                                                   form=""
                                                   data-selected-size="{{ $item->selected_size }}"
                                                   data-update-url="{{ route('cart.update', $item) }}"
                                                   autocomplete="off"
                                                   style="width: 60px; text-align: center; border: 1px solid #ddd; border-radius: 5px; padding: 5px;"
                                                   onfocus="handleQuantityFocus(this)"
                                                   onblur="handleQuantityBlur(this)"
                                                   oninput="handleQuantityInput(this)"
                                                   onkeydown="handleQuantityKeydown(event, this)">

                                            <button type="button" 
                                                    class="quantity-btn"
                                                    onclick="incrementQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                    {{ !$isVariantAvailable || $item->quantity >= $maxQuantity ? 'disabled' : '' }}>+</button>
                                        </div>
                                    </div>
                                    @if ($isVariantAvailable && $maxQuantity)
                                        <small class="text-muted stock-warning">Max: {{ $maxQuantity }}</small>
                                    @endif
                                    <div class="quantity-error" id="quantity-error-{{ $item->id }}">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Quantity cannot exceed {{ $maxQuantity }} available items.
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <!-- Only show current price on product card -->
                                        <strong class="sale-price" id="item-total-{{ $item->id }}">
                                            ₱{{ number_format($itemTotalPrice, 2) }}
                                        </strong>

                                        @if ($item->product->has_variants || $hasDiscount)
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

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total:</strong>
                            <strong class="text-success" id="summary-total">
                                ₱0.00
                            </strong>
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
                            <button type="button" id="proceed-checkout-btn" class="btn btn-primary w-100 btn-lg"
                                disabled>
                                <i class="fas fa-cart-arrow-down me-2"></i>
                                Proceed to Checkout
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Global form validation prevention for quantity inputs
        document.addEventListener('DOMContentLoaded', function() {
            // Add global event listeners to prevent form validation
            document.addEventListener('submit', function(e) {
                // Check if submit was triggered by a quantity input
                const activeElement = document.activeElement;
                if (activeElement && activeElement.classList.contains('quantity-input')) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            }, true);
        });

        // Global functions for quantity input handling
        function handleQuantityFocus(input) {
            // Store original value
            input.setAttribute('data-original-value', input.value);
            
            // Prevent form validation by disabling any nearby forms temporarily
            const cartItem = input.closest('.cart-item');
            if (cartItem) {
                const forms = cartItem.querySelectorAll('form');
                forms.forEach(form => {
                    form.setAttribute('data-validation-temporarily-disabled', 'true');
                    form.setAttribute('novalidate', 'true');
                });
            }
        }

        function handleQuantityBlur(input) {
            const itemId = input.getAttribute('data-item-id');
            const cartItem = input.closest('.cart-item');
            const errorDiv = document.getElementById(`quantity-error-${itemId}`);
            const originalValue = input.getAttribute('data-original-value') || input.value;
            const maxQuantity = parseInt(cartItem.getAttribute('data-max-quantity')) || 999;
            
            // Validate quantity
            let quantity = parseInt(input.value);
            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
                input.value = 1;
            }

            if (quantity > maxQuantity) {
                // Show error
                input.classList.add('error');
                if (errorDiv) {
                    errorDiv.style.display = 'block';
                }
                // Reset to original value or max quantity
                input.value = Math.min(originalValue, maxQuantity);
                showToast(`Quantity cannot exceed ${maxQuantity} available items.`, 'error');
                return;
            }

            // Clear error if valid
            input.classList.remove('error');
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }

            // Only update if quantity changed and quantity is valid
            if (quantity !== parseInt(originalValue) && quantity >= 1 && quantity <= maxQuantity) {
                updateCartQuantity(itemId, quantity);
            }
            
            // Re-enable form validation
            const forms = cartItem.querySelectorAll('form');
            forms.forEach(form => {
                form.removeAttribute('data-validation-temporarily-disabled');
                form.removeAttribute('novalidate');
            });
        }

        function handleQuantityInput(input) {
            const itemId = input.getAttribute('data-item-id');
            const cartItem = input.closest('.cart-item');
            const errorDiv = document.getElementById(`quantity-error-${itemId}`);
            const maxQuantity = parseInt(cartItem.getAttribute('data-max-quantity')) || 999;
            const value = parseInt(input.value);
            
            // Only validate if there's an actual value entered
            if (input.value !== '' && !isNaN(value)) {
                if (value > maxQuantity) {
                    input.classList.add('error');
                    if (errorDiv) {
                        errorDiv.style.display = 'block';
                    }
                } else if (value < 1) {
                    input.classList.add('error');
                    if (errorDiv) {
                        errorDiv.style.display = 'block';
                        errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Quantity must be at least 1.';
                    }
                } else {
                    if (errorDiv) {
                        errorDiv.style.display = 'none';
                    }
                    input.classList.remove('error');
                }
            }
        }

        function handleQuantityKeydown(event, input) {
            // Prevent form submission on Enter key
            if (event.key === 'Enter') {
                event.preventDefault();
                event.stopPropagation();
                // Trigger blur to validate and update
                input.blur();
                return false;
            }
        }

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
                const quantityElement = document.querySelector(`#quantity-input-${itemId}`);
                const quantity = quantityElement ? parseInt(quantityElement.value) || 1 : 1;

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
                cartItem.setAttribute('data-max-quantity', variantStock);

                // Update quantity input max attribute
                if (quantityElement) {
                    quantityElement.setAttribute('max', variantStock);
                    // If current quantity exceeds new max, adjust it
                    if (quantity > variantStock) {
                        quantityElement.value = variantStock;
                        // Update the displayed total
                        const adjustedTotal = variantPrice * variantStock;
                        itemTotalElement.textContent = `₱${adjustedTotal.toFixed(2)}`;
                    }
                }

                // Update error message with new max quantity
                const errorDiv = document.getElementById(`quantity-error-${itemId}`);
                if (errorDiv) {
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>Quantity cannot exceed ${variantStock} available items.`;
                }

                // Update max quantity display
                const maxQuantityDisplay = cartItem.querySelector('.stock-warning');
                if (maxQuantityDisplay) {
                    maxQuantityDisplay.textContent = `Max: ${variantStock}`;
                }

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
                                    showToast(data.message, 'success');
                                }
                            } else {
                                // Show error message
                                if (data.message) {
                                    showToast(data.message, 'error');
                                    location.reload();
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('An error occurred while updating the cart', 'error');
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





        // Functions for increment/decrement buttons
        function decrementQuantity(itemId, newQuantity) {
            if (newQuantity >= 1) {
                updateCartQuantity(itemId, newQuantity);
            }
        }

        function incrementQuantity(itemId, newQuantity) {
            const cartItem = document.getElementById(`cart-item-${itemId}`);
            const maxQuantity = parseInt(cartItem.getAttribute('data-max-quantity')) || 999;
            
            if (newQuantity <= maxQuantity) {
                updateCartQuantity(itemId, newQuantity);
            }
        }



        function updateCartQuantity(itemId, quantity) {
            const cartItem = document.getElementById(`cart-item-${itemId}`);
            const loadingSpinner = document.getElementById(`loading-${itemId}`);
            
            // Get selected size and update URL from the quantity input
            const quantityInput = document.getElementById(`quantity-input-${itemId}`);
            const selectedSize = quantityInput ? quantityInput.getAttribute('data-selected-size') : '';
            const updateUrl = quantityInput ? quantityInput.getAttribute('data-update-url') : '';
            
            if (!updateUrl) {
                showToast('Error: Could not find update URL', 'error');
                return;
            }

            // Create form data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');
            formData.append('quantity', quantity);
            if (selectedSize) {
                formData.append('selected_size', selectedSize);
            }

            if (loadingSpinner) {
                loadingSpinner.style.display = 'block';
            }

            fetch(updateUrl, {
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
                    showToast(data.message || 'Cart updated successfully', 'success');
                    // Update cart count if provided
                    if (data.cart_count !== undefined) {
                        const cartCountElements = document.querySelectorAll('.cart-count');
                        cartCountElements.forEach(el => {
                            el.textContent = data.cart_count;
                        });
                    }
                    // Reload to get updated totals
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    showToast(data.message || 'Error updating cart', 'error');
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while updating the cart', 'error');
                window.location.reload();
            })
            .finally(() => {
                if (loadingSpinner) {
                    loadingSpinner.style.display = 'none';
                }
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
            const summaryTotal = document.getElementById('summary-total');
            const originalPriceRow = document.getElementById('original-price-row');

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
                        shipping: 0,
                        total: 0
                    };
                }

                // Calculate based on selected items
                selectedIds.forEach(itemId => {
                    const cartItem = document.getElementById(`cart-item-${itemId}`);
                    if (cartItem) {
                        const unitPrice = parseFloat(cartItem.getAttribute('data-unit-price')) || 0;
                        const originalUnitPrice = parseFloat(cartItem.getAttribute(
                            'data-original-unit-price')) || 0;
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
                const shipping = 0;
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

                fetch('{{ route('cart.checkout-selected') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            selected_items: selectedIds
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '{{ route('orders.create') }}';
                        } else {
                            showToast(data.message || 'Error processing selection', 'error');
                            checkoutBtn.disabled = false;
                            checkoutBtn.innerHTML =
                                '<i class="fas fa-lock me-2"></i>Proceed to Checkout';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred', 'error');
                        checkoutBtn.disabled = false;
                        checkoutBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Proceed to Checkout';
                    });
            });

            // Initialize with no items selected
            updateSelection();
        });
    </script>
@endsection
