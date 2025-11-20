<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard - DIMDI Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0C6F8F;
            --dark-blue: #084A5A;
            --light-blue: #E6F3F5;
            --accent-blue: #4CAFCE;
            --sidebar-blue: #2c3e50;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.1);
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

        .bg-white {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Order Status Badges */
        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-shipped {
            background-color: #17a2b8;
            color: white;
        }

        .badge-out-for-delivery {
            background-color: #fd7e14;
            color: white;
        }

        .badge-delivered {
            background-color: #28a745;
            color: white;
        }

        .btn-pickup {
            background-color: #fd7e14;
            border-color: #fd7e14;
            color: white;
        }

        .btn-pickup:hover {
            background-color: #e56a00;
            border-color: #d86200;
            color: white;
        }

        .btn-deliver {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-deliver:hover {
            background-color: #218838;
            border-color: #1e7e34;
            color: white;
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
                        <i class="fas fa-truck me-2"></i>DIMDI Delivery
                    </h4>
                    <ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}"
            href="{{ route('delivery.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('delivery.orders.index') ? 'active' : '' }}"
            href="{{ route('delivery.orders.index') }}">
            <i class="fas fa-list me-2"></i>All Orders
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('delivery/orders/pickup') ? 'active' : '' }}"
            href="{{ route('delivery.orders.pickup') }}">
            <i class="fas fa-box me-2"></i>Pick Up Orders
        </a>
    </li>
    <!-- ADD THIS NEW LINK -->
    <li class="nav-item">
        <a class="nav-link {{ request()->is('delivery/orders/my-orders') ? 'active' : '' }}"
            href="{{ route('delivery.orders.my-orders') }}">
            <i class="fas fa-clipboard-list me-2"></i>My Orders
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('delivery/orders/delivered') ? 'active' : '' }}"
            href="{{ route('delivery.orders.delivered') }}">
            <i class="fas fa-check-circle me-2"></i>Delivered Orders
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('delivery.profile.show') ? 'active' : '' }}"
            href="{{ route('delivery.profile.show') }}">
            <i class="fas fa-user me-2"></i>My Profile
        </a>
    </li>
    <li class="nav-item mt-4">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-store me-2"></i>View Store
        </a>
    </li>
    <li class="nav-item">
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
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Top navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#deliveryNavbar">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="deliveryNavbar">
                            <ul class="navbar-nav me-auto">
                                <li class="nav-item">
                                    <span class="navbar-text">
                                        <i class="fas fa-truck me-1"></i> Delivery Dashboard
                                    </span>
                                </li>
                            </ul>
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                                        role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('delivery.profile.show') }}">My Profile</a></li>
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
    @stack('scripts')
</body>

</html>