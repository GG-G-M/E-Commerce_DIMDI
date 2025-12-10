@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-green: #2C8F0C;
        --dark-green: #1E6A08;
        --light-green: #E8F5E6;
        --accent-green: #4CAF50;
        --light-gray: #F8F9FA;
        --medium-gray: #E9ECEF;
        --dark-gray: #6C757D;
        --text-dark: #212529;
    }
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .info-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid var(--medium-gray);
        margin-bottom: 1.5rem;
    }
    .info-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--light-green);
    }
    .info-header i {
        background: var(--light-green);
        color: var(--primary-green);
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 1rem;
    }
    .info-header h5 {
        margin: 0;
        color: var(--text-dark);
        font-weight: 600;
    }
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--light-gray);
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 500;
        color: var(--dark-gray);
        flex: 1;
        font-size: 0.9rem;
    }
    .info-value {
        color: var(--text-dark);
        font-weight: 500;
        text-align: right;
        flex: 1;
        font-size: 0.9rem;
    }
    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
    }
    .status-complete {
        background: #D4EDDA;
        color: #155724;
    }
    .status-missing {
        background: #F8D7DA;
        color: #721C24;
    }
    .checkout-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .checkout-header {
        background: linear-gradient(135deg, var(--primary-green), var(--accent-green));
        color: white;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
    }
    .checkout-header i {
        margin-right: 0.75rem;
        font-size: 1.1rem;
    }
    .checkout-header h5 {
        margin: 0;
        font-weight: 600;
    }
    .checkout-body {
        padding: 1.5rem;
    }
    .form-label {
        color: var(--primary-green);
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border: 2px solid var(--medium-gray);
        border-radius: 8px;
        padding: 0.75rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.25);
        outline: none;
    }
    .order-summary-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
        position: sticky;
        top: 20px;
    }
    .summary-header {
        background: linear-gradient(135deg, var(--primary-green), var(--accent-green));
        color: white;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
    }
    .summary-header i {
        margin-right: 0.75rem;
        font-size: 1.1rem;
    }
    .summary-header h5 {
        margin: 0;
        font-weight: 600;
    }
    .summary-body {
        padding: 1.5rem;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--light-gray);
    }
    .summary-item:last-child {
        border-bottom: none;
    }
    .summary-total {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-green);
    }
    .product-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1rem 0;
        border-bottom: 1px solid var(--light-gray);
    }
    .product-item:last-child {
        border-bottom: none;
    }
    .product-info {
        flex: 1;
    }
    .product-name {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }
    .product-variant {
        color: var(--dark-gray);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
    .product-price {
        font-size: 0.9rem;
        color: var(--text-dark);
    }
    .product-total {
        font-weight: 600;
        color: var(--primary-green);
        text-align: right;
    }
    .btn-primary-modern {
        background: linear-gradient(135deg, var(--primary-green), var(--accent-green));
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        min-height: 50px;
    }
    .btn-primary-modern:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
        color: white;
    }
    .btn-primary-modern:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    .btn-outline-modern {
        border: 2px solid var(--primary-green);
        color: var(--primary-green);
        background: transparent;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        min-height: 50px;
        text-decoration: none;
    }
    .btn-outline-modern:hover {
        background: var(--primary-green);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
        text-decoration: none;
    }
    .alert-modern {
        border: none;
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid;
    }
    .alert-danger-modern {
        background: #F8D7DA;
        color: #721C24;
        border-left-color: #dc3545;
    }
    .alert-info-modern {
        background: #D1ECF1;
        color: #0C5460;
        border-left-color: #17a2b8;
    }
    .payment-section {
        background: linear-gradient(135deg, #f8fdf8 0%, #f0f9f0 100%);
        border: 1px solid var(--light-green);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .payment-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        color: var(--primary-green);
        font-weight: 600;
    }
    .payment-header i {
        margin-right: 0.5rem;
    }
    .edit-profile-btn {
        background: transparent;
        border: 1.5px solid white;
        color: white;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    .edit-profile-btn:hover {
        background: white;
        color: var(--primary-green);
        transform: translateY(-1px);
        text-decoration: none;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .empty-state i {
        font-size: 4rem;
        color: var(--light-green);
        margin-bottom: 1rem;
    }
    .empty-state h4 {
        color: var(--dark-gray);
        margin-bottom: 1rem;
        font-weight: 600;
    }
    .empty-state p {
        color: var(--dark-gray);
        margin-bottom: 1.5rem;
    }
</style>

<div class="container py-4">
    @php
        // Build address from separate fields
        $addressParts = [];
        if ($user->street_address) {
            $addressParts[] = $user->street_address;
        }
        if ($user->barangay) {
            $addressParts[] = $user->barangay;
        }
        if ($user->city) {
            $addressParts[] = $user->city;
        }
        if ($user->province) {
            $addressParts[] = $user->province;
        }
        if ($user->region) {
            $addressParts[] = $user->region;
        }
        if ($user->country) {
            $addressParts[] = $user->country;
        }
        $fullAddress = !empty($addressParts) ? implode(', ', $addressParts) : '';
        $hasAddress = !empty($fullAddress);
        $hasPhone = !empty($user->phone);

        // Calculate totals (shipping will be updated dynamically via JavaScript)
        $subtotal = $cartItems->sum('total_price');
        $shipping = 100; // Default placeholder; will be calculated from coordinates
        $total = $subtotal + $shipping;
    @endphp

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1" style="color: var(--primary-green); font-weight: 700;">Checkout</h1>
                <p class="mb-0 text-muted">Complete your order and payment details</p>
            </div>
            <div>
                <a href="{{ route('cart.index') }}" class="btn-outline-modern" style="width: auto; min-height: auto; padding: 0.5rem 1rem; font-size: 0.85rem;">
                    <i class="fas fa-arrow-left"></i>
                    Back to Cart
                </a>
            </div>
        </div>
    </div>

    <!-- Address Warning Alert -->
    @if (!$hasAddress || !$hasPhone)
        <div class="alert alert-danger-modern alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Profile Information Required</h5>
                    <p class="mb-2">Please complete your profile before placing an order:</p>
                    <ul class="mb-1">
                        @if (!$hasAddress)
                            <li><strong>Delivery Address:</strong> Required for shipping</li>
                        @endif
                        @if (!$hasPhone)
                            <li><strong>Contact Phone:</strong> Required for delivery updates</li>
                        @endif
                    </ul>
                    <a href="{{ route('profile.show') }}" class="btn btn-danger btn-sm mt-2">
                        <i class="fas fa-user-edit me-1"></i>Update Profile Now
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Delivery Information Section -->
    <div class="info-section">
        <div class="info-header">
            <i class="fas fa-map-marker-alt"></i>
            <h5>Delivery Information</h5>
            <a href="{{ route('profile.show') }}" class="edit-profile-btn">
                <i class="fas fa-edit"></i>
                Edit Profile
            </a>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="info-item">
                    <span class="info-label">Customer Name</span>
                    <span class="info-value">{{ $user->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email Address</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone Number</span>
                    <span class="info-value">
                        @if($hasPhone)
                            {{ $user->phone }}
                        @else
                            <span class="status-indicator status-missing">
                                <i class="fas fa-exclamation-circle"></i>
                                Phone number not set
                            </span>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Delivery Address</span>
                    <span class="info-value">
                        @if ($hasAddress)
                            <span class="">
                                {{-- <i class="fas fa-check-circle"></i> --}}
                                {{ $fullAddress }}
                            </span>
                        @else
                            <span class="status-indicator status-missing">
                                <i class="fas fa-exclamation-circle"></i>
                                Address not set. Please update your profile.
                            </span>
                        @endif
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="payment-section">
                    <div class="payment-header">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Profile Status</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone Number</span>
                        <span class="info-value">
                            <span class="status-indicator {{ $hasPhone ? 'status-complete' : 'status-missing' }}">
                                <i class="fas fa-{{ $hasPhone ? 'check-circle' : 'exclamation-circle' }}"></i>
                                {{ $hasPhone ? 'Complete' : 'Missing' }}
                            </span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Address</span>
                        <span class="info-value">
                            <span class="status-indicator {{ $hasAddress ? 'status-complete' : 'status-missing' }}">
                                <i class="fas fa-{{ $hasAddress ? 'check-circle' : 'exclamation-circle' }}"></i>
                                {{ $hasAddress ? 'Complete' : 'Incomplete' }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Only show checkout form if user has address and phone -->
    @if ($hasAddress && $hasPhone)
        <div class="row">
            <div class="col-lg-8">
                <div class="checkout-card">
                    <div class="checkout-header">
                        <i class="fas fa-credit-card"></i>
                        <h5>Payment & Order Details</h5>
                    </div>
                    <div class="checkout-body">
                        <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                            @csrf

                            <!-- Required fields for order processing -->
                            <input type="hidden" name="shipping_address" value="{{ $fullAddress }}">
                            <input type="hidden" name="customer_phone" value="{{ $user->phone }}">
                            <input type="hidden" name="billing_address" value="{{ $fullAddress }}">

                            <div class="mb-4">
                                <label for="payment_method" class="form-label">
                                    <i class="fas fa-money-check-alt me-1"></i> Payment Method *
                                </label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="card">Credit/Debit Card (PayMongo)</option>
                                    <option value="gcash">GCash (PayMongo)</option>
                                    <option value="grab_pay">GrabPay (PayMongo)</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                </select>
                            </div>

                            <!-- PayMongo Payment Section -->
                            <div id="paymongo-section" class="payment-section" style="display: none;">
                                <div class="payment-header">
                                    <i class="fas fa-credit-card"></i>
                                    <span>PayMongo Payment</span>
                                </div>
                                
                                <!-- Card Payment Form -->
                                <div id="card-payment" class="payment-method">
                                    <div id="paymongo-card-form"></div>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>We accept:</span>
                                            <div>
                                                <i class="fab fa-cc-visa fa-2x text-primary me-2"></i>
                                                <i class="fab fa-cc-mastercard fa-2x text-danger me-2"></i>
                                                <i class="fab fa-cc-amex fa-2x text-info"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- GCash Payment -->
                                <div id="gcash-payment" class="payment-method" style="display: none;">
                                    <div class="text-center">
                                        <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                                        <p>You will be redirected to GCash to complete your payment after placing the order.</p>
                                    </div>
                                </div>

                                <!-- GrabPay Payment -->
                                <div id="grabpay-payment" class="payment-method" style="display: none;">
                                    <div class="text-center">
                                        <i class="fas fa-car fa-3x text-success mb-3"></i>
                                        <p>You will be redirected to GrabPay to complete your payment after placing the order.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i> Order Notes (Optional)
                                </label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"
                                    placeholder="Any special instructions for delivery...">{{ old('notes') }}</textarea>
                                <small class="text-muted">E.g., "Leave at gate", "Call before delivery", etc.</small>
                            </div>

                            <!-- Hidden fields for PayMongo -->
                            <input type="hidden" id="payment_intent_id" name="payment_intent_id">
                            <input type="hidden" id="payment_method_id" name="payment_method_id">
                            <input type="hidden" id="payment_status" name="payment_status" value="pending">
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="order-summary-card">
                    <div class="summary-header">
                        <i class="fas fa-receipt"></i>
                        <h5>Order Summary</h5>
                    </div>
                    <div class="summary-body">
                        @foreach ($cartItems as $item)
                            @php
                                // Get product and variant information
                                $product = $item->product;
                                $hasVariants = $product->has_variants && $product->variants->count() > 0;
                                
                                if ($hasVariants && $item->selected_size) {
                                    // Get the variant for this cart item
                                    $variant = $product->variants->first(function ($v) use ($item) {
                                        return ($v->size === $item->selected_size) || 
                                               ($v->variant_name === $item->selected_size);
                                    });
                                    
                                    if ($variant) {
                                        $unitPrice = $variant->current_price;
                                        $originalUnitPrice = $variant->price;
                                        $hasDiscount = $variant->has_discount;
                                        $discountPercent = $variant->discount_percentage;
                                        $variantName = $variant->size ?? $variant->variant_name ?? $item->selected_size;
                                    } else {
                                        // Fallback if variant not found
                                        $unitPrice = $product->current_price;
                                        $originalUnitPrice = $product->price;
                                        $hasDiscount = $product->has_discount;
                                        $discountPercent = $product->discount_percentage;
                                        $variantName = $item->selected_size;
                                    }
                                } else {
                                    // Product without variants
                                    $variant = null;
                                    $unitPrice = $product->current_price;
                                    $originalUnitPrice = $product->price;
                                    $hasDiscount = $product->has_discount;
                                    $discountPercent = $product->discount_percentage;
                                    $variantName = 'Standard';
                                }
                                
                                $itemTotalPrice = $unitPrice * $item->quantity;
                                $itemOriginalTotalPrice = $originalUnitPrice * $item->quantity;
                                $itemSavings = $itemOriginalTotalPrice - $itemTotalPrice;
                            @endphp
                            
                            <div class="product-item">
                                <div class="product-info">
                                    <div class="product-name">{{ $product->name }}</div>
                                    
                                    @if ($hasVariants && $variantName !== 'Standard')
                                        <div class="product-variant">
                                            <i class="fas fa-tag me-1"></i>Variant: {{ $variantName }}
                                        </div>
                                    @endif
                                    
                                    <div class="product-price">
                                        <small class="text-muted">Qty: {{ $item->quantity }} × </small>
                                        @if ($hasDiscount && $originalUnitPrice > $unitPrice)
                                            <span style="color: var(--primary-green); font-weight: 600;">₱{{ number_format($unitPrice, 2) }}</span>
                                            <small class="text-muted" style="text-decoration: line-through;">₱{{ number_format($originalUnitPrice, 2) }}</small>
                                            @if ($discountPercent > 0)
                                                <span class="badge bg-danger ms-1">-{{ $discountPercent }}%</span>
                                            @endif
                                        @else
                                            <span style="color: var(--primary-green); font-weight: 600;">₱{{ number_format($unitPrice, 2) }}</span>
                                        @endif
                                    </div>
                                    
                                    @if ($hasDiscount && $itemSavings > 0)
                                        <small style="color: var(--dark-gray);">
                                            You save ₱{{ number_format($itemSavings, 2) }}
                                        </small>
                                    @endif
                                </div>
                                <div class="product-total">
                                    @if ($hasDiscount && $itemOriginalTotalPrice > $itemTotalPrice)
                                        <div>
                                            ₱{{ number_format($itemTotalPrice, 2) }}
                                            <div>
                                                <small class="text-muted" style="text-decoration: line-through;">₱{{ number_format($itemOriginalTotalPrice, 2) }}</small>
                                            </div>
                                        </div>
                                    @else
                                        ₱{{ number_format($itemTotalPrice, 2) }}
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="summary-item">
                            <span class="info-label">Subtotal:</span>
                            <span class="info-value" id="display-subtotal">₱{{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="summary-item">
                            <span class="info-label">Shipping:</span>
                            <span class="info-value" id="display-shipping">₱100.00</span>
                        </div>
                        
                        <small class="text-muted d-block mb-3" id="shipping-info">
                            <i class="fas fa-truck me-1"></i>
                            Calculating distance-based shipping fee from your address...
                        </small>

                        <hr class="my-3">
                        <div class="summary-item summary-total">
                            <span class="info-label">Total:</span>
                            <span class="info-value" id="display-total">₱{{ number_format($total, 2) }}</span>
                        </div>

                        @if ($subtotal < 100)
                            <div class="alert alert-info-modern">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Add ₱{{ number_format(100 - $subtotal, 2) }} more for free shipping!
                                </small>
                            </div>
                        @endif

                        <button type="submit" class="btn-primary-modern" id="place-order-btn" form="checkout-form">
                            <i class="fas fa-lock"></i>
                            Place Order & Pay
                        </button>

                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Your payment is secure and encrypted
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Show disabled checkout section if info is missing -->
        <div class="empty-state">
            <i class="fas fa-shopping-cart"></i>
            <h4>Checkout Unavailable</h4>
            <p>Please complete your profile information to proceed with checkout.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('profile.show') }}" class="btn-primary-modern" style="width: auto; min-height: auto; padding: 0.75rem 1.5rem;">
                    <i class="fas fa-user-edit"></i>
                    Complete Your Profile
                </a>
                <a href="{{ route('cart.index') }}" class="btn-outline-modern" style="width: auto; min-height: auto; padding: 0.75rem 1.5rem;">
                    <i class="fas fa-arrow-left"></i>
                    Back to Cart
                </a>
            </div>
        </div>
    @endif
</div>

<!-- PayMongo Script -->
<script src="https://js.paymongo.com/v1/paymongo.js"></script>
<script>
    // Constants for calculations
    const SUBTOTAL = {{ $subtotal }};

    // Calculate and display shipping fee based on address
    function calculateShippingFromAddress() {
        const address = document.querySelector('input[name="shipping_address"]').value;

        if (!address) {
            updateShippingDisplay(100, 'Unable to estimate', 'No address provided');
            return;
        }

        // Use the estimated coordinates endpoint to get approximate location
        // The server will calculate shipping based on the address hash
        fetch('{{ route('orders.calculate-shipping') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    address: address
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateShippingDisplay(
                        data.shipping_fee,
                        data.zone_name || 'Address-based estimation',
                        data.distance ? `Distance: ${data.distance.toFixed(2)} km` : null
                    );
                } else {
                    // Fallback to default fee if calculation fails
                    updateShippingDisplay(100, 'Default zone', 'Using standard shipping rate');
                }
            })
            .catch(error => {
                console.error('Shipping calculation error:', error);
                updateShippingDisplay(100, 'Default zone', 'Using standard shipping rate');
            });
    }

    // Update shipping display and recalculate total
    function updateShippingDisplay(shippingFee, zoneName, details) {
        const subtotal = SUBTOTAL;
        const total = subtotal + shippingFee;

        // Update display elements with proper comma formatting
        document.getElementById('display-shipping').textContent = '₱' + shippingFee.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('display-total').textContent = '₱' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // Update shipping info text
        let infoText = '<i class="fas fa-truck me-1"></i>';
        if (zoneName) {
            infoText += zoneName;
            if (details) {
                infoText += ` (${details})`;
            }
        } else {
            infoText += 'Standard shipping rate applied';
        }
        document.getElementById('shipping-info').innerHTML = infoText;
    }

    // Calculate shipping on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate initial shipping estimate
        setTimeout(calculateShippingFromAddress, 500);

        const checkoutForm = document.getElementById('checkout-form');
        const placeOrderBtn = document.getElementById('place-order-btn');
        const paymentMethodSelect = document.getElementById('payment_method');

        if (checkoutForm) {
            // Validate form before submission
            checkoutForm.addEventListener('submit', function(e) {
                const paymentMethod = paymentMethodSelect.value;

                if (!paymentMethod) {
                    e.preventDefault();
                    showToast('Please select a payment method.', 'warning');
                    return;
                }

                // Show loading state
                placeOrderBtn.disabled = true;
                placeOrderBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i>Processing Payment...';

                // Form will submit normally for non-card payments
            });

            // Payment method change handler
            paymentMethodSelect.addEventListener('change', function() {
                const method = this.value;
                const paymongoSection = document.getElementById('paymongo-section');

                if (method === 'card' || method === 'gcash' || method === 'grab_pay') {
                    paymongoSection.style.display = 'block';

                    // Show/hide specific payment methods
                    document.getElementById('card-payment').style.display = method === 'card' ?
                        'block' : 'none';
                    document.getElementById('gcash-payment').style.display = method === 'gcash' ?
                        'block' : 'none';
                    document.getElementById('grabpay-payment').style.display = method === 'grab_pay' ?
                        'block' : 'none';
                } else {
                    paymongoSection.style.display = 'none';
                }
            });

            // Initialize PayMongo for card payments
            function initializePayMongo() {
                const paymongo = PayMongo('{{ env('PAYMONGO_PUBLIC_KEY') }}');

                // Create card element only if card payment is selected
                if (document.getElementById('payment_method').value === 'card') {
                    const card = paymongo.elements().create('card');
                    card.mount('#paymongo-card-form');

                    // Handle form submission for card payments
                    checkoutForm.addEventListener('submit', async function(e) {
                        if (document.getElementById('payment_method').value === 'card') {
                            e.preventDefault();

                            try {
                                const {
                                    paymentIntent,
                                    paymentMethod
                                } = await paymongo.createPaymentMethodFromCard(card, {
                                    billing: {
                                        name: '{{ $user->name }}',
                                        email: '{{ $user->email }}',
                                        phone: '{{ $user->phone }}'
                                    }
                                });

                                // Set hidden fields
                                document.getElementById('payment_intent_id').value = paymentIntent
                                    .id;
                                document.getElementById('payment_method_id').value = paymentMethod
                                    .id;
                                document.getElementById('payment_status').value = 'pending';

                                // Submit form
                                checkoutForm.submit();
                            } catch (error) {
                                console.error('PayMongo error:', error);
                                showToast('Payment failed. Please try again.', 'error');
                                placeOrderBtn.disabled = false;
                                placeOrderBtn.innerHTML =
                                    '<i class="fas fa-lock"></i>Place Order & Pay';
                            }
                        }
                    });
                }
            }

            // Reinitialize PayMongo when payment method changes
            paymentMethodSelect.addEventListener('change', function() {
                if (this.value === 'card') {
                    setTimeout(initializePayMongo, 100);
                }
            });

            // Trigger change event on page load to set initial state
            paymentMethodSelect.dispatchEvent(new Event('change'));
        }
    });

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
</script>
@endsection
