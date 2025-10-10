@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-success fw-bold" style="color: #2C8F0C;">Shopping Cart</h1>

    @if($cartItems->count() > 0)
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header text-white fw-bold" style="background-color: #2C8F0C;">
                    <i class="fas fa-shopping-cart me-2"></i> Your Cart Items
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="row align-items-center mb-4 pb-4 border-bottom">
                        <div class="col-md-2">
                            <img src="{{ $item->product->image_url }}" 
                                 alt="{{ $item->product->name }}" class="img-fluid rounded" style="height: 80px; object-fit: cover;">
                        </div>
                        <div class="col-md-3">
                            <h5 class="mb-1">{{ $item->product->name }}</h5>
                            <p class="text-muted mb-0">${{ $item->product->current_price }}</p>
                        </div>
                        <div class="col-md-3">
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-2" id="cart-form-{{ $item->id }}">
                                @csrf
                                @method('PUT')
                                
                                <!-- Size Selection Dropdown -->
                                <div class="me-2">
                                    <label class="form-label small mb-1 text-success fw-semibold">Size:</label>
                                    <select name="selected_size" class="form-select form-select-sm auto-submit border-success">
                                        @foreach($item->product->available_sizes as $size)
                                        <option value="{{ $size }}" {{ $item->selected_size == $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Quantity Input -->
                                <div>
                                    <label class="form-label small mb-1 text-success fw-semibold">Qty:</label>
                                    <div class="quantity-control" style="width: 100px;">
                                        <div class="input-group input-group-sm">
                                            <button class="btn btn-outline-success quantity-btn" type="button" data-action="decrease">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                min="1" max="{{ $item->product->stock_quantity }}" 
                                                class="form-control text-center auto-submit border-success" readonly
                                                style="border-left: 0; border-right: 0;">
                                            <button class="btn btn-outline-success quantity-btn" type="button" data-action="increase">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Loading Spinner -->
                                <div class="mt-3 loading-spinner" style="display: none;">
                                    <div class="spinner-border spinner-border-sm text-success" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <strong class="text-success">$<span id="item-total-{{ $item->id }}">{{ number_format($item->total_price, 2) }}</span></strong>
                        </div>
                        <div class="col-md-2">
                            <form action="{{ route('cart.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header text-white fw-bold" style="background-color: #2C8F0C;">
                    <i class="fas fa-receipt me-2"></i> Order Summary
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span class="text-success">$<span id="summary-subtotal">{{ number_format($subtotal, 2) }}</span></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span class="text-success">$<span id="summary-tax">{{ number_format($tax, 2) }}</span></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <span class="text-success">$<span id="summary-shipping">{{ number_format($shipping, 2) }}</span></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="text-success">$<span id="summary-total">{{ number_format($total, 2) }}</span></strong>
                    </div>
                    <a href="{{ route('orders.create') }}" class="btn w-100 btn-lg text-white" style="background-color: #2C8F0C;">
                        Proceed to Checkout
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-success w-100 mt-2">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
        <h3 class="text-success">Your cart is empty</h3>
        <p class="text-muted mb-4">Start shopping to add items to your cart</p>
        <a href="{{ route('products.index') }}" class="btn text-white btn-lg" style="background-color: #2C8F0C;">Start Shopping</a>
    </div>
    @endif
</div>

@push('scripts')
<script>
// Handle custom quantity buttons
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodSelect = document.getElementById('payment_method');
    const paymongoSection = document.getElementById('paymongo-section');
    const paymentMethods = document.querySelectorAll('.payment-method');
    const placeOrderBtn = document.getElementById('place-order-btn');
    const checkoutForm = document.getElementById('checkout-form');
    
    let cardForm;
    let paymentIntentId;

    // Show/hide PayMongo section based on payment method
    function updatePaymentDisplay() {
        const selectedMethod = paymentMethodSelect.value;
        
        // Hide all payment methods first
        paymentMethods.forEach(method => method.style.display = 'none');
        paymongoSection.style.display = 'none';
        
        // Show PayMongo section for PayMongo payment methods
        if (['card', 'gcash', 'grab_pay'].includes(selectedMethod)) {
            paymongoSection.style.display = 'block';
            
            if (selectedMethod === 'card') {
                document.getElementById('card-payment').style.display = 'block';
                initializeCardPayment();
            } else if (selectedMethod === 'gcash') {
                document.getElementById('gcash-payment').style.display = 'block';
            } else if (selectedMethod === 'grab_pay') {
                document.getElementById('grabpay-payment').style.display = 'block';
            }
        }
    }

    // Initialize card payment
    async function initializeCardPayment() {
        try {
            if (!paymentIntentId) {
                await createPaymentIntent();
            }
            
            if (paymentIntentId && !cardForm) {
                cardForm = paymongo.elements.create('cardForm', {
                    intent: {
                        id: paymentIntentId,
                        clientKey: '{{ config("services.paymongo.public_key") }}'
                    },
                    style: {
                        base: {
                            fontSize: '16px',
                            color: '#2C8F0C',
                            '::placeholder': {
                                color: '#aab7c4'
                            }
                        }
                    }
                });

                cardForm.mount('#paymongo-card-form');
            }
        } catch (error) {
            console.error('Error initializing card payment:', error);
        }
    }

    // Create payment intent via AJAX
    async function createPaymentIntent() {
        try {
            const response = await fetch('{{ route("payment.create-intent") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    amount: Math.round({{ $total }} * 100), // Convert to cents
                    currency: 'PHP'
                })
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.error || 'Failed to create payment intent');
            }

            if (!data.intent || !data.intent.id) {
                throw new Error('Invalid payment intent response');
            }

            paymentIntentId = data.intent.id;
            document.getElementById('payment_intent_id').value = paymentIntentId;
            
            return data.intent;
            
        } catch (error) {
            console.error('Payment intent creation failed:', error);
            alert('Failed to initialize payment: ' + error.message);
            throw error;
        }
    }

    // Create source for e-wallet payments
    async function createSource(type) {
        try {
            const response = await fetch('{{ route("payment.create-source") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    type: type,
                    amount: Math.round({{ $total }} * 100),
                    currency: 'PHP'
                })
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.error || 'Failed to create payment source');
            }

            if (!data.source || !data.source.id) {
                throw new Error('Invalid payment source response');
            }

            return data;
            
        } catch (error) {
            console.error('Source creation failed:', error);
            throw error;
        }
    }

    // Handle payment method change
    paymentMethodSelect.addEventListener('change', updatePaymentDisplay);

    // Initialize on page load
    updatePaymentDisplay();

    // Handle form submission
    checkoutForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const paymentMethod = paymentMethodSelect.value;
        placeOrderBtn.disabled = true;
        placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

        try {
            // For PayMongo payments
            if (['card', 'gcash', 'grab_pay'].includes(paymentMethod)) {
                
                if (!paymentIntentId) {
                    await createPaymentIntent();
                }

                if (paymentMethod === 'card') {
                    // Handle card payment
                    const result = await cardForm.confirmPayment();
                    
                    if (result.error) {
                        throw new Error(result.error.message);
                    }

                    document.getElementById('payment_method_id').value = result.paymentIntent.id;
                    document.getElementById('payment_status').value = result.paymentIntent.status;
                    
                } else {
                    // Handle e-wallet payments (GCash, GrabPay)
                    const sourceResponse = await createSource(paymentMethod);
                    document.getElementById('payment_method_id').value = sourceResponse.source.id;
                    
                    // Redirect to payment page
                    if (sourceResponse.source.attributes.redirect && 
                        sourceResponse.source.attributes.redirect.checkout_url) {
                        window.location.href = sourceResponse.source.attributes.redirect.checkout_url;
                        return; // Stop further execution
                    }
                }
            }

            // For bank transfer or if redirection didn't happen
            checkoutForm.submit();

        } catch (error) {
            console.error('Payment error:', error);
            alert('Payment failed: ' + error.message);
            placeOrderBtn.disabled = false;
            placeOrderBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Place Order';
        }
    });
});
<style>
.auto-submit:focus {
    border-color: #2C8F0C;
    box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.25);
}

.loading-spinner {
    margin-left: 10px;
}

.flash-message {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

input[type="number"] {
    -moz-appearance: textfield;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
@endpush
@endsection
