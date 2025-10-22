@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="text-danger mb-4">
                        <i class="fas fa-times-circle" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="text-danger mb-3">Payment Failed</h2>
                    <p class="text-muted mb-4">We're sorry, but your payment could not be processed. Please try again.</p>
                    <div class="d-grid gap-2 d-md-block">
                        <a href="{{ route('cart.index') }}" class="btn btn-success px-4">
                            <i class="fas fa-shopping-cart me-2"></i>Back to Cart
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-success px-4">
                            <i class="fas fa-home me-2"></i>Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection