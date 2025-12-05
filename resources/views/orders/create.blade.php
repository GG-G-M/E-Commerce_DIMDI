@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4 fw-bold text-success" style="color: #2C8F0C;">Checkout</h1>

        <!-- Delivery Address Section at the Top -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header text-white fw-bold d-flex justify-content-between align-items-center" style="background-color: #2C8F0C;">
                <span><i class="fas fa-map-marker-alt me-2"></i> Delivery Address</span>
                <a href="{{ route('profile.show') }}" class="btn btn-sm btn-light">Change</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                        <p class="mb-1"><strong>{{ $user->phone }}</strong></p>
                        <p class="mb-0 text-muted">
                            {{ $user->address }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

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
                            <input type="hidden" name="shipping_address" value="{{ $user->address }}">
                            <input type="hidden" name="customer_phone" value="{{ $user->phone }}">
                            <input type="hidden" name="billing_address" value="{{ $user->address }}">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="payment_method" class="form-label text-success fw-semibold">Payment Method *</label>
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
                                <label for="notes" class="form-label text-success fw-semibold">Order Notes</label>
                                <textarea class="form-control border-success" id="notes" name="notes" rows="3"
                                    placeholder="Any special instructions...">{{ old('notes') }}</textarea>
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
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                    <small class="text-muted">Qty: {{ $item->quantity }} ×
                                        ₱{{ number_format($item->unit_price, 2) }}</small>
                                    @if ($item->selected_size)
                                        <br>
                                        <small class="text-muted">Variant: {{ $item->selected_size }}</small>
                                    @endif
                                </div>
                                <span class="text-success">₱{{ number_format($item->total_price, 2) }}</span>
                            </div>
                        @endforeach

                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span class="text-success">₱{{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping:</span>
                            <span class="text-success">₱{{ number_format($shipping, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="text-success">₱{{ number_format($subtotal + $shipping, 2) }}</strong>
                        </div>

                        <button type="submit" class="btn w-100 btn-lg text-white" style="background-color: #2C8F0C;"
                            id="place-order-btn">
                            <i class="fas fa-lock me-2"></i>Place Order
                        </button>

                        <a href="{{ route('cart.index') }}" class="btn btn-outline-success w-100 mt-2">
                            Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>

    <!-- PayMongo Script -->
    <script src="https://js.paymongo.com/v1/paymongo.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkoutForm = document.getElementById('checkout-form');
            const placeOrderBtn = document.getElementById('place-order-btn');
            const paymentMethodSelect = document.getElementById('payment_method');

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
                placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

                // Form will submit normally
            });

            // Payment method change handler
            paymentMethodSelect.addEventListener('change', function() {
                const method = this.value;
                const paymongoSection = document.getElementById('paymongo-section');

                if (method === 'card' || method === 'gcash' || method === 'grab_pay') {
                    paymongoSection.style.display = 'block';

                    // Show/hide specific payment methods
                    document.getElementById('card-payment').style.display = method === 'card' ? 'block' :
                        'none';
                    document.getElementById('gcash-payment').style.display = method === 'gcash' ? 'block' :
                        'none';
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
                                placeOrderBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Place Order';
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
    </style>
@endsection