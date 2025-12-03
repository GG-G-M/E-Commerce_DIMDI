@extends('layouts.admin')

@section('title', 'Inventory Reports')

@section('content')
<style>
    /* Add this to your existing styles */
    .card-body canvas {
        width: 100% !important;
        height: 140px !important;
        display: block !important;
    }
    
    /* Make sure chart containers have proper dimensions */
    .card-body {
        position: relative;
        min-height: 160px;
    }
</style>
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
                            <h3 class="fw-bold">{{ number_format($overview['total_stock_in']) }}</h3>
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
                            <h3 class="fw-bold">{{ number_format($overview['total_stock_out']) }}</h3>
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

        {{-- Date Range Display --}}
        <div class="alert alert-info mb-4">
            <i class="fas fa-calendar-alt me-2"></i>
            Showing data from <strong>{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}</strong> 
            to <strong>{{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</strong>
            @if($categoryId)
                | Category: <strong>{{ \App\Models\Category::find($categoryId)?->name ?? 'N/A' }}</strong>
            @endif
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
                        @if(!empty($charts['trend']['labels']))
                            <canvas id="inventoryTrendChart" height="140"></canvas>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                <p>No trend data available for the selected period</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Stock In vs Stock Out --}}
            <div class="col-xl-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-bold">
                        Stock-In vs Stock-Out (Daily)
                    </div>
                    <div class="card-body">
                        @if(!empty($charts['in_out']['labels']))
                            <canvas id="stockInOutChart" height="140"></canvas>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <p>No stock movement data available</p>
                            </div>
                        @endif
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
                        @if(!empty($charts['low_stock']['labels']))
                            <canvas id="lowStockChart" height="140"></canvas>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                <p>No low stock items found</p>
                            </div>
                        @endif
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
                        @if(!empty($charts['categories']['labels']))
                            <canvas id="categoryDistributionChart" height="140"></canvas>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-chart-pie fa-2x mb-2"></i>
                                <p>No category distribution data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- ========================= --}}
        {{--    INVENTORY TABLE       --}}
        {{-- ========================= --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                <span>Inventory Summary ({{ $paginatedInventory->total() }} records)</span>
                
                {{-- Optional filters --}}
                <form action="{{ route('admin.inventory-reports.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                    {{-- Start Date --}}
                    <input type="date" name="start_date" class="form-control form-control-sm"
                        value="{{ request('start_date', \Carbon\Carbon::now()->subMonth()->format('Y-m-d')) }}">

                    {{-- End Date --}}
                    <input type="date" name="end_date" class="form-control form-control-sm"
                        value="{{ request('end_date', \Carbon\Carbon::now()->format('Y-m-d')) }}">

                    {{-- Category --}}
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.inventory-reports.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Variant</th>
                                <th>Category</th>
                                <th class="text-center">Stock-In</th>
                                <th class="text-center">Stock-Out</th>
                                <th class="text-center">Current Stock</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paginatedInventory as $index => $row)
                                @php
                                    $currentStock = $row['current_stock'];
                                    $isLowStock = $currentStock <= 5;
                                    $isOutOfStock = $currentStock <= 0;
                                @endphp
                                <tr>
                                    <td>{{ $paginatedInventory->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                @if($isOutOfStock)
                                                    <span class="badge bg-danger">OUT</span>
                                                @elseif($isLowStock)
                                                    <span class="badge bg-warning">LOW</span>
                                                @else
                                                    <span class="badge bg-success">OK</span>
                                                @endif
                                            </div>
                                            {{ $row['product_name'] }}
                                        </div>
                                    </td>
                                    <td>{{ $row['variant_name'] ?? '—' }}</td>
                                    <td>{{ $row['category_name'] ?? '—' }}</td>
                                    <td class="text-center text-success fw-bold">+{{ number_format($row['total_stock_in']) }}</td>
                                    <td class="text-center text-danger fw-bold">-{{ number_format($row['total_stock_out']) }}</td>
                                    <td class="text-center fw-bold {{ $isOutOfStock ? 'text-danger' : ($isLowStock ? 'text-warning' : 'text-success') }}">
                                        {{ number_format($currentStock) }}
                                    </td>
                                    <td class="text-center">
                                        @if($isOutOfStock)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @elseif($isLowStock)
                                            <span class="badge bg-warning">Low Stock</span>
                                        @else
                                            <span class="badge bg-success">In Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="py-3">
                                            <i class="fas fa-database fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No inventory data found</h5>
                                            <p class="text-muted">Try adjusting your filters or date range</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($paginatedInventory->hasPages())
                    <div class="p-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing {{ $paginatedInventory->firstItem() }} to {{ $paginatedInventory->lastItem() }} 
                                of {{ $paginatedInventory->total() }} entries
                            </div>
                            {{ $paginatedInventory->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@section('scripts')
<script>
    // Debug: Check PHP data BEFORE charts
    console.log('=== INVENTORY REPORT DATA DEBUG ===');
    console.log('Trend data:', @json($charts['trend']));
    console.log('In/Out data:', @json($charts['in_out']));
    console.log('Low stock data:', @json($charts['low_stock']));
    console.log('Category data:', @json($charts['categories']));
    
    // Check if data arrays are empty
    const trendData = @json($charts['trend']['data'] ?? []);
    console.log('Trend data length:', trendData.length);
    
    // If data is empty, show message
    if (trendData.length === 0) {
        console.log('WARNING: No trend data available. Charts will not render.');
    }
    
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, checking canvas elements...');
        
        // Check all canvas elements
        const canvasIds = [
            'inventoryTrendChart',
            'stockInOutChart',
            'lowStockChart',
            'categoryDistributionChart'
        ];
        
        canvasIds.forEach(id => {
            const canvas = document.getElementById(id);
            if (canvas) {
                console.log(`✓ ${id}: Found, dimensions: ${canvas.offsetWidth}x${canvas.offsetHeight}`);
                
                // Check if canvas is visible
                const style = window.getComputedStyle(canvas);
                console.log(`  Display: ${style.display}, Visibility: ${style.visibility}`);
            } else {
                console.log(`✗ ${id}: NOT FOUND`);
            }
        });
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing charts...');
        
        // Debug: Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('ERROR: Chart.js is not loaded!');
            alert('Chart.js library failed to load. Please refresh the page.');
            return;
        }
        
        console.log('Chart.js loaded successfully, version:', Chart.version);
        
        /* ================== INVENTORY TREND CHART ================== */
        const trendCtx = document.getElementById('inventoryTrendChart');
        if (trendCtx) {
            console.log('Found trend chart canvas');
            
            const trendLabels = @json($charts['trend']['labels'] ?? []);
            const trendData = @json($charts['trend']['data'] ?? []);
            
            console.log('Trend Labels:', trendLabels);
            console.log('Trend Data:', trendData);
            
            if (trendLabels.length > 0 && trendData.length > 0) {
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: trendLabels,
                        datasets: [{
                            label: 'Stock Level',
                            data: trendData,
                            borderColor: 'rgba(40, 167, 69, 1)',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Stock Level'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                },
                                ticks: {
                                    maxTicksLimit: 10
                                }
                            }
                        }
                    }
                });
                console.log('Trend chart created successfully');
            } else {
                console.warn('No trend data available');
            }
        } else {
            console.error('Trend chart canvas not found!');
        }

        /* ================== STOCK-IN VS STOCK-OUT ================== */
        const inOutCtx = document.getElementById('stockInOutChart');
        if (inOutCtx) {
            console.log('Found stock in/out chart canvas');
            
            const inOutLabels = @json($charts['in_out']['labels'] ?? []);
            const stockInData = @json($charts['in_out']['stock_in'] ?? []);
            const stockOutData = @json($charts['in_out']['stock_out'] ?? []);
            
            console.log('In/Out Labels:', inOutLabels);
            console.log('Stock-In Data:', stockInData);
            console.log('Stock-Out Data:', stockOutData);
            
            if (inOutLabels.length > 0) {
                new Chart(inOutCtx, {
                    type: 'bar',
                    data: {
                        labels: inOutLabels,
                        datasets: [
                            {
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
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                stacked: false,
                                title: {
                                    display: true,
                                    text: 'Quantity'
                                }
                            },
                            x: {
                                stacked: false,
                                title: {
                                    display: true,
                                    text: 'Date'
                                },
                                ticks: {
                                    maxTicksLimit: 10
                                }
                            }
                        }
                    }
                });
                console.log('Stock in/out chart created successfully');
            } else {
                console.warn('No stock in/out data available');
            }
        }

        /* ================== LOW STOCK CHART ================== */
        const lowCtx = document.getElementById('lowStockChart');
        if (lowCtx) {
            console.log('Found low stock chart canvas');
            
            const lowLabels = @json($charts['low_stock']['labels'] ?? []);
            const lowData = @json($charts['low_stock']['data'] ?? []);
            
            console.log('Low Stock Labels:', lowLabels);
            console.log('Low Stock Data:', lowData);
            
            if (lowLabels.length > 0 && lowData.length > 0) {
                new Chart(lowCtx, {
                    type: 'bar',
                    data: {
                        labels: lowLabels,
                        datasets: [{
                            label: 'Current Stock',
                            data: lowData,
                            backgroundColor: function(context) {
                                const value = context.raw;
                                if (value <= 0) return 'rgba(220, 53, 69, 0.8)';
                                if (value <= 5) return 'rgba(255, 193, 7, 0.8)';
                                return 'rgba(40, 167, 69, 0.8)';
                            },
                            borderWidth: 1,
                            borderColor: '#333'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Stock: ${context.raw} units`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Stock Quantity'
                                }
                            }
                        }
                    }
                });
                console.log('Low stock chart created successfully');
            } else {
                console.warn('No low stock data available');
            }
        }

        /* ================== CATEGORY DISTRIBUTION CHART ================== */
        const catCtx = document.getElementById('categoryDistributionChart');
        if (catCtx) {
            console.log('Found category distribution chart canvas');
            
            const catLabels = @json($charts['categories']['labels'] ?? []);
            const catData = @json($charts['categories']['data'] ?? []);
            
            console.log('Category Labels:', catLabels);
            console.log('Category Data:', catData);
            
            if (catLabels.length > 0 && catData.length > 0) {
                new Chart(catCtx, {
                    type: 'pie',
                    data: {
                        labels: catLabels,
                        datasets: [{
                            data: catData,
                            backgroundColor: [
                                '#0d6efd', '#198754', '#ffc107', '#dc3545',
                                '#0dcaf0', '#6f42c1', '#fd7e14', '#6c757d',
                                '#20c997', '#e83e8c', '#6610f2', '#d63384'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} products (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
                console.log('Category distribution chart created successfully');
            } else {
                console.warn('No category data available');
            }
        }
        
        console.log('All charts initialized successfully');
    });
</script>
@endsection