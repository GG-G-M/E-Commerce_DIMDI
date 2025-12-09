@extends('layouts.admin')

@section('content')
<style>
    /* Modern Clean Design System */
    :root {
        --primary-green: #2C8F0C;
        --secondary-green: #4CAF50;
        --light-green: #E8F5E9;
        --dark-green: #1B5E20;
        --gray-50: #F9FAFB;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-300: #D1D5DB;
        --gray-400: #9CA3AF;
        --gray-500: #6B7280;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
        --gray-900: #111827;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --radius-sm: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
    }

    /* Clean Back Navigation */
    .back-nav {
        margin-bottom: 2rem;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--gray-100);
        border: 1px solid var(--gray-300);
        border-radius: var(--radius-lg);
        color: var(--gray-700);
        font-weight: 500;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .back-btn:hover {
        background: var(--gray-200);
        color: var(--gray-800);
        transform: translateX(-2px);
        text-decoration: none;
    }

    .back-btn i {
        font-size: 0.875rem;
    }

    /* Main Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Product Image Card */
    .image-card {
        background: white;
        border-radius: var(--radius-xl);
        padding: 2rem;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow);
        position: sticky;
        top: 2rem;
    }

    .product-image-container {
        position: relative;
        background: var(--gray-50);
        border-radius: var(--radius-lg);
        padding: 2rem;
        border: 2px solid var(--gray-200);
        margin-bottom: 1.5rem;
    }

    .product-image {
        width: 100%;
        height: 300px;
        object-fit: contain;
        border-radius: var(--radius-md);
        background: white;
        padding: 1rem;
        box-shadow: var(--shadow-sm);
    }

    .sku-text {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: var(--gray-800);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        font-weight: 500;
        letter-spacing: 0.025em;
    }

    /* Price Display */
    .price-display {
        background: linear-gradient(135deg, var(--light-green), #C8E6C9);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        border: 2px solid var(--primary-green);
        text-align: center;
        margin-top: 1.5rem;
    }

    .current-price {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-green);
        line-height: 1;
        letter-spacing: -0.025em;
    }

    .original-price {
        font-size: 1.25rem;
        color: var(--gray-500);
        text-decoration: line-through;
        margin-top: 0.5rem;
    }

    .discount-text {
        color: var(--primary-green);
        font-size: 0.875rem;
        font-weight: 600;
        margin-top: 0.75rem;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    /* Info Card */
    .info-card {
        background: white;
        border-radius: var(--radius-xl);
        padding: 2rem;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow);
    }

    .info-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-green);
    }

    .info-card-header i {
        color: var(--primary-green);
        font-size: 1.25rem;
    }

    .info-card-header h3 {
        font-weight: 600;
        color: var(--gray-800);
        font-size: 1.125rem;
        margin: 0;
    }

    /* Info List */
    .info-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-100);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: var(--gray-600);
        font-weight: 500;
        font-size: 0.9rem;
        flex: 1;
    }

    .info-value {
        color: var(--gray-800);
        font-weight: 600;
        text-align: right;
        flex: 1;
        max-width: 60%;
        word-break: break-word;
    }

    /* Status Text */
    .status-text {
        color: var(--primary-green);
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Stock Text */
    .stock-text {
        color: var(--gray-800);
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Variants Table Card */
    .variants-card {
        background: white;
        border-radius: var(--radius-xl);
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-top: 2rem;
    }

    .variants-card-header {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .variants-card-header h3 {
        font-weight: 600;
        font-size: 1.25rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .variant-count {
        color: white;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Modern Table */
    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table thead {
        background: var(--gray-50);
        border-bottom: 2px solid var(--gray-200);
    }

    .modern-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 600;
        color: var(--gray-700);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid var(--gray-200);
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background: var(--gray-50);
    }

    .modern-table td {
        padding: 1.25rem 1.5rem;
        color: var(--gray-700);
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .variant-name {
        font-weight: 600;
        color: var(--gray-800);
    }

    .variant-sku {
        font-family: 'SF Mono', Monaco, monospace;
        color: var(--gray-600);
        font-size: 0.875rem;
        background: var(--gray-100);
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
    }

    .variant-price {
        font-weight: 700;
        color: var(--primary-green);
        font-size: 1rem;
    }

    /* Action Button */
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        cursor: pointer;
        text-decoration: none;
    }

    .action-btn i {
        font-size: 0.875rem;
    }

    .btn-edit {
        background: var(--primary-green);
        color: white;
        border-color: var(--primary-green);
    }

    .btn-edit:hover {
        background: var(--dark-green);
        border-color: var(--dark-green);
        transform: translateY(-1px);
        box-shadow: var(--shadow);
        color: white;
        text-decoration: none;
    }

    .btn-edit-variant {
        background: rgba(44, 143, 12, 0.1);
        color: var(--primary-green);
        border: 1px solid rgba(44, 143, 12, 0.2);
        border-radius: var(--radius-md);
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-edit-variant:hover {
        background: rgba(44, 143, 12, 0.2);
        color: var(--dark-green);
        transform: translateY(-1px);
        text-decoration: none;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--gray-500);
    }

    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
        color: var(--gray-400);
    }

    .empty-state h4 {
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--gray-500);
        max-width: 400px;
        margin: 0 auto;
    }

    /* Stock Summary */
    .stock-summary {
        background: linear-gradient(135deg, #F0F9FF, #E0F2FE);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        margin-top: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stock-summary-item {
        text-align: center;
    }

    .stock-summary-label {
        color: var(--gray-600);
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .stock-summary-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-green);
    }

    /* Action Bar */
    .action-bar {
        background: white;
        border-radius: var(--radius-xl);
        padding: 2rem;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow);
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .action-bar {
            flex-direction: column;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }

        .stock-summary {
            flex-direction: column;
            gap: 1rem;
        }
    }

    @media (max-width: 480px) {
        .modern-table {
            display: block;
            overflow-x: auto;
        }

        .content-grid {
            gap: 1rem;
        }

        .image-card,
        .info-card {
            padding: 1.5rem;
        }

        .variants-card-header {
            padding: 1rem;
        }
    }
</style>

<!-- Clean Back Navigation -->
<div class="back-nav">
    <a href="{{ route('admin.products.index') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i>
        Back to Products
    </a>
</div>

<!-- Main Content Grid -->
<div class="content-grid">
    <!-- Left Column: Image & Price -->
    <div class="image-card">
        <div class="product-image-container">
            <span class="sku-text">SKU: {{ $product->sku }}</span>
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image" 
                 onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
        </div>
        
        <div class="price-display">
            @if($product->has_discount)
                <div class="discount-text">{{ $product->discount_percentage }}% OFF</div>
                <div class="current-price">₱{{ number_format($product->sale_price, 2) }}</div>
                <div class="original-price">₱{{ number_format($product->price, 2) }}</div>
            @else
                <div class="current-price">₱{{ number_format($product->price, 2) }}</div>
            @endif
        </div>
    </div>

    <!-- Right Column: Info Cards -->
    <div>
        <div class="info-grid">
            <!-- Product Information -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Product Information</h3>
                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Product Name</span>
                        <span class="info-value">{{ $product->name }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Description</span>
                        <span class="info-value">{{ $product->description ?: 'No description provided' }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Category</span>
                        <span class="info-value">{{ optional($product->category)->name ?? '—' }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Brand</span>
                        <span class="info-value">{{ optional($product->brand)->name ?? '—' }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            @if($product->is_archived)
                                <span class="status-text">Archived</span>
                            @elseif($product->is_effectively_inactive)
                                <span class="status-text">Inactive</span>
                            @else
                                <span class="status-text">Active</span>
                            @endif
                            @if($product->is_featured)
                                <br><small class="text-muted">(Featured)</small>
                            @endif
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Created</span>
                        <span class="info-value">{{ $product->created_at->format('M d, Y • h:i A') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Last Updated</span>
                        <span class="info-value">{{ $product->updated_at->format('M d, Y • h:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- Stock & Inventory -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-warehouse"></i>
                    <h3>Stock & Inventory</h3>
                </div>
                
                <div class="info-list">
                    @if($product->has_variants)
                        <div class="info-item">
                            <span class="info-label">Total Stock</span>
                            <span class="info-value stock-text">{{ $product->total_stock }} units</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Variant Count</span>
                            <span class="info-value">{{ $variants->count() }} variants</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Stock Status</span>
                            <span class="info-value stock-text">
                                @if($product->total_stock > 50)
                                    In Stock
                                @elseif($product->total_stock > 10)
                                    Low Stock
                                @elseif($product->total_stock > 0)
                                    Very Low
                                @else
                                    Out of Stock
                                @endif
                            </span>
                        </div>
                    @else
                        <div class="info-item">
                            <span class="info-label">Stock Quantity</span>
                            <span class="info-value stock-text">{{ $product->stock_quantity }} units</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Stock Status</span>
                            <span class="info-value stock-text">
                                @if($product->stock_quantity > 50)
                                    In Stock
                                @elseif($product->stock_quantity > 10)
                                    Low Stock
                                @elseif($product->stock_quantity > 0)
                                    Very Low
                                @else
                                    Out of Stock
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Variants -->
@if($product->has_variants)
<div class="variants-card">
    <div class="variants-card-header">
        <h3><i class="fas fa-layer-group"></i> Product Variants</h3>
        <span class="variant-count">{{ $variants->count() }} variants</span>
    </div>
    
    @if($variants->count() > 0)
        <div style="overflow-x: auto;">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Variant</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($variants as $variant)
                    <tr>
                        <td>
                            <div class="variant-name">{{ $variant->variant_name ?? 'Standard' }}</div>
                            <div class="text-xs text-gray-500 mt-1">ID: {{ $variant->id }}</div>
                        </td>
                        <td>
                            <span class="variant-sku">{{ $variant->sku }}</span>
                        </td>
                        <td>
                            <span class="variant-price">₱{{ number_format($variant->current_price, 2) }}</span>
                        </td>
                        <td>
                            <span class="stock-text">{{ $variant->stock_quantity }} units</span>
                        </td>
                        <td>
                            @if($variant->is_active)
                            <span class="status-text">Active</span>
                            @else
                            <span class="text-muted">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}?variant_id={{ $variant->id }}" 
                               class="btn-edit-variant">
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Stock Summary -->
        <div class="stock-summary">
            <div class="stock-summary-item">
                <div class="stock-summary-label">Total Stock</div>
                <div class="stock-summary-value">{{ $product->total_stock }} units</div>
            </div>
            <div class="stock-summary-item">
                <div class="stock-summary-label">Average Price</div>
                <div class="stock-summary-value">₱{{ number_format($variants->avg('current_price'), 2) }}</div>
            </div>
            <div class="stock-summary-item">
                <div class="stock-summary-label">Active Variants</div>
                <div class="stock-summary-value">{{ $variants->where('is_active', true)->count() }}</div>
            </div>
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h4>No Variants Found</h4>
            <p>This product has no variants configured.</p>
        </div>
    @endif
</div>
@endif

<!-- Action Bar -->
<div class="action-bar">
    @if($product->is_archived)
        <form action="{{ route('admin.products.update', $product) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')
            <input type="hidden" name="is_archived" value="0">
            <button type="submit" class="action-btn btn-edit" onclick="return confirm('Unarchive this product?')">
                <i class="fas fa-box-open"></i>
                Unarchive Product
            </button>
        </form>
    @else
        <form action="{{ route('admin.products.update', $product) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')
            <input type="hidden" name="is_archived" value="1">
            <button type="submit" class="action-btn" 
                    style="background: var(--gray-100); color: var(--gray-700); border-color: var(--gray-300);"
                    onclick="return confirm('Archive this product?')">
                <i class="fas fa-archive"></i>
                Archive Product
            </button>
        </form>
    @endif
    
    @if($product->is_featured)
        <form action="{{ route('admin.products.update', $product) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')
            <input type="hidden" name="is_featured" value="0">
            <button type="submit" class="action-btn btn-edit" 
                    onclick="return confirm('Unfeature this product?')">
                <i class="fas fa-star"></i>
                Unfeature
            </button>
        </form>
    @else
        <form action="{{ route('admin.products.update', $product) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')
            <input type="hidden" name="is_featured" value="1">
            <button type="submit" class="action-btn btn-edit" 
                    onclick="return confirm('Feature this product?')">
                <i class="far fa-star"></i>
                Feature Product
            </button>
        </form>
    @endif
    
    <a href="{{ route('admin.products.edit', $product) }}" class="action-btn btn-edit">
        <i class="fas fa-edit"></i>
        Edit Product
    </a>
</div>

@endsection