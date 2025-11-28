@extends('layouts.admin')

@section('content')
<style>
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
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
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
    }

    .btn-success {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        color: #fff;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        color: #fff;
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    .stock-critical {
        background-color: #FFEBEE !important;
        border-left: 4px solid #C62828;
    }

    .stock-warning {
        background-color: #FFF8E1 !important;
        border-left: 4px solid #FBC02D;
    }

    .stock-low {
        background-color: #E8F5E9 !important;
        border-left: 4px solid #2C8F0C;
    }

    .stock-badge {
        padding: 0.35em 0.65em;
        border-radius: 0.25rem;
        font-weight: 600;
        font-size: 0.75em;
    }

    .badge-critical {
        background-color: #C62828;
        color: white;
    }

    .badge-warning {
        background-color: #FBC02D;
        color: #000;
    }

    .badge-low {
        background-color: #2C8F0C;
        color: white;
    }

    .product-name {
        font-weight: 600;
        color: #2C8F0C;
    }

    .variant-name {
        color: #6c757d;
        font-size: 0.875em;
    }

    .stock-quantity {
        font-weight: 700;
        font-size: 1.1em;
    }

    .threshold-indicator {
        width: 100px;
        height: 6px;
        background-color: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 5px;
    }

    .threshold-fill {
        height: 100%;
        border-radius: 3px;
    }

    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #2C8F0C;
    }

    .summary-card {
        background: linear-gradient(135deg, #E8F5E6, #F8FDF8);
        border: 1px solid #2C8F0C;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .summary-number {
        font-size: 2rem;
        font-weight: 700;
        color: #2C8F0C;
        line-height: 1;
    }

    .summary-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 600;
    }
</style>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="summary-card text-center">
            <div class="summary-number">{{ $products->count() + $variants->count() }}</div>
            <div class="summary-label">Total Low Stock Items</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary-card text-center">
            <div class="summary-number">{{ $products->count() }}</div>
            <div class="summary-label">Main Products</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary-card text-center">
            <div class="summary-number">{{ $variants->count() }}</div>
            <div class="summary-label">Product Variants</div>
        </div>
    </div>
</div>

<!-- Low Stock Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Low Stock Items (Threshold: {{ $threshold }})</h5>
        <a href="{{ route('admin.low_stock.download_csv', ['threshold' => $threshold]) }}" class="btn btn-success">
            <i class="fas fa-file-download me-2"></i>Download CSV Report
        </a>
    </div>
    <div class="card-body">
        @if($products->count() > 0 || $variants->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product / Variant</th>
                            <th>Current Stock</th>
                            <th>Stock Level</th>
                            <th>Status</th>
                            <th>Stock Ratio</th>
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
                                <td>
                                    <span class="text-muted">#{{ $product->id }}</span>
                                </td>
                                <td>
                                    <div class="product-name">{{ $product->name }}</div>
                                    <div class="text-muted small">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                    @if($product->brand)
                                        <div class="text-muted small">Brand: {{ $product->brand->name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="stock-quantity">{{ $product->stock_quantity }}</span>
                                    <div class="text-muted small">units</div>
                                </td>
                                <td>
                                    <span class="stock-badge {{ $badgeClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
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
                                    <div class="text-muted small text-center mt-1">
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
                                <td>
                                    <span class="text-muted">#{{ $variant->id }}</span>
                                </td>
                                <td>
                                    <div class="product-name">{{ $variant->product->name }}</div>
                                    <div class="variant-name">{{ $variant->variant_name }}</div>
                                    <div class="text-muted small">SKU: {{ $variant->sku ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <span class="stock-quantity">{{ $variant->stock_quantity }}</span>
                                    <div class="text-muted small">units</div>
                                </td>
                                <td>
                                    <span class="stock-badge {{ $badgeClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    @if($variant->product->is_active && $variant->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
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
                                    <div class="text-muted small text-center mt-1">
                                        {{ number_format($stockPercentage, 1) }}% of threshold
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Stock Level Legend -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="alert alert-light border">
                        <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Stock Level Legend</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="stock-badge badge-critical me-2">Critical</div>
                                    <small class="text-muted">Below 30% of threshold or out of stock</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="stock-badge badge-warning me-2">Warning</div>
                                    <small class="text-muted">Between 30% - 60% of threshold</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="stock-badge badge-low me-2">Low Stock</div>
                                    <small class="text-muted">Between 60% - 100% of threshold</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-boxes"></i>
                <h4 class="text-success">All Stock Levels Are Good!</h4>
                <p class="text-muted mb-4">No items are currently below the threshold of {{ $threshold }} units.</p>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Great job!</strong> Your inventory is well-stocked above the threshold level.
                </div>
            </div>
        @endif
    </div>
</div>
@endsection