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

    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fdf8 100%);
        border-radius: 12px;
        padding: 1.5rem;
        border-left: 4px solid #2C8F0C;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        height: 100%;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #2C8F0C;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.875rem;
    }

    .chart-container {
        position: relative;
        height: 300px;
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
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
    }
</style>

<!-- Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Sales Reports</h1>
        <p class="text-muted mb-0">Track and analyze your sales performance</p>
    </div>
    <button type="button" class="btn btn-success" onclick="exportReport('pdf')">
        <i class="fas fa-download me-1"></i> Export PDF
    </button>
</div>

<!-- Filters -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <i class="fas fa-filter me-2"></i> Report Filters
    </div>
    <div class="card-body">
        <form action="{{ route('admin.sales-report.index') }}" method="GET" id="filterForm">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Date Range</label>
                        <select name="date_range" class="form-select" onchange="toggleCustomDate()">
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="this_year" {{ request('date_range') == 'this_year' ? 'selected' : '' }}>This Year</option>
                            <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3" id="customDateRange" style="display: {{ request('date_range') == 'custom' ? 'block' : 'none' }};">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="all">All Payments</option>
                            <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                            <option value="gcash" {{ request('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                            <option value="grab_pay" {{ request('payment_method') == 'grab_pay' ? 'selected' : '' }}>GrabPay</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">&nbsp;</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-1"></i> Apply Filters
                            </button>
                            <a href="{{ route('admin.sales-report.index') }}" class="btn btn-outline-success">
                                <i class="fas fa-refresh me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-value">₱{{ number_format($salesData['totalSales'], 2) }}</div>
            <div class="stat-label">Total Sales</div>
            <div class="text-success small mt-2">
                <i class="fas fa-chart-line me-1"></i> Overall revenue
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-value">{{ $salesData['totalOrders'] }}</div>
            <div class="stat-label">Total Orders</div>
            <div class="text-success small mt-2">
                <i class="fas fa-shopping-cart me-1"></i> Completed orders
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-value">₱{{ number_format($salesData['averageOrderValue'], 2) }}</div>
            <div class="stat-label">Average Order Value</div>
            <div class="text-success small mt-2">
                <i class="fas fa-calculator me-1"></i> Per order average
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-value">{{ $salesData['dateRangeText'] }}</div>
            <div class="stat-label">Date Range</div>
            <div class="text-success small mt-2">
                <i class="fas fa-calendar me-1"></i> Selected period
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mt-4">
    <!-- Payment Method Chart -->
    <div class="col-xl-6 col-lg-6">
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <i class="fas fa-credit-card me-2"></i> Sales by Payment Method
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    @php
                        $colors = ['#2C8F0C', '#4CAF50', '#8BC34A', '#CDDC39'];
                    @endphp
                    @if($salesData['salesByPayment']->count() > 0)
                        @foreach($salesData['salesByPayment'] as $method => $data)
                        <span class="me-3">
                            <i class="fas fa-circle me-1" style="color: {{ $colors[$loop->index] ?? '#2C8F0C' }}"></i> 
                            {{ ucfirst($method) }} ({{ number_format($data['percentage'], 1) }}%)
                        </span>
                        @endforeach
                    @else
                        <p class="text-muted">No payment method data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Sales Chart -->
    <div class="col-xl-6 col-lg-6">
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <i class="fas fa-chart-bar me-2"></i> Monthly Sales ({{ date('Y') }})
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daily Sales Chart -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <i class="fas fa-chart-line me-2"></i> Daily Sales (Last 30 Days)
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="dailySalesChart"></canvas>
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
                        <div class="col-md-4 mb-3">
                            <div class="stat-card text-center">
                                <div class="payment-method-badge mb-3">{{ $method }}</div>
                                <div class="stat-value">₱{{ number_format($data['total'], 2) }}</div>
                                <div class="stat-label">Total Sales</div>
                                <div class="text-muted small mt-2">
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
function toggleCustomDate() {
    const dateRange = document.querySelector('select[name="date_range"]').value;
    const customDateRange = document.getElementById('customDateRange');
    
    if (dateRange === 'custom') {
        customDateRange.style.display = 'block';
    } else {
        customDateRange.style.display = 'none';
    }
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

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Payment Method Pie Chart
    @if($salesData['salesByPayment']->count() > 0)
    const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
    const paymentMethodChart = new Chart(paymentMethodCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($salesData['salesByPayment']->keys()->map(fn($key) => ucfirst($key))->toArray()) !!},
            datasets: [{
                data: {!! json_encode($salesData['salesByPayment']->pluck('total')->toArray()) !!},
                backgroundColor: ['#2C8F0C', '#4CAF50', '#8BC34A', '#CDDC39'],
                hoverBackgroundColor: ['#1E6A08', '#2C8F0C', '#4CAF50', '#8BC34A'],
                hoverBorderColor: "rgba(255, 255, 255, 1)",
                borderWidth: 2
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
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
    @else
    document.getElementById('paymentMethodChart').closest('.card').style.display = 'none';
    @endif

    // Monthly Sales Bar Chart
    const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
    const monthlySalesChart = new Chart(monthlySalesCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: "Sales (₱)",
                backgroundColor: "#2C8F0C",
                hoverBackgroundColor: "#1E6A08",
                borderColor: "#2C8F0C",
                borderRadius: 6,
                data: {!! json_encode($salesData['monthlySalesArray']) !!},
            }],
        },
        options: {
            maintainAspectRatio: false,
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
                            return `Sales: ₱${context.raw.toLocaleString()}`;
                        }
                    }
                }
            }
        },
    });

    // Daily Sales Chart
    @if($salesData['dailySales']->count() > 0)
    const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
    const dailySalesChart = new Chart(dailySalesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesData['dailySales']->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('M j'))->toArray()) !!},
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
                data: {!! json_encode($salesData['dailySales']->pluck('total')->toArray()) !!},
            }],
        },
        options: {
            maintainAspectRatio: false,
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
                            return `Sales: ₱${context.raw.toLocaleString()}`;
                        }
                    }
                }
            }
        },
    });
    @else
    document.getElementById('dailySalesChart').closest('.card').style.display = 'none';
    @endif
});
</script>
@endpush