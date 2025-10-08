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
                    <form action="{{ route('orders.store') }}" method="POST">
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
                                    <option value="card">Credit</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                </select>
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
                    
                    <button type="submit" class="btn w-100 btn-lg text-white" style="background-color: #2C8F0C;">
                        Place Order
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
</style>
@endsection
