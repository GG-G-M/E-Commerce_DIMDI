@extends('layouts.app') 
@section('content')
    <style>
        :root {
            --primary-green: #2C8F0C;
            --dark-green: #1E6A08;
            --light-green: #E8F5E6;
            --accent-green: #4CAF50;
            --light-gray: #F8F9FA;
            --medium-gray: #E9ECEF;
            --dark-gray: #6C757D;
            --text-dark: #212529;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background-color: #fff;
            line-height: 1.6;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: white;
            padding: 6rem 0 5rem;
            margin-bottom: 3rem;
            border-radius: 0 0 30px 30px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000" opacity="0.05"><polygon fill="white" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }
        
        .hero-title {
            font-weight: 700;
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .btn-hero {
            background-color: white;
            color: var(--primary-green);
            border-radius: 30px;
            padding: 0.8rem 2.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            font-size: 1.1rem;
        }
        
        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        
        /* About Section */
        .about-section {
            padding: 5rem 0;
        }
        
        .section-title {
            font-weight: 700;
            margin-bottom: 3rem;
            position: relative;
            display: inline-block;
            font-size: 2.5rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 0;
            width: 80px;
            height: 4px;
            background-color: var(--primary-green);
            border-radius: 2px;
        }
        
        .about-text {
            font-size: 1.1rem;
            color: var(--dark-gray);
            margin-bottom: 2rem;
        }
        
        .about-feature {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        
        .about-feature-icon {
            background-color: var(--light-green);
            color: var(--primary-green);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
            font-size: 1.2rem;
        }
        
        /* Featured Products Section */
        .featured-section {
            padding: 5rem 0;
            background-color: var(--light-gray);
        }
        
        .product-card {
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            height: 100%;
            background: white;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .product-img-container {
            position: relative;
            overflow: hidden;
            height: 250px;
        }
        
        .product-img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .product-card:hover .product-img {
            transform: scale(1.05);
        }
        
        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--primary-green);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }
        
        .product-badge.sale {
            background-color: #e74c3c;
        }
        
        .product-body {
            padding: 1.8rem;
        }
        
        .product-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
            line-height: 1.4;
        }
        
        .product-description {
            color: var(--dark-gray);
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }
        
        .product-price {
            font-weight: 700;
            color: var(--primary-green);
            font-size: 1.4rem;
            margin-bottom: 0.5rem;
        }
        
        .product-old-price {
            text-decoration: line-through;
            color: var(--dark-gray);
            font-size: 1rem;
            margin-left: 0.5rem;
        }
        
        .btn-add-cart {
            background-color: var(--primary-green);
            color: white;
            border-radius: 30px;
            padding: 0.7rem 1.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            width: 100%;
            margin-top: 1rem;
        }
        
        .btn-add-cart:hover {
            background-color: var(--dark-green);
            transform: translateY(-2px);
        }
        
        /* Why Choose Us Section */
        .why-section {
            padding: 5rem 0;
            background: linear-gradient(to bottom, #ffffff 0%, var(--light-green) 100%);
        }
        
        .feature-card {
            text-align: center;
            padding: 2.5rem 1.5rem;
            border-radius: 15px;
            transition: all 0.3s ease;
            height: 100%;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-top: 4px solid var(--primary-green);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
        }
        
        .feature-title {
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        
        .feature-text {
            color: var(--dark-gray);
        }
        
        /* Testimonials Section */
        .testimonials-section {
            padding: 5rem 0;
            background-color: var(--light-green);
        }
        
        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            height: 100%;
            position: relative;
        }
        
        .testimonial-card::before {
            content: """;
            position: absolute;
            top: 20px;
            left: 25px;
            font-size: 4rem;
            color: var(--light-green);
            font-family: Georgia, serif;
            line-height: 1;
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 1rem;
        }
        
        .author-info h5 {
            margin: 0;
            font-weight: 600;
        }
        
        .author-info p {
            margin: 0;
            color: var(--dark-gray);
            font-size: 0.9rem;
        }
        
        /* Contact Section */
        .contact-section {
            padding: 5rem 0;
        }
        
        .contact-info {
            margin-bottom: 2rem;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        
        .contact-icon {
            background-color: var(--light-green);
            color: var(--primary-green);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
            font-size: 1.2rem;
        }
        
        /* Stats Section */
        .stats-section {
            padding: 4rem 0;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: white;
            text-align: center;
        }
        
        .stat-item {
            padding: 1.5rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .hero-title {
                font-size: 2.8rem;
            }
            
            .section-title {
                font-size: 2.2rem;
            }
        }
        
        @media (max-width: 767.98px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
        }
    </style>

    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="hero-title">Transform Your Living Space with DIMDI</h1>
                        <p class="hero-subtitle">Premium appliances and furniture designed to elevate your home experience with style, comfort, and innovation.</p>
                        <a href="{{ url('/products') }}" class="btn btn-hero">Shop Now <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                    <div class="col-lg-6 text-center">
                        <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Modern Living Room" class="img-fluid rounded-3 shadow-lg">
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="stat-number">15+</div>
                            <div class="stat-label">Years Experience</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="stat-number">10K+</div>
                            <div class="stat-label">Happy Customers</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Products</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="stat-number">25+</div>
                            <div class="stat-label">Store Locations</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <h2 class="section-title">About DIMDI Store</h2>
                        <p class="about-text">Since 2010, DIMDI has been transforming homes across the country with our carefully curated selection of premium appliances and furniture. We believe that your home should be a reflection of your personality and a sanctuary for your family.</p>
                        <p class="about-text">Our mission is to provide high-quality, durable products that combine innovative design with practical functionality, ensuring your home is both beautiful and comfortable.</p>
                        
                        <div class="about-feature">
                            <div class="about-feature-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div>
                                <h5>Quality Guaranteed</h5>
                                <p class="text-muted">All our products undergo rigorous quality checks and come with comprehensive warranties.</p>
                            </div>
                        </div>
                        
                        <div class="about-feature">
                            <div class="about-feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h5>Expert Team</h5>
                                <p class="text-muted">Our design consultants help you choose the perfect pieces for your space and style.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="About DIMDI" class="img-fluid rounded-3 shadow">
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="featured-section">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-12 text-center">
                        <h2 class="section-title">Featured Products</h2>
                        <p class="lead text-muted">Discover our handpicked selection of premium home solutions</p>
                    </div>
                </div>
                
                <div class="row">
                    @foreach($featuredProducts as $product)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card product-card h-100">
                            @if($product->has_discount)
                            <span class="discount-badge badge bg-danger">{{ $product->discount_percentage }}% OFF</span>
                            @endif
                            <img src="{{ $product->image_url }}" class="card-img-top product-image" alt="{{ $product->name }}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text text-muted small">{{ Str::limit($product->description, 60) }}</p>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        @if($product->has_discount)
                                        <span class="text-danger fw-bold">₱{{ $product->sale_price }}</span>
                                        <span class="text-muted text-decoration-line-through">₱{{ $product->price }}</span>
                                        @else
                                        <span class="text-primary fw-bold">₱{{ $product->price }}</span>
                                        @endif
                                    </div>
                                    
                                    @if($product->in_stock)
                                    <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        
                                        <!-- Variant Selection -->
                                        @if($product->has_variants && $product->variants->count() > 0)
                                        <div class="mb-2">
                                            <select name="selected_size" class="form-select form-select-sm" required>
                                                <option value="">Select Option</option>
                                                @foreach($product->variants as $variant)
                                                    @php
                                                        $variantName = $variant->size ?? $variant->variant_name ?? 'Option';
                                                        $variantPrice = $variant->current_price ?? $variant->price ?? $variant->sale_price ?? 0;
                                                        $variantStock = $variant->stock_quantity ?? 0;
                                                        $isInStock = $variantStock > 0;
                                                    @endphp
                                                    <option value="{{ $variantName }}" {{ !$isInStock ? 'disabled' : '' }}>
                                                        {{ $variantName }} 
                                                        @if(!$isInStock)
                                                        (Out of Stock)
                                                        @else
                                                        - ₱{{ number_format($variantPrice, 2) }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @else
                                        <input type="hidden" name="selected_size" value="Standard">
                                        @endif
                                        
                                        <button type="submit" class="btn btn-add-cart add-to-cart-btn">
                                            <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                        </button>
                                    </form>
                                    @else
                                    <button class="btn btn-secondary w-100" disabled>Out of Stock</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="row mt-5">
                    <div class="col-12 text-center">
                        <a href="{{ url('/products') }}" class="btn btn-outline-primary btn-lg" style="border-color: var(--primary-green); color: var(--primary-green);">View All Products <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="why-section">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-12 text-center">
                        <h2 class="section-title">Why Choose DIMDI</h2>
                        <p class="lead text-muted">With over 15 years of excellence in home solutions, we've built our reputation on quality and customer satisfaction</p>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4 class="feature-title">Extended Warranty Protection</h4>
                            <p class="feature-text">All our products come with industry-leading warranty coverage, giving you peace of mind for years to come.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <h4 class="feature-title">Free Delivery & Installation</h4>
                            <p class="feature-text">We offer complimentary delivery and professional installation services for all orders over $500.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h4 class="feature-title">24/7 Customer Support</h4>
                            <p class="feature-text">Our dedicated customer support team is available around the clock to assist with any questions or concerns.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <h4 class="feature-title">Premium Quality Products</h4>
                            <p class="feature-text">We carefully select each item in our collection for durability, functionality, and aesthetic appeal.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h4 class="feature-title">Nationwide Store Network</h4>
                            <p class="feature-text">With 25+ locations across the country, we're never far away when you need us.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-recycle"></i>
                            </div>
                            <h4 class="feature-title">Hassle-Free Returns</h4>
                            <p class="feature-text">Not completely satisfied? We offer straightforward returns within 30 days of purchase.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonials-section">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-12 text-center">
                        <h2 class="section-title">What Our Customers Say</h2>
                        <p class="lead text-muted">Hear from homeowners who transformed their spaces with DIMDI</p>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <p class="testimonial-text">"The quality of furniture from DIMDI exceeded my expectations. Our living room has never looked better, and the delivery team was professional and careful with installation."</p>
                            <div class="testimonial-author">
                                <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Sarah Johnson" class="author-avatar">
                                <div class="author-info">
                                    <h5>Sarah Johnson</h5>
                                    <p>Homeowner</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <p class="testimonial-text">"I was hesitant to buy appliances online, but DIMDI made the process seamless. The refrigerator and oven we purchased have been working perfectly for over a year now."</p>
                            <div class="testimonial-author">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Michael Chen" class="author-avatar">
                                <div class="author-info">
                                    <h5>Michael Chen</h5>
                                    <p>Interior Designer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <p class="testimonial-text">"The bedroom set we bought from DIMDI completely transformed our space. The quality is exceptional, and the customer service was outstanding throughout the process."</p>
                            <div class="testimonial-author">
                                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Emily Rodriguez" class="author-avatar">
                                <div class="author-info">
                                    <h5>Emily Rodriguez</h5>
                                    <p>Home Stager</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <h2 class="section-title">Visit Our Store</h2>
                        <p class="about-text mb-4">Come experience our products in person at our flagship showroom. Our design consultants are ready to help you create the home of your dreams.</p>
                        
                        <div class="contact-info">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h5>Our Location</h5>
                                    <p class="text-muted">123 Home Design District, Furniture Street, Cityville 12345</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <h5>Call Us</h5>
                                    <p class="text-muted">+1 (555) 123-4567</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h5>Email Us</h5>
                                    <p class="text-muted">info@dimdistore.com</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h5>Store Hours</h5>
                                    <p class="text-muted">Mon-Sat: 9AM-8PM, Sun: 11AM-6PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="bg-light p-4 rounded-3 h-100">
                            <h4 class="mb-4">Get In Touch</h4>
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Your Name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" placeholder="Your Email" required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" class="form-control" placeholder="Subject">
                                    </div>
                                    <div class="col-12">
                                        <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100" style="background-color: var(--primary-green); border-color: var(--primary-green); padding: 0.8rem;">Send Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart form handling
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('.add-to-cart-btn');
            const originalText = submitBtn.innerHTML;
            
            // Validate variant selection
            const sizeSelect = this.querySelector('select[name="selected_size"]');
            if (sizeSelect && !sizeSelect.value) {
                showToast('Please select an option before adding to cart.', 'warning');
                sizeSelect.focus();
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
            
            // Submit via AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('Product added to cart successfully! ', 'success');
                    if (data.cart_count !== undefined) {
                        updateCartCount(data.cart_count);
                    }
                } else {
                    showToast(data.message || 'Error adding product to cart.', 'error');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showToast('Unable to add product to cart. Please try again.', 'error');
            })
            .finally(() => {
                // Restore button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    });
    
    // Upper middle toast notification function
    function showToast(message, type = 'success') {
        // Remove existing toasts
        document.querySelectorAll('.upper-middle-toast').forEach(toast => toast.remove());
        
        const bgColors = {
            'success': '#2C8F0C',
            'error': '#dc3545',
            'warning': '#ffc107',
            'info': '#17a2b8'
        };
        
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-triangle',
            'warning': 'fa-exclamation-circle',
            'info': 'fa-info-circle'
        };
        
        const bgColor = bgColors[type] || bgColors.success;
        const icon = icons[type] || icons.success;
        const textColor = type === 'warning' ? 'text-dark' : 'text-white';
        
        const toast = document.createElement('div');
        toast.className = 'upper-middle-toast position-fixed start-50 translate-middle-x p-3';
        toast.style.cssText = `
            top: 100px;
            z-index: 9999;
            min-width: 300px;
            text-align: center;
        `;
        
        toast.innerHTML = `
            <div class="toast align-items-center border-0 show shadow-lg" role="alert" style="background-color: ${bgColor}; border-radius: 10px;">
                <div class="d-flex justify-content-center align-items-center p-3">
                    <div class="toast-body ${textColor} d-flex align-items-center">
                        <i class="fas ${icon} me-2 fs-5"></i>
                        <span class="fw-semibold">${message}</span>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                // Add fade out animation
                toast.style.transition = 'all 0.3s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50%) translateY(-20px)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }
        }, 3000);
    }
    
    // Update cart count
    function updateCartCount(count) {
        const cartCountElements = document.querySelectorAll('.cart-count, .cart-badge');
        cartCountElements.forEach(element => {
            element.textContent = count;
            element.style.display = count > 0 ? 'inline-block' : 'none';
        });
    }
});
</script>
@endsection