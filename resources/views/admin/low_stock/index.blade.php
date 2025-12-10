@extends('layouts.admin')

@section('content')
<style>
    /* === Consistent Green Theme === */
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
        overflow: hidden;
    }

    .card-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
    }

    /* CSV Download Button - Consistent with Import */
    .btn-download-csv {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(44, 143, 12, 0.2);
        height: 46px;
    }
    
    .btn-download-csv:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
    }

    /* Filter Section - Matching stock_in structure */
    .search-section {
        background-color: #F8FDF8;
        border: 1px solid #E8F5E6;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    /* Tips Box - Matching stock_in */
    .tips-box {
        background-color: #F8FDF8;
        border-left: 3px solid #2C8F0C;
        border-radius: 6px;
        padding: 0.75rem;
        font-size: 0.85rem;
        color: #2C8F0C;
    }

    .tips-box i {
        color: #2C8F0C;
        margin-right: 5px;
    }

    .search-loading {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }

    .position-relative {
        position: relative;
    }

    /* Table Styling - Matching stock_out structure */
    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
        table-layout: fixed;
    }
    
    /* Center align all table headers and cells */
    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
    }
    
    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 0.75rem 0.5rem;
        white-space: nowrap;
    }

    .table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        text-align: center;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    /* Alternating row colors */
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #F8FDF8;
    }

    /* Stock Level Styling - Color-coded */
    .stock-critical {
        background-color: #FFEBEE !important;
    }
    
    .stock-critical:hover {
        background-color: #FFCDD2 !important;
    }

    .stock-warning {
        background-color: #FFF8E1 !important;
    }
    
    .stock-warning:hover {
        background-color: #FFECB3 !important;
    }

    .stock-low {
        background-color: #E8F5E9 !important;
    }
    
    .stock-low:hover {
        background-color: #C8E6C9 !important;
    }

    /* Stock Status - No badges, just text with indicators */
    .status-text {
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-text-critical {
        color: #C62828;
    }
    
    .status-text-critical::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #C62828;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-text-warning {
        color: #F57C00;
    }
    
    .status-text-warning::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #F57C00;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-text-low {
        color: #2C8F0C;
    }
    
    .status-text-low::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #2C8F0C;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }

    /* Active/Inactive Status - Consistent with customers page */
    .status-text-active {
        color: #2C8F0C;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-text-active::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #2C8F0C;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    /* Inactive Status - Consistent with customers page */
    .status-badge-inactive {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 60px;
        background-color: #FFF3CD;
        color: #856404;
        border: 1px solid #FFEAA7;
    }

    /* Product Info Cell - Matching stock_out style */
    .product-info-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }
    
    .product-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
        margin: 0 auto;
        display: block;
    }
    
    .product-name {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
        line-height: 1.2;
        text-align: center;
    }
    
    .variant-name {
        color: #6c757d;
        font-size: 0.8rem;
        font-style: italic;
        text-align: center;
    }
    
    .product-sku {
        color: #6c757d;
        font-size: 0.75rem;
        margin-top: 2px;
    }
    
    .product-brand {
        color: #495057;
        font-size: 0.75rem;
    }

    /* Stock Quantity Styling */
    .stock-quantity {
        font-weight: 700;
        font-size: 1rem;
        color: #333;
    }
    
    .stock-unit {
        color: #6c757d;
        font-size: 0.75rem;
    }

    /* Progress Bar - Compact */
    .threshold-indicator {
        width: 120px;
        height: 6px;
        background-color: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
        margin: 4px 0;
    }

    .threshold-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    /* Percentage Text */
    .percentage-text {
        font-size: 0.8rem;
        color: #6c757d;
        text-align: center;
        margin-top: 2px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #C8E6C9;
    }

    /* Summary Cards - Compact */
    .summary-card {
        background: linear-gradient(135deg, #E8F5E6, #F8FDF8);
        border: none;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .summary-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2C8F0C;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .summary-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
    }

    /* Column widths - Matching stock_out structure */
    .id-col { width: 70px; min-width: 70px; text-align: center; }
    .image-col { width: 80px; min-width: 80px; text-align: center; }
    .product-col { width: 160px; min-width: 160px; text-align: center; }
    .stock-col { width: 100px; min-width: 100px; text-align: center; }
    .level-col { width: 120px; min-width: 120px; text-align: center; }
    .status-col { width: 100px; min-width: 100px; text-align: center; }
    .ratio-col { width: 140px; min-width: 140px; text-align: center; }

    /* Legend Styling */
    .legend-container {
        background-color: #F8FDF8;
        border: 1px solid #E8F5E6;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 0.5rem;
    }
    
    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 4px;
    }
    
    .legend-text {
        font-size: 0.85rem;
        color: #495057;
    }

    /* Pagination styling - Matching stock_out */
    .pagination .page-item .page-link {
        color: #2C8F0C;
        border: 1px solid #dee2e6;
        margin: 0 1px;
        border-radius: 4px;
        transition: all 0.3s ease;
        padding: 0.4rem 0.7rem;
        font-size: 0.85rem;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-color: #2C8F0C;
        color: white;
    }
    
    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #E8FDF8;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
    }

    /* Header button group */
    .header-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .header-buttons .btn {
        margin: 0;
        font-size: 0.9rem;
    }

    /* Make table more compact on mobile - Matching stock_out */
    @media (max-width: 768px) {
        .header-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
        }
        
        .product-img {
            width: 40px;
            height: 40px;
        }
        
        .summary-number {
            font-size: 1.5rem;
        }
        
        .summary-label {
            font-size: 0.8rem;
        }
        
        .status-text,
        .status-text-active,
        .status-text-critical,
        .status-text-warning,
        .status-text-low {
            font-size: 0.8rem;
        }
        
        .status-badge-inactive {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            min-width: 60px;
            background-color: #FFF3CD;
            color: #856404;
            border: 1px solid #FFEAA7;
        }
    }
</style>


<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->count() + $variants->count() }}</div>
            <div class="summary-label">Total Low Stock Items</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">
                @php
                    $criticalCount = 0;
                    foreach($products as $product) {
                        if ($product->stock_quantity <= 0 || $product->stock_quantity <= ceil($threshold * 0.3)) {
                            $criticalCount++;
                        }
                    }
                    foreach($variants as $variant) {
                        if ($variant->stock_quantity <= 0 || $variant->stock_quantity <= ceil($threshold * 0.3)) {
                            $criticalCount++;
                        }
                    }
                    echo $criticalCount;
                @endphp
            </div>
            <div class="summary-label">Critical Items</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->count() }}</div>
            <div class="summary-label">Main Products</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $variants->count() }}</div>
            <div class="summary-label">Product Variants</div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.low_stock.index') }}" id="filterForm">
            <div class="row g-2">

                <!-- Search -->
                <div class="col-md-6">
                    <div class="mb-2 position-relative">
                        <label for="search" class="form-label fw-bold">Search Products/Variants</label>
                        <input type="text" class="form-control" id="search" name="search" 
                            value="{{ request('search') }}" placeholder="Search by name, SKU, or brand...">
                        <div class="search-loading" id="searchLoading">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Threshold Filter -->
                <div class="col-md-2">
                    <div class="mb-2 position-relative">
                        <label for="threshold" class="form-label fw-bold">Stock Threshold</label>
                        <input type="number" class="form-control" id="threshold" name="threshold" 
                            value="{{ request('threshold', 10) }}" min="1" placeholder="Threshold">
                        <div class="search-loading" id="thresholdLoading" style="display: none;">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock Level Filter -->
                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="stock_level" class="form-label fw-bold">Stock Level</label>
                        <select class="form-select" id="stock_level" name="stock_level">
                            <option value="">All Levels</option>
                            <option value="critical" {{ request('stock_level') == 'critical' ? 'selected' : '' }}>Critical</option>
                            <option value="warning" {{ request('stock_level') == 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="low" {{ request('stock_level') == 'low' ? 'selected' : '' }}>Low Stock</option>
                        </select>
                    </div>
                </div>

                <!-- Items per page selection -->
                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="per_page" class="form-label fw-bold">Items per page</label>
                        <select class="form-select" id="per_page" name="per_page">
                            @foreach([5, 10, 15, 25, 50] as $option)
                                <option value="{{ $option }}" {{ request('per_page', 15) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
        
        <!-- Tips Box -->
        {{-- <div class="tips-box mt-3">
            <i class="fas fa-lightbulb"></i>
            <strong>Tip:</strong> Use the filters above to quickly find specific low stock items. The threshold determines what counts as "low stock" - items at or below this number will be displayed.
        </div> --}}
    </div>
</div>


<!-- Low Stock Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Low Stock Items (Threshold: {{ $threshold }})</h5>
        <div class="header-buttons">
            <a href="{{ route('admin.low_stock.download_csv', array_merge(request()->query(), ['threshold' => $threshold])) }}" class="btn-download-csv">
                <i class="fas fa-file-download"></i> Download CSV
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        @if($products->count() > 0 || $variants->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
                            <th class="image-col">Image</th>
                            <th class="product-col">Product / Variant</th>
                            <th class="stock-col">Current Stock</th>
                            <th class="level-col">Stock Level</th>
                            <th class="status-col">Status</th>
                            <th class="ratio-col">Stock Ratio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            @php
                                $stockLevel = 'low';
                                $stockClass = 'stock-low';
                                $statusText = 'Low Stock';
                                $statusClass = 'status-text-low';
                                
                                if ($product->stock_quantity <= 0) {
                                    $stockLevel = 'critical';
                                    $stockClass = 'stock-critical';
                                    $statusText = 'Out of Stock';
                                    $statusClass = 'status-text-critical';
                                } elseif ($product->stock_quantity <= ceil($threshold * 0.3)) {
                                    $stockLevel = 'critical';
                                    $stockClass = 'stock-critical';
                                    $statusText = 'Critical';
                                    $statusClass = 'status-text-critical';
                                } elseif ($product->stock_quantity <= ceil($threshold * 0.6)) {
                                    $stockLevel = 'warning';
                                    $stockClass = 'stock-warning';
                                    $statusText = 'Warning';
                                    $statusClass = 'status-text-warning';
                                }
                                
                                $stockPercentage = min(100, ($product->stock_quantity / $threshold) * 100);
                            @endphp
                            <tr class="{{ $stockClass }}">
                                <td class="id-col">
                                    <small class="text-muted">#{{ $product->id }}</small>
                                </td>
                                <td class="image-col">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                             class="product-img">
                                    @else
                                        <div class="product-img d-flex align-items-center justify-content-center bg-light">
                                            <i class="fas fa-box text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="product-col">
                                    <div>
                                        <div class="product-name">{{ Str::limit($product->name, 25) }}</div>
                                        @if($product->sku)
                                            <div class="product-sku">{{ $product->sku }}</div>
                                        @endif
                                        @if($product->brand)
                                            <div class="product-brand">{{ $product->brand->name }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="stock-col">
                                    <div class="stock-quantity">{{ $product->stock_quantity }}</div>
                                    <div class="stock-unit">units</div>
                                </td>
                                <td class="level-col">
                                    <span class="status-text {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td class="status-col">
                                    @if($product->is_effectively_inactive)
                                        <span class="status-badge-inactive">Inactive</span>
                                    @else
                                        <span class="status-text-active">Active</span>
                                    @endif
                                </td>
                                <td class="ratio-col">
                                    <div class="threshold-indicator">
                                        <div class="threshold-fill" 
                                             style="width: {{ $stockPercentage }}%; 
                                                    background-color: 
                                                    @if($stockLevel == 'critical') #C62828
                                                    @elseif($stockLevel == 'warning') #FBC02D
                                                    @else #2C8F0C
                                                    @endif">
                                        </div>
                                    </div>
                                    <div class="percentage-text">
                                        {{ number_format($stockPercentage, 1) }}% of threshold
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @foreach ($variants as $variant)
                            @php
                                $stockLevel = 'low';
                                $stockClass = 'stock-low';
                                $statusText = 'Low Stock';
                                $statusClass = 'status-text-low';
                                
                                if ($variant->stock_quantity <= 0) {
                                    $stockLevel = 'critical';
                                    $stockClass = 'stock-critical';
                                    $statusText = 'Out of Stock';
                                    $statusClass = 'status-text-critical';
                                } elseif ($variant->stock_quantity <= ceil($threshold * 0.3)) {
                                    $stockLevel = 'critical';
                                    $stockClass = 'stock-critical';
                                    $statusText = 'Critical';
                                    $statusClass = 'status-text-critical';
                                } elseif ($variant->stock_quantity <= ceil($threshold * 0.6)) {
                                    $stockLevel = 'warning';
                                    $stockClass = 'stock-warning';
                                    $statusText = 'Warning';
                                    $statusClass = 'status-text-warning';
                                }
                                
                                $stockPercentage = min(100, ($variant->stock_quantity / $threshold) * 100);
                            @endphp
                            <tr class="{{ $stockClass }}">
                                <td class="id-col">
                                    <small class="text-muted">#{{ $variant->id }}</small>
                                </td>
                                <td class="image-col">
                                    @if($variant->product->image_url)
                                        <img src="{{ $variant->product->image_url }}" alt="{{ $variant->product->name }}" 
                                             class="product-img">
                                    @else
                                        <div class="product-img d-flex align-items-center justify-content-center bg-light">
                                            <i class="fas fa-box text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="product-col">
                                    <div>
                                        <div class="product-name">{{ Str::limit($variant->product->name, 20) }}</div>
                                        <div class="variant-name">{{ Str::limit($variant->variant_name, 15) }}</div>
                                        @if($variant->sku)
                                            <div class="product-sku">{{ $variant->sku }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="stock-col">
                                    <div class="stock-quantity">{{ $variant->stock_quantity }}</div>
                                    <div class="stock-unit">units</div>
                                </td>
                                <td class="level-col">
                                    <span class="status-text {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td class="status-col">
                                    @if($variant->is_effectively_inactive)
                                        <span class="status-badge-inactive">Inactive</span>
                                    @else
                                        <span class="status-text-active">Active</span>
                                    @endif
                                </td>
                                <td class="ratio-col">
                                    <div class="threshold-indicator">
                                        <div class="threshold-fill" 
                                             style="width: {{ $stockPercentage }}%; 
                                                    background-color: 
                                                    @if($stockLevel == 'critical') #C62828
                                                    @elseif($stockLevel == 'warning') #FBC02D
                                                    @else #2C8F0C
                                                    @endif">
                                        </div>
                                    </div>
                                    <div class="percentage-text">
                                        {{ number_format($stockPercentage, 1) }}% of threshold
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Stock Level Legend -->
            <div class="legend-container m-3">
                <h6 class="mb-3" style="color: #2C8F0C; font-weight: 600;">
                    <i class="fas fa-info-circle me-2"></i>Stock Level Legend
                </h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #C62828;"></div>
                            <div class="legend-text">
                                <strong>Critical:</strong> Below 30% of threshold or out of stock
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #FBC02D;"></div>
                            <div class="legend-text">
                                <strong>Warning:</strong> Between 30% - 60% of threshold
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #2C8F0C;"></div>
                            <div class="legend-text">
                                <strong>Low Stock:</strong> Between 60% - 100% of threshold
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state p-5">
                <i class="fas fa-boxes"></i>
                <h5 class="text-muted">All Stock Levels Are Good!</h5>
                <p class="text-muted mb-4">No items are currently below the threshold of {{ $threshold }} units.</p>
                <div class="alert alert-success border-success" style="background-color: #E8F5E6; border-color: #C8E6C9;">
                    <i class="fas fa-check-circle me-2" style="color: #2C8F0C;"></i>
                    <strong style="color: #2C8F0C;">Great job!</strong> 
                    <span style="color: #2C8F0C;">Your inventory is well-stocked above the threshold level.</span>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Auto-search functionality ---
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const thresholdInput = document.getElementById('threshold');
    const stockLevelSelect = document.getElementById('stock_level');
    const perPageSelect = document.getElementById('per_page');
    const searchLoading = document.getElementById('searchLoading');
    const thresholdLoading = document.getElementById('thresholdLoading');
    let searchTimeout;
    let thresholdTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            if (searchLoading) searchLoading.style.display = 'block';
            searchTimeout = setTimeout(() => filterForm.submit(), 800);
        });
    }

    if (thresholdInput) {
        thresholdInput.addEventListener('input', function() {
            clearTimeout(thresholdTimeout);
            if (thresholdLoading) thresholdLoading.style.display = 'block';
            thresholdTimeout = setTimeout(() => filterForm.submit(), 1000);
        });
    }

    // Auto-submit other filters immediately
    [stockLevelSelect, perPageSelect].forEach(el => {
        if (el) {
            el.addEventListener('change', () => filterForm.submit());
        }
    });

    if (filterForm) {
        filterForm.addEventListener('submit', () => {
            if (searchLoading) searchLoading.style.display = 'none';
            if (thresholdLoading) thresholdLoading.style.display = 'none';
        });
    }

    // --- CSV Download Loading State ---
    const csvButton = document.querySelector('.btn-download-csv');
    if (csvButton) {
        csvButton.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Preparing CSV...';
            this.style.pointerEvents = 'none';
            
            // Reset button after 3 seconds (in case of slow download)
            setTimeout(() => {
                this.innerHTML = originalText;
                this.style.pointerEvents = 'auto';
            }, 3000);
        });
    }
});
</script>
@endpush
@endsection