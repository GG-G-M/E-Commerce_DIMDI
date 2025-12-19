{{-- resources/views/admin/sales-report/charts.blade.php --}}
@extends('layouts.admin')

@section('title', 'Sales Charts')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Sales Analytics</h1>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Sales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ₱{{ number_format($salesData['totalSales'], 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $salesData['totalOrders'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Average Order Value</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ₱{{ number_format($salesData['averageOrderValue'], 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <!-- Payment Method Pie Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sales by Payment Method</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @php
                            $colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'];
                        @endphp
                        @if($salesData['salesByPayment']->count() > 0)
                            @foreach($salesData['salesByPayment'] as $method => $data)
                            <span class="mr-3">
                                <i class="fas fa-circle" style="color: {{ $colors[$loop->index] ?? '#4e73df' }}"></i> 
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
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Sales ({{ date('Y') }})</h6>
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
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daily Sales (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="dailySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Method Details -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Method Breakdown</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($salesData['salesByPayment']->count() > 0)
                            @foreach($salesData['salesByPayment'] as $method => $data)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-uppercase text-primary">{{ $method }}</h5>
                                        <h3 class="text-success">₱{{ number_format($data['total'], 2) }}</h3>
                                        <p class="card-text">
                                            <strong>Orders:</strong> {{ $data['count'] }}<br>
                                            <strong>Percentage:</strong> {{ number_format($data['percentage'], 1) }}%<br>
                                            <strong>Average:</strong> ₱{{ number_format($data['count'] > 0 ? $data['total'] / $data['count'] : 0, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="col-12 text-center">
                                <p class="text-muted">No payment method data available for the selected filters.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="row">
        <div class="col-12">
            <a href="{{ route('admin.sales-report.index') }}?{{ http_build_query(request()->query()) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Payment Method Pie Chart
@if($salesData['salesByPayment']->count() > 0)
const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
const paymentMethodChart = new Chart(paymentMethodCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($salesData['salesByPayment']->keys()->map(fn($key) => ucfirst($key))->toArray()) !!},
        datasets: [{
            data: {!! json_encode($salesData['salesByPayment']->pluck('total')->toArray()) !!},
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
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
// Hide the chart container if no data
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
            backgroundColor: "#4e73df",
            hoverBackgroundColor: "#2e59d9",
            borderColor: "#4e73df",
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
            backgroundColor: "rgba(78, 115, 223, 0.05)",
            borderColor: "#4e73df",
            pointBackgroundColor: "#4e73df",
            pointBorderColor: "#4e73df",
            pointHoverBackgroundColor: "#2e59d9",
            pointHoverBorderColor: "#2e59d9",
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
// Hide the daily sales chart if no data
document.getElementById('dailySalesChart').closest('.card').style.display = 'none';
@endif
</script>
@endpush