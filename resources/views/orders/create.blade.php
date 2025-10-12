@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold text-success">Checkout</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header text-white fw-bold" style="background-color: #2C8F0C;">
                    <i class="fas fa-user me-2"></i> Customer Information
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST" id="order-form">
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
                                <label for="customer_phone" class="form-label text-success fw-semibold">Phone Number *</label>
                                <input type="text" class="form-control border-success" id="customer_phone" name="customer_phone" 
                                       value="{{ old('customer_phone', $user->phone) }}" required>
                                <small class="form-text text-muted">We'll contact you about your order</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label text-success fw-semibold">Payment Method *</label>
                                <select class="form-select border-success" id="payment_method" name="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label text-success fw-semibold">Shipping Address *</label>
                            <textarea class="form-control border-success" id="shipping_address" name="shipping_address" 
                                      rows="3" required placeholder="Enter your complete shipping address">{{ old('shipping_address', $user->address) }}</textarea>
                            <small class="form-text text-muted">We'll deliver to this address</small>
                        </div>

                        <div class="mb-3">
                            <label for="billing_address" class="form-label text-success fw-semibold">Billing Address *</label>
                            <textarea class="form-control border-success" id="billing_address" name="billing_address" 
                                      rows="3" required placeholder="Enter your complete billing address">{{ old('billing_address', $user->address) }}</textarea>
                            <div class="form-text">Your billing address for payment verification</div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label text-success fw-semibold">Order Notes (Optional)</label>
                            <textarea class="form-control border-success" id="notes" name="notes" 
                                      rows="3" placeholder="Any special instructions or notes for your order...">{{ old('notes') }}</textarea>
                        </div>
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
                    <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            <small class="text-muted d-block">Qty: {{ $item->quantity }} × ${{ number_format($item->product->current_price, 2) }}</small>
                            @if($item->selected_size && $item->selected_size !== 'Standard')
                            <small class="text-muted">Variant: {{ $item->selected_size }}</small>
                            @endif
                        </div>
                        <span class="text-success fw-bold">${{ number_format($item->total_price, 2) }}</span>
                    </div>
                    @endforeach
                    
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span class="text-success">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span class="text-success">${{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <span class="text-success">{{ $shipping == 0 ? 'FREE' : '$' . number_format($shipping, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong class="fs-5">Total:</strong>
                        <strong class="text-success fs-5">${{ number_format($total, 2) }}</strong>
                    </div>
                    
                    <button type="submit" class="btn w-100 btn-lg text-white place-order-btn" style="background-color: #2C8F0C;">
                        <i class="fas fa-lock me-2"></i>Place Order
                    </button>
                    </form>
                    
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-success w-100 mt-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('order-form');
    const submitBtn = form.querySelector('.place-order-btn');
    
    form.addEventListener('submit', function(e) {
        // Basic validation
        const phone = document.getElementById('customer_phone').value.trim();
        const paymentMethod = document.getElementById('payment_method').value;
        const shippingAddress = document.getElementById('shipping_address').value.trim();
        const billingAddress = document.getElementById('billing_address').value.trim();
        
        if (!phone || !paymentMethod || !shippingAddress || !billingAddress) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
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
.place-order-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>
@endsection