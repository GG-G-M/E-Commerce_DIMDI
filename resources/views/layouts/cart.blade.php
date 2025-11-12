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
            padding: 0.6rem 0; /* Reduced padding */
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Add padding to body to account for fixed navbar */
        body {
            padding-top: 70px; /* Reduced from 80px */
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
            height: 40px; /* Reduced from 50px */
            transition: all 0.3s ease;
        }
        
        /* Logo on the left */
        .navbar-brand {
            display: flex;
            align-items: center;
            margin-right: 1.5rem; /* Reduced margin */
        }
        
        /* Search Bar Styles */
        .search-container {
            position: relative;
            max-width: 350px; /* Reduced width */
            margin: 0 1rem;
            flex: 1;
        }
        .search-input {
            border-radius: 20px; /* Slightly smaller radius */
            border: 2px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            color: white;
            padding: 0.4rem 1rem 0.4rem 2.5rem; /* Reduced padding */
            transition: all 0.3s ease;
            width: 100%;
            font-size: 0.9rem; /* Smaller font */
        }
        .search-input::placeholder {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem; /* Smaller placeholder */
        }
        
        /* Modern Navigation Styles - COMPACT VERSION */
        .nav-icon-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.3rem 0.8rem; /* Reduced padding */
            transition: all 0.3s ease;
            border-radius: 8px; /* Smaller radius */
            min-width: 65px; /* Reduced width */
        }
        
        .nav-icon-container:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px); /* Smaller lift */
        }
        
        .nav-icon {
            font-size: 1.1rem; /* Reduced from 1.4rem */
            margin-bottom: 0.2rem; /* Reduced margin */
            transition: all 0.3s ease;
        }
        
        .nav-icon-label {
            font-size: 0.65rem; /* Reduced from 0.75rem */
            font-weight: 500;
            text-align: center;
            line-height: 1.1;
        }
        
        /* Cart badge adjustments */
        .cart-badge {
            position: absolute;
            top: -5px; /* Adjusted position */
            right: 2px; /* Adjusted position */
            font-size: 0.7rem; /* Smaller font */
            padding: 0.2rem 0.4rem; /* Smaller padding */
        }
        
        .navbar.scrolled {
            padding: 0.4rem 0; /* Reduced scrolled padding */
        }
        
        .navbar.scrolled .logo-img {
            height: 35px; /* Reduced scrolled height */
        }
        
        /* Mobile Navigation - COMPACT */
        .mobile-nav-icons {
            display: none;
            justify-content: space-around;
            padding: 0.4rem 0; /* Reduced padding */
            background: var(--primary-green);
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1020;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            height: 60px; /* Fixed height */
        }
        
        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 0.3rem; /* Reduced padding */
            border-radius: 6px; /* Smaller radius */
            transition: all 0.3s ease;
            flex: 1;
            max-width: 65px; /* Reduced width */
        }
        
        .mobile-nav-icon {
            font-size: 1rem; /* Reduced from 1.2rem */
            margin-bottom: 0.1rem; /* Reduced margin */
        }
        
        .mobile-nav-label {
            font-size: 0.6rem; /* Reduced from 0.7rem */
            text-align: center;
        }
        
        /* Mobile cart badge */
        #mobileCartCount {
            font-size: 0.6rem; /* Smaller font */
            padding: 0.15rem 0.3rem; /* Smaller padding */
        }
        
        @media (max-width: 991.98px) {
            .navbar {
                position: relative;
            }
            body {
                padding-top: 0;
                padding-bottom: 60px; /* Reduced padding */
            }
            .navbar.scrolled {
                padding: 0.6rem 0; /* Consistent padding */
            }
            .navbar.scrolled .logo-img {
                height: 40px; /* Consistent height */
            }
            .search-container {
                margin: 0.8rem 0; /* Reduced margin */
                max-width: 100%;
            }
            
            /* Hide desktop nav on mobile */
            .desktop-nav {
                display: none !important;
            }
            
            /* Show mobile nav */
            .mobile-nav-icons {
                display: flex;
            }
        }
        
        /* Dropdown menu adjustments */
        .dropdown-menu {
            border-radius: 8px; /* Smaller radius */
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem; /* Slightly smaller padding */
            font-size: 0.9rem; /* Smaller font */
        }
        
        .dropdown-item i {
            font-size: 0.9rem; /* Smaller icons */
            width: 18px; /* Fixed width */
        }
        
        /* Search results adjustments */
        .search-results {
            border-radius: 8px; /* Smaller radius */
            max-height: 250px; /* Reduced height */
        }
        
        .search-result-item {
            padding: 0.6rem 0.8rem; /* Reduced padding */
            font-size: 0.9rem; /* Smaller font */
        }
        
        .result-type {
            font-size: 0.65rem; /* Smaller font */
        }
        
        /* Footer adjustments (optional - if you want consistent sizing) */
        footer {
            padding: 3rem 0 1.5rem; /* Reduced padding */
        }
        
        .footer-title {
            font-size: 1.1rem; /* Slightly smaller */
            margin-bottom: 1rem;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem; /* Reduced spacing */
        }
        
        .footer-links a {
            font-size: 0.9rem; /* Smaller font */
        }
        
        .social-links a {
            width: 35px; /* Smaller social icons */
            height: 35px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg shadow-sm" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/DIMDI_LOGO.png') }}" alt="DIMDI Store" class="logo-img me-2">
                <span class="d-none d-sm-inline" style="font-size: 1.1rem;">DIMDI Store</span> <!-- Smaller text -->
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="padding: 0.25rem 0.5rem;"> <!-- Smaller button -->
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Desktop Navigation with Icons -->
            <div class="collapse navbar-collapse desktop-nav" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-icon-container" href="{{ route('home') }}">
                            <i class="fas fa-home nav-icon"></i>
                            <span class="nav-icon-label">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-icon-container" href="{{ route('products.index') }}">
                            <i class="fas fa-box nav-icon"></i>
                            <span class="nav-icon-label">Products</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-icon-container" href="{{ route('about') }}">
                            <i class="fas fa-info-circle nav-icon"></i>
                            <span class="nav-icon-label">About Us</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-icon-container" href="{{ route('contact') }}">
                            <i class="fas fa-envelope nav-icon"></i>
                            <span class="nav-icon-label">Contact</span>
                        </a>
                    </li>
                </ul>

                <!-- Search Bar -->
                <div class="search-container">
                    <input type="text" 
                           class="form-control search-input" 
                           placeholder="🔎 Search products or categories..." 
                           id="searchInput"
                           autocomplete="off">
                    <div class="search-results" id="searchResults"></div>
                </div>

                <!-- Right Section -->
                <ul class="navbar-nav align-items-center">
                    @auth
                        @if(Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link nav-icon-container" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-cog nav-icon"></i>
                                <span class="nav-icon-label">Admin</span>
                            </a>
                        </li>
                        @endif
                    @endauth

                    <li class="nav-item">
                        <a class="nav-link position-relative nav-icon-container" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart nav-icon"></i>
                            <span class="cart-badge badge bg-danger rounded-pill" id="cartCount">0</span>
                            <span class="nav-icon-label">Cart</span>
                        </a>
                    </li>
                    
                    <!-- User Dropdown -->
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle nav-icon-container" href="#" id="navbarDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.9rem;"> <!-- Smaller font -->
                            <i class="fas fa-user nav-icon"></i>
                            <span class="nav-icon-label">Account</span>
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
                        <a class="nav-link nav-icon-container" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt nav-icon"></i>
                            <span class="nav-icon-label">Login</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-icon-container" href="{{ route('register') }}">
                            <i class="fas fa-user-plus nav-icon"></i>
                            <span class="nav-icon-label">Register</span>
                        </a>
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
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="mobileCartCount">0</span>
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