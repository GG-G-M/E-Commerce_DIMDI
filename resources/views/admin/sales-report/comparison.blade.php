@extends('layouts.admin')

@section('title', 'Sales Comparison')

@section('content')
<style>
    .comparison-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #2C8F0C;
    }
    
    .growth-positive {
        color: #2C8F0C;
        font-weight: 600;
    }
    
    .growth-negative {
        color: #dc3545;
        font-weight: 600;
    }
    
    .year-badge {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
    }
    
    .comparison-chart-container {
        height: 400px;
        position: relative;
    }
</style>

<!-- Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Sales Comparison</h1>
        <p class="text-muted mb-0">Compare sales performance between years</p>
    </div>
    <div>
        <a href="{{ route('admin.sales-report.index') }}" class="btn btn-outline-success me-2">
            <i class="fas fa-arrow-left me-1"></i> Back to Reports
        </a>
        <button type="button" class="btn btn-success" onclick="exportComparison()">
            <i class="fas fa-download me-1"></i> Export PDF
        </button>
    </div>
</div>

<!-- Year Selection Form -->
<div class="comparison-card">
    <form action="{{ route('admin.sales-report.comparison') }}" method="GET" id="comparisonForm">
        <div class="row align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">First Year</label>
                <select name="year1" class="form-select" onchange="updateComparison()">
                    @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $year1 == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Second Year</label>
                <select name="year2" class="form-select" onchange="updateComparison()">
                    @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $year2 == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-chart-line me-1"></i> Compare Years
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-md-6">
        <div class="comparison-card text-center">
            <span class="year-badge">{{ $year1 }}</span>
            <div class="stat-value mt-3">₱{{ number_format(array_sum($year1Sales), 2) }}</div>
            <div class="stat-label">Total Sales</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="comparison-card text-center">
            <span class="year-badge">{{ $year2 }}</span>
            <div class="stat-value mt-3">₱{{ number_format(array_sum($year2Sales), 2) }}</div>
            <div class="stat-label">Total Sales</div>
        </div>
    </div>
</div>

<!-- Growth Summary -->
<div class="comparison-card">
    <h5 class="fw-bold mb-3">Overall Growth Summary</h5>
    <div class="row text-center">
        <div class="col-md-4">
            <div class="stat-value {{ $totalGrowth >= 0 ? 'growth-positive' : 'growth-negative' }}">
                {{ number_format($totalGrowth, 1) }}%
            </div>
            <div class="stat-label">Total Growth</div>
        </div>
        <div class="col-md-4">
            <div class="stat-value text-success">
                ₱{{ number_format(array_sum($year1Sales) - array_sum($year2Sales), 2) }}
            </div>
            <div class="stat-label">Revenue Difference</div>
        </div>
        <div class="col-md-4">
            <div class="stat-value text-primary">
                {{ $year1 }}
            </div>
            <div class="stat-label">vs {{ $year2 }}</div>
        </div>
    </div>
</div>

<!-- Comparison Chart -->
<div class="comparison-card">
    <h5 class="fw-bold mb-3">Monthly Sales Comparison</h5>
    <div class="comparison-chart-container">
        <canvas id="comparisonChart"></canvas>
    </div>
</div>

<!-- Monthly Breakdown -->
<div class="comparison-card">
    <h5 class="fw-bold mb-3">Monthly Breakdown</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Month</th>
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
                    <td><strong>{{ $month }}</strong></td>
                    <td class="text-end">₱{{ number_format($sales1, 2) }}</td>
                    <td class="text-end">₱{{ number_format($sales2, 2) }}</td>
                    <td class="text-end {{ $growth >= 0 ? 'growth-positive' : 'growth-negative' }}">
                        {{ number_format($growth, 1) }}%
                    </td>
                    <td class="text-end {{ $difference >= 0 ? 'growth-positive' : 'growth-negative' }}">
                        ₱{{ number_format($difference, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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

// Initialize comparison chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('comparisonChart').getContext('2d');
    const comparisonChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: "{{ $year1 }} Sales",
                    borderColor: "#2C8F0C",
                    backgroundColor: "rgba(44, 143, 12, 0.1)",
                    pointBackgroundColor: "#2C8F0C",
                    pointBorderColor: "#2C8F0C",
                    borderWidth: 3,
                    tension: 0.4,
                    data: {!! json_encode(array_values($year1Sales)) !!},
                },
                {
                    label: "{{ $year2 }} Sales",
                    borderColor: "#4A90E2",
                    backgroundColor: "rgba(74, 144, 226, 0.1)",
                    pointBackgroundColor: "#4A90E2",
                    pointBorderColor: "#4A90E2",
                    borderWidth: 3,
                    tension: 0.4,
                    data: {!! json_encode(array_values($year2Sales)) !!},
                }
            ],
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
                            return context.dataset.label + ': ₱' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        },
    });
});
</script>
@endpush