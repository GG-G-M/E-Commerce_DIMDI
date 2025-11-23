{{-- resources/views/admin/sales-report/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Sales Reports')

@section('content')
<style>
    /* === Green Theme and Card Styling === */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #2C8F0C;
    }

    .page-header h1 {
        color: #2C8F0C;
        font-weight: 700;
    }

    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1rem 1.5rem;
    }

    .btn-success {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        font-weight: 600;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
    }

    .btn-outline-success {
        border-color: #2C8F0C;
        color: #2C8F0C;
        font-weight: 500;
    }

    .btn-outline-success:hover {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 1rem;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    /* Smaller stat cards */
    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fdf8 100%);
        border-radius: 8px;
        padding: 1rem;
        border-left: 3px solid #2C8F0C;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        height: 100%;
    }

    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2C8F0C;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
    }

    .badge-success {
        background-color: #2C8F0C;
        color: white;
    }

    .filter-section {
        background: #f8fdf8;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e8f5e6;
    }

    .payment-method-badge {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
    }

    .small-card {
        height: 100%;
    }
</style>

<!-- Add this button in the page-header div, next to the existing Export PDF button -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Sales Reports</h1>
        <p class="text-muted mb-0">Track and analyze your sales performance</p>
    </div>
    <div>
        <a href="{{ route('admin.sales-report.comparison') }}" class="btn btn-outline-success me-2">
            <i class="fas fa-chart-line me-1"></i> Year Comparison
        </a>
        <button type="button" class="btn btn-success" onclick="exportReport('pdf')">
            <i class="fas fa-download me-1"></i> Export PDF
        </button>
    </div>
</div>

<!-- Filters -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <i class="fas fa-filter me-2"></i> Report Filters
    </div>
    <div class="card-body">
        <form action="{{ route('admin.sales-report.index') }}" method="GET" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Date Range</label>
                        <select name="date_range" class="form-select" id="dateRangeSelect" onchange="handleDateRangeChange()">
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="this_year" {{ request('date_range') == 'this_year' ? 'selected' : '' }}>This Year</option>
                            <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4" id="customDateRange" style="display: {{ request('date_range') == 'custom' ? 'block' : 'none' }};">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Start Date</label>
                        <input type="date" name="start_date" class="form-control" id="startDate" value="{{ request('start_date') }}" onchange="handleCustomDateChange()">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">End Date</label>
                        <input type="date" name="end_date" class="form-control" id="endDate" value="{{ request('end_date') }}" onchange="handleCustomDateChange()">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Payment Method</label>
                        <select name="payment_method" class="form-select" onchange="submitForm()">
                            <option value="all">All Payments</option>
                            <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                            <option value="gcash" {{ request('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                            <option value="grab_pay" {{ request('payment_method') == 'grab_pay' ? 'selected' : '' }}>GrabPay</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Hidden submit button for form submission -->
            <button type="submit" id="hiddenSubmit" style="display: none;"></button>
        </form>
    </div>
</div>
<!-- Summary Cards - Smaller and more compact -->
<div class="row">
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card small-card">
            <div class="stat-value">₱{{ number_format($salesData['totalSales'], 2) }}</div>
            <div class="stat-label">Total Sales</div>
            <div class="text-success small mt-1">
                <i class="fas fa-chart-line me-1"></i> Revenue
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card small-card">
            <div class="stat-value">{{ $salesData['totalOrders'] }}</div>
            <div class="stat-label">Total Orders</div>
            <div class="text-success small mt-1">
                <i class="fas fa-shopping-cart me-1"></i> Orders
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card small-card">
            <div class="stat-value">₱{{ number_format($salesData['averageOrderValue'], 2) }}</div>
            <div class="stat-label">Avg Order Value</div>
            <div class="text-success small mt-1">
                <i class="fas fa-calculator me-1"></i> Average
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card small-card">
            <div class="stat-value">{{ $salesData['dateRangeText'] }}</div>
            <div class="stat-label">Date Range</div>
            <div class="text-success small mt-1">
                <i class="fas fa-calendar me-1"></i> Period
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card small-card">
            <div class="stat-value">{{ $salesData['salesByPayment']->count() }}</div>
            <div class="stat-label">Payment Methods</div>
            <div class="text-success small mt-1">
                <i class="fas fa-credit-card me-1"></i> Methods
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="stat-card small-card">
            <div class="stat-value">{{ $salesData['dailySales']->count() }}</div>
            <div class="stat-label">Data Points</div>
            <div class="text-success small mt-1">
                <i class="fas fa-database me-1"></i> Points
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mt-4">
    <!-- Dynamic Sales Trend Chart -->
<div class="col-xl-8 col-lg-7">
    <div class="card card-custom">
        <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-chart-line me-2"></i> 
                Sales Trend
            </div>
            <div class="text-white small">
                {{ $salesData['dateRangeText'] }}
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>
    </div>
</div>

    <!-- Payment Method Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <i class="fas fa-credit-card me-2"></i> Sales by Payment Method
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
                <div class="mt-3 text-center small">
                    @php
                        $colors = ['#2C8F0C', '#4CAF50', '#8BC34A', '#CDDC39'];
                    @endphp
                    @if($salesData['salesByPayment']->count() > 0)
                        @foreach($salesData['salesByPayment'] as $method => $data)
                        <div class="mb-1">
                            <i class="fas fa-circle me-1" style="color: {{ $colors[$loop->index] ?? '#2C8F0C' }}"></i> 
                            {{ ucfirst($method) }} ({{ number_format($data['percentage'], 1) }}%)
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No payment method data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Method Breakdown -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <i class="fas fa-money-bill-wave me-2"></i> Payment Method Breakdown
            </div>
            <div class="card-body">
                <div class="row">
                    @if($salesData['salesByPayment']->count() > 0)
                        @foreach($salesData['salesByPayment'] as $method => $data)
                        <div class="col-md-3 mb-3">
                            <div class="stat-card text-center small-card">
                                <div class="payment-method-badge mb-2">{{ $method }}</div>
                                <div class="stat-value">₱{{ number_format($data['total'], 2) }}</div>
                                <div class="stat-label">Total Sales</div>
                                <div class="text-muted small mt-1">
                                    {{ $data['count'] }} orders • {{ number_format($data['percentage'], 1) }}%
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="col-12 text-center">
                        <p class="text-muted">No sales data available for the selected filters.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="card card-custom mt-4">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-receipt me-2"></i> Sales Orders
        </div>
        <div class="text-white">
            Showing {{ $orders->count() }} of {{ $orders->total() }} orders
        </div>
    </div>
    <div class="card-body">
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
                            <span class="payment-method-badge">{{ $order->payment_method }}</span>
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
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global variables to store chart instances and form state
let salesTrendChart = null;
let paymentMethodChart = null;
let formSubmissionTimer = null;
let customDateWaitTimer = null;

function toggleCustomDate() {
    const dateRange = document.querySelector('select[name="date_range"]').value;
    const customDateRange = document.getElementById('customDateRange');
    
    if (dateRange === 'custom') {
        customDateRange.style.display = 'block';
    } else {
        customDateRange.style.display = 'none';
        // Auto-submit when switching from custom to other ranges
        submitFormWithDelay();
    }
}

function handleDateRangeChange() {
    const dateRange = document.getElementById('dateRangeSelect').value;
    
    if (dateRange === 'custom') {
        document.getElementById('customDateRange').style.display = 'block';
        // Don't auto-submit for custom until dates are selected
    } else {
        document.getElementById('customDateRange').style.display = 'none';
        // Auto-submit for non-custom ranges
        submitFormWithDelay();
    }
}

function handleCustomDateChange() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    // Clear any existing timer
    if (customDateWaitTimer) {
        clearTimeout(customDateWaitTimer);
    }
    
    // Only submit if both dates are selected
    if (startDate && endDate) {
        // Wait a moment to ensure both dates are set, then submit
        customDateWaitTimer = setTimeout(() => {
            submitFormWithDelay();
        }, 500);
    }
}

function submitForm() {
    // Clear any existing timer
    if (formSubmissionTimer) {
        clearTimeout(formSubmissionTimer);
    }
    
    // Submit immediately for non-custom filters
    document.getElementById('filterForm').submit();
}

function submitFormWithDelay() {
    // Clear any existing timer
    if (formSubmissionTimer) {
        clearTimeout(formSubmissionTimer);
    }
    
    // Submit after a short delay to avoid rapid requests
    formSubmissionTimer = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 300);
}

function exportReport(type) {
    const form = document.getElementById('filterForm');
    const action = "{{ route('admin.sales-report.export') }}";
    
    // Create a temporary form for export
    const exportForm = document.createElement('form');
    exportForm.method = 'GET';
    exportForm.action = action;
    
    // Copy all parameters from main form
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        if (input.name) {
            const clone = input.cloneNode(true);
            exportForm.appendChild(clone);
        }
    });
    
    // Add export type
    const exportTypeInput = document.createElement('input');
    exportTypeInput.type = 'hidden';
    exportTypeInput.name = 'export_type';
    exportTypeInput.value = type;
    exportForm.appendChild(exportTypeInput);
    
    document.body.appendChild(exportForm);
    exportForm.submit();
    document.body.removeChild(exportForm);
}

// Function to destroy existing chart if it exists
function destroyChart(chart) {
    if (chart) {
        chart.destroy();
    }
}

// Function to initialize sales trend chart based on current filter
function initializeSalesTrendChart() {
    const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
    
    // Destroy existing chart
    destroyChart(salesTrendChart);
    
    // Always use line graph regardless of date range
    const dateRange = "{{ request('date_range', 'this_month') }}";
    
    if (dateRange === 'this_year' || dateRange === 'custom' && isLongRange()) {
        // For yearly or long custom ranges, use monthly data with line graph
        const monthlyLabels = {!! json_encode($salesData['monthlySales']->pluck('month')->toArray()) !!};
        const monthlyData = {!! json_encode($salesData['monthlySales']->pluck('total')->toArray()) !!};
        
        if (monthlyData.length > 0) {
            salesTrendChart = new Chart(salesTrendCtx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: "Monthly Sales (₱)",
                        backgroundColor: "rgba(44, 143, 12, 0.1)",
                        borderColor: "#2C8F0C",
                        pointBackgroundColor: "#2C8F0C",
                        pointBorderColor: "#2C8F0C",
                        pointHoverBackgroundColor: "#1E6A08",
                        pointHoverBorderColor: "#1E6A08",
                        borderWidth: 3,
                        tension: 0.4,
                        data: monthlyData,
                    }],
                },
                options: getLineChartOptions("Monthly Sales (₱)")
            });
        } else {
            showNoDataMessage('salesTrendChart', 'Sales Trend', 'No monthly sales data available for the selected filters.');
        }
    } else if (dateRange === 'this_week' || (dateRange === 'custom' && isMediumRange())) {
        // For weekly or medium custom ranges, use weekly data with line graph
        const weeklyLabels = {!! json_encode($salesData['weeklySales']->pluck('week_range')->toArray()) !!};
        const weeklyData = {!! json_encode($salesData['weeklySales']->pluck('total')->toArray()) !!};
        
        if (weeklyData.length > 0) {
            salesTrendChart = new Chart(salesTrendCtx, {
                type: 'line',
                data: {
                    labels: weeklyLabels,
                    datasets: [{
                        label: "Weekly Sales (₱)",
                        backgroundColor: "rgba(44, 143, 12, 0.1)",
                        borderColor: "#2C8F0C",
                        pointBackgroundColor: "#2C8F0C",
                        pointBorderColor: "#2C8F0C",
                        pointHoverBackgroundColor: "#1E6A08",
                        pointHoverBorderColor: "#1E6A08",
                        borderWidth: 3,
                        tension: 0.4,
                        data: weeklyData,
                    }],
                },
                options: getLineChartOptions("Weekly Sales (₱)")
            });
        } else {
            showNoDataMessage('salesTrendChart', 'Sales Trend', 'No weekly sales data available for the selected filters.');
        }
    } else {
        // For daily or short ranges, use daily data with line graph
        const dailyLabels = {!! json_encode($salesData['dailySales']->pluck('date')->toArray()) !!};
        const dailyData = {!! json_encode($salesData['dailySales']->pluck('total')->toArray()) !!};
        
        if (dailyData.length > 0) {
            salesTrendChart = new Chart(salesTrendCtx, {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: "Daily Sales (₱)",
                        backgroundColor: "rgba(44, 143, 12, 0.1)",
                        borderColor: "#2C8F0C",
                        pointBackgroundColor: "#2C8F0C",
                        pointBorderColor: "#2C8F0C",
                        pointHoverBackgroundColor: "#1E6A08",
                        pointHoverBorderColor: "#1E6A08",
                        borderWidth: 3,
                        tension: 0.4,
                        data: dailyData,
                    }],
                },
                options: getLineChartOptions("Daily Sales (₱)")
            });
        } else {
            showNoDataMessage('salesTrendChart', 'Sales Trend', 'No daily sales data available for the selected filters.');
        }
    }
}

// Helper function to determine if custom range is long (more than 3 months)
function isLongRange() {
    const startDate = "{{ request('start_date') }}";
    const endDate = "{{ request('end_date') }}";
    
    if (!startDate || !endDate) return false;
    
    const start = new Date(startDate);
    const end = new Date(endDate);
    const diffTime = Math.abs(end - start);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    return diffDays > 90; // More than 3 months
}

// Helper function to determine if custom range is medium (1-3 months)
function isMediumRange() {
    const startDate = "{{ request('start_date') }}";
    const endDate = "{{ request('end_date') }}";
    
    if (!startDate || !endDate) return false;
    
    const start = new Date(startDate);
    const end = new Date(endDate);
    const diffTime = Math.abs(end - start);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    return diffDays >= 30 && diffDays <= 90; // 1 to 3 months
}

// Common line chart options
function getLineChartOptions(label) {
    return {
        maintainAspectRatio: false,
        responsive: true,
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${label}: ₱${context.raw.toLocaleString()}`;
                    }
                }
            }
        }
    };
}

// Helper function to show no data message
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

// Function to initialize payment method chart
function initializePaymentMethodChart() {
    const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
    
    // Destroy existing chart
    destroyChart(paymentMethodChart);
    
    const paymentLabels = {!! json_encode($salesData['salesByPayment']->keys()->map(fn($key) => ucfirst($key))->toArray()) !!};
    const paymentData = {!! json_encode($salesData['salesByPayment']->pluck('total')->toArray()) !!};
    
    if (paymentData.length > 0) {
        paymentMethodChart = new Chart(paymentMethodCtx, {
            type: 'doughnut',
            data: {
                labels: paymentLabels,
                datasets: [{
                    data: paymentData,
                    backgroundColor: ['#2C8F0C', '#4CAF50', '#8BC34A', '#CDDC39'],
                    hoverBackgroundColor: ['#1E6A08', '#2C8F0C', '#4CAF50', '#8BC34A'],
                    hoverBorderColor: "rgba(255, 255, 255, 1)",
                    borderWidth: 2
                }],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
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
                cutout: '60%',
            },
        });
    } else {
        // Hide chart container if no data
        document.getElementById('paymentMethodChart').closest('.card').innerHTML = `
            <div class="card-header card-header-custom">
                <i class="fas fa-credit-card me-2"></i> Sales by Payment Method
            </div>
            <div class="card-body text-center py-5">
                <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                <p class="text-muted">No payment method data available for the selected filters.</p>
            </div>
        `;
    }
}

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeSalesTrendChart();
    initializePaymentMethodChart();
});

// Reinitialize charts when window is resized (for responsiveness)
window.addEventListener('resize', function() {
    initializeSalesTrendChart();
    initializePaymentMethodChart();
});
</script>
@endpush