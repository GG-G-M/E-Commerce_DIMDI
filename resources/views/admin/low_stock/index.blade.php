@extends('layouts.admin')

@section('content')
<style>
    /* === Consistent Green Theme === */
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
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

    /* Table Styling - Compact */
    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
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

    /* Stock Badges - Compact */
    .stock-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 70px;
    }

    .badge-critical {
        background-color: #FFEBEE;
        color: #C62828;
        border: 1px solid #FFCDD2;
    }

    .badge-warning {
        background-color: #FFF8E1;
        color: #F57C00;
        border: 1px solid #FFE082;
    }

    .badge-low {
        background-color: #E8F5E6;
        color: #2C8F0C;
        border: 1px solid #C8E6C9;
    }

    /* Product Info Cell - Compact */
    .product-info-cell {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .product-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }
    
    .product-name {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
        line-height: 1.2;
    }
    
    .variant-name {
        color: #6c757d;
        font-size: 0.8rem;
        font-style: italic;
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

    /* Status Badge - Consistent with Products Page */
    .status-badge-active {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 60px;
        background-color: #E8F5E6;
        color: #2C8F0C;
        border: 1px solid #C8E6C9;
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

    /* Table Container */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        max-width: 100%;
    }

    /* Column widths - More compact */
    .id-col { width: 70px; min-width: 70px; }
    .product-col { width: 220px; min-width: 220px; }
    .stock-col { width: 100px; min-width: 100px; }
    .level-col { width: 100px; min-width: 100px; }
    .status-col { width: 80px; min-width: 80px; }
    .ratio-col { width: 140px; min-width: 140px; }

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

    /* Pagination styling - Consistent */
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

    /* Make table more compact on mobile */
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
        
        .stock-badge {
            min-width: 60px;
            font-size: 0.7rem;
        }
        
        .status-badge-active,
        .status-badge-inactive {
            min-width: 50px;
            font-size: 0.7rem;
        }
    }
</style>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="summary-card">
            <div class="summary-number">{{ $products->count() + $variants->count() }}</div>
            <div class="summary-label">Total Low Stock Items</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary-card">
            <div class="summary-number">{{ $products->count() }}</div>
            <div class="summary-label">Main Products</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary-card">
            <div class="summary-number">{{ $variants->count() }}</div>
            <div class="summary-label">Product Variants</div>
        </div>
    </div>
</div>

<!-- Low Stock Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Low Stock Items (Threshold: {{ $threshold }})</h5>
        <div class="header-buttons">
            <a href="{{ route('admin.low_stock.download_csv', ['threshold' => $threshold]) }}" class="btn-download-csv">
                <i class="fas fa-file-download"></i> Download CSV
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        @if($products->count() > 0 || $variants->count() > 0)
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
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
                                $badgeClass = 'badge-low';
                                $statusText = 'Low Stock';
                                
                                if ($product->stock_quantity <= 0) {
                                    $stockLevel = 'critical';
                                    $stockClass = 'stock-critical';
                                    $badgeClass = 'badge-critical';
                                    $statusText = 'Out of Stock';
                                } elseif ($product->stock_quantity <= ceil($threshold * 0.3)) {
                                    $stockLevel = 'critical';
                                    $stockClass = 'stock-critical';
                                    $badgeClass = 'badge-critical';
                                    $statusText = 'Critical';
                                } elseif ($product->stock_quantity <= ceil($threshold * 0.6)) {
                                    $stockLevel = 'warning';
                                    $stockClass = 'stock-warning';
                                    $badgeClass = 'badge-warning';
                                    $statusText = 'Warning';
                                }
                                
                                $stockPercentage = min(100, ($product->stock_quantity / $threshold) * 100);
                            @endphp
                            <tr class="{{ $stockClass }}">
                                <td class="id-col">
                                    <small class="text-muted">#{{ $product->id }}</small>
                                </td>
                                <td class="product-col">
                                    <div class="product-info-cell">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                                 class="product-img">
                                        @else
                                            <div class="product-img d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="product-name">{{ Str::limit($product->name, 30) }}</div>
                                            @if($product->sku)
                                                <div class="product-sku">{{ $product->sku }}</div>
                                            @endif
                                            @if($product->brand)
                                                <div class="product-brand">{{ $product->brand->name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="stock-col">
                                    <div class="stock-quantity">{{ $product->stock_quantity }}</div>
                                    <div class="stock-unit">units</div>
                                </td>
                                <td class="level-col">
                                    <span class="stock-badge {{ $badgeClass }}">{{ $statusText }}</span>
                                </td>
                                <td class="status-col">
                                    @if($product->is_effectively_inactive)
                                        <span class="status-badge-inactive">Inactive</span>
                                    @else
                                        <span class="status-badge-active">Active</span>
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
                                $badgeClass = 'badge-low';
                                $statusText = 'Low Stock';
                                
                                if ($variant->stock_quantity <= 0) {
                                    $stockLevel = 'critical';
                                    $stockClass = 'stock-critical';
                                    $badgeClass = 'badge-critical';
                                    $statusText = 'Out of Stock';
                                } elseif ($variant->stock_quantity <= ceil($threshold * 0.3)) {
                                    $stockLevel = 'critical';
                                    $stockClass = 'stock-critical';
                                    $badgeClass = 'badge-critical';
                                    $statusText = 'Critical';
                                } elseif ($variant->stock_quantity <= ceil($threshold * 0.6)) {
                                    $stockLevel = 'warning';
                                    $stockClass = 'stock-warning';
                                    $badgeClass = 'badge-warning';
                                    $statusText = 'Warning';
                                }
                                
                                $stockPercentage = min(100, ($variant->stock_quantity / $threshold) * 100);
                            @endphp
                            <tr class="{{ $stockClass }}">
                                <td class="id-col">
                                    <small class="text-muted">#{{ $variant->id }}</small>
                                </td>
                                <td class="product-col">
                                    <div class="product-info-cell">
                                        @if($variant->product->image_url)
                                            <img src="{{ $variant->product->image_url }}" alt="{{ $variant->product->name }}" 
                                                 class="product-img">
                                        @else
                                            <div class="product-img d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="product-name">{{ Str::limit($variant->product->name, 25) }}</div>
                                            <div class="variant-name">{{ Str::limit($variant->variant_name, 20) }}</div>
                                            @if($variant->sku)
                                                <div class="product-sku">{{ $variant->sku }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="stock-col">
                                    <div class="stock-quantity">{{ $variant->stock_quantity }}</div>
                                    <div class="stock-unit">units</div>
                                </td>
                                <td class="level-col">
                                    <span class="stock-badge {{ $badgeClass }}">{{ $statusText }}</span>
                                </td>
                                <td class="status-col">
                                    @if($variant->product->is_effectively_inactive || !$variant->is_active)
                                        <span class="status-badge-inactive">Inactive</span>
                                    @else
                                        <span class="status-badge-active">Active</span>
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
@endsection