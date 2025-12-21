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

       /* Search Box - FIXED: Always white text */
.search-input {
    border-radius: 50px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.1);
    color: white !important; /* Force white text */
    padding: 0.4rem 1rem 0.4rem 2.5rem;
    transition: all 0.3s ease;
    width: 100%;
    font-size: 0.9rem;
}

/* Force white text specifically for the search input */
#searchInput,
.search-input,
.form-control.search-input {
    color: white !important;
}

/* Change placeholder color to be more visible */
.search-input::placeholder {
    color: rgba(255, 255, 255, 0.7) !important;
    font-size: 0.9rem;
}

/* When input is focused, keep text white */
.search-input:focus {
    border-color: rgba(255, 255, 255, 0.5);
    background: rgba(255, 255, 255, 0.15);
    color: white !important;
    box-shadow: none !important;
}

/* Remove any Bootstrap styling that makes text black */
.search-input.form-control:focus {
    color: white !important;
}

        /* Search results styling - FIXED - Separate container */
        .search-results-container {
            position: fixed;
            z-index: 9999;
            display: none;
        }

        .search-results {
            background: white;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-height: 300px;
            overflow-y: auto;
        }

        .search-result-item {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.2s;
            color: #212529 !important;
        }

        .search-result-item:hover {
            background-color: #f8f9fa;
        }

        .result-type {
            font-size: 0.7rem;
            color: #6c757d !important;
            text-transform: uppercase;
        }

        .result-name {
            font-weight: 500;
            margin-bottom: 2px;
            color: #212529 !important;
        }

        .no-results {
            padding: 15px;
            text-align: center;
            color: #6c757d !important;
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .cart-container:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .cart-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            font-size: 0.65rem;
            padding: 0.15rem 0.35rem;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            font-weight: 600;
        }

        /* Notification badge styles */
        .nav-link.position-relative {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .notification-badge {
            position: absolute !important;
            top: -2px !important;
            right: -2px !important;
            font-size: 0.65rem !important;
            padding: 0.15rem 0.35rem !important;
            min-width: 18px !important;
            height: 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            font-weight: 600 !important;
            text-align: center !important;
        }

        .notification-dropdown {
            width: 380px;
            max-height: 450px;
            overflow: hidden;
            border-radius: 12px;
        }

        .notification-item {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: #f0f9ff;
            border-left: 3px solid #2C8F0C;
        }

        .notification-item.read {
            opacity: 0.8;
        }

        .notification-item .notification-icon {
            font-size: 1.2rem;
        }

        .notification-header {
            background: #2C8F0C !important;
            color: white;
        }

        .notification-footer {
            background: #f8f9fa;
        }

        .notification-time {
            font-size: 0.75rem;
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
            position: relative;
        }

        .mobile-nav-icon {
            font-size: 1rem;
            margin-bottom: 0.1rem;
        }

        .mobile-nav-label {
            font-size: 0.6rem;
            text-align: center;
        }

        #mobileCartCount,
        #mobileNotificationCount {
            font-size: 0.65rem;
            padding: 0.15rem 0.35rem;
            position: absolute;
            top: -2px;
            right: -2px;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            font-weight: 600;
        }

        /* Mobile notification bell */
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

            .notification-dropdown {
                position: fixed !important;
                top: 60px !important;
                left: 10px !important;
                right: 10px !important;
                width: auto !important;
                max-height: 60vh !important;
            }
        }

        .dropdown-menu {
            border-radius: 8px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Notification dropdown tweaks (sync with cart layout) */
        .notification-dropdown {
            min-width: 320px;
            max-width: 420px;
            border-radius: 10px;
            overflow: hidden;
        }

        .notification-header h6 {
            font-weight: 600;
            font-size: 1rem;
        }

        .notification-body {
            max-height: 320px;
            overflow-y: auto;
        }

        .notification-body {
            max-height: 320px;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .notification-item {
            color: inherit;
            transition: background-color 0.2s ease;
        }

        .notification-item:hover {
            background: #f8f9fa;
            text-decoration: none;
        }

        .notification-item:last-child .border-bottom {
            border-bottom: none !important;
        }

        .notification-item.unread {
            background: rgba(44, 143, 12, 0.06);
        }

        .notification-footer a {
            font-weight: 600;
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
            background-color: #2C8F0C !important;
            color: white !important;
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

        /* Toast notifications */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1050;
        }

        .custom-toast {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-left: 4px solid;
            min-width: 300px;
            animation: slideIn 0.3s ease;
        }

        .custom-toast.success {
            border-left-color: #2C8F0C;
        }

        .custom-toast.info {
            border-left-color: #17a2b8;
        }

        .custom-toast.warning {
            border-left-color: #ffc107;
        }

        .custom-toast.error {
            border-left-color: #dc3545;
        }

        /* Fix notification badge positioning */
        .notification-badge {
            position: absolute !important;
            top: -2px !important;
            right: -2px !important;
            font-size: 0.65rem !important;
            padding: 0.15rem 0.35rem !important;
            min-width: 18px !important;
            height: 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            font-weight: 600 !important;
            text-align: center !important;
        }

        /* For cart badge too */
        .cart-badge {
            position: absolute !important;
            top: -3px !important;
            right: 0px !important;
            font-size: 0.7rem !important;
            padding: 0.2rem 0.4rem !important;
            min-width: 18px !important;
            height: 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
        }

        /* Mobile bottom nav badges */
        .mobile-nav-item .badge {
            position: absolute !important;
            top: -3px !important;
            right: -3px !important;
            font-size: 0.65rem !important;
            padding: 0.15rem 0.35rem !important;
            min-width: 16px !important;
            height: 16px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        /* NUCLEAR OPTION - Add this at the VERY END of your CSS */
#searchInput {
    color: white !important;
    -webkit-text-fill-color: white !important;
    caret-color: white !important; /* Makes the cursor white too */
}

#searchInput:focus,
#searchInput:active,
#searchInput:hover,
#searchInput:focus-visible,
#searchInput:focus-within {
    color: white !important;
    -webkit-text-fill-color: white !important;
}

/* Override ALL Bootstrap form-control styles for this specific input */
.navbar .form-control#searchInput,
.navbar input[type="text"]#searchInput {
    color: white !important;
    -webkit-text-fill-color: white !important;
}

/* Specific override for Bootstrap's :focus state */
.form-control#searchInput:focus {
    color: white !important;
    -webkit-text-fill-color: white !important;
    background-color: rgba(255, 255, 255, 0.15) !important;
}

/* Chrome autofill override */
input#searchInput:-webkit-autofill,
input#searchInput:-webkit-autofill:hover,
input#searchInput:-webkit-autofill:focus,
input#searchInput:-webkit-autofill:active {
    -webkit-text-fill-color: white !important;
    -webkit-box-shadow: 0 0 0px 1000px rgba(255, 255, 255, 0.1) inset !important;
    transition: background-color 5000s ease-in-out 0s;
}
    </style>
</head>

<body>
    <!-- Toast Notification Container -->
    <div class="toast-container"></div>
    
    <!-- Search Results Container (Separate from footer) -->
    <div id="searchResultsContainer" class="search-results-container"></div>

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

            <!-- Desktop Navigation with Text Only -->
            <div class="collapse navbar-collapse desktop-nav" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            Products
                        </a>
                    </li>
                </ul>

                <!-- Search Bar -->
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           class="form-control search-input" 
                           placeholder="      Search products or categories..." 
                           id="searchInput"
                           autocomplete="off"
                           style="color: white !important; -webkit-text-fill-color: white !important;">
                </div>

                <!-- Right Section -->
                <ul class="navbar-nav align-items-center">
                    @auth
                        @if (Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    Admin
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->isSuperAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.dashboard') }}">
                                    Super Admin
                                </a>
                            </li>
                        @endif

                        <!-- Cart - Icon Only -->
                        <li class="nav-item">
                            <a class="nav-link position-relative cart-container" href="{{ route('cart.index') }}">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-badge badge bg-danger rounded-pill" id="cartCount">0</span>
                            </a>
                        </li>

                        <!-- Notifications Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                @php
                                    $unreadCount = auth()->user()->unreadNotifications->count();
                                @endphp
                                @if ($unreadCount > 0)
                                    <span class="notification-badge badge bg-danger rounded-pill"
                                        id="desktopNotificationCount">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end notification-dropdown p-0"
                                aria-labelledby="notificationsDropdown">
                                <div
                                    class="notification-header p-3 border-bottom d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0" style="font-weight: 600; font-size: 1rem;">Notifications</h6>
                                    @if ($unreadCount > 0)
                                        <a href="#" class="text-white mark-all-read"
                                            style="text-decoration: none; font-size: 0.85rem; padding: 0.15rem 0.4rem;">Mark
                                            all as read</a>
                                    @endif
                                </div>

                                <div class="notification-body" id="notificationList">
                                    @php
                                        $notifications = auth()->user()->notifications->take(5);
                                    @endphp
                                    @if ($notifications->count() > 0)
                                        @foreach ($notifications as $notification)
                                            @php
                                                $data = $notification->data;
                                                $isUnread = $notification->read_at === null;
                                            @endphp
                                            <a href="#"
                                                class="notification-item d-block text-decoration-none {{ $isUnread ? 'unread' : 'read' }}"
                                                data-id="{{ $notification->id }}" data-url="{{ $data['url'] ?? '#' }}">
                                                <div class="d-flex align-items-start gap-3 p-3 border-bottom">
                                                    <div class="notification-icon flex-shrink-0">
                                                        <i class="{{ $data['icon'] ?? 'fas fa-bell' }} text-{{ $data['color'] ?? 'primary' }}"
                                                            style="font-size: 1.15rem; width: 36px; text-align: center;"></i>
                                                    </div>
                                                    <div class="notification-content flex-grow-1" style="min-width: 0;">
                                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                                            <h6 class="mb-0"
                                                                style="font-size: 0.95rem; font-weight: 600; line-height: 1.3;">
                                                                @if (isset($data['order_number']))
                                                                    Order #{{ $data['order_number'] }}
                                                                @else
                                                                    Notification
                                                                @endif
                                                            </h6>
                                                            <small class="text-muted notification-time flex-shrink-0 ms-2"
                                                                style="font-size: 0.75rem; white-space: nowrap;">{{ $data['time_ago'] ?? '' }}</small>
                                                        </div>
                                                        <p class="mb-1"
                                                            style="font-size: 0.88rem; color: #495057; line-height: 1.4; margin: 0.25rem 0 0;">
                                                            {{ $data['message'] ?? 'New notification' }}
                                                        </p>
                                                        @if (isset($data['status_display']))
                                                            <small class="text-muted mt-1 d-block">
                                                                Status: <span
                                                                    class="badge bg-{{ $data['color'] ?? 'secondary' }}"
                                                                    style="font-size: 0.75rem;">
                                                                    {{ $data['status_display'] }}
                                                                </span>
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">No notifications</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="notification-footer p-3 border-top text-center">
                                    <a href="{{ route('notifications.index') }}" class="text-decoration-none"
                                        style="color: #2C8F0C;">
                                        View all notifications
                                    </a>
                                </div>
                            </div>
                        </li>

                        <!-- User Dropdown -->
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
                                    <a class="dropdown-item" href="{{ route('notifications.index') }}">
                                        <i class="fas fa-bell me-2"></i>Notifications
                                        @if ($unreadCount > 0)
                                            <span class="badge bg-danger float-end">{{ $unreadCount }}</span>
                                        @endif
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
                            <a class="nav-link" href="{{ route('login') }}">
                                Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                Register
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
        <a href="{{ route('cart.index') }}" class="mobile-nav-item position-relative">
            <i class="fas fa-shopping-cart mobile-nav-icon"></i>
            <span class="mobile-nav-label">Cart</span>
            <span class="badge bg-danger rounded-pill" id="mobileCartCount">0</span>
        </a>
        @auth
            <a href="#" class="mobile-nav-item position-relative" data-bs-toggle="dropdown"
                id="mobileNotificationBell">
                <i class="fas fa-bell mobile-nav-icon"></i>
                <span class="mobile-nav-label">Alerts</span>
                @if (auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge bg-danger rounded-pill" id="mobileNotificationCount">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
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
    @include('components.ui-elements')
    <script>
        // Notification System
        class NotificationSystem {
            constructor() {
                this.initialize();
                this.setupEventListeners();
                this.checkForNewNotifications();
            }

            initialize() {
                this.updateNotificationCounts();
                this.setupPolling();
            }

            setupEventListeners() {
                // Mark notification as read when clicked
                document.addEventListener('click', (e) => {
                    const notificationItem = e.target.closest('.notification-item');
                    if (notificationItem) {
                        e.preventDefault();
                        this.markAsRead(notificationItem.dataset.id, notificationItem.dataset.url);
                    }

                    // Mark all as read
                    if (e.target.classList.contains('mark-all-read')) {
                        e.preventDefault();
                        this.markAllAsRead();
                    }
                });

                // Request notification permission
                if ("Notification" in window && Notification.permission === "default") {
                    Notification.requestPermission();
                }
            }

            updateNotificationCounts() {
                const unreadCount = {{ auth()->check() ? auth()->user()->unreadNotifications->count() : 0 }};

                // Update desktop count
                const desktopCount = document.getElementById('desktopNotificationCount');
                if (desktopCount) {
                    if (unreadCount > 0) {
                        desktopCount.textContent = unreadCount;
                        desktopCount.style.display = 'block';
                    } else {
                        desktopCount.style.display = 'none';
                    }
                }

                // Update mobile count
                const mobileCount = document.getElementById('mobileNotificationCount');
                if (mobileCount) {
                    if (unreadCount > 0) {
                        mobileCount.textContent = unreadCount;
                        mobileCount.style.display = 'block';
                    } else {
                        mobileCount.style.display = 'none';
                    }
                }
            }

            async markAsRead(notificationId, url) {
                try {
                    const response = await fetch(`/notifications/mark-as-read/${notificationId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            _method: 'POST'
                        })
                    });

                    if (response.ok) {
                        // Update counts
                        this.updateNotificationCounts();

                        // Navigate to the order page if URL exists
                        if (url && url !== '#') {
                            window.location.href = url;
                        }
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                }
            }

            async markAllAsRead() {
                try {
                    const response = await fetch('/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            _method: 'POST'
                        })
                    });

                    if (response.ok) {
                        this.updateNotificationCounts();
                        this.showToast('All notifications marked as read', 'success');

                        // Update notification list
                        this.loadNotifications();
                    }
                } catch (error) {
                    console.error('Error marking all notifications as read:', error);
                    this.showToast('Error marking notifications as read', 'error');
                }
            }

            async loadNotifications() {
                try {
                    const response = await fetch('/notifications/list');
                    if (response.ok) {
                        const html = await response.text();
                        document.getElementById('notificationList').innerHTML = html;
                    }
                } catch (error) {
                    console.error('Error loading notifications:', error);
                }
            }

            setupPolling() {
                // Check for new notifications every 30 seconds
                setInterval(() => {
                    this.checkForNewNotifications();
                }, 30000);
            }

            async checkForNewNotifications() {
                try {
                    const response = await fetch('/notifications/check-new', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    if (response.ok) {
                        const data = await response.json();
                        if (data.new_count > 0) {
                            this.updateNotificationCounts();
                            // REMOVED: Automatic pop-up notifications
                            // Users will see notifications only when they click the notification icon
                        }
                    }
                } catch (error) {
                    console.error('Error checking for new notifications:', error);
                }
            }

            showPushNotification(data) {
                // Browser notification
                if (Notification.permission === "granted" && data) {
                    new Notification("DIMDI Store - Order Update", {
                        body: data.message,
                        icon: '{{ asset('images/logo-bg-removed.png') }}'
                    });
                }

                // Toast notification
                if (data) {
                    this.showToast(data.message, 'info');
                }
            }

            showToast(message, type = 'info') {
                const toastContainer = document.querySelector('.toast-container');
                const toastId = 'toast-' + Date.now();

                const toast = document.createElement('div');
                toast.className = `custom-toast ${type} mb-2`;
                toast.id = toastId;
                toast.setAttribute('role', 'status');
                toast.setAttribute('aria-live', 'polite');

                const severity = type.charAt(0).toUpperCase() + type.slice(1);
                const icon = this.getToastIcon(type);

                toast.innerHTML = `
                    <div class="d-flex align-items-start p-2">
                        <div class="me-3 d-flex align-items-center">
                            <i class="fas fa-${icon} fa-lg text-${type}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="fw-semibold">${severity}</div>
                                <button type="button" class="btn-close" aria-label="Close" onclick="document.getElementById('${toastId}').remove()"></button>
                            </div>
                            <div class="toast-message mt-1" style="font-size:0.95rem;">${message}</div>
                        </div>
                    </div>
                `;

                toastContainer.appendChild(toast);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 5000);
            }

            getToastIcon(type) {
                const icons = {
                    'success': 'check-circle',
                    'error': 'exclamation-circle',
                    'warning': 'exclamation-triangle',
                    'info': 'info-circle'
                };
                return icons[type] || 'bell';
            }
        }

        // Initialize notification system
        let notificationSystem;
        document.addEventListener('DOMContentLoaded', function() {
            notificationSystem = new NotificationSystem();

            // Cart count update
            updateCartCount();

            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.getElementById('mainNavbar');
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Initialize navbar state
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            }
        });

        // Cart functions
        async function updateCartCount() {
            try {
                const response = await fetch('{{ route('cart.count') }}');
                if (response.ok) {
                    const data = await response.json();
                    document.getElementById('cartCount').textContent = data.count;
                    document.getElementById('mobileCartCount').textContent = data.count;
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        }

        // Search functionality - FIXED
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        const searchResultsContainer = document.getElementById('searchResultsContainer');

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();

                clearTimeout(searchTimeout);

                if (query.length === 0) {
                    searchResultsContainer.style.display = 'none';
                    return;
                }

                // Position the dropdown under the search input
                const searchInputRect = searchInput.getBoundingClientRect();
                searchResultsContainer.style.position = 'fixed';
                searchResultsContainer.style.top = (searchInputRect.bottom + window.scrollY) + 'px';
                searchResultsContainer.style.left = (searchInputRect.left + window.scrollX) + 'px';
                searchResultsContainer.style.width = searchInputRect.width + 'px';
                
                searchResultsContainer.innerHTML = '<div class="no-results">Searching...</div>';
                searchResultsContainer.style.display = 'block';

                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResultsContainer.contains(e.target)) {
                    searchResultsContainer.style.display = 'none';
                }
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const query = e.target.value.trim();
                    if (query.length > 0) {
                        window.location.href = `/products?search=${encodeURIComponent(query)}`;
                    }
                }
            });
        }

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

                const filteredResults = mockResults.filter(item =>
                    item.name.toLowerCase().includes(query.toLowerCase())
                );

                displaySearchResults(filteredResults);
            }, 500);
        }

        function displaySearchResults(results) {
            if (!results || results.length === 0) {
                searchResultsContainer.innerHTML = '<div class="no-results">No products found</div>';
                return;
            }

            let html = '<div class="search-results">';
            results.forEach(item => {
                html += `
                    <div class="search-result-item" onclick="selectResult('${item.slug}', '${item.type}')">
                        <div class="result-type">${item.type}</div>
                        <div class="result-name">${item.name}</div>
                        ${item.price ? `<small class="text-muted">${item.price}</small>` : ''}
                    </div>
                `;
            });
            html += '</div>';
            
            searchResultsContainer.innerHTML = html;
        }

        function selectResult(slug, type) {
            searchResultsContainer.style.display = 'none';
            searchInput.value = '';
            
            if (type === 'Product') {
                window.location.href = `/products/${slug}`;
            } else {
                window.location.href = `/products?category=${slug}`;
            }
        }

        // Add this to your existing JavaScript
if (searchInput) {
    // Force white text on input
    searchInput.style.color = 'white';
    
    // Add input event to maintain white text
    searchInput.addEventListener('input', function() {
        this.style.color = 'white';
    });
    
    // Also force white text on focus
    searchInput.addEventListener('focus', function() {
        this.style.color = 'white';
    });
    
    // And on blur
    searchInput.addEventListener('blur', function() {
        this.style.color = 'white';
    });
    
}
    </script>
    @stack('scripts')
</body>

</html>