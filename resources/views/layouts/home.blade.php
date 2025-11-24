<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce DIMDI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        .navbar {
            background-color: #2C8F0C !important;
            padding: 0.6rem 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        body {
            padding-top: 70px;
        }

        .navbar-brand,
        .nav-link {
            color: #fff !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #dfffd6 !important;
        }

        .logo-img {
            height: 40px;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            margin-right: 1.5rem;
        }

        .search-container {
            position: relative;
            max-width: 350px;
            margin: 0 1rem;
            flex: 1;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            z-index: 2;
            font-size: 0.9rem;
        }

        .search-input {
            border-radius: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.4rem 1rem 0.4rem 2.5rem;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 0.9rem;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .search-input:focus {
            border-color: rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.15);
        }

        /* Search results styling */
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
            max-height: 300px;
            overflow-y: auto;
        }

        .search-result-item {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .search-result-item:hover {
            background-color: #f8f9fa;
        }

        .result-type {
            font-size: 0.7rem;
            color: #6c757d;
            text-transform: uppercase;
        }

        .result-name {
            font-weight: 500;
            margin-bottom: 2px;
            color: #212529;
        }

        .no-results {
            padding: 15px;
            text-align: center;
            color: #6c757d;
        }

        /* Updated nav styling - text only */
        .nav-item {
            margin: 0 0.3rem;
        }

        .nav-link {
            padding: 0.5rem 0.8rem !important;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        /* Cart icon styling - icon only */
        .cart-container {
            position: relative;
            padding: 0.5rem 0.8rem !important;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .cart-container:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: 2px;
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
        }

        .navbar.scrolled {
            padding: 0.4rem 0;
        }

        .navbar.scrolled .logo-img {
            height: 35px;
        }

        /* Mobile navigation - text only */
        .mobile-nav-icons {
            display: none;
            justify-content: space-around;
            padding: 0.4rem 0;
            background: var(--primary-green);
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1020;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            height: 60px;
        }

        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 0.3rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            flex: 1;
            max-width: 65px;
        }

        .mobile-nav-icon {
            font-size: 1rem;
            margin-bottom: 0.1rem;
        }

        .mobile-nav-label {
            font-size: 0.6rem;
            text-align: center;
        }

        #mobileCartCount {
            font-size: 0.6rem;
            padding: 0.15rem 0.3rem;
        }

        @media (max-width: 991.98px) {
            .navbar {
                position: relative;
            }

            body {
                padding-top: 0;
                padding-bottom: 60px;
            }

            .navbar.scrolled {
                padding: 0.6rem 0;
            }

            .navbar.scrolled .logo-img {
                height: 40px;
            }

            .search-container {
                margin: 0.8rem 0;
                max-width: 100%;
            }

            .desktop-nav {
                display: none !important;
            }

            .mobile-nav-icons {
                display: flex;
            }
        }

        .dropdown-menu {
            border-radius: 8px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .dropdown-item i {
            font-size: 0.9rem;
            width: 18px;
        }

        footer {
            padding: 3rem 0 1.5rem;
        }

        .footer-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            font-size: 0.9rem;
        }

        .social-links a {
            width: 35px;
            height: 35px;
            font-size: 0.9rem;
        }

        /* HOME LAYOUT SPECIFIC STYLES */
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
            position: relative;
            z-index: 10;
            cursor: pointer;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-top: 4px solid var(--primary-green);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            position: relative;
        }

        .testimonial-card::before {
            content: "";
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
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg shadow-sm" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo-bg-removed.png') }}" alt="DIMDI Store" class="logo-img me-2">
                <span class="d-none d-sm-inline" style="font-size: 1.1rem;">DIMDI Store</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                style="padding: 0.25rem 0.5rem;">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- LEFT SIDE: Home, Products, About Us -->
                <ul class="navbar-nav me-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#home">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}#home">Products</a>
                    </li>

                    <!-- ABOUT US DROPDOWN -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            About Us
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                            <li><a class="dropdown-item" href="{{ route('home') }}#stats">Stats</a></li>
                            <li><a class="dropdown-item" href="{{ route('home') }}#about">About</a></li>
                            <li><a class="dropdown-item" href="{{ route('home') }}#featured">Featured</a></li>
                            <li><a class="dropdown-item" href="{{ route('home') }}#why-choose-us">Why Choose Us</a></li>
                            <li><a class="dropdown-item" href="{{ route('home') }}#testimonials">Testimonials</a></li>
                            <li><a class="dropdown-item" href="{{ route('home') }}#contact">Contact</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- RIGHT SIDE: Search + Login/Register/User -->
                <ul class="navbar-nav ms-auto align-items-center">

                    <!-- Search Bar -->
                    <li class="nav-item me-3">
                        <div class="search-container">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control search-input"
                                placeholder="Search products or categories..." id="searchInput" autocomplete="off">
                            <div class="search-results" id="searchResults"></div>
                        </div>
                    </li>

                    @auth
                        @if (Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a>
                            </li>
                        @endif

                        <!-- USER DROPDOWN -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.9rem;">
                                Account
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2"></i>Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">
                                        <i class="fas fa-shopping-bag me-2"></i>My Orders
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <a class="dropdown-item" href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>

            </div>
        </div>
    </nav>


    <!-- Mobile Bottom Navigation -->
    <div class="mobile-nav-icons d-lg-none">
        <a href="{{ route('home') }}" class="mobile-nav-item">
            <i class="fas fa-home mobile-nav-icon"></i>
            <span class="mobile-nav-label">Home</span>
        </a>
        <a href="{{ route('products.index') }}" class="mobile-nav-item">
            <i class="fas fa-box mobile-nav-icon"></i>
            <span class="mobile-nav-label">Products</span>
        </a>
        <a href="{{ route('about') }}" class="mobile-nav-item">
            <i class="fas fa-info-circle mobile-nav-icon"></i>
            <span class="mobile-nav-label">About</span>
        </a>
        <a href="{{ route('cart.index') }}" class="mobile-nav-item position-relative">
            <i class="fas fa-shopping-cart mobile-nav-icon"></i>
            <span class="mobile-nav-label">Cart</span>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                id="mobileCartCount">0</span>
        </a>
        @auth
            <a href="{{ route('profile.show') }}" class="mobile-nav-item">
                <i class="fas fa-user mobile-nav-icon"></i>
                <span class="mobile-nav-label">Account</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="mobile-nav-item">
                <i class="fas fa-sign-in-alt mobile-nav-icon"></i>
                <span class="mobile-nav-label">Login</span>
            </a>
        @endauth
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer style="background-color: #2C8F0C !important; color: white !important;">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title" style="color: white !important; font-size: 1.1rem;">DIMDI Store</h5>
                    <p style="color: white !important; font-size: 0.9rem;">Your trusted destination for premium
                        appliances and furniture that transform houses into homes.</p>
                    <div class="social-links mt-3">
                        <a href="#" style="color: white !important;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" style="color: white !important;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="color: white !important;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="color: white !important;"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-title" style="color: white !important; font-size: 1.1rem;">Shop</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/products') }}" style="color: white !important; font-size: 0.9rem;">All
                                Products</a></li>
                        <li><a href="{{ url('/products?category=appliances') }}"
                                style="color: white !important; font-size: 0.9rem;">Appliances</a></li>
                        <li><a href="{{ url('/products?category=furniture') }}"
                                style="color: white !important; font-size: 0.9rem;">Furniture</a></li>
                        <li><a href="{{ url('/products?sort=newest') }}"
                                style="color: white !important; font-size: 0.9rem;">New Arrivals</a></li>
                        <li><a href="{{ url('/products?sort=popular') }}"
                                style="color: white !important; font-size: 0.9rem;">Best Sellers</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-title" style="color: white !important; font-size: 1.1rem;">Help</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/contact') }}"
                                style="color: white !important; font-size: 0.9rem;">Customer Service</a></li>
                        <li><a href="{{ url('/track-order') }}"
                                style="color: white !important; font-size: 0.9rem;">Track Order</a></li>
                        <li><a href="{{ url('/returns') }}"
                                style="color: white !important; font-size: 0.9rem;">Returns & Exchanges</a></li>
                        <li><a href="{{ url('/shipping') }}"
                                style="color: white !important; font-size: 0.9rem;">Shipping Info</a></li>
                        <li><a href="{{ url('/faq') }}" style="color: white !important; font-size: 0.9rem;">FAQ</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title" style="color: white !important; font-size: 1.1rem;">Newsletter</h5>
                    <p style="color: white !important; font-size: 0.9rem;">Subscribe to get special offers, free
                        giveaways, and new product updates.</p>
                    <div class="input-group mb-3" style="max-width: 300px;">
                        <input type="email" class="form-control" placeholder="Your email address"
                            style="font-size: 0.9rem;">
                        <button class="btn btn-light" type="button"
                            style="color: #2C8F0C !important; font-weight: bold; font-size: 0.9rem; padding: 0.4rem 0.8rem;">Subscribe</button>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row">
                    <div class="col-md-6">
                        <p style="color: white !important; font-size: 0.85rem;">&copy; 2024 DIMDI Store. All rights
                            reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ url('/privacy') }}"
                            style="color: white !important; margin-right: 1rem; font-size: 0.85rem;">Privacy Policy</a>
                        <a href="{{ url('/terms') }}" style="color: white !important; font-size: 0.85rem;">Terms of
                            Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');

        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.trim();

            clearTimeout(searchTimeout);

            if (query.length === 0) {
                searchResults.style.display = 'none';
                return;
            }

            // Show loading state
            searchResults.innerHTML = '<div class="no-results">Searching...</div>';
            searchResults.style.display = 'block';

            // Debounce search
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });

        // Perform search with proper error handling
        function performSearch(query) {
            setTimeout(() => {
                const mockResults = [{
                        type: 'Product',
                        name: 'Modern Refrigerator',
                        price: '$899.99',
                        slug: 'modern-refrigerator'
                    },
                    {
                        type: 'Product',
                        name: 'Leather Sofa',
                        price: '$1,299.99',
                        slug: 'leather-sofa'
                    },
                    {
                        type: 'Product',
                        name: 'Coffee Maker',
                        price: '$149.99',
                        slug: 'coffee-maker'
                    },
                    {
                        type: 'Category',
                        name: 'Appliances',
                        slug: 'appliances'
                    },
                    {
                        type: 'Category',
                        name: 'Furniture',
                        slug: 'furniture'
                    }
                ];

                // Filter results based on query
                const filteredResults = mockResults.filter(item =>
                    item.name.toLowerCase().includes(query.toLowerCase())
                );

                displaySearchResults(filteredResults);
            }, 500);
        }

        // Display search results
        function displaySearchResults(results) {
            if (!results || results.length === 0) {
                searchResults.innerHTML = '<div class="no-results">No products found</div>';
                return;
            }

            let html = '';

            results.forEach(item => {
                html += `
                    <div class="search-result-item" onclick="selectResult('${item.slug}', '${item.type}')">
                        <div class="result-type">${item.type}</div>
                        <div class="result-name">${item.name}</div>
                        ${item.price ? `<small class="text-muted">${item.price}</small>` : ''}
                    </div>
                `;
            });

            searchResults.innerHTML = html;
        }

        // Handle result selection
        function selectResult(slug, type) {
            if (type === 'Product') {
                window.location.href = `/products/${slug}`;
            } else {
                window.location.href = `/products?category=${slug}`;
            }
        }

        // Handle Enter key press for search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = e.target.value.trim();
                if (query.length > 0) {
                    window.location.href = `/products?search=${encodeURIComponent(query)}`;
                }
            }
        });

        // Update cart count
        function updateCartCount() {
            fetch('{{ route('cart.count') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cartCount').textContent = data.count;
                    document.getElementById('mobileCartCount').textContent = data.count;
                });
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();

            // Initialize navbar state
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
