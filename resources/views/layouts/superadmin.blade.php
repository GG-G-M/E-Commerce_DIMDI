<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin - DIMDI Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #2C8F0C;
            --dark-green: #1E6A08;
            --light-green: #E8F5E6;
            --accent-green: #4CAF50;
            --sidebar-green: #2c3e50;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 270px;
            background: linear-gradient(180deg, var(--primary-green) 0%, var(--dark-green) 100%);
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            z-index: 1000;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
            padding-right: 10px;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
            margin-right: 8px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .sidebar .position-sticky {
            padding-top: 1rem;
        }

        .sidebar h4 {
            color: white;
            font-weight: 700;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 0.85rem 1.5rem;
            margin: 0.2rem 0.8rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        main {
            margin-left: 270px !important;
            padding-left: 20px;
        }

        .container-fluid.py-4 {
            padding-left: 25px !important;
            padding-right: 15px !important;
        }

        .bg-white {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 90%;
        }

        .sidebar .nav-link .float-end {
            flex-shrink: 0;
            margin-left: auto;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link span {
            flex: 1;
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .navbar-nav .dropdown-menu {
            position: absolute;
            right: 0;
            left: auto;
        }

        /* Super Admin Badge */
        .super-admin-badge {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: #000;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-left: 10px;
        }

        /* Super Admin Card Styles */
        .super-admin-card {
            border-left: 4px solid var(--primary-green);
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            main {
                margin-left: 0 !important;
            }
            
            .navbar-nav .dropdown-menu {
                right: auto;
                left: 0;
            }
        }

        /* Status Indicators */
        .status-active {
            background-color: #d4edda;
            color: #155724;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
        }

        /* Green accent colors */
        .text-green {
            color: var(--primary-green) !important;
        }
        
        .bg-green {
            background-color: var(--primary-green) !important;
        }
        
        .border-green {
            border-color: var(--primary-green) !important;
        }
        
        .btn-green {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            color: white;
        }
        
        .btn-green:hover {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
            color: white;
        }
        
        .badge-green {
            background-color: var(--primary-green);
            color: white;
        }
        
        .alert-green {
            background-color: var(--light-green);
            border-color: var(--accent-green);
            color: var(--dark-green);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 col-lg-1 d-md-block sidebar collapse">
                <div class="position-sticky pt-2">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-crown me-2"></i>DIMDI Super Admin
                        <div class="super-admin-badge mt-2">SUPER ADMIN</div>
                    </h4>
                    <ul class="nav flex-column">

                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}"
                                href="{{ route('superadmin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>

                        <!-- User Management -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#userManagementMenu"
                                role="button" aria-expanded="false" aria-controls="userManagementMenu">
                                <i class="fas fa-users-cog me-2"></i>User Management
                                <i class="fas fa-chevron-down float-end"></i>
                            </a>
                            <div class="collapse 
        {{ request()->routeIs('superadmin.users.*') ? 'show' : '' }}"
                                id="userManagementMenu">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('superadmin.users.index') ? 'active' : '' }}"
                                            href="{{ route('superadmin.users.index') }}">
                                            <i class="fas fa-users me-2"></i>All Users
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('superadmin.users.create') ? 'active' : '' }}"
                                            href="{{ route('superadmin.users.create') }}">
                                            <i class="fas fa-user-plus me-2"></i>Create User
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.customers.index') ? 'active' : '' }}"
                                            href="{{ route('admin.customers.index') }}">
                                            <i class="fas fa-user-tag me-2"></i>Customers
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}"
                                            href="{{ route('admin.deliveries.index') }}">
                                            <i class="fas fa-truck me-2"></i>Delivery Staff
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.stock_checkers.*') ? 'active' : '' }}"
                                            href="{{ route('admin.stock_checkers.index') }}">
                                            <i class="fas fa-user-check me-2"></i>Stock Checkers
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- System Access -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#systemAccessMenu"
                                role="button" aria-expanded="false" aria-controls="systemAccessMenu">
                                <i class="fas fa-cogs me-2"></i>System Access
                                <i class="fas fa-chevron-down float-end"></i>
                            </a>
                            <div class="collapse 
        {{ request()->routeIs('admin.suppliers.*') ||
           request()->routeIs('admin.warehouses.*') ||
           request()->routeIs('admin.categories.*') ||
           request()->routeIs('admin.brands.*') ||
           request()->routeIs('admin.banners.*') ||
           request()->routeIs('admin.products.*') ||
           request()->routeIs('admin.sessions.*') ||
           request()->routeIs('admin.orders.*') ||
           request()->routeIs('admin.sales-report.*') ||
           request()->routeIs('admin.low_stock.*') ||
           request()->routeIs('admin.stock_in.*') ||
           request()->routeIs('admin.stock_out.*') ? 'show' : '' }}"
                                id="systemAccessMenu">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                                            href="{{ route('admin.products.index') }}">
                                            <i class="fas fa-box me-2"></i>Products
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                                            href="{{ route('admin.orders.index') }}">
                                            <i class="fas fa-shopping-cart me-2"></i>Orders
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.sales-report.*') ? 'active' : '' }}"
                                            href="{{ route('admin.sales-report.index') }}">
                                            <i class="fas fa-chart-line me-2"></i>Sales Reports
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}"
                                            href="{{ route('admin.suppliers.index') }}">
                                            <i class="fas fa-truck-loading me-2"></i>Suppliers
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.warehouses.*') ? 'active' : '' }}"
                                            href="{{ route('admin.warehouses.index') }}">
                                            <i class="fas fa-warehouse me-2"></i>Warehouses
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                                            href="{{ route('admin.categories.index') }}">
                                            <i class="fas fa-tags me-2"></i>Categories
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"
                                            href="{{ route('admin.brands.index') }}">
                                            <i class="fas fa-tag me-2"></i>Brands
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}"
                                            href="{{ route('admin.banners.index') }}">
                                            <i class="fas fa-image me-2"></i>Banners
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.low_stock.*') ? 'active' : '' }}"
                                            href="{{ route('admin.low_stock.index') }}">
                                            <i class="fas fa-exclamation-triangle me-2"></i>Low Stocks
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.stock_in.*') ? 'active' : '' }}"
                                            href="{{ route('admin.stock_in.index') }}">
                                            <i class="fas fa-boxes me-2"></i>Stock-In
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.stock_out.*') ? 'active' : '' }}"
                                            href="{{ route('admin.stock_out.index') }}">
                                            <i class="fas fa-box-open me-2"></i>Stock-Out
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}"
                                            href="{{ route('admin.sessions.index') }}">
                                            <i class="fas fa-user-clock me-2"></i>User Sessions
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Settings -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('superadmin.settings') ? 'active' : '' }}"
                                href="{{ route('superadmin.settings') }}">
                                <i class="fas fa-sliders-h me-2"></i>System Settings
                            </a>
                        </li>

                        <!-- Audit Log -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('superadmin.audits.*') ? 'active' : '' }}"
                                href="{{ route('superadmin.audits.index') }}">
                                <i class="fas fa-clipboard-list me-2"></i>Audit Log
                            </a>
                        </li>

                        <!-- Quick Links -->
                        <li class="nav-item mt-4">
                            <div class="nav-link text-center" style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">
                                <strong>Quick Links</strong>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-user-shield me-2"></i>Admin Panel
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('delivery.dashboard') }}">
                                <i class="fas fa-truck me-2"></i>Delivery Panel
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="fas fa-store me-2"></i>View Store
                            </a>
                        </li>

                        <!-- Logout -->
                        <li class="nav-item mt-4">
                            <a href="{{ route('logout') }}" class="nav-link"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="margin-left:270px;">
                <!-- Top navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <div class="navbar-brand">
                            <h5 class="mb-0">
                                <i class="fas fa-crown text-green me-2"></i>
                                Super Admin Dashboard
                                <small class="text-muted ms-2">{{ auth()->user()->email }}</small>
                            </h5>
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#superAdminNavbar">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="superAdminNavbar">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                                        role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-crown text-green me-1"></i>
                                        {{ Auth::user()->name }}
                                        <span class="super-admin-badge">SUPER ADMIN</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('superadmin.profile') }}">
                                            <i class="fas fa-user-cog me-2"></i>Super Admin Profile
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                            <i class="fas fa-user me-2"></i>My Profile
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('superadmin.settings') }}">
                                            <i class="fas fa-sliders-h me-2"></i>Settings
                                        </a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Page content -->
                <div class="container-fluid py-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Auto-collapse other menus when one is opened
        document.addEventListener('DOMContentLoaded', function() {
            var collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
            
            collapseElements.forEach(function(element) {
                element.addEventListener('click', function() {
                    var target = document.querySelector(this.getAttribute('href'));
                    var isExpanding = !target.classList.contains('show');
                    
                    // If we're expanding this menu, collapse others
                    if (isExpanding) {
                        collapseElements.forEach(function(otherElement) {
                            if (otherElement !== element) {
                                var otherTarget = document.querySelector(otherElement.getAttribute('href'));
                                if (otherTarget && otherTarget.classList.contains('show')) {
                                    otherTarget.classList.remove('show');
                                }
                            }
                        });
                    }
                });
            });
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @stack('scripts')
</body>

</html>