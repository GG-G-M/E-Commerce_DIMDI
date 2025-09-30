@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Shopping Cart</h1>

    @if($cartItems->count() > 0)
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="row align-items-center mb-4 pb-4 border-bottom">
                        <div class="col-md-2">
                            <img src="{{ $item->product->image ?: 'https://via.placeholder.com/100x100' }}" 
                                 alt="{{ $item->product->name }}" class="img-fluid rounded">
                        </div>
                        <div class="col-md-4">
                            <h5 class="mb-1">{{ $item->product->name }}</h5>
                            <p class="text-muted mb-0">${{ $item->product->current_price }}</p>
                        </div>
                        <div class="col-md-3">
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                       min="1" max="{{ $item->product->stock_quantity }}" 
                                       class="form-control form-control-sm" style="width: 80px;">
                                <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <strong>${{ number_format($item->total_price, 2) }}</strong>
                        </div>
                        <div class="col-md-1">
                            <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
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
                    <a href="{{ route('orders.create') }}" class="btn btn-primary w-100 btn-lg">
                        Proceed to Checkout
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
        <h3>Your cart is empty</h3>
        <p class="text-muted mb-4">Start shopping to add items to your cart</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Start Shopping</a>
    </div>
    @endif
</div>
@endsection