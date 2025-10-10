@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-5">
                    <div class="text-danger mb-4">
                        <i class="fas fa-times-circle fa-5x"></i>
                    </div>
                    <h2 class="text-danger mb-3">Payment Failed</h2>
                    <p class="text-muted mb-4">We couldn't process your payment. Please try again or use a different payment method.</p>
                    
                    @if(isset($payment_id))
                    <p class="mb-3">Payment ID: <strong>{{ $payment_id }}</strong></p>
                    @endif
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('cart.index') }}" class="btn btn-success me-md-2">
                            <i class="fas fa-shopping-cart me-2"></i>Back to Cart
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-success">
                            <i class="fas fa-home me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
