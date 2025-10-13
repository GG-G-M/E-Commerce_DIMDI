@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold text-success" style="color: #2C8F0C;">Checkout</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header text-white fw-bold" style="background-color: #2C8F0C;">
                    <i class="fas fa-user me-2"></i> Customer Information
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                        @csrf
                        
                        <!-- Info Notice -->
                        <div class="alert alert-success bg-opacity-10 border-success text-success">
                            <i class="fas fa-info-circle me-2"></i>
                            Your profile information will be used for this order. 
                            <a href="{{ route('profile.show') }}" class="alert-link text-decoration-underline">Update profile</a> if needed.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-success fw-semibold">Full Name</label>
                                <input type="text" class="form-control border-success" value="{{ $user->name }}" readonly>
                                <small class="form-text text-muted">From your profile</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-success fw-semibold">Email</label>
                                <input type="email" class="form-control border-success" value="{{ $user->email }}" readonly>
                                <small class="form-text text-muted">From your profile</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-success fw-semibold">Phone</label>
                                <input type="text" class="form-control border-success" value="{{ $user->phone }}" readonly>
                                <small class="form-text text-muted">From your profile</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label text-success fw-semibold">Payment Method *</label>
                                <select class="form-select border-success" id="payment_method" name="payment_method" required>
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
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label text-success fw-semibold">Shipping Address *</label>
                            <textarea class="form-control border-success" id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address', $user->address) }}</textarea>
                            <small class="form-text text-muted">We'll deliver to this address</small>
                        </div>

                        <div class="mb-3">
                            <label for="billing_address" class="form-label text-success fw-semibold">Billing Address</label>
                            <textarea class="form-control border-success" id="billing_address" name="billing_address" rows="3">{{ old('billing_address', $user->address) }}</textarea>
                            <div class="form-text">Leave blank if same as shipping address</div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label text-success fw-semibold">Order Notes</label>
                            <textarea class="form-control border-success" id="notes" name="notes" rows="3" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
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
                    @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                            <small class="text-muted">Qty: {{ $item->quantity }} × ${{ $item->product->current_price }}</small>
                            @if($item->selected_size)
                            <br>
                            <small class="text-muted">Size: {{ $item->selected_size }}</small>
                            @endif
                        </div>
                        <span class="text-success">${{ number_format($item->total_price, 2) }}</span>
                    </div>
                    @endforeach
                    
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span class="text-success">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span class="text-success">${{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <span class="text-success">${{ number_format($shipping, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="text-success">${{ number_format($total, 2) }}</strong>
                    </div>
                    
                    <button type="submit" class="btn w-100 btn-lg text-white" style="background-color: #2C8F0C;" id="place-order-btn">
                        <i class="fas fa-lock me-2"></i>Place Order
                    </button>
                    </form>
                    
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-success w-100 mt-2">
                        Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PayMongo Script -->
<script src="https://js.paymongo.com/v1/paymongo.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    const placeOrderBtn = document.getElementById('place-order-btn');
    
    checkoutForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const paymentMethod = document.getElementById('payment_method').value;
        
        // Show loading state
        placeOrderBtn.disabled = true;
        placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        
        try {
            // Submit the form normally for all payment methods
            // The backend will handle the redirects for GCash/GrabPay
            checkoutForm.submit();
            
        } catch (error) {
            console.error('Error:', error);
            alert('Error: ' + error.message);
            
            // Reset button
            placeOrderBtn.disabled = false;
            placeOrderBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Place Order';
        }
    });
});
</script>

<style>
.form-control:focus, .form-select:focus {
    border-color: #2C8F0C;
    box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.25);
}
.alert-success a {
    color: #2C8F0C;
    font-weight: 600;
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