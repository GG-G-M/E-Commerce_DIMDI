@extends('layouts.admin')

@section('content')
<style>
    /* Modern Design System */
    :root {
        --primary-green: #2C8F0C;
        --secondary-green: #4CAF50;
        --light-green: #E8F5E9;
        --dark-green: #1B5E20;
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

    /* Modern Header */
    .modern-header {
        background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .modern-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle at top right, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .modern-header h1 {
        font-weight: 800;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .modern-header .subtitle {
        opacity: 0.9;
        font-size: 1rem;
        position: relative;
        z-index: 1;
    }

    .header-actions {
        position: relative;
        z-index: 2;
        margin-top: 1.5rem;
    }

    /* Modern Card Design */
    .modern-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .modern-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .modern-card-header {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modern-card-header i {
        font-size: 1.25rem;
    }

    .modern-card-body {
        padding: 2rem;
    }

    /* Product Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .info-section {
        background: var(--gray-50);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        border: 1px solid var(--gray-200);
    }

    .info-section h6 {
        color: var(--gray-700);
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--light-green);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-200);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: var(--gray-600);
        font-weight: 500;
    }

    .info-value {
        color: var(--gray-800);
        font-weight: 600;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
    }

    .status-active {
        background: linear-gradient(135deg, #DCFCE7, #BBF7D0);
        color: #166534;
    }

    .status-inactive {
        background: linear-gradient(135deg, #FEE2E2, #FECACA);
        color: #991B1B;
    }

    .status-archived {
        background: linear-gradient(135deg, #F3F4F6, #E5E7EB);
        color: #4B5563;
    }

    .status-featured {
        background: linear-gradient(135deg, #FEF3C7, #FDE68A);
        color: #92400E;
    }

    /* Price Display */
    .price-display {
        background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        border: 2px solid var(--primary-green);
        text-align: center;
    }

    .current-price {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-green);
        line-height: 1;
    }

    .original-price {
        font-size: 1.25rem;
        color: var(--gray-500);
        text-decoration: line-through;
        margin-top: 0.5rem;
    }

    .discount-badge {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 0.5rem;
    }

    /* Image Container */
    .image-container {
        background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        border: 2px dashed var(--gray-300);
        position: relative;
        overflow: hidden;
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

    .sku-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Variants Table */
    .variants-table-container {
        background: var(--gray-50);
        border-radius: var(--radius-md);
        overflow: hidden;
        border: 1px solid var(--gray-200);
    }

    .variants-table {
        width: 100%;
        border-collapse: collapse;
    }

    .variants-table thead {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
    }

    .variants-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .variants-table tbody tr {
        border-bottom: 1px solid var(--gray-200);
        transition: all 0.2s ease;
    }

    .variants-table tbody tr:hover {
        background: var(--light-green);
    }

    .variants-table td {
        padding: 1rem;
        color: var(--gray-700);
    }

    .variant-sku {
        font-family: 'SF Mono', Monaco, monospace;
        color: var(--gray-600);
        font-size: 0.875rem;
    }

    .stock-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .stock-high {
        background: #DCFCE7;
        color: #166534;
    }

    .stock-medium {
        background: #FEF3C7;
        color: #92400E;
    }

    .stock-low {
        background: #FEE2E2;
        color: #991B1B;
    }

    .stock-out {
        background: #F3F4F6;
        color: #6B7280;
    }

    /* Action Buttons */
    .action-btn {
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
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
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        border: 2px solid transparent;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
        transform: translateY(-1px);
        box-shadow: var(--shadow);
        color: white;
        text-decoration: none;
    }

    .btn-secondary {
        background: white;
        color: var(--gray-700);
        border: 2px solid var(--gray-300);
    }

    .btn-secondary:hover {
        background: var(--gray-100);
        color: var(--gray-800);
        border-color: var(--gray-400);
        transform: translateY(-1px);
        box-shadow: var(--shadow);
        text-decoration: none;
    }

    .btn-edit-variant {
        padding: 0.5rem 1rem;
        background: rgba(44, 143, 12, 0.1);
        color: var(--primary-green);
        border: 1px solid rgba(44, 143, 12, 0.2);
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.2s ease;
    }

    .btn-edit-variant:hover {
        background: rgba(44, 143, 12, 0.2);
        color: var(--dark-green);
        transform: translateY(-1px);
        text-decoration: none;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        border: 1px solid var(--gray-200);
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-green);
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: var(--primary-green);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--gray-800);
        line-height: 1;
    }

    .stat-label {
        color: var(--gray-600);
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--gray-500);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modern-header {
            padding: 1.5rem;
        }

        .modern-header h1 {
            font-size: 1.5rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .variants-table {
            display: block;
            overflow-x: auto;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Modern Header -->
<div class="modern-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap">
        <div class="flex-grow-1">
            <h1>{{ $product->name }}</h1>
            <p class="subtitle">Product Details & Management</p>
            
            <!-- Product Status Badges -->
            <div class="d-flex flex-wrap gap-2 mt-3">
                <span class="status-badge status-{{ $product->is_archived ? 'archived' : ($product->is_effectively_inactive ? 'inactive' : 'active') }}">
                    <i class="fas fa-circle fa-xs"></i>
                    {{ $product->is_archived ? 'Archived' : ($product->is_effectively_inactive ? 'Inactive' : 'Active') }}
                </span>
                @if($product->is_featured)
                <span class="status-badge status-featured">
                    <i class="fas fa-star fa-xs"></i>
                    Featured
                </span>
                @endif
                @if($product->has_variants)
                <span class="status-badge" style="background: linear-gradient(135deg, #E0F2FE, #BAE6FD); color: #0369A1;">
                    <i class="fas fa-layer-group fa-xs"></i>
                    Has Variants
                </span>
                @endif
                @if($product->has_discount)
                <span class="status-badge" style="background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #92400E;">
                    <i class="fas fa-tag fa-xs"></i>
                    On Sale
                </span>
                @endif
            </div>
        </div>
        
        <div class="header-actions d-flex gap-2">
            <a href="{{ route('admin.products.index') }}" class="action-btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
            <a href="{{ route('admin.products.edit', $product) }}" class="action-btn btn-primary">
                <i class="fas fa-edit"></i>
                Edit Product
            </a>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="info-grid">
    <!-- Product Image & Price -->
    <div class="image-container">
        <span class="sku-badge">SKU: {{ $product->sku }}</span>
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
        
        <div class="price-display mt-4">
            @if($product->has_discount)
                <div class="discount-badge">{{ $product->discount_percentage }}% OFF</div>
                <div class="current-price">₱{{ number_format($product->sale_price, 2) }}</div>
                <div class="original-price">₱{{ number_format($product->price, 2) }}</div>
            @else
                <div class="current-price">₱{{ number_format($product->price, 2) }}</div>
            @endif
        </div>
    </div>

    <!-- Product Information -->
    <div class="info-section">
        <h6><i class="fas fa-info-circle"></i> Product Information</h6>
        
        <div class="info-item">
            <span class="info-label">Name</span>
            <span class="info-value">{{ $product->name }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Description</span>
            <span class="info-value">{{ $product->description }}</span>
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
            <span class="info-label">Created</span>
            <span class="info-value">{{ $product->created_at->format('M d, Y') }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Last Updated</span>
            <span class="info-value">{{ $product->updated_at->format('M d, Y') }}</span>
        </div>
    </div>

    <!-- Stock & Inventory -->
    <div class="info-section">
        <h6><i class="fas fa-warehouse"></i> Stock & Inventory</h6>
        
        @if($product->has_variants)
            <div class="info-item">
                <span class="info-label">Total Stock</span>
                <span class="info-value">{{ $product->total_stock }} units</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Variant Count</span>
                <span class="info-value">{{ $variants->count() }} variants</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Stock Status</span>
                <span class="info-value">
                    @if($product->total_stock > 50)
                        <span class="stock-indicator stock-high">
                            <i class="fas fa-check-circle"></i>
                            In Stock
                        </span>
                    @elseif($product->total_stock > 10)
                        <span class="stock-indicator stock-medium">
                            <i class="fas fa-exclamation-circle"></i>
                            Low Stock
                        </span>
                    @elseif($product->total_stock > 0)
                        <span class="stock-indicator stock-low">
                            <i class="fas fa-exclamation-triangle"></i>
                            Very Low
                        </span>
                    @else
                        <span class="stock-indicator stock-out">
                            <i class="fas fa-times-circle"></i>
                            Out of Stock
                        </span>
                    @endif
                </span>
            </div>
        @else
            <div class="info-item">
                <span class="info-label">Stock Quantity</span>
                <span class="info-value">{{ $product->stock_quantity }} units</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Stock Status</span>
                <span class="info-value">
                    @if($product->stock_quantity > 50)
                        <span class="stock-indicator stock-high">
                            <i class="fas fa-check-circle"></i>
                            In Stock
                        </span>
                    @elseif($product->stock_quantity > 10)
                        <span class="stock-indicator stock-medium">
                            <i class="fas fa-exclamation-circle"></i>
                            Low Stock
                        </span>
                    @elseif($product->stock_quantity > 0)
                        <span class="stock-indicator stock-low">
                            <i class="fas fa-exclamation-triangle"></i>
                            Very Low
                        </span>
                    @else
                        <span class="stock-indicator stock-out">
                            <i class="fas fa-times-circle"></i>
                            Out of Stock
                        </span>
                    @endif
                </span>
            </div>
        @endif
    </div>
</div>

<!-- Product Variants Section -->
<div class="modern-card mb-4">
    <div class="modern-card-header">
        <i class="fas fa-layer-group"></i>
        Product Variants
        @if($product->has_variants)
        <span class="badge bg-light text-dark ms-2">{{ $variants->count() }} variants</span>
        @endif
    </div>
    
    <div class="modern-card-body">
        @if($product->has_variants && $variants->count() > 0)
            <div class="variants-table-container">
                <table class="variants-table">
                    <thead>
                        <tr>
                            <th>Variant Name</th>
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
                                <strong>{{ $variant->variant_name ?? 'Standard' }}</strong>
                                <div class="small text-muted mt-1">ID: {{ $variant->id }}</div>
                            </td>
                            <td>
                                <span class="variant-sku">{{ $variant->sku }}</span>
                            </td>
                            <td>
                                <strong>₱{{ number_format($variant->current_price, 2) }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span>{{ $variant->stock_quantity }} units</span>
                                    @if($variant->stock_quantity > 50)
                                        <span class="stock-indicator stock-high">High</span>
                                    @elseif($variant->stock_quantity > 10)
                                        <span class="stock-indicator stock-medium">Medium</span>
                                    @elseif($variant->stock_quantity > 0)
                                        <span class="stock-indicator stock-low">Low</span>
                                    @else
                                        <span class="stock-indicator stock-out">Out</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($variant->is_active)
                                <span class="status-badge status-active">Active</span>
                                @else
                                <span class="status-badge status-inactive">Inactive</span>
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
            <div class="d-flex justify-content-between align-items-center mt-3 p-3" 
                 style="background: linear-gradient(135deg, #F0F9FF, #E0F2FE); border-radius: var(--radius-md);">
                <div>
                    <strong class="text-primary">Total Stock Across All Variants:</strong>
                    <div class="h4 mb-0 text-primary">{{ $product->total_stock }} units</div>
                </div>
                <div class="text-end">
                    <div class="small text-muted">Average Price</div>
                    <div class="h5 mb-0">₱{{ number_format($variants->avg('current_price'), 2) }}</div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h5 class="mt-3 mb-2">No Variants Found</h5>
                <p class="text-muted">This product has no variants. Click "Edit Product" to add variants.</p>
            </div>
        @endif
    </div>
</div>

<!-- Quick Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-value">₱{{ number_format($product->price, 2) }}</div>
        <div class="stat-label">Base Price</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-value">{{ $product->has_variants ? $product->total_stock : $product->stock_quantity }}</div>
        <div class="stat-label">Total Stock</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-tag"></i>
        </div>
        <div class="stat-value">{{ $product->has_discount ? $product->discount_percentage . '%' : '—' }}</div>
        <div class="stat-label">Discount</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-layer-group"></i>
        </div>
        <div class="stat-value">{{ $variants->count() }}</div>
        <div class="stat-label">Variants</div>
    </div>
</div>

<!-- Bottom Actions -->
<div class="d-flex justify-content-between align-items-center mt-4 p-4" 
     style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200);">
    <div>
        <h6 class="mb-1">Product Actions</h6>
        <p class="text-muted mb-0">Manage this product</p>
    </div>
    <div class="d-flex gap-2">
        <!-- Archive/Unarchive Form -->
        @if($product->is_archived)
            <form action="{{ route('admin.products.update', $product) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="is_archived" value="0">
                <button type="submit" class="action-btn btn-secondary" onclick="return confirm('Unarchive this product?')">
                    <i class="fas fa-box-open"></i>
                    Unarchive
                </button>
            </form>
        @else
            <form action="{{ route('admin.products.update', $product) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="is_archived" value="1">
                <button type="submit" class="action-btn btn-secondary" onclick="return confirm('Archive this product?')">
                    <i class="fas fa-archive"></i>
                    Archive
                </button>
            </form>
        @endif
        
        <!-- Feature/Unfeature Form -->
        @if($product->is_featured)
            <form action="{{ route('admin.products.update', $product) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="is_featured" value="0">
                <button type="submit" class="action-btn btn-secondary" onclick="return confirm('Unfeature this product?')">
                    <i class="fas fa-star"></i>
                    Unfeature
                </button>
            </form>
        @else
            <form action="{{ route('admin.products.update', $product) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="is_featured" value="1">
                <button type="submit" class="action-btn btn-secondary" onclick="return confirm('Feature this product?')">
                    <i class="far fa-star"></i>
                    Feature
                </button>
            </form>
        @endif
        
        <a href="{{ route('admin.products.edit', $product) }}" class="action-btn btn-primary">
            <i class="fas fa-edit"></i>
            Edit Product
        </a>
    </div>
</div>

@endsection