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
            padding: 0.8rem 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Add padding to body to account for fixed navbar */
        body {
            padding-top: 80px;
        }
        
        .navbar-brand, 
        .nav-link {
            color: #fff !important;
            font-weight: 500;
        }
        .nav-link:hover {
            color: #dfffd6 !important; /* lighter green hover */
        }
        .product-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
        }
        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .product-image {
            height: 200px;
            object-fit: cover;
        }
        .logo-img {
            height: 50px; /* Increased from 40px */
            transition: all 0.3s ease;
        }
        
        /* Logo on the left */
        .navbar-brand {
            display: flex;
            align-items: center;
            margin-right: 2rem;
        }
        
        /* Search Bar Styles */
        .search-container {
            position: relative;
            max-width: 400px;
            margin: 0 1rem;
            flex: 1;
        }
        .search-input {
            border-radius: 25px;
            border: 2px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            color: white;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            transition: all 0.3s ease;
            width: 100%;
        }
        .search-input::placeholder {
            color: rgba(255,255,255,0.7);
        }
        .search-input:focus {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.5);
            box-shadow: 0 0 0 0.2rem rgba(255,255,255,0.25);
            color: white;
        }
        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.7);
            z-index: 2;
        }
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        .search-result-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .search-result-item:hover {
            background-color: #f8f9fa;
        }
        .search-result-item:last-child {
            border-bottom: none;
        }
        .result-type {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: bold;
        }
        .result-name {
            color: #333;
            font-weight: 500;
        }
        .no-results {
            padding: 1rem;
            text-align: center;
            color: #6c757d;
        }
        
        .navbar.scrolled {
            padding: 0.5rem 0;
        }
        
        .navbar.scrolled .logo-img {
            height: 40px; 
        }
        
        @media (max-width: 991.98px) {
            .navbar {
                position: relative;
            }
            body {
                padding-top: 0;
            }
            .navbar.scrolled {
                padding: 0.8rem 0;
            }
            .navbar.scrolled .logo-img {
                height: 50px;
            }
            .search-container {
                margin: 1rem 0;
                max-width: 100%;
            }
            .search-results {
                position: fixed;
                top: auto;
                left: 15px;
                right: 15px;
                max-height: 50vh;
            }
            /* Footer */
        footer {
            background-color: #1a1a1a;
            color: white;
            padding: 4rem 0 2rem;
        }
        
        .footer-title {
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }
        
        .footer-title:after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 30px;
            height: 2px;
            background-color: var(--primary-green);
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.7rem;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--primary-green);
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: var(--primary-green);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 2rem;
            margin-top: 3rem;
        }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg shadow-sm" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/DIMDI_LOGO.png') }}" alt="DIMDI Store" class="logo-img me-2">
                <span class="d-none d-sm-inline">DIMDI Store</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Nav Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                    </li>
                </ul>

                <!-- Search Bar -->
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           class="form-control search-input" 
                           placeholder="Search products or categories..." 
                           id="searchInput"
                           autocomplete="off">
                    <div class="search-results" id="searchResults"></div>
                </div>

                <!-- Right Section -->
                <ul class="navbar-nav align-items-center">
                    @auth
                        @if(Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-cog me-1"></i>Admin
                            </a>
                        </li>
                        @endif
                    @endauth

                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge badge bg-danger rounded-pill" id="cartCount">0</span>
                        </a>
                    </li>
                    <!-- User Dropdown -->
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i>
                            {{ Auth::user()->name }}
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
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer
<footer style="background-color: #2C8F0C !important; color: white !important;">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5 class="footer-title" style="color: white !important;">DIMDI Store</h5>
                <p style="color: white !important;">Your trusted destination for premium appliances and furniture that transform houses into homes.</p>
                <div class="social-links mt-4">
                    <a href="#" style="color: white !important;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" style="color: white !important;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="color: white !important;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="color: white !important;"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="footer-title" style="color: white !important;">Shop</h5>
                <ul class="footer-links">
                    <li><a href="{{ url('/products') }}" style="color: white !important;">All Products</a></li>
                    <li><a href="{{ url('/products?category=appliances') }}" style="color: white !important;">Appliances</a></li>
                    <li><a href="{{ url('/products?category=furniture') }}" style="color: white !important;">Furniture</a></li>
                    <li><a href="{{ url('/products?sort=newest') }}" style="color: white !important;">New Arrivals</a></li>
                    <li><a href="{{ url('/products?sort=popular') }}" style="color: white !important;">Best Sellers</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="footer-title" style="color: white !important;">Help</h5>
                <ul class="footer-links">
                    <li><a href="{{ url('/contact') }}" style="color: white !important;">Customer Service</a></li>
                    <li><a href="{{ url('/track-order') }}" style="color: white !important;">Track Order</a></li>
                    <li><a href="{{ url('/returns') }}" style="color: white !important;">Returns & Exchanges</a></li>
                    <li><a href="{{ url('/shipping') }}" style="color: white !important;">Shipping Info</a></li>
                    <li><a href="{{ url('/faq') }}" style="color: white !important;">FAQ</a></li>
                </ul>
            </div>
            <div class="col-lg-4 mb-4">
                <h5 class="footer-title" style="color: white !important;">Newsletter</h5>
                <p style="color: white !important;">Subscribe to get special offers, free giveaways, and new product updates.</p>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Your email address">
                    <button class="btn btn-light" type="button" style="color: #2C8F0C !important; font-weight: bold;">Subscribe</button>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-6">
                    <p style="color: white !important;">&copy; 2024 DIMDI Store. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ url('/privacy') }}" style="color: white !important; margin-right: 1rem;">Privacy Policy</a>
                    <a href="{{ url('/terms') }}" style="color: white !important;">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</footer> -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');

        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.trim();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Hide results if query is empty
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

        // Perform search using existing products route with search parameter
        function performSearch(query) {
            // Use the existing products index route with a search parameter
            fetch(`/products?search=${encodeURIComponent(query)}&ajax=1`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.innerHTML = '<div class="no-results">Error performing search</div>';
                });
        }

        // Display search results
        function displaySearchResults(data) {
            if (!data.products || data.products.length === 0) {
                searchResults.innerHTML = '<div class="no-results">No products found</div>';
                return;
            }

            let html = '';
            
            // Display products
            data.products.forEach(product => {
                html += `
                    <div class="search-result-item" onclick="selectProduct('${product.slug}')">
                        <div class="result-type">Product</div>
                        <div class="result-name">${product.name}</div>
                        <small class="text-muted">$${product.price}</small>
                    </div>
                `;
            });
            
            searchResults.innerHTML = html;
        }

        // Handle product selection
        function selectProduct(productSlug) {
            window.location.href = `/products/${productSlug}`;
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
            fetch('{{ route("cart.count") }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cartCount').textContent = data.count;
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