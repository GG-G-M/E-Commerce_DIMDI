@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4 fw-bold text-success" style="color: #2C8F0C;">Checkout</h1>

        @php
            // Build address from separate fields
            $addressParts = [];
            if ($user->street_address) $addressParts[] = $user->street_address;
            if ($user->barangay) $addressParts[] = $user->barangay;
            if ($user->city) $addressParts[] = $user->city;
            if ($user->province) $addressParts[] = $user->province;
            if ($user->region) $addressParts[] = $user->region;
            if ($user->country) $addressParts[] = $user->country;
            $fullAddress = !empty($addressParts) ? implode(', ', $addressParts) : '';
            $hasAddress = !empty($fullAddress);
            $hasPhone = !empty($user->phone);
            
            // Calculate totals
            $subtotal = $cartItems->sum('total_price');
            $shipping = $subtotal >= 100 ? 0 : 10;
            $total = $subtotal + $shipping;
        @endphp

        <!-- Address Warning Alert -->
        @if(!$hasAddress || !$hasPhone)
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Profile Information Required</h5>
                        <p class="mb-2">Please complete your profile before placing an order:</p>
                        <ul class="mb-1">
                            @if(!$hasAddress)
                                <li><strong>Delivery Address:</strong> Required for shipping</li>
                            @endif
                            @if(!$hasPhone)
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
            <div class="card-header text-white fw-bold d-flex justify-content-between align-items-center" style="background-color: #2C8F0C;">
                <span><i class="fas fa-map-marker-alt me-2"></i> Delivery Information</span>
                <a href="{{ route('profile.show') }}" class="btn btn-sm btn-light">
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
                                @if($hasAddress)
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
                                <span class="badge bg-{{ $hasPhone ? 'success' : 'danger' }}">
                                    {{ $hasPhone ? '✓ Set' : '✗ Missing' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Address:</span>
                                <span class="badge bg-{{ $hasAddress ? 'success' : 'danger' }}">
                                    {{ $hasAddress ? '✓ Complete' : '✗ Incomplete' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Only show checkout form if user has address and phone -->
        @if($hasAddress && $hasPhone)
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
                                        <select class="form-select border-success" id="payment_method" name="payment_method" required>
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
                                                    <p>You will be redirected to GCash to complete your payment after placing
                                                        the order.</p>
                                                </div>
                                            </div>

                                            <!-- GrabPay Payment -->
                                            <div id="grabpay-payment" class="payment-method" style="display: none;">
                                                <div class="text-center">
                                                    <i class="fas fa-car fa-3x text-success mb-3"></i>
                                                    <p>You will be redirected to GrabPay to complete your payment after placing
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
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $item->product->name }}</h6>
                                        <small class="text-muted">Qty: {{ $item->quantity }} ×
                                            ₱{{ number_format($item->unit_price, 2) }}</small>
                                        @if ($item->selected_size)
                                            <br>
                                            <small class="text-muted">Variant: {{ $item->selected_size }}</small>
                                        @endif
                                    </div>
                                    <span class="text-success fw-semibold">₱{{ number_format($item->total_price, 2) }}</span>
                                </div>
                            @endforeach

                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span class="text-success">₱{{ number_format($subtotal, 2) }}</span>
                                </div>

                                <div class="d-flex justify-content-between mb-3">
                                    <span>Shipping:</span>
                                    <span class="text-success">
                                        @if ($subtotal >= 100)
                                            <span class="text-success">FREE</span>
                                        @else
                                            ₱{{ number_format($shipping, 2) }}
                                        @endif
                                    </span>
                                </div>
                                
                                <!-- REMOVED TAX SECTION -->
                                
                                <hr class="my-3">
                                <div class="d-flex justify-content-between mb-3">
                                    <strong class="fs-5">Total:</strong>
                                    <strong class="text-success fs-5">₱{{ number_format($total, 2) }}</strong>
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
        document.addEventListener('DOMContentLoaded', function() {
            const checkoutForm = document.getElementById('checkout-form');
            const placeOrderBtn = document.getElementById('place-order-btn');
            const paymentMethodSelect = document.getElementById('payment_method');

            if (checkoutForm) {
                // Validate form before submission
                checkoutForm.addEventListener('submit', function(e) {
                    const paymentMethod = paymentMethodSelect.value;

                    if (!paymentMethod) {
                        e.preventDefault();
                        alert('Please select a payment method.');
                        return;
                    }

                    // Show loading state
                    placeOrderBtn.disabled = true;
                    placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing Payment...';

                    // Form will submit normally for non-card payments
                });

                // Payment method change handler
                paymentMethodSelect.addEventListener('change', function() {
                    const method = this.value;
                    const paymongoSection = document.getElementById('paymongo-section');

                    if (method === 'card' || method === 'gcash' || method === 'grab_pay') {
                        paymongoSection.style.display = 'block';

                        // Show/hide specific payment methods
                        document.getElementById('card-payment').style.display = method === 'card' ? 'block' : 'none';
                        document.getElementById('gcash-payment').style.display = method === 'gcash' ? 'block' : 'none';
                        document.getElementById('grabpay-payment').style.display = method === 'grab_pay' ? 'block' : 'none';
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
                                    const { paymentIntent, paymentMethod } = await paymongo.createPaymentMethodFromCard(card, {
                                        billing: {
                                            name: '{{ $user->name }}',
                                            email: '{{ $user->email }}',
                                            phone: '{{ $user->phone }}'
                                        }
                                    });
                                    
                                    // Set hidden fields
                                    document.getElementById('payment_intent_id').value = paymentIntent.id;
                                    document.getElementById('payment_method_id').value = paymentMethod.id;
                                    document.getElementById('payment_status').value = 'pending';
                                    
                                    // Submit form
                                    checkoutForm.submit();
                                } catch (error) {
                                    console.error('PayMongo error:', error);
                                    alert('Payment failed. Please try again.');
                                    placeOrderBtn.disabled = false;
                                    placeOrderBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Place Order & Pay';
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
@endsection