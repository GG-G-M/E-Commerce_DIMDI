<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - DIMDI Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
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
                            <ul class="navbar-nav me-auto">
                                @if(auth()->check() && auth()->user()->isSuperAdmin())
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-outline-success me-2" href="{{ route('superadmin.dashboard') }}">
                                            <i class="fas fa-crown me-1"></i>Super Admin Panel
                                        </a>
                                    </li>
                                @endif
                            </ul>
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
