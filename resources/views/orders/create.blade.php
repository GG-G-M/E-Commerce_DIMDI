@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 fw-bold text-success" style="color: #2C8F0C;">Checkout</h2>

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

        <!-- Address Warning Alert -->
        @if (!$hasAddress || !$hasPhone)
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
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

        <!-- Delivery Address Section at the Top -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header text-white fw-bold d-flex justify-content-between align-items-center"
                style="background-color: #2C8F0C;">
                <span><i class="fas fa-map-marker-alt me-2"></i> Delivery Information</span>
                <a href="{{ route('profile.show') }}" class="btn btn-sm rounded-pill px-3 edit-btn-custom">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                            <p class="mb-1">
                                <i class="fas fa-envelope me-1 text-muted"></i>
                                <span class="text-dark">{{ $user->email }}</span>
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-phone me-1 text-muted"></i>
                                <span class="{{ $hasPhone ? 'text-dark' : 'text-danger' }}">
                                    {{ $user->phone ?? 'Phone number not set' }}
                                </span>
                            </p>
                        </div>

                        <div class="border-top pt-3">
                            <h6 class="fw-bold mb-2">Delivery Address:</h6>
                            <p class="mb-0 {{ $hasAddress ? 'text-dark' : 'text-danger' }}">
                                @if ($hasAddress)
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    {{ $fullAddress }}
                                @else
                                    <i class="fas fa-exclamation-circle text-danger me-1"></i>
                                    Address not set. Please update your profile.
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded">
                            <h6 class="fw-bold mb-3">Delivery Information Status:</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phone Number:</span>
                                <span class="text-{{ $hasPhone ? 'success' : 'danger' }} small fw-semibold">
                                    <i class="fas fa-{{ $hasPhone ? 'check-circle' : 'exclamation-circle' }} me-1"></i>
                                    {{-- {{ $hasPhone ? 'Set' : 'Missing' }} --}}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Address:</span>
                                <span class="text-{{ $hasAddress ? 'success' : 'danger' }} small fw-semibold">
                                    <i class="fas fa-{{ $hasAddress ? 'check-circle' : 'exclamation-circle' }} me-1"></i>
                                    {{-- {{ $hasAddress ? 'Complete' : 'Incomplete' }} --}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Only show checkout form if user has address and phone -->
        @if ($hasAddress && $hasPhone)
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header text-white fw-bold" style="background-color: #2C8F0C;">
                            <i class="fas fa-credit-card me-2"></i> Payment & Order Details
                        </div>
                        <div class="card-body">
                            <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                                @csrf

                                <!-- Required fields for order processing -->
                                <input type="hidden" name="shipping_address" value="{{ $fullAddress }}">
                                <input type="hidden" name="customer_phone" value="{{ $user->phone }}">
                                <input type="hidden" name="billing_address" value="{{ $fullAddress }}">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_method" class="form-label text-success fw-semibold">
                                            <i class="fas fa-money-check-alt me-1"></i> Payment Method *
                                        </label>
                                        <select class="form-select border-success" id="payment_method" name="payment_method"
                                            required>
                                            <option value="">Select Payment Method</option>
                                            <option value="card">Credit/Debit Card (PayMongo)</option>
                                            <option value="gcash">GCash (PayMongo)</option>
                                            <option value="grab_pay">GrabPay (PayMongo)</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- PayMongo Payment Section -->
                                <div id="paymongo-section" class="mb-4" style="display: none;">
                                    <div class="card border-success">
                                        <div class="card-header bg-success bg-opacity-10 text-success fw-bold">
                                            <i class="fas fa-credit-card me-2"></i> PayMongo Payment
                                        </div>
                                        <div class="card-body">
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
                                                    <p>You will be redirected to GCash to complete your payment after
                                                        placing
                                                        the order.</p>
                                                </div>
                                            </div>

                                            <!-- GrabPay Payment -->
                                            <div id="grabpay-payment" class="payment-method" style="display: none;">
                                                <div class="text-center">
                                                    <i class="fas fa-car fa-3x text-success mb-3"></i>
                                                    <p>You will be redirected to GrabPay to complete your payment after
                                                        placing
                                                        the order.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label text-success fw-semibold">
                                        <i class="fas fa-sticky-note me-1"></i> Order Notes (Optional)
                                    </label>
                                    <textarea class="form-control border-success" id="notes" name="notes" rows="3"
                                        placeholder="Any special instructions for delivery...">{{ old('notes') }}</textarea>
                                    <small class="text-muted">E.g., "Leave at gate", "Call before delivery", etc.</small>
                                </div>

                                <!-- Hidden fields for PayMongo -->
                                <input type="hidden" id="payment_intent_id" name="payment_intent_id">
                                <input type="hidden" id="payment_method_id" name="payment_method_id">
                                <input type="hidden" id="payment_status" name="payment_status" value="pending">
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header text-white fw-bold" style="background-color: #2C8F0C;">
                            <i class="fas fa-receipt me-2"></i> Order Summary
                        </div>
                        <div class="card-body">
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
                                
                                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold">{{ $product->name }}</h6>
                                        
                                        @if ($hasVariants && $variantName !== 'Standard')
                                            <small class="text-muted d-block mb-1">
                                                <i class="fas fa-tag me-1"></i>Variant: {{ $variantName }}
                                            </small>
                                        @endif
                                        
                                        <div class="mb-1">
                                            <small class="text-muted">Qty: {{ $item->quantity }} × </small>
                                            @if ($hasDiscount && $originalUnitPrice > $unitPrice)
                                                <span class="sale-price">₱{{ number_format($unitPrice, 2) }}</span>
                                                <small class="original-price ms-1">₱{{ number_format($originalUnitPrice, 2) }}</small>
                                                @if ($discountPercent > 0)
                                                    <span class="badge bg-danger ms-1">-{{ $discountPercent }}%</span>
                                                @endif
                                            @else
                                                <span class="text-success fw-semibold">₱{{ number_format($unitPrice, 2) }}</span>
                                            @endif
                                        </div>
                                        
                                        @if ($hasDiscount && $itemSavings > 0)
                                            <small class="savings-text">
                                                <i></i>You save ₱{{ number_format($itemSavings, 2) }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="text-end ms-3">
                                        @if ($hasDiscount && $itemOriginalTotalPrice > $itemTotalPrice)
                                            <div>
                                                <span class="text-success fw-bold">₱{{ number_format($itemTotalPrice, 2) }}</span>
                                                <div>
                                                    <small class="original-price">₱{{ number_format($itemOriginalTotalPrice, 2) }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-success fw-bold">₱{{ number_format($itemTotalPrice, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span class="text-success"
                                        id="display-subtotal">₱{{ number_format($subtotal, 2) }}</span>
                                </div>

                                <div class="d-flex justify-content-between mb-3">
                                    <span>Shipping:</span>
                                    <span class="text-success" id="display-shipping">₱100.00</span>
                                </div>
                                <small class="text-muted d-block mb-3" id="shipping-info">
                                    <i class="fas fa-truck me-1"></i>
                                    Calculating distance-based shipping fee from your address...
                                </small>

                                <hr class="my-3">
                                <div class="d-flex justify-content-between mb-3">
                                    <strong class="fs-5">Total:</strong>
                                    <strong class="text-success fs-5"
                                        id="display-total">₱{{ number_format($total, 2) }}</strong>
                                </div>

                                @if ($subtotal < 100)
                                    <div class="alert alert-info py-2">
                                        <small>
                                            <i class="fas fa-info-circle me-1"></i>
                                            Add ₱{{ number_format(100 - $subtotal, 2) }} more for free shipping!
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <button type="submit" class="btn w-100 btn-lg text-white" style="background-color: #2C8F0C;"
                                id="place-order-btn">
                                <i class="fas fa-lock me-2"></i>Place Order & Pay
                            </button>

                            <a href="{{ route('cart.index') }}" class="btn btn-outline-success w-100 mt-2">
                                <i class="fas fa-arrow-left me-2"></i>Back to Cart
                            </a>

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
            </form>
        @else
            <!-- Show disabled checkout section if info is missing -->
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted mb-3">Checkout Unavailable</h4>
                    <p class="text-muted mb-4">Please complete your profile information to proceed with checkout.</p>
                    <a href="{{ route('profile.show') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-user-edit me-2"></i>Complete Your Profile
                    </a>
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-lg ms-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to Cart
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

            // Update display elements
            document.getElementById('display-shipping').textContent = '₱' + shippingFee.toFixed(2);
            document.getElementById('display-total').textContent = '₱' + total.toFixed(2);

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
                        '<i class="fas fa-spinner fa-spin me-2"></i>Processing Payment...';

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
                                        '<i class="fas fa-lock me-2"></i>Place Order & Pay';
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

    <style>
        .form-control:focus,
        .form-select:focus {
            border-color: #2C8F0C;
            box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.25);
        }

        .btn-outline-success:hover {
            background-color: #2C8F0C;
            color: #fff !important;
        }

        .payment-method {
            min-height: 100px;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
        }

        .alert-danger {
            border-left: 4px solid #dc3545;
        }

        .border-success {
            border-color: #2C8F0C !important;
        }

        /* Discount styling for original price */
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
            color: #000000;
            font-weight: 100;
        }

        .edit-btn-custom {
            color: white;
            border: 1.5px solid white;
            background: transparent;
            transition: all 0.25s ease;
        }

        .edit-btn-custom i {
            color: white;
            transition: all 0.25s ease;
        }

        .edit-btn-custom:hover {
            background: white;
            color: #333;
        }

        .edit-btn-custom:hover i {
            color: #333;
        }
    </style>
@endsection
