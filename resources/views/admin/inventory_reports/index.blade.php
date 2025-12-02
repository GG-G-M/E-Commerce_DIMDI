@extends('layouts.admin')

@section('title', 'Inventory Reports')

@section('content')
    <div class="container-fluid px-4">

        {{-- Page Title --}}
        <h1 class="mt-4 text-success fw-bold">Inventory Reports</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-success">Dashboard</a></li>
            <li class="breadcrumb-item active text-success">Inventory Reports</li>
        </ol>

        {{-- ========================= --}}
        {{--       OVERVIEW CARDS     --}}
        {{-- ========================= --}}
        <div class="row g-4 mb-4">

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-success mb-1">Total Stock-In</h6>
                            <h3 class="fw-bold">{{ $overview['total_stock_in'] }}</h3>
                        </div>
                        <i class="fas fa-arrow-down text-success fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm bg-danger bg-opacity-10">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-danger mb-1">Total Stock-Out</h6>
                            <h3 class="fw-bold">{{ $overview['total_stock_out'] }}</h3>
                        </div>
                        <i class="fas fa-arrow-up text-danger fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-warning mb-1">Low Stock Items</h6>
                            <h3 class="fw-bold">{{ $overview['low_stock_count'] }}</h3>
                        </div>
                        <i class="fas fa-exclamation-triangle text-warning fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-primary mb-1">Total Products</h6>
                            <h3 class="fw-bold">{{ $overview['product_count'] }}</h3>
                        </div>
                        <i class="fas fa-boxes text-primary fa-2x"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- ========================= --}}
        {{--         CHARTS           --}}
        {{-- ========================= --}}
        <div class="row g-4 mb-4">

            {{-- Inventory Trend Chart --}}
            <div class="col-xl-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white fw-bold">
                        Inventory Trend (Stock Movement)
                    </div>
                    <div class="card-body">
                        <canvas id="inventoryTrendChart" height="140"></canvas>
                    </div>
                </div>
            </div>

            {{-- Stock In vs Stock Out --}}
            <div class="col-xl-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-bold">
                        Stock-In vs Stock-Out
                    </div>
                    <div class="card-body">
                        <canvas id="stockInOutChart" height="140"></canvas>
                    </div>
                </div>
            </div>

            {{-- Low Stock Items --}}
            <div class="col-xl-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-white fw-bold">
                        Low Stock Products
                    </div>
                    <div class="card-body">
                        <canvas id="lowStockChart" height="140"></canvas>
                    </div>
                </div>
            </div>

            {{-- Category Distribution --}}
            <div class="col-xl-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white fw-bold">
                        Inventory by Category
                    </div>
                    <div class="card-body">
                        <canvas id="categoryDistributionChart" height="140"></canvas>
                    </div>
                </div>
            </div>

        </div>

        {{-- ========================= --}}
        {{--    INVENTORY TABLE       --}}
        {{-- ========================= --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between">
                <span>Inventory Summary</span>

                {{-- Optional filters --}}
                <form action="" method="GET" class="d-flex gap-2 align-items-center">
                    {{-- Start Date --}}
                    <input type="date" name="start_date" class="form-control form-control-sm"
                        value="{{ request('start_date', \Carbon\Carbon::now()->subMonth()->format('Y-m-d')) }}"
                        placeholder="Start Date">

                    {{-- End Date --}}
                    <input type="date" name="end_date" class="form-control form-control-sm"
                        value="{{ request('end_date', \Carbon\Carbon::now()->format('Y-m-d')) }}" placeholder="End Date">

                    {{-- Category --}}
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Search --}}
                    <input type="text" name="search" class="form-control form-control-sm"
                        value="{{ request('search') }}" placeholder="Search product...">

                    <button class="btn btn-success btn-sm">Filter</button>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-success">
                            <tr>
                                <th>Product</th>
                                <th>Variant</th>
                                <th>Category</th>
                                <th>Stock-In</th>
                                <th>Stock-Out</th>
                                <th>Current Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventoryTable as $row)
                                <tr>
                                    <td>{{ $row['product_name'] }}</td>
                                    <td>{{ $row['variant_name'] ?? '—' }}</td>
                                    <td>{{ $row['category_name'] ?? '—' }}</td>
                                    <td class="text-success fw-bold">+{{ $row['total_stock_in'] }}</td>
                                    <td class="text-danger fw-bold">-{{ $row['total_stock_out'] }}</td>
                                    <td class="fw-bold">{{ $row['current_stock'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">No inventory data found.</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-3">
                    {{ $paginatedInventory->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        /* ================== INVENTORY TREND CHART ================== */
        const trendCtx = document.getElementById('inventoryTrendChart').getContext('2d');
        const trendLabels = @json($charts['trend']['labels'] ?? []);
        const trendData = @json($charts['trend']['data'] ?? []);

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Stock Level',
                    data: trendData,
                    borderColor: 'rgba(40, 167, 69, 1)',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        /* ================== STOCK-IN VS STOCK-OUT ================== */
        const inOutCtx = document.getElementById('stockInOutChart').getContext('2d');
        const inOutLabels = @json($charts['in_out']['labels'] ?? []);
        const stockInData = @json($charts['in_out']['stock_in'] ?? []);
        const stockOutData = @json($charts['in_out']['stock_out'] ?? []);

        new Chart(inOutCtx, {
            type: 'bar',
            data: {
                labels: inOutLabels,
                datasets: [{
                        label: 'Stock-In',
                        data: stockInData,
                        backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    },
                    {
                        label: 'Stock-Out',
                        data: stockOutData,
                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        /* ================== LOW STOCK CHART ================== */
        const lowCtx = document.getElementById('lowStockChart').getContext('2d');
        const lowLabels = @json($charts['low_stock']['labels'] ?? []);
        const lowData = @json($charts['low_stock']['data'] ?? []);

        new Chart(lowCtx, {
            type: 'bar',
            data: {
                labels: lowLabels,
                datasets: [{
                    label: 'Stock',
                    data: lowData,
                    backgroundColor: 'rgba(255, 193, 7, 0.7)',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        /* ================== CATEGORY DISTRIBUTION CHART ================== */
        const catCtx = document.getElementById('categoryDistributionChart').getContext('2d');
        const catLabels = @json($charts['categories']['labels'] ?? []);
        const catData = @json($charts['categories']['data'] ?? []);

        new Chart(catCtx, {
            type: 'pie',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catData,
                    backgroundColor: [
                        '#0d6efd', '#198754', '#ffc107', '#dc3545',
                        '#0dcaf0', '#6f42c1', '#fd7e14', '#6c757d'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endsection
