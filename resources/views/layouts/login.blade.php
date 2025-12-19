<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DIMDI - Premium Home Appliances & Furniture</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold fs-3" href="{{ url('/') }}" style="color: var(--primary-green);">
                <i class="fas fa-home me-2"></i>DIMDI
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Products
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('products.index') }}">All Products</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('products.index') }}?category=furniture">Furniture</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.index') }}?category=appliances">Appliances</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.index') }}?category=decor">Home Decor</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>

                <!-- Right Navigation -->
                <div class="d-flex align-items-center">
                    <!-- Search -->
                    <div class="input-group me-3 d-none d-md-flex" style="width: 250px;">
                        <input type="text" class="form-control" placeholder="Search products...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <!-- User Account -->
                    @auth
                        <div class="dropdown me-3">
                            <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-shopping-bag me-2"></i>Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <a class="dropdown-item" href="#" onclick="logoutWithConfirm(event);">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary" style="background-color: var(--primary-green); border-color: var(--primary-green);">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    @endauth

                    <!-- Shopping Cart -->
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        @if($cartCount ?? 0 > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row">
                <!-- Company Info -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-home me-2"></i>DIMDI STORE
                    </h5>
                    <p class="text-light">Transforming homes with premium appliances and furniture since 2010. Your comfort and satisfaction is our priority.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/') }}" class="text-light text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="#about" class="text-light text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="{{ route('products.index') }}" class="text-light text-decoration-none">Products</a></li>
                        <li class="mb-2"><a href="#contact" class="text-light text-decoration-none">Contact</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Categories</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('products.index') }}?category=furniture" class="text-light text-decoration-none">Furniture</a></li>
                        <li class="mb-2"><a href="{{ route('products.index') }}?category=appliances" class="text-light text-decoration-none">Appliances</a></li>
                        <li class="mb-2"><a href="{{ route('products.index') }}?category=decor" class="text-light text-decoration-none">Home Decor</a></li>
                        <li class="mb-2"><a href="{{ route('products.index') }}?category=lighting" class="text-light text-decoration-none">Lighting</a></li>
                        <li class="mb-2"><a href="{{ route('products.index') }}?category=outdoor" class="text-light text-decoration-none">Outdoor</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Contact Info</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span class="text-light">123 Home Design District, Furniture Street, Cityville 12345</span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-phone me-2"></i>
                            <span class="text-light">+1 (555) 123-4567</span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-envelope me-2"></i>
                            <span class="text-light">info@dimdistore.com</span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-clock me-2"></i>
                            <span class="text-light">Mon-Sat: 9AM-8PM, Sun: 11AM-6PM</span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="bg-light">

            <!-- Copyright -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2024 DIMDI Store. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Designed with <i class="fas fa-heart text-danger"></i> for amazing homes</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('components.ui-elements')
    @stack('scripts')
</body>
</html>