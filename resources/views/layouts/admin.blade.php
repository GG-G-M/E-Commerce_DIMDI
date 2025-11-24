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
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-green) 0%, var(--dark-green) 100%);
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            /* Reserve space for scrollbar */
            padding-right: 4px;
        }

        .sidebar .position-sticky {
            padding-top: 1rem;
            height: 100vh;
            overflow-y: auto;
            /* Prevent content shift */
            width: calc(100% - 4px);
        }

        /* Modern subtle scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        .sidebar:hover::-webkit-scrollbar-thumb {
            opacity: 1;
        }

        /* Firefox scrollbar */
        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .sidebar h4 {
            color: white;
            font-weight: 700;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            /* Prevent text wrapping */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 0.85rem 1.5rem;
            margin: 0.2rem 0.8rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            /* Prevent text wrapping */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            flex-shrink: 0;
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
            /* Prevent text wrapping */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar .btn-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Adjust nested menu items */
        .sidebar .nav.flex-column.ms-3 .nav-link {
            padding-left: 2rem;
            font-size: 0.9rem;
        }

        .bg-white {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Main content area with modern scrollbar */
        main {
            height: 100vh;
            overflow-y: auto;
        }

        /* Modern scrollbar for main content */
        main::-webkit-scrollbar {
            width: 6px;
        }

        main::-webkit-scrollbar-track {
            background: #f8f9fa;
        }

        main::-webkit-scrollbar-thumb {
            background: rgba(44, 143, 12, 0.2);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        main::-webkit-scrollbar-thumb:hover {
            background: rgba(44, 143, 12, 0.4);
        }

        /* Firefox scrollbar for main */
        main {
            scrollbar-width: thin;
            scrollbar-color: rgba(44, 143, 12, 0.2) #f8f9fa;
        }

        /* Chart Styles */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
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
            min-width: 0; /* Allow text truncation */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-cogs me-2"></i>DIMDI Admin
                    </h4>
                    <ul class="nav flex-column">

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"
                                href="{{ route('admin.customers.index') }}">
                                <i class="fas fa-users me-2"></i>
                                <span>Customers</span>
                            </a>
                        </li>

                        <!-- MANAGEMENT DROPDOWN -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#managementMenu"
                                role="button" aria-expanded="false" aria-controls="managementMenu">
                                <i class="fas fa-cogs me-2"></i>
                                <span>Management</span>
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
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}"
                                            href="{{ route('admin.suppliers.index') }}">
                                            <i class="fas fa-truck-loading me-2"></i>
                                            <span>Suppliers</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.warehouses.*') ? 'active' : '' }}"
                                            href="{{ route('admin.warehouses.index') }}">
                                            <i class="fas fa-warehouse me-2"></i>
                                            <span>Warehouses</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.stock_checkers.*') ? 'active' : '' }}"
                                            href="{{ route('admin.stock_checkers.index') }}">
                                            <i class="fas fa-user-check me-2"></i>
                                            <span>Stock Checkers</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}"
                                            href="{{ route('admin.deliveries.index') }}">
                                            <i class="fas fa-truck me-2"></i>
                                            <span>Delivery</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                                            href="{{ route('admin.categories.index') }}">
                                            <i class="fas fa-tags me-2"></i>
                                            <span>Categories</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"
                                            href="{{ route('admin.brands.index') }}">
                                            <i class="fas fa-tag me-2"></i>
                                            <span>Brands</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}"
                                            href="{{ route('admin.banners.index') }}">
                                            <i class="fas fa-image me-2"></i>
                                            <span>Banners</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>


                        <!-- INVENTORY DROPDOWN -->
                        <li class="nav-item">
                            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#inventoryMenu" role="button"
                                aria-expanded="false" aria-controls="inventoryMenu">
                                <i class="fas fa-boxes me-2"></i>
                                <span>Inventory</span>
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
                                            <i class="fas fa-box me-2"></i>
                                            <span>Products</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.low_stock.*') ? 'active' : '' }}"
                                            href="{{ route('admin.low_stock.index') }}">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <span>Low Stocks</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.stock_in.*') ? 'active' : '' }}"
                                            href="{{ route('admin.stock_in.index') }}">
                                            <i class="fas fa-boxes me-2"></i>
                                            <span>Stock-In</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.stock_out.*') ? 'active' : '' }}"
                                            href="{{ route('admin.stock_out.index') }}">
                                            <i class="fas fa-box-open me-2"></i>
                                            <span>Stock-Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>


                        <!-- ORDERS & REPORTS -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                                href="{{ route('admin.orders.index') }}">
                                <i class="fas fa-shopping-cart me-2"></i>
                                <span>Orders</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.sales-report.*') ? 'active' : '' }}"
                                href="{{ route('admin.sales-report.index') }}">
                                <i class="fas fa-chart-line me-2"></i>
                                <span>Sales Reports</span>
                            </a>
                        </li>

                        <li class="nav-item mt-4">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="fas fa-store me-2"></i>
                                <span>View Store</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                <span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>


            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
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
                                    <ul class="dropdown-menu">
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
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
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
    @stack('scripts')
</body>

</html>