@extends('layouts.admin')

@section('title', 'Sales Comparison')

@section('content')
<style>
    /* === Modern Design System === */
    :root {
        --primary-green: #2C8F0C;
        --secondary-green: #4CAF50;
        --light-green: #E8F5E9;
        --dark-green: #1B5E20;
        --blue: #4A90E2;
        --light-blue: rgba(74, 144, 226, 0.1);
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

    /* Dashboard Header Card */
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

    /* Year Comparison Header Section */
    .comparison-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-200);
    }

    .year-selection-form {
        flex: 1;
    }

    /* Comparison Stats Grid */
    .comparison-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1px;
        background: var(--gray-200);
    }

    .comparison-stat-item {
        background: white;
        padding: 1.5rem;
        text-align: center;
        position: relative;
        transition: all 0.3s ease;
    }

    .comparison-stat-item:hover {
        background: var(--gray-50);
        transform: translateY(-2px);
    }

    .year-badge {
        display: inline-block;
        background: var(--gradient-green);
        color: white;
        padding: 0.4rem 1.2rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 1rem;
        box-shadow: var(--shadow-sm);
    }

    .year-badge.blue {
        background: linear-gradient(135deg, var(--blue), #2D9CDB);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        line-height: 1.2;
    }

    .stat-value.green {
        color: var(--primary-green);
    }

    .stat-value.blue {
        color: var(--blue);
    }

    .stat-label {
        color: var(--gray-600);
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Growth Indicators */
    .growth-indicator {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        margin-left: 0.5rem;
    }

    .growth-positive {
        background: rgba(44, 143, 12, 0.1);
        color: var(--primary-green);
    }

    .growth-negative {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .growth-icon {
        font-size: 0.75rem;
        margin-right: 0.25rem;
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

    /* Cards */
    .card-modern {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-200);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .card-modern:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .card-header-modern {
        background: var(--gradient-green);
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
    }

    .card-body-modern {
        padding: 1.5rem;
    }

    /* Comparison Chart */
    .comparison-chart-container {
        height: 400px;
        position: relative;
        width: 100%;
    }

    /* Comparison Table */
    .comparison-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .comparison-table th {
        background: var(--gray-50);
        color: var(--gray-700);
        font-weight: 600;
        padding: 1rem;
        border-bottom: 2px solid var(--gray-300);
        text-align: left;
    }

    .comparison-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--gray-200);
        vertical-align: middle;
    }

    .comparison-table tbody tr:hover {
        background: var(--gray-50);
    }

    .month-label {
        font-weight: 600;
        color: var(--gray-800);
        min-width: 60px;
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .comparison-stats-grid {
            grid-template-columns: 1fr;
        }

        .comparison-stat-item:not(:last-child) {
            border-bottom: 1px solid var(--gray-200);
        }

        .comparison-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .dashboard-header {
            padding: 1.25rem 1.5rem;
        }

        .dashboard-header h1 {
            font-size: 1.5rem;
        }

        .comparison-chart-container {
            height: 300px;
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

        .card-header-modern {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }
    }

    /* Year Selection Row */
    .year-selection-row {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 1rem;
        align-items: end;
    }

    @media (max-width: 768px) {
        .year-selection-row {
            grid-template-columns: 1fr;
        }
    }

    /* Summary Row */
    .summary-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        text-align: center;
    }

    @media (max-width: 768px) {
        .summary-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Unified Dashboard Header Card -->
<div class="dashboard-header-card">
    <!-- Header Section -->
    <div class="dashboard-header">
        <h1>Sales Comparison</h1>
        <p class="subtitle">Compare sales performance between different years</p>
        
        <div class="header-actions d-flex gap-2">
            <a href="{{ route('admin.sales-report.index') }}" class="action-btn btn-outline">
                <i class="fas fa-arrow-left me-1"></i> Back to Reports
            </a>
            <button type="button" class="action-btn btn-primary" onclick="exportComparison()">
                <i class="fas fa-download me-1"></i> Export PDF
            </button>
        </div>
    </div>

    <!-- Year Selection Section -->
    <div class="comparison-header">
        <div class="year-selection-form">
            <form action="{{ route('admin.sales-report.comparison') }}" method="GET" id="comparisonForm">
                <div class="year-selection-row">
                    <div>
                        <label class="form-label">First Year</label>
                        <select name="year1" class="form-select" onchange="updateComparison()">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $year1 == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Second Year</label>
                        <select name="year2" class="form-select" onchange="updateComparison()">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $year2 == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-line me-2"></i> Compare
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Comparison Stats Grid -->
    <div class="comparison-stats-grid">
        <div class="comparison-stat-item">
            <span class="year-badge">{{ $year1 }}</span>
            <div class="stat-value green">₱{{ number_format(array_sum($year1Sales), 2) }}</div>
            <div class="stat-label">Total Sales</div>
            <div class="mt-2">
                <span class="badge bg-light text-dark">
                    {{ $year1 }} Performance
                </span>
            </div>
        </div>
        
        <div class="comparison-stat-item">
            <span class="year-badge blue">{{ $year2 }}</span>
            <div class="stat-value blue">₱{{ number_format(array_sum($year2Sales), 2) }}</div>
            <div class="stat-label">Total Sales</div>
            <div class="mt-2">
                <span class="badge bg-light text-dark">
                    {{ $year2 }} Performance
                </span>
            </div>
        </div>
        
        <div class="comparison-stat-item">
            <div class="mb-3">
                <span class="badge bg-success bg-opacity-10 text-success">
                    <i class="fas fa-chart-line me-1"></i> Growth Analysis
                </span>
            </div>
            <div class="stat-value {{ $totalGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                {{ number_format($totalGrowth, 1) }}%
                @if($totalGrowth >= 0)
                    <span class="growth-indicator growth-positive">
                        <i class="fas fa-arrow-up growth-icon"></i> Growth
                    </span>
                @else
                    <span class="growth-indicator growth-negative">
                        <i class="fas fa-arrow-down growth-icon"></i> Decline
                    </span>
                @endif
            </div>
            <div class="stat-label">Overall Growth Rate</div>
        </div>
    </div>
</div>

<!-- Growth Summary Card -->
<div class="card-modern">
    <div class="card-header-modern">
        <div class="d-flex align-items-center">
            <i class="fas fa-chart-bar me-2"></i> Growth Summary
        </div>
        <div class="text-white">
            {{ $year1 }} vs {{ $year2 }}
        </div>
    </div>
    <div class="card-body-modern">
        <div class="summary-row">
            <div class="text-center p-3">
                <div class="stat-value {{ $totalGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($totalGrowth, 1) }}%
                </div>
                <div class="stat-label">Total Growth Rate</div>
                <small class="text-muted">Year-over-year comparison</small>
            </div>
            
            <div class="text-center p-3">
                <div class="stat-value {{ (array_sum($year1Sales) - array_sum($year2Sales)) >= 0 ? 'text-success' : 'text-danger' }}">
                    ₱{{ number_format(array_sum($year1Sales) - array_sum($year2Sales), 2) }}
                </div>
                <div class="stat-label">Revenue Difference</div>
                <small class="text-muted">Absolute change</small>
            </div>
            
            <div class="text-center p-3">
                <div class="stat-value text-primary">
                    {{ $year1 }}
                </div>
                <div class="stat-label">Compared To</div>
                <div class="stat-value text-info">{{ $year2 }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Comparison Chart Card -->
<div class="card-modern">
    <div class="card-header-modern">
        <div class="d-flex align-items-center">
            <i class="fas fa-chart-line me-2"></i> Monthly Sales Comparison
        </div>
        <div class="text-white">
            {{ $year1 }} vs {{ $year2 }}
        </div>
    </div>
    <div class="card-body-modern">
        <div class="comparison-chart-container">
            <canvas id="comparisonChart"></canvas>
        </div>
        <div class="row text-center mt-3">
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div style="width: 12px; height: 12px; background-color: #2C8F0C; border-radius: 2px; margin-right: 8px;"></div>
                    <span class="fw-medium">{{ $year1 }} Sales Trend</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div style="width: 12px; height: 12px; background-color: #4A90E2; border-radius: 2px; margin-right: 8px;"></div>
                    <span class="fw-medium">{{ $year2 }} Sales Trend</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Breakdown Card -->
<div class="card-modern">
    <div class="card-header-modern">
        <div class="d-flex align-items-center">
            <i class="fas fa-table me-2"></i> Monthly Breakdown
        </div>
        <div class="text-white">
            Detailed Comparison
        </div>
    </div>
    <div class="card-body-modern">
        <div class="table-responsive">
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th class="month-label">Month</th>
                        <th class="text-end">{{ $year1 }} Sales</th>
                        <th class="text-end">{{ $year2 }} Sales</th>
                        <th class="text-end">Growth</th>
                        <th class="text-end">Difference</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    @endphp
                    @foreach($months as $index => $month)
                    @php
                        $monthNum = $index + 1;
                        $sales1 = $year1Sales[$monthNum];
                        $sales2 = $year2Sales[$monthNum];
                        $growth = $growthData[$monthNum];
                        $difference = $sales1 - $sales2;
                    @endphp
                    <tr>
                        <td class="month-label">
                            <strong>{{ $month }}</strong>
                        </td>
                        <td class="text-end">
                            <div class="fw-bold text-success">₱{{ number_format($sales1, 2) }}</div>
                        </td>
                        <td class="text-end">
                            <div class="fw-bold text-info">₱{{ number_format($sales2, 2) }}</div>
                        </td>
                        <td class="text-end">
                            <span class="{{ $growth >= 0 ? 'growth-positive' : 'growth-negative' }} fw-bold">
                                {{ number_format($growth, 1) }}%
                            </span>
                        </td>
                        <td class="text-end">
                            <span class="{{ $difference >= 0 ? 'growth-positive' : 'growth-negative' }} fw-bold">
                                ₱{{ number_format($difference, 2) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    <tr style="background: var(--gray-50);">
                        <td class="month-label">
                            <strong>TOTAL</strong>
                        </td>
                        <td class="text-end">
                            <div class="fw-bold text-success">₱{{ number_format(array_sum($year1Sales), 2) }}</div>
                        </td>
                        <td class="text-end">
                            <div class="fw-bold text-info">₱{{ number_format(array_sum($year2Sales), 2) }}</div>
                        </td>
                        <td class="text-end">
                            <span class="{{ $totalGrowth >= 0 ? 'growth-positive' : 'growth-negative' }} fw-bold">
                                {{ number_format($totalGrowth, 1) }}%
                            </span>
                        </td>
                        <td class="text-end">
                            <span class="{{ (array_sum($year1Sales) - array_sum($year2Sales)) >= 0 ? 'growth-positive' : 'growth-negative' }} fw-bold">
                                ₱{{ number_format(array_sum($year1Sales) - array_sum($year2Sales), 2) }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateComparison() {
    document.getElementById('comparisonForm').submit();
}

function exportComparison() {
    const year1 = document.querySelector('select[name="year1"]').value;
    const year2 = document.querySelector('select[name="year2"]').value;
    
    const url = "{{ route('admin.sales-report.export') }}?export_type=comparison&year1=" + year1 + "&year2=" + year2;
    window.open(url, '_blank');
}

// Initialize comparison chart with enhanced styling
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('comparisonChart').getContext('2d');
    
    // Create gradient for the chart
    const gradient1 = ctx.createLinearGradient(0, 0, 0, 400);
    gradient1.addColorStop(0, 'rgba(44, 143, 12, 0.3)');
    gradient1.addColorStop(1, 'rgba(44, 143, 12, 0.05)');
    
    const gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
    gradient2.addColorStop(0, 'rgba(74, 144, 226, 0.3)');
    gradient2.addColorStop(1, 'rgba(74, 144, 226, 0.05)');
    
    const comparisonChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: "{{ $year1 }} Sales",
                    borderColor: "#2C8F0C",
                    backgroundColor: gradient1,
                    pointBackgroundColor: "#2C8F0C",
                    pointBorderColor: "#ffffff",
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: "#1E6A08",
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    data: {!! json_encode(array_values($year1Sales)) !!},
                },
                {
                    label: "{{ $year2 }} Sales",
                    borderColor: "#4A90E2",
                    backgroundColor: gradient2,
                    pointBackgroundColor: "#4A90E2",
                    pointBorderColor: "#ffffff",
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: "#2D9CDB",
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    data: {!! json_encode(array_values($year2Sales)) !!},
                }
            ],
        },
        options: {
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
                        }
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
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₱' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            elements: {
                line: {
                    tension: 0.4
                }
            }
        },
    });
});
</script>
@endpush