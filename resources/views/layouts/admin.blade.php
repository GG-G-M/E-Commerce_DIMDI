<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - DIMDI Store</title>
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
            /* Custom scrollbar for Webkit browsers */
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
            padding-right: 10px;
        }

        /* Custom scrollbar styling for Webkit browsers */
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

        .sidebar .btn-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            padding: 0.85rem 1.5rem;
            text-align: left;
            width: 100%;
            border: none;
            background: transparent;
            font-weight: 500;
        }

        .sidebar .btn-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        main {
            margin-left: 270px !important;
            padding-left: 20px;
            /* Add extra padding on the left */
        }

        /* Ensure content doesn't get too close to sidebar */
        .container-fluid.py-4 {
            padding-left: 25px !important;
            padding-right: 15px !important;
        }

        .bg-white {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Chart Styles */
        .chart-container {
            position: relative;
            height: 300px;
            width: 90%;
        }

        /* Ensure dropdown icons don't cause wrapping */
        .sidebar .nav-link .float-end {
            flex-shrink: 0;
            margin-left: auto;
        }

        /* Make sidebar content flex to handle text properly */
        .sidebar .nav-link {
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link span {
            flex: 1;
            min-width: 0;
            /* Allow text truncation */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Fix for profile dropdown overflow */
        .navbar-nav .dropdown-menu {
            position: absolute;
            right: 0;
            left: auto;
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
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 col-lg-1 d-md-block sidebar collapse">
                <div class="position-sticky pt-2">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-cogs me-2"></i>DIMDI Admin
                    </h4>
                    @if(auth()->check() && auth()->user()->isSuperAdmin())
                        <div class="text-center mb-2">
                            <a href="{{ route('superadmin.dashboard') }}" class="btn btn-sm btn-outline-light">← Super Admin Panel</a>
                        </div>
                    @endif
                    <ul class="nav flex-column">

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"
                                href="{{ route('admin.customers.index') }}">
                                <i class="fas fa-users me-2"></i>Customers
                            </a>
                        </li>

                        <!-- MANAGEMENT DROPDOWN -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#managementMenu"
                                role="button" aria-expanded="false" aria-controls="managementMenu">
                                <i class="fas fa-cogs me-2"></i>Management
                                <i class="fas fa-chevron-down float-end"></i>
                            </a>

                            <div class="collapse 
        {{ request()->routeIs('admin.suppliers.*') ||
        request()->routeIs('admin.warehouses.*') ||
        request()->routeIs('admin.stock_checkers.*') ||
        request()->routeIs('admin.deliveries.*') ||
        request()->routeIs('admin.categories.*') ||
        request()->routeIs('admin.brands.*') ||
        request()->routeIs('admin.banners.*')
            ? 'show'
            : '' }}"
                                id="managementMenu">

                                <ul class="nav flex-column ms-3">

                                    {{-- <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.abouts.*') ? 'active' : '' }}"
                                            href="{{ route('admin.abouts.index') }}">
                                            <i class="fas fa-info-circle me-2"></i>About
                                        </a>
                                    </li> --}}

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
                                        <a class="nav-link {{ request()->routeIs('admin.stock_checkers.*') ? 'active' : '' }}"
                                            href="{{ route('admin.stock_checkers.index') }}">
                                            <i class="fas fa-user-check me-2"></i>Stock Checkers
                                        </a>
                                    </li>

                                    {{-- <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}"
                                            href="{{ route('admin.deliveries.index') }}">
                                            <i class="fas fa-truck me-2"></i>Delivery
                                        </a>
                                    </li> --}}

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
                                </ul>
                            </div>
                        </li>




                        <!-- INVENTORY DROPDOWN -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#inventoryMenu" role="button"
                                aria-expanded="false" aria-controls="inventoryMenu">
                                <i class="fas fa-boxes me-2"></i>Inventory
                                <i class="fas fa-chevron-down float-end"></i>
                            </a>

                            <div class="collapse 
        {{ request()->routeIs('admin.products.*') ||
        request()->routeIs('admin.low_stock.*') ||
        request()->routeIs('admin.stock_in.*') ||
        request()->routeIs('admin.stock_out.*')
            ? 'show'
            : '' }}"
                                id="inventoryMenu">

                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                                            href="{{ route('admin.products.index') }}">
                                            <i class="fas fa-box me-2"></i>Products
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
                                </ul>
                            </div>
                        </li>


                        <!-- ORDERS & REPORTS -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                                href="{{ route('admin.orders.index') }}">
                                <i class="fas fa-shopping-cart me-2"></i>Orders
                            </a>
                        </li>

                        {{-- <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.inventory-reports.*') ? 'active' : '' }}"
                                href="{{ route('admin.inventory-reports.index') }}">
                                <i class="fas fa-boxes me-2"></i>Inventory Reports
                            </a>
                        </li> --}}

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.sales-report.*') ? 'active' : '' }}"
                                href="{{ route('admin.sales-report.index') }}">
                                <i class="fas fa-chart-line me-2"></i>Sales Reports
                            </a>
                        </li>

                        <li class="nav-item mt-4">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="fas fa-store me-2"></i>View Store
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link"
                                onclick="logoutWithConfirm(event);">
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
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#adminNavbar">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="adminNavbar">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                                        role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('profile.show') }}">My
                                                Profile</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Page content -->
                <div class="container-fluid py-4">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @include('components.ui-elements')
    @stack('scripts')
</body>

</html>
