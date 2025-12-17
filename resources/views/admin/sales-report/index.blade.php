{{-- resources/views/admin/sales-report/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Sales Reports')

@section('content')
<style>
    /* === Modern Design System === */
    :root {
        --primary-green: #2C8F0C;
        --secondary-green: #4CAF50;
        --light-green: #E8F5E9;
        --dark-green: #1B5E20;
        --gradient-green: linear-gradient(135deg, #2C8F0C, #4CAF50);
        --gradient-dark: linear-gradient(135deg, #1B5E20, #2C8F0C);
        --gray-50: #F9FAFB;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-300: #D1D5DB;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --radius-lg: 16px;
        --radius-md: 12px;
        --radius-sm: 8px;
    }

    /* Unified Dashboard Header Card */
    .dashboard-header-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 2rem;
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
    }

    .dashboard-header-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .dashboard-header {
        background: var(--gradient-green);
        color: white;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle at top right, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .dashboard-header h1 {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.25rem;
        position: relative;
        z-index: 2;
    }

    .dashboard-header .subtitle {
        opacity: 0.9;
        font-size: 0.95rem;
        position: relative;
        z-index: 2;
    }

    .header-actions {
        position: relative;
        z-index: 2;
        margin-top: 1rem;
    }

    /* Stats Grid - Integrated into dashboard card */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1px;
        background: var(--gray-200);
        margin-top: 1px;
    }

    .stat-item {
        background: white;
        padding: 1.5rem;
        text-align: center;
        position: relative;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        background: var(--gray-50);
        transform: translateY(-2px);
    }

    .stat-item:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 20%;
        height: 60%;
        width: 1px;
        background: var(--gray-200);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 0.25rem;
        line-height: 1.2;
    }

    .stat-label {
        color: var(--gray-600);
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        background: var(--light-green);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: var(--primary-green);
        font-size: 1.25rem;
    }

    /* Action Buttons */
    .action-btn {
        padding: 0.5rem 1.25rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary {
        background: white;
        color: var(--primary-green);
        border: 2px solid rgba(255,255,255,0.3);
    }

    .btn-primary:hover {
        background: rgba(255,255,255,0.2);
        border-color: white;
        transform: translateY(-1px);
        box-shadow: var(--shadow);
        text-decoration: none;
    }

    .btn-outline {
        background: transparent;
        color: white;
        border: 2px solid rgba(255,255,255,0.3);
    }

    .btn-outline:hover {
        background: rgba(255,255,255,0.1);
        border-color: white;
        transform: translateY(-1px);
        box-shadow: var(--shadow);
        text-decoration: none;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-200);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .filter-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-1px);
    }

    .filter-header {
        background: var(--gray-50);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        font-weight: 600;
        color: var(--gray-700);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-body {
        padding: 1.5rem;
    }

    /* Chart Cards */
    .chart-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-200);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .chart-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .chart-header {
        background: var(--gradient-green);
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
    }

    .chart-body {
        padding: 1.5rem;
        position: relative;
    }

    /* Enhanced Chart Styles */
    .chart-container-enhanced {
        position: relative;
        height: 280px;
        width: 100%;
        margin-bottom: 1rem;
    }

    .chart-stats {
        background: var(--gray-50);
        border-radius: var(--radius-sm);
        padding: 1rem;
        border-left: 3px solid var(--primary-green);
    }

    /* Payment Method Legend */
    .payment-legend {
        background: var(--gray-50);
        border-radius: var(--radius-sm);
        padding: 1rem;
        margin-top: 1rem;
    }

    .payment-item {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-sm);
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }

    .payment-item:hover {
        border-color: var(--primary-green);
        transform: translateX(3px);
    }

    .color-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
    }

    /* Orders Table */
    .orders-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-200);
        overflow: hidden;
        margin-top: 2rem;
    }

    .orders-header {
        background: var(--gradient-green);
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        background: var(--gray-50);
        color: var(--gray-700);
        font-weight: 600;
        padding: 1rem;
        border-bottom: 2px solid var(--gray-300);
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
        border-color: var(--gray-200);
    }

    .table tbody tr:hover {
        background: var(--gray-50);
    }

    .badge-success {
        background: var(--gradient-green);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    /* Pagination */
    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-link {
        color: var(--primary-green);
        border-color: var(--gray-300);
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-green);
        border-color: var(--primary-green);
        color: white;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-item:not(:last-child)::after {
            display: none;
        }

        .stat-item:not(:last-child) {
            border-bottom: 1px solid var(--gray-200);
        }

        .chart-container-enhanced {
            height: 250px;
        }

        .dashboard-header {
            padding: 1.25rem 1.5rem;
        }

        .dashboard-header h1 {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .header-actions {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }

        .chart-header {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }
    }

    /* Custom Date Range */
    #customDateRange {
        background: var(--gray-50);
        border-radius: var(--radius-sm);
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid var(--gray-200);
    }

    /* Form Controls */
    .form-select, .form-control {
        border: 2px solid var(--gray-300);
        border-radius: var(--radius-sm);
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .form-select:focus, .form-control:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(44, 143, 12, 0.1);
        outline: none;
    }

    .form-label {
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
        display: block;
    }
</style>

<!-- Unified Dashboard Header Card with Stats -->
<div class="dashboard-header-card">
    <!-- Header Section -->
    <div class="dashboard-header">
        <h1>Sales Reports</h1>
        <p class="subtitle">Track and analyze your sales performance</p>
        
        <div class="header-actions d-flex gap-2">
            <a href="{{ route('admin.sales-report.comparison') }}" class="action-btn btn-outline">
                <i class="fas fa-chart-line me-1"></i> Year Comparison
            </a>
            <a href="{{ route('admin.sales-report.export-pdf', request()->query()) }}" class="action-btn btn-primary" target="_blank">
                <i class="fas fa-file-pdf me-1"></i> Download PDF
            </a>
        </div>
    </div>

    <!-- Stats Grid Section -->
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-value">₱{{ number_format($salesData['totalSales'], 2) }}</div>
            <div class="stat-label">Total Sales</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-value">{{ $salesData['totalOrders'] }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="stat-value">₱{{ number_format($salesData['averageOrderValue'], 2) }}</div>
            <div class="stat-label">Avg Order Value</div>
        </div>
    </div>
</div>

<!-- Filters Card -->
<div class="filter-card">
    <div class="filter-header">
        <i class="fas fa-filter"></i> Filter Reports
    </div>
    <div class="filter-body">
        <form action="{{ route('admin.sales-report.index') }}" method="GET" id="filterForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Date Range</label>
                    <select name="date_range" class="form-select" id="dateRangeSelect" onchange="handleDateRangeChange()">
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="this_year" {{ request('date_range') == 'this_year' ? 'selected' : '' }}>This Year</option>
                        <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select" onchange="submitForm()">
                        <option value="all">All Payments</option>
                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                        <option value="gcash" {{ request('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                        <option value="grab_pay" {{ request('payment_method') == 'grab_pay' ? 'selected' : '' }}>GrabPay</option>
                    </select>
                </div>
            </div>
            
            <!-- Custom Date Range -->
            <div id="customDateRange" style="display: {{ request('date_range') == 'custom' ? 'block' : 'none' }};">
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" id="startDate" value="{{ request('start_date') }}" max="{{ date('Y-m-d') }}" onchange="handleCustomDateChange()">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" id="endDate" value="{{ request('end_date') }}" max="{{ date('Y-m-d') }}" onchange="handleCustomDateChange()">
                    </div>
                </div>
            </div>
            
            <button type="submit" id="hiddenSubmit" style="display: none;"></button>
        </form>
    </div>
</div>

<!-- Charts Section -->
<div class="row mt-3">
    <!-- Sales Trend Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="chart-card">
            <div class="chart-header">
                <div class="d-flex align-items-center">
                    <i class="fas fa-chart-line me-2"></i> 
                    <span>Sales Trend</span>
                    <span class="badge bg-white text-dark ms-2">
                        <i class="fas fa-calendar me-1"></i>{{ $salesData['dateRangeText'] }}
                    </span>
                </div>
                <button class="btn btn-sm btn-white text-dark" onclick="toggleChartType()">
                    <i class="fas fa-exchange-alt me-1"></i> Switch View
                </button>
            </div>
            <div class="chart-body">
                <div class="chart-container-enhanced">
                    <canvas id="salesTrendChart"></canvas>
                </div>
                <div class="chart-stats">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="text-success fw-bold">₱{{ number_format($salesData['dailySales']->max('total') ?? 0, 2) }}</div>
                            <small class="text-muted">Peak Day</small>
                        </div>
                        <div class="col-4">
                            <div class="text-success fw-bold">₱{{ number_format($salesData['dailySales']->avg('total') ?? 0, 2) }}</div>
                            <small class="text-muted">Daily Average</small>
                        </div>
                        <div class="col-4">
                            <div class="text-success fw-bold">{{ $salesData['dailySales']->count() }} days</div>
                            <small class="text-muted">Period</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Method Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <i class="fas fa-credit-card me-2"></i> Payment Distribution
                </div>
                <div class="text-white">
                    {{ $salesData['totalOrders'] }} orders
                </div>
            </div>
            <div class="chart-body">
                <div class="chart-container-enhanced">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
                <div class="payment-legend">
                    @php
                        $colors = ['#2C8F0C', '#4CAF50', '#8BC34A', '#CDDC39', '#AED581'];
                    @endphp
                    @if($salesData['salesByPayment']->count() > 0)
                        @foreach($salesData['salesByPayment'] as $method => $data)
                        <div class="payment-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="color-indicator" style="background-color: {{ $colors[$loop->index] ?? '#2C8F0C' }}"></div>
                                <span class="fw-medium">{{ ucfirst($method) }}</span>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">₱{{ number_format($data['total'], 2) }}</div>
                                <small class="text-muted">{{ $data['count'] }} orders • {{ number_format($data['percentage'], 1) }}%</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center mb-0">No payment method data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="orders-card">
    <div class="orders-header">
        <div class="d-flex align-items-center">
            <i class="fas fa-receipt me-2"></i> Sales Orders
        </div>
        <div class="text-white">
            Showing {{ $orders->count() }} of {{ $orders->total() }} orders
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Payment Method</th>
                    <th>Total Amount</th>
                    <th>Items</th>
                    <th>Delivery Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>
                        <strong>{{ $order->order_number }}</strong>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $order->customer_name }}</div>
                        <small class="text-muted">{{ $order->customer_email }}</small>
                    </td>
                    <td>
                        <span class="badge-success">{{ $order->payment_method }}</span>
                    </td>
                    <td>
                        <strong class="text-success">₱{{ number_format($order->total_amount, 2) }}</strong>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $order->items->count() }} items</span>
                    </td>
                    <td>
                        @if($order->delivered_at)
                            <span class="text-success">
                                {{ $order->delivered_at->format('M j, Y g:i A') }}
                            </span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
                @if($orders->count() === 0)
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-search fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No orders found for the selected filters.</p>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="d-flex justify-content-center p-3 border-top">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Enhanced Sales Report Charts JavaScript
let salesTrendChart = null;
let paymentMethodChart = null;
let formSubmissionTimer = null;
let customDateWaitTimer = null;
let currentChartType = 'line';

// Chart configuration functions
function initializeSalesTrendChart() {
    const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
    
    destroyChart(salesTrendChart);
    
    const dateRange = "{{ request('date_range', 'this_month') }}";
    
    // Determine which data to use based on date range
    let labels = [];
    let data = [];
    let label = '';
    
    if (dateRange === 'this_year' || dateRange === 'custom' && isLongRange()) {
        labels = {!! json_encode($salesData['monthlySales']->pluck('month')->toArray()) !!};
        data = {!! json_encode($salesData['monthlySales']->pluck('total')->toArray()) !!};
        label = "Monthly Sales (₱)";
    } else if (dateRange === 'this_week' || (dateRange === 'custom' && isMediumRange())) {
        labels = {!! json_encode($salesData['weeklySales']->pluck('week_range')->toArray()) !!};
        data = {!! json_encode($salesData['weeklySales']->pluck('total')->toArray()) !!};
        label = "Weekly Sales (₱)";
    } else {
        labels = {!! json_encode($salesData['dailySales']->pluck('date')->toArray()) !!};
        data = {!! json_encode($salesData['dailySales']->pluck('total')->toArray()) !!};
        label = "Daily Sales (₱)";
    }
    
    if (data.length > 0) {
        const gradient = salesTrendCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(44, 143, 12, 0.3)');
        gradient.addColorStop(1, 'rgba(44, 143, 12, 0.05)');
        
        salesTrendChart = new Chart(salesTrendCtx, {
            type: currentChartType,
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    backgroundColor: currentChartType === 'line' ? gradient : '#2C8F0C',
                    borderColor: "#2C8F0C",
                    pointBackgroundColor: "#2C8F0C",
                    pointBorderColor: "#ffffff",
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: "#1E6A08",
                    pointHoverBorderColor: "#ffffff",
                    borderWidth: currentChartType === 'line' ? 3 : 1,
                    tension: 0.4,
                    fill: currentChartType === 'line',
                    data: data,
                }],
            },
            options: getEnhancedChartOptions(label)
        });
    } else {
        showNoDataMessage('salesTrendChart', 'Sales Trend', 'No sales data available for the selected filters.');
    }
}

function initializePaymentMethodChart() {
    const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
    
    destroyChart(paymentMethodChart);
    
    const paymentLabels = {!! json_encode($salesData['salesByPayment']->keys()->map(fn($key) => ucfirst($key))->toArray()) !!};
    const paymentData = {!! json_encode($salesData['salesByPayment']->pluck('total')->toArray()) !!};
    const paymentColors = ['#2C8F0C', '#4CAF50', '#8BC34A', '#CDDC39', '#AED581'];
    
    if (paymentData.length > 0) {
        paymentMethodChart = new Chart(paymentMethodCtx, {
            type: 'doughnut',
            data: {
                labels: paymentLabels,
                datasets: [{
                    data: paymentData,
                    backgroundColor: paymentColors,
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverBorderColor: '#ffffff',
                    hoverBorderWidth: 4,
                    hoverOffset: 8,
                }],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: 'rgba(44, 143, 12, 0.9)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#2C8F0C',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ₱${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            },
        });
    } else {
        document.getElementById('paymentMethodChart').closest('.card').innerHTML = `
            <div class="card-header card-header-custom">
                <i class="fas fa-credit-card me-2"></i> Payment Distribution
            </div>
            <div class="card-body text-center py-5">
                <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                <p class="text-muted">No payment method data available for the selected filters.</p>
            </div>
        `;
    }
}

function getEnhancedChartOptions(label) {
    return {
        maintainAspectRatio: false,
        responsive: true,
        interaction: {
            intersect: false,
            mode: 'index',
        },
        scales: {
            x: {
                grid: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    color: '#6B7280',
                    font: {
                        size: 11
                    },
                    maxRotation: 45
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                },
                ticks: {
                    color: '#6B7280',
                    callback: function(value) {
                        if (value >= 1000) {
                            return '₱' + (value / 1000).toFixed(0) + 'K';
                        }
                        return '₱' + value;
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(44, 143, 12, 0.9)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: '#2C8F0C',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        return `${label}: ₱${context.raw.toLocaleString()}`;
                    },
                    title: function(tooltipItems) {
                        return tooltipItems[0].label;
                    }
                }
            }
        },
        animation: {
            duration: 1000,
            easing: 'easeOutQuart'
        },
        elements: {
            line: {
                tension: 0.4
            }
        }
    };
}

// Interactive Features
function toggleChartType() {
    currentChartType = currentChartType === 'line' ? 'bar' : 'line';
    initializeSalesTrendChart();
    
    // Update button text
    const button = document.querySelector('.chart-actions .btn');
    if (button) {
        const icon = currentChartType === 'line' ? 'fa-chart-bar' : 'fa-chart-line';
        const text = currentChartType === 'line' ? 'Switch View' : 'Back to Line';
        button.innerHTML = `<i class="fas ${icon}"></i> ${text}`;
    }
}

function exportChartAsImage(chartId, filename) {
    const chartCanvas = document.getElementById(chartId);
    const link = document.createElement('a');
    link.download = filename + '.png';
    link.href = chartCanvas.toDataURL('image/png');
    link.click();
}

// Filter Form Functions
function toggleCustomDate() {
    const dateRange = document.querySelector('select[name="date_range"]').value;
    const customDateRange = document.getElementById('customDateRange');
    
    if (dateRange === 'custom') {
        customDateRange.style.display = 'block';
    } else {
        customDateRange.style.display = 'none';
        submitFormWithDelay();
    }
}

function handleDateRangeChange() {
    const dateRange = document.getElementById('dateRangeSelect').value;
    
    if (dateRange === 'custom') {
        document.getElementById('customDateRange').style.display = 'block';
    } else {
        document.getElementById('customDateRange').style.display = 'none';
        submitFormWithDelay();
    }
}

function handleCustomDateChange() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (customDateWaitTimer) {
        clearTimeout(customDateWaitTimer);
    }
    
    if (startDate && endDate) {
        customDateWaitTimer = setTimeout(() => {
            submitFormWithDelay();
        }, 500);
    }
}

function submitForm() {
    if (formSubmissionTimer) {
        clearTimeout(formSubmissionTimer);
    }
    
    document.getElementById('filterForm').submit();
}

function submitFormWithDelay() {
    if (formSubmissionTimer) {
        clearTimeout(formSubmissionTimer);
    }
    
    formSubmissionTimer = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 300);
}

function exportReport(type) {
    const form = document.getElementById('filterForm');
    const action = "{{ route('admin.sales-report.export') }}";
    
    const exportForm = document.createElement('form');
    exportForm.method = 'GET';
    exportForm.action = action;
    
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        if (input.name) {
            const clone = input.cloneNode(true);
            exportForm.appendChild(clone);
        }
    });
    
    const exportTypeInput = document.createElement('input');
    exportTypeInput.type = 'hidden';
    exportTypeInput.name = 'export_type';
    exportTypeInput.value = type;
    exportForm.appendChild(exportTypeInput);
    
    document.body.appendChild(exportForm);
    exportForm.submit();
    document.body.removeChild(exportForm);
}

// Utility Functions
function destroyChart(chart) {
    if (chart) {
        chart.destroy();
    }
}

function isLongRange() {
    const startDate = "{{ request('start_date') }}";
    const endDate = "{{ request('end_date') }}";
    
    if (!startDate || !endDate) return false;
    
    const start = new Date(startDate);
    const end = new Date(endDate);
    const diffTime = Math.abs(end - start);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    return diffDays > 90;
}

function isMediumRange() {
    const startDate = "{{ request('start_date') }}";
    const endDate = "{{ request('end_date') }}";
    
    if (!startDate || !endDate) return false;
    
    const start = new Date(startDate);
    const end = new Date(endDate);
    const diffTime = Math.abs(end - start);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    return diffDays >= 30 && diffDays <= 90;
}

function showNoDataMessage(canvasId, title, message) {
    const card = document.getElementById(canvasId).closest('.card');
    card.innerHTML = `
        <div class="card-header card-header-custom">
            <i class="fas fa-chart-line me-2"></i> ${title}
        </div>
        <div class="card-body text-center py-5">
            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
            <p class="text-muted">${message}</p>
        </div>
    `;
}

// Real-time Data Updates (Optional)
function refreshChartData() {
    // This function can be extended to fetch real-time data
    console.log('Refreshing chart data...');
    initializeSalesTrendChart();
    initializePaymentMethodChart();
}

// Chart Performance Optimization
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Event Listeners and Initialization
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeSalesTrendChart();
    initializePaymentMethodChart();
    
    // Add chart export buttons
    addChartExportButtons();
    
    // Add real-time refresh (optional)
    // setInterval(refreshChartData, 300000); // Refresh every 5 minutes
    
    // Add resize debouncing
    const debouncedResize = debounce(function() {
        initializeSalesTrendChart();
        initializePaymentMethodChart();
    }, 250);
    
    window.addEventListener('resize', debouncedResize);
});

// Add export buttons to charts
function addChartExportButtons() {
    const salesChartHeader = document.querySelector('#salesTrendChart').closest('.card').querySelector('.card-header');
    const paymentChartHeader = document.querySelector('#paymentMethodChart').closest('.card').querySelector('.card-header');
    
    if (salesChartHeader && !salesChartHeader.querySelector('.export-btn')) {
        const exportBtn = document.createElement('button');
        exportBtn.className = 'btn btn-sm btn-outline-success export-btn ms-2';
        exportBtn.innerHTML = '<i class="fas fa-download me-1"></i> Export';
        exportBtn.onclick = () => exportChartAsImage('salesTrendChart', 'sales-trend');
        salesChartHeader.appendChild(exportBtn);
    }
    
    if (paymentChartHeader && !paymentChartHeader.querySelector('.export-btn')) {
        const exportBtn = document.createElement('button');
        exportBtn.className = 'btn btn-sm btn-outline-success export-btn ms-2';
        exportBtn.innerHTML = '<i class="fas fa-download me-1"></i> Export';
        exportBtn.onclick = () => exportChartAsImage('paymentMethodChart', 'payment-methods');
        paymentChartHeader.appendChild(exportBtn);
    }
}

// Data Analysis Functions
function calculateTrend() {
    const dailyData = {!! json_encode($salesData['dailySales']->pluck('total')->toArray()) !!};
    if (dailyData.length < 2) return 0;
    
    const firstValue = dailyData[0];
    const lastValue = dailyData[dailyData.length - 1];
    return ((lastValue - firstValue) / firstValue) * 100;
}

function getPeakPerformance() {
    const dailyData = {!! json_encode($salesData['dailySales']->pluck('total')->toArray()) !!};
    const dailyLabels = {!! json_encode($salesData['dailySales']->pluck('date')->toArray()) !!};
    
    if (dailyData.length === 0) return { value: 0, date: 'N/A' };
    
    const maxValue = Math.max(...dailyData);
    const maxIndex = dailyData.indexOf(maxValue);
    
    return {
        value: maxValue,
        date: dailyLabels[maxIndex] || 'N/A'
    };
}

// Initialize trend analysis on page load
document.addEventListener('DOMContentLoaded', function() {
    const trend = calculateTrend();
    const peak = getPeakPerformance();
    
    // You can display these values in your UI
    console.log('Sales Trend:', trend.toFixed(2) + '%');
    console.log('Peak Performance:', '₱' + peak.value.toLocaleString() + ' on ' + peak.date);
});
</script>
@endpush