@extends('layouts.app')

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
</style>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Shopping Cart</h2>
                <span class="text-muted">{{ $cartItems->count() }} items</span>
            </div>

            @if($cartItems->count() > 0)
                @foreach($cartItems as $item)
                @php
                // Check if product has variants
                $hasVariants = $item->product->has_variants && $item->product->variants->count() > 0;
                
                if ($hasVariants) {
                    // Get the actual variant for this cart item
                    $currentVariant = $item->product->variants->first(function($variant) use ($item) {
                        return ($variant->size === $item->selected_size) || ($variant->variant_name === $item->selected_size);
                    });
                    
                    $currentStock = $currentVariant ? $currentVariant->stock_quantity : 0;
                    $variantName = $currentVariant ? ($currentVariant->size ?? $currentVariant->variant_name) : $item->selected_size;
                    $isVariantAvailable = $currentVariant && $currentStock > 0;
                    $maxQuantity = $currentStock;
                    
                    // Get variant-specific image or fallback to product image
                    $displayImage = $currentVariant && $currentVariant->image_url ? $currentVariant->image_url : $item->product->image_url;
                    
                    // Calculate price based on actual variant
                    $unitPrice = $currentVariant ? $currentVariant->current_price : $item->product->current_price;
                } else {
                    // For products without variants, use product's own stock and price
                    $currentVariant = null;
                    $currentStock = $item->product->stock_quantity;
                    $variantName = 'Standard';
                    $isVariantAvailable = $currentStock > 0;
                    $maxQuantity = $currentStock;
                    $displayImage = $item->product->image_url;
                    $unitPrice = $item->product->current_price;
                }
                
                $itemTotalPrice = $unitPrice * $item->quantity;
            @endphp
                
                <div class="cart-item position-relative" id="cart-item-{{ $item->id }}">
                    <div class="loading-spinner" id="loading-{{ $item->id }}">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="{{ $displayImage }}" alt="{{ $item->product->name }}" class="cart-item-image" id="item-image-{{ $item->id }}">
                        </div>
                        <div class="col-md-4">
                            <h5 class="mb-1">{{ $item->product->name }}</h5>
                            <p class="text-muted mb-1 small">{{ Str::limit($item->product->description, 50) }}</p>
                            
                            <!-- Variant Selection -->
                            @if($item->product->has_variants && $item->product->variants->count() > 0)
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="mb-2 variant-form" id="variant-form-{{ $item->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="quantity" value="{{ $item->quantity }}" id="quantity-{{ $item->id }}">
                                <select name="selected_size" class="variant-select" data-item-id="{{ $item->id }}">
                                    @foreach($item->product->variants as $variant)
                                        @php
                                            $variantNameOption = $variant->size ?? $variant->variant_name ?? 'Option';
                                            $variantStock = $variant->stock_quantity ?? 0;
                                            $variantPrice = $variant->current_price ?? $variant->price ?? 0;
                                            $variantImage = $variant->image_url;
                                        @endphp
                                        <option value="{{ $variantNameOption }}" 
                                            data-price="{{ $variantPrice }}"
                                            data-image="{{ $variantImage }}"
                                            data-stock="{{ $variantStock }}"
                                            {{ $item->selected_size == $variantNameOption ? 'selected' : '' }}
                                            {{ $variantStock <= 0 ? 'disabled' : '' }}>
                                            {{ $variantNameOption }} 
                                            @if($variantStock <= 0)
                                            (Out of Stock)
                                            @else
                                            - ₱{{ number_format($variantPrice, 2) }} ({{ $variantStock }} available)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            @else
                            <span class="badge bg-light text-dark">{{ $variantName }}</span>
                            @endif
                            
                            <!-- Stock Warning -->
                            @if(!$isVariantAvailable)
                            <div class="alert alert-warning py-1 mt-2 small" role="alert">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                @if($hasVariants)
                                Selected option is out of stock
                                @else
                                This product is out of stock
                                @endif
                            </div>
                            @elseif($currentStock < $item->quantity)
                            <div class="alert alert-warning py-1 mt-2 small" role="alert">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Only {{ $currentStock }} available
                                @if($hasVariants)
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
                            @if($isVariantAvailable && $maxQuantity)
                            <small class="text-muted stock-warning">Max: {{ $maxQuantity }}</small>
                            @endif
                        </div>
                        <div class="col-md-2 text-center">
                            <strong class="text-success item-total" id="item-total-{{ $item->id }}">₱{{ number_format($itemTotalPrice, 2) }}</strong>
                            @if($item->product->has_variants)
                            <br>
                            <small class="text-muted item-unit-price" id="item-unit-{{ $item->id }}">₱{{ number_format($unitPrice, 2) }} each</small>
                            @endif
                        </div>
                        <div class="col-md-1 text-end">
                            <form action="{{ route('cart.destroy', $item) }}" method="POST" onsubmit = "return confirm('Are you sure you want to remove this item from the cart?');">
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
                    <form action="{{ route('cart.clear') }}" method="POST" onsubmit = "return confirm('Are you sure you want to clear this cart? This action cannot be undone.');">
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

        @if($cartItems->count() > 0)
        <div class="col-lg-4">
            <div class="summary-card">
                <h4 class="mb-4">Order Summary</h4>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal ({{ $cartItems->sum('quantity') }} items):</span>
                    <span>₱{{ number_format($subtotal, 2) }}</span>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax (10%):</span>
                    <span>₱{{ number_format($tax, 2) }}</span>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Shipping:</span>
                    <span>{{ $shipping == 0 ? 'FREE' : '$' . number_format($shipping, 2) }}</span>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between mb-4">
                    <strong>Total:</strong>
                    <strong class="text-success">₱{{ number_format($total, 2) }}</strong>
                </div>

                @if($subtotal < 100)
                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-info-circle me-2"></i>
                        Add ₱{{ number_format(100 - $subtotal, 2) }} more for free shipping!
                    </small>
                </div>
                @endif

                <!-- Check if any items are out of stock -->
                @php
                    $outOfStockItems = $cartItems->filter(function($item) {
                        $hasVariants = $item->product->has_variants && $item->product->variants->count() > 0;
                        
                        if ($hasVariants) {
                            // For products with variants, check the selected variant's stock
                            $variant = $item->product->variants->first(function($v) use ($item) {
                                return ($v->size === $item->selected_size) || ($v->variant_name === $item->selected_size);
                            });
                            return !$variant || ($variant->stock_quantity ?? 0) <= 0;
                        } else {
                            // For products without variants, check the product's stock
                            return ($item->product->stock_quantity ?? 0) <= 0;
                        }
                    });
                @endphp

                @if($outOfStockItems->count() > 0)
                <div class="alert alert-warning">
                    <small>
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ $outOfStockItems->count() }} item(s) in your cart are out of stock. Please update your cart before checkout.
                    </small>
                </div>
                <button class="btn btn-secondary w-100 btn-lg" disabled>
                    <i class="fas fa-lock me-2"></i>Update Cart to Checkout
                </button>
                @else
                <a href="{{ route('orders.create') }}" class="btn btn-primary w-100 btn-lg">
                    <i class="fas fa-lock me-2"></i>Proceed to Checkout
                </a>
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
            const variantImage = selectedOption.getAttribute('data-image');
            const variantStock = parseInt(selectedOption.getAttribute('data-stock'));
            
            // Get current quantity
            const quantityElement = document.querySelector(`#cart-item-${itemId} .quantity-input`);
            const quantity = quantityElement ? parseInt(quantityElement.textContent) : 1;
            
            // Update price display immediately
            const itemTotal = variantPrice * quantity;
            document.getElementById(`item-total-${itemId}`).textContent = `$${itemTotal.toFixed(2)}`;
            
            const unitPriceElement = document.getElementById(`item-unit-${itemId}`);
            if (unitPriceElement) {
                unitPriceElement.textContent = `$${variantPrice.toFixed(2)} each`;
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
                            // Update cart count in header if you have one
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
                            // Revert the selection on error
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
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
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
        
        // Remove toast from DOM after it's hidden
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }
});
</script>
@endsection