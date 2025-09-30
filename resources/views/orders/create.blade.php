@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Checkout</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        
                        <!-- Pre-filled user information -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Your profile information will be used for this order. 
                            <a href="{{ route('profile.show') }}" class="alert-link">Update profile</a> if needed.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                                <small class="form-text text-muted">From your profile</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                                <small class="form-text text-muted">From your profile</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" value="{{ $user->phone }}" readonly>
                                <small class="form-text text-muted">From your profile</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method *</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="cash">Cash on Delivery</option>
                                    <option value="card">Credit Card</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Shipping Address *</label>
                            <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address', $user->address) }}</textarea>
                            <small class="form-text text-muted">We'll deliver to this address</small>
                        </div>

                        <div class="mb-3">
                            <label for="billing_address" class="form-label">Billing Address</label>
                            <textarea class="form-control" id="billing_address" name="billing_address" rows="3">{{ old('billing_address', $user->address) }}</textarea>
                            <div class="form-text">Leave blank if same as shipping address</div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                        </div>
                </div>
            </div>
        </div>                      

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                            <small class="text-muted">Qty: {{ $item->quantity }} × ${{ $item->product->current_price }}</small>
                        </div>
                        <span>${{ number_format($item->total_price, 2) }}</span>
                    </div>
                    @endforeach
                    
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>${{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <span>${{ number_format($shipping, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 btn-lg">Place Order</button>
                    </form>
                    
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection