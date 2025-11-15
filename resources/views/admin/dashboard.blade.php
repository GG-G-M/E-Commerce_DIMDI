@extends('layouts.admin')

@section('content')
<style>
    .stats-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }
    
    .card-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
    }
    
    .card-success {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        color: white;
    }
    
    .card-info {
        background: linear-gradient(135deg, #4CAF50, #66BB6A);
        color: white;
    }
    
    .card-warning {
        background: linear-gradient(135deg, #FFA000, #FFB300);
        color: white;
    }
    
    .stats-icon {
        font-size: 2rem;
        opacity: 0.8;
    }
    
    .stats-number {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
    }
    
    .stats-label {
        font-size: 0.85rem;
        opacity: 0.9;
        font-weight: 500;
    }
    
    .dashboard-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #2C8F0C;
    }
    
    .section-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }
    
    .section-card .card-header {
        background: white;
        border-bottom: 2px solid #E8F5E6;
        font-weight: 600;
        color: #2C8F0C;
        padding: 1rem 1.5rem;
    }
    
    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
    }
    
    .badge-success {
        background-color: #E8F5E6;
        color: #2C8F0C;
    }
    
    .badge-warning {
        background-color: #FFF3CD;
        color: #856404;
    }
    
    .badge-danger {
        background-color: #F8D7DA;
        color: #721C24;
    }
    
    .product-image {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        object-fit: cover;
    }
    
    .performance-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .filter-active {
        background-color: #2C8F0C !important;
        color: white !important;
        border-color: #2C8F0C !important;
    }
    
    .custom-date-inputs {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid #E8F5E6;
    }
    .clickable-card {
    transition: all 0.3s ease;
    cursor: pointer;
    }

    .clickable-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 12px 20px rgba(0,0,0,0.2) !important;
    }

    /* Make sure the link covers the entire card */
    .text-decoration-none {
        display: block;
    }
</style>

<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">Dashboard Overview</h1>
            <p class="mb-0 text-muted">Welcome back, {{ Auth::user()->name }}! Here's what's happening with your store.</p>
        </div>
        <div class="text-end">
            <small class="text-muted">Last updated: {{ now()->format('M d, Y \\a\\t h:i A') }}</small>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card section-card mb-4">
    <div class="card-body">
        <form id="dashboardFilter" method="GET" action="{{ route('admin.dashboard') }}">
            <div class="row align-items-end">
                <div class="col-md-8">
                    <label class="form-label fw-bold text-muted mb-2">Filter by Date Range:</label>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="filter" value="all" id="filter_all" 
                               {{ $filter == 'all' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-success" for="filter_all">All Time</label>

                        <input type="radio" class="btn-check" name="filter" value="today" id="filter_today" 
                               {{ $filter == 'today' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-success" for="filter_today">Today</label>

                        <input type="radio" class="btn-check" name="filter" value="week" id="filter_week" 
                               {{ $filter == 'week' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-success" for="filter_week">This Week</label>

                        <input type="radio" class="btn-check" name="filter" value="month" id="filter_month" 
                               {{ $filter == 'month' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-success" for="filter_month">This Month</label>

                        <input type="radio" class="btn-check" name="filter" value="year" id="filter_year" 
                               {{ $filter == 'year' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-success" for="filter_year">This Year</label>

                        <input type="radio" class="btn-check" name="filter" value="custom" id="filter_custom" 
                               {{ $filter == 'custom' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-success" for="filter_custom">Custom</label>
                    </div>

                    <!-- Custom Date Inputs -->
                    <div id="customDateInputs" class="custom-date-inputs" 
                         style="{{ $filter == 'custom' ? 'display: block;' : 'display: none;' }}">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ $startDate }}" max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-5">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ $endDate }}" max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-success w-100">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    @if($filter != 'all')
                    <div class="d-flex align-items-center justify-content-end">
                        <small class="text-muted me-2">
                            @php
                                $displayText = match($filter) {
                                    'today' => 'Today: ' . now()->format('M d, Y'),
                                    'week' => 'This Week: ' . now()->startOfWeek()->format('M d') . ' - ' . now()->endOfWeek()->format('M d, Y'),
                                    'month' => 'This Month: ' . now()->format('F Y'),
                                    'year' => 'This Year: ' . now()->format('Y'),
                                    'custom' => 'Custom: ' . ($startDate ? Carbon\Carbon::parse($startDate)->format('M d, Y') : '') . ' - ' . ($endDate ? Carbon\Carbon::parse($endDate)->format('M d, Y') : ''),
                                    default => ''
                                };
                            @endphp
                            {{ $displayText }}
                        </small>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <!-- Total Products Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
            <div class="card stats-card card-primary h-100 clickable-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="stats-label">TOTAL PRODUCTS</div>
                            <div class="stats-number">{{ $stats['total_products'] }}</div>
                            <small>
                                @if($filter != 'all')
                                    Added in selected period
                                @else
                                    Active in catalog
                                @endif
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box stats-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total Orders Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
            <div class="card stats-card card-success h-100 clickable-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="stats-label">TOTAL ORDERS</div>
                            <div class="stats-number">{{ $stats['total_orders'] }}</div>
                            <small>
                                @if($filter != 'all')
                                    Orders in selected period
                                @else
                                    All time orders
                                @endif
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart stats-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total Customers Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('admin.customers.index') }}" class="text-decoration-none">
            <div class="card stats-card card-info h-100 clickable-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="stats-label">TOTAL CUSTOMERS</div>
                            <div class="stats-number">{{ $stats['total_customers'] }}</div>
                            <small>
                                @if($filter != 'all')
                                    Registered in selected period
                                @else
                                    Registered users
                                @endif
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users stats-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total Revenue Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card card-warning h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stats-label">TOTAL REVENUE</div>
                        <div class="stats-number">₱{{ number_format($stats['revenue'], 2) }}</div>
                        <small>
                            @if($filter != 'all')
                                Revenue in selected period
                            @else
                                Lifetime sales
                            @endif
                        </small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Chart Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card section-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-chart-line me-2"></i>Sales Performance
                    @if($filter != 'all')
                        <small class="text-muted ms-2">(Filtered)</small>
                    @endif
                </h6>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-success active" data-metric="revenue">
                        Revenue
                    </button>
                    <button type="button" class="btn btn-outline-success" data-metric="orders">
                        Orders
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 300px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Selling Products -->
    <div class="col-lg-6">
        <div class="card section-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-chart-line me-2"></i>Top Selling Products
                    @if($filter != 'all')
                        <small class="text-muted ms-2">(Filtered)</small>
                    @endif
                </h6>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-success">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSellingProducts as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" 
                                             class="product-image me-2">
                                        <div>
                                            <a href="{{ route('admin.products.edit', $item->product) }}" 
                                               class="text-decoration-none fw-bold" style="color: #2C8F0C;">
                                                {{ Str::limit($item->product->name, 25) }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $item->product->sku }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-success performance-badge">
                                        {{ $item->product->category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="fw-bold text-success">
                                    {{ $item->total_quantity_sold }}
                                    <br>
                                    <small class="text-muted">units</small>
                                </td>
                                <td class="fw-bold">
                                    ₱{{ number_format($item->total_revenue, 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-chart-bar fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No sales data available</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Selling Products -->
    <div class="col-lg-6">
        <div class="card section-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-chart-line me-2" style="transform: rotate(180deg);"></i>Low Selling Products
                    @if($filter != 'all')
                        <small class="text-muted ms-2">(Filtered)</small>
                    @endif
                </h6>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-warning">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowSellingProducts as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" 
                                             class="product-image me-2">
                                        <div>
                                            <a href="{{ route('admin.products.edit', $item->product) }}" 
                                               class="text-decoration-none fw-bold" style="color: #2C8F0C;">
                                                {{ Str::limit($item->product->name, 25) }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $item->product->sku }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-warning performance-badge">
                                        {{ $item->product->category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="fw-bold text-warning">
                                    {{ $item->total_quantity_sold }}
                                    <br>
                                    <small class="text-muted">units</small>
                                </td>
                                <td class="fw-bold">
                                    ₱{{ number_format($item->total_revenue, 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-chart-bar fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No sales data available</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Quick Stats -->
    <div class="col-md-4">
        <div class="card section-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-info-circle me-2"></i>Performance Summary
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Pending Orders:</span>
                    <span class="fw-bold text-warning">{{ $stats['pending_orders'] }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Total Categories:</span>
                    <span class="fw-bold text-success">{{ $stats['total_categories'] }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Avg. Order Value:</span>
                    <span class="fw-bold text-info">
                        ₱{{ $stats['total_orders'] > 0 ? number_format($stats['revenue'] / $stats['total_orders'], 2) : '0.00' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-8">
        <div class="card section-card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-4">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-plus me-2"></i>Add Product
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-tags me-2"></i>Add Category
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-shopping-cart me-2"></i>View Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const salesData = @json($salesData);
    
    // Prepare chart data
    const labels = salesData.map(item => item.period);
    const revenueData = salesData.map(item => parseFloat(item.revenue));
    const orderData = salesData.map(item => item.order_count);

    // Create chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue',
                data: revenueData,
                borderColor: '#2C8F0C',
                backgroundColor: 'rgba(44, 143, 12, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                yAxisID: 'y'
            }, {
                label: 'Orders',
                data: orderData,
                borderColor: '#FFA000',
                backgroundColor: 'rgba(255, 160, 0, 0.1)',
                borderWidth: 2,
                fill: false,
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue (₱)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: false,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Orders'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label === 'Revenue') {
                                return `Revenue: ₱${parseFloat(context.parsed.y).toLocaleString()}`;
                            } else {
                                return `Orders: ${context.parsed.y}`;
                            }
                        }
                    }
                }
            }
        }
    });

    // Toggle between revenue and orders view
    const metricButtons = document.querySelectorAll('[data-metric]');
    metricButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active button
            metricButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const metric = this.dataset.metric;
            
            if (metric === 'revenue') {
                salesChart.data.datasets[0].hidden = false;
                salesChart.data.datasets[1].hidden = true;
                salesChart.options.scales.y.display = true;
                salesChart.options.scales.y1.display = false;
            } else {
                salesChart.data.datasets[0].hidden = true;
                salesChart.data.datasets[1].hidden = false;
                salesChart.options.scales.y.display = false;
                salesChart.options.scales.y1.display = true;
            }
            
            salesChart.update();
        });
    });

    // Filter functionality (existing code)
    const filterRadios = document.querySelectorAll('input[name="filter"]');
    const customDateInputs = document.getElementById('customDateInputs');
    const form = document.getElementById('dashboardFilter');

    filterRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateInputs.style.display = 'block';
            } else {
                customDateInputs.style.display = 'none';
                if (this.value !== 'custom') {
                    form.submit();
                }
            }
        });
    });

    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    [startDate, endDate].forEach(input => {
        input.addEventListener('change', function() {
            if (document.querySelector('input[name="filter"]:checked').value === 'custom') {
                form.submit();
            }
        });
    });
});
</script>
@endsection