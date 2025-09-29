@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">Welcome to DIMDI Store</h1>
                <p class="lead">Discover amazing products at great prices. Shop now and experience the best online shopping!</p>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Shop Now</a>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/600x400" alt="Hero Image" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Featured Products</h2>
        <div class="row">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card product-card h-100 shadow">
                    @if($product->has_discount)
                    <span class="discount-badge badge bg-danger">{{ $product->discount_percentage }}% OFF</span>
                    @endif
                    <img src="{{ $product->image ?: 'https://via.placeholder.com/300x200' }}" class="card-img-top product-image" alt="{{ $product->name }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($product->description, 60) }}</p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                @if($product->has_discount)
                                <span class="text-danger fw-bold">${{ $product->sale_price }}</span>
                                <span class="text-muted text-decoration-line-through">${{ $product->price }}</span>
                                @else
                                <span class="text-primary fw-bold">${{ $product->price }}</span>
                                @endif
                            </div>
                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Shop by Category</h2>
        <div class="row">
            @foreach($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text">{{ $category->products->count() }} products</p>
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="btn btn-outline-primary">Browse</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection