@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <img src="{{ $product->image_url }}" class="img-fluid rounded" alt="{{ $product->name }}">
        </div>
        <div class="col-lg-6">
            <h1 class="h2">{{ $product->name }}</h1>
            <p class="text-muted">Category: {{ $product->category->name }}</p>
            
            <div class="mb-3">
                @if($product->has_discount)
                <span class="h3 text-danger me-2">${{ $product->sale_price }}</span>
                <span class="h5 text-muted text-decoration-line-through">${{ $product->price }}</span>
                <span class="badge bg-danger ms-2">{{ $product->discount_percentage }}% OFF</span>
                @else
                <span class="h3 text-primary">${{ $product->price }}</span>
                @endif
            </div>

            <p class="mb-4">{{ $product->description }}</p>

            <div class="mb-4">
                <span class="badge bg-{{ $product->stock_quantity > 0 ? 'success' : 'danger' }}">
                    {{ $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                </span>
                <small class="text-muted ms-2">{{ $product->stock_quantity }} units available</small>
            </div>

            @if($product->stock_quantity > 0)
            <form action="{{ route('cart.store') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="quantity" class="col-form-label">Quantity:</label>
                    </div>
                    <div class="col-auto">
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $product->stock_quantity }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-cart-plus me-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
            </form>
            @else
            <button class="btn btn-secondary btn-lg" disabled>Out of Stock</button>
            @endif

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Product Details</h6>
                    <ul class="list-unstyled">
                        <li><strong>SKU:</strong> {{ $product->sku }}</li>
                        <li><strong>Category:</strong> {{ $product->category->name }}</li>
                        <li><strong>Availability:</strong> {{ $product->stock_quantity }} in stock</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="mt-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card product-card h-100">
                    <img src="{{ $relatedProduct->image_url }}" class="card-img-top product-image" alt="{{ $relatedProduct->name }}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-primary fw-bold">${{ $relatedProduct->current_price }}</span>
                            </div>
                            <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-outline-primary btn-sm w-100">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection