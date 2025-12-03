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

   /* Enhanced Chart Styles */
.chart-container-enhanced {
    position: relative;
    height: 280px;
    width: 100%;
    background: linear-gradient(135deg, #f8fdf8 0%, #ffffff 100%);
    border-radius: 8px;
    padding: 1rem;
}

.chart-container-pie {
    position: relative;
    height: 220px;
    width: 100%;
    margin-bottom: 1rem;
}

.chart-stats {
    background: #f8fdf8;
    border-radius: 8px;
    padding: 1rem;
    border-left: 3px solid #2C8F0C;
}

.chart-actions .btn {
    border-radius: 20px;
    font-size: 0.8rem;
    padding: 0.25rem 0.75rem;
}

.payment-item {
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.payment-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.color-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}

/* Chart tooltip improvements */
.chartjs-tooltip {
    background: rgba(44, 143, 12, 0.9) !important;
    border: none !important;
    border-radius: 8px !important;
    color: white !important;
    padding: 8px 12px !important;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
}

/* Animation for chart loading */
.chart-loading {
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0% { opacity: 0.6; }
    50% { opacity: 1; }
    100% { opacity: 0.6; }
}

/* Responsive chart adjustments */
@media (max-width: 768px) {
    .chart-container-enhanced {
        height: 250px;
    }
    
    .chart-container-pie {
        height: 200px;
    }
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


<!-- Summary Cards - Smaller and more compact -->
{{-- Simple 3-column layout without the redundant cards --}}
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="stat-card">
            <div class="stat-value">₱{{ number_format($salesData['totalSales'], 2) }}</div>
            <div class="stat-label">Total Sales</div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-card">
            <div class="stat-value">{{ $salesData['totalOrders'] }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-card">
            <div class="stat-value">₱{{ number_format($salesData['averageOrderValue'], 2) }}</div>
            <div class="stat-label">Avg Order Value</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card card-custom">
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

<!-- Improved Charts Section -->
<div class="row mt-4">
    <!-- Dynamic Sales Trend Chart - Improved Design -->
    <div class="col-xl-8 col-lg-7">
        <div class="card card-custom">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-chart-line me-2"></i> 
                    <span>Sales Trend</span>
                    <div class="badge bg-light text-dark ms-2">
                        <i class="fas fa-calendar me-1"></i>{{ $salesData['dateRangeText'] }}
                    </div>
                </div>
                <div class="chart-actions">
                    <button class="btn btn-sm btn-outline-success" onclick="toggleChartType()">
                        <i class="fas fa-chart-bar"></i> Switch View
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container-enhanced">
                    <canvas id="salesTrendChart"></canvas>
                </div>
                <div class="chart-stats mt-3">
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

    <!-- Payment Method Chart - Improved Design -->
    <div class="col-xl-4 col-lg-5">
        <div class="card card-custom">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-credit-card me-2"></i> Payment Distribution
                </div>
                <div class="text-white small">
                    {{ $salesData['totalOrders'] }} orders
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container-pie">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
                <div class="payment-legend mt-3">
                    @php
                        $colors = ['#2C8F0C', '#4CAF50', '#8BC34A', '#CDDC39', '#AED581'];
                    @endphp
                    @if($salesData['salesByPayment']->count() > 0)
                        @foreach($salesData['salesByPayment'] as $method => $data)
                        <div class="payment-item d-flex justify-content-between align-items-center mb-2 p-2 rounded">
                            <div class="d-flex align-items-center">
                                <div class="color-indicator me-2" style="background-color: {{ $colors[$loop->index] ?? '#2C8F0C' }}"></div>
                                <span class="fw-medium">{{ ucfirst($method) }}</span>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">₱{{ number_format($data['total'], 2) }}</div>
                                <small class="text-muted">{{ $data['count'] }} orders • {{ number_format($data['percentage'], 1) }}%</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No payment method data available.</p>
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