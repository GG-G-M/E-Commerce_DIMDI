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

    /* Form Design */
    .modern-form-card {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .modern-form-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .form-header {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .form-body {
        padding: 2rem;
    }

    /* Form Controls */
    .form-label {
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid var(--gray-300);
        border-radius: var(--radius-sm);
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(44, 143, 12, 0.1);
        outline: none;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Toggle Switch */
    .form-check-input:checked {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }

    .form-check-input:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 0.25rem rgba(44, 143, 12, 0.25);
    }

    /* Variants Section */
    .variants-container {
        background: var(--gray-50);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        border: 2px solid var(--gray-200);
        margin-top: 1rem;
    }

    .variant-card {
        background: white;
        border: 2px solid var(--light-green);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .variant-card:hover {
        border-color: var(--primary-green);
        box-shadow: var(--shadow);
        transform: translateY(-2px);
    }

    .variant-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--light-green);
    }

    .variant-number {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.875rem;
    }

    .btn-remove-variant {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        color: white;
        border: none;
        border-radius: var(--radius-sm);
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-remove-variant:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow);
    }

    .btn-add-variant {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        border: none;
        border-radius: var(--radius-sm);
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-add-variant:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    /* Image Preview */
    .image-preview-container {
        background: var(--gray-50);
        border: 2px dashed var(--gray-300);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        text-align: center;
        margin-top: 0.5rem;
    }

    .variant-image-preview {
        max-height: 120px;
        max-width: 100%;
        border-radius: var(--radius-sm);
        object-fit: contain;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--gray-500);
        background: var(--gray-50);
        border-radius: var(--radius-md);
        border: 2px dashed var(--gray-300);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Stock Summary */
    .stock-summary {
        background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-top: 1.5rem;
        border: 2px solid var(--primary-green);
    }

    .stock-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .stock-label {
        color: var(--dark-green);
        font-weight: 600;
    }

    .stock-value {
        background: white;
        padding: 0.25rem 1rem;
        border-radius: 2rem;
        font-weight: 700;
        color: var(--dark-green);
        border: 2px solid var(--primary-green);
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .modern-header {
            padding: 1.5rem;
        }

        .modern-header h1 {
            font-size: 1.5rem;
        }

        .form-body {
            padding: 1.5rem;
        }

        .variant-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }

    @media (max-width: 480px) {
        .modern-header {
            padding: 1rem;
        }

        .form-body {
            padding: 1rem;
        }
    }
</style>

<!-- Modern Header -->
<div class="modern-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap">
        <div class="flex-grow-1">
            <h1>Edit Product: {{ $product->name }}</h1>
            <p class="subtitle">Update product details and manage variants</p>
        </div>
        
        <div class="header-actions d-flex gap-2">
            <a href="{{ route('admin.products.index') }}" class="action-btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>
    </div>
</div>

<!-- Product Edit Form -->
<div class="modern-form-card">
    <div class="form-header">
        <i class="fas fa-edit"></i>
        Edit Product Details
    </div>
    
    <div class="form-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Left Column - Main Information -->
                <div class="col-lg-8">
                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: var(--primary-green);">
                            <i class="fas fa-info-circle me-2"></i>Basic Information
                        </h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-tag"></i>Product Name *
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i>Description *
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: var(--primary-green);">
                            <i class="fas fa-money-bill-wave me-2"></i>Pricing
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">
                                    <i class="fas fa-tag"></i>Base Price *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="sale_price" class="form-label">
                                    <i class="fas fa-percentage"></i>Sale Price
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror"
                                           id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}"
                                           placeholder="Optional">
                                </div>
                                @error('sale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty if not on sale</small>
                            </div>
                        </div>
                    </div>

                    <!-- Categories & Brand -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: var(--primary-green);">
                            <i class="fas fa-layer-group me-2"></i>Categories & Brand
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">
                                    <i class="fas fa-folder"></i>Category *
                                </label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="brand_id" class="form-label">
                                    <i class="fas fa-copyright"></i>Brand *
                                </label>
                                <select class="form-select @error('brand_id') is-invalid @enderror"
                                        id="brand_id" name="brand_id" required>
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Stock Management -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: var(--primary-green);">
                            <i class="fas fa-boxes me-2"></i>Stock Management
                        </h5>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">
                                        <i class="fas fa-cube"></i>Base Stock Quantity *
                                    </label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                           id="stock_quantity" name="stock_quantity" 
                                           value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                                           {{ $product->has_variants ? 'readonly' : 'required' }}
                                           {{ $product->has_variants ? 'disabled' : '' }}>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Used when variants are disabled</small>
                                </div>
                            </div>
                        </div>

                        <!-- Variants Toggle -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="has_variants" name="has_variants" value="1" 
                                       {{ old('has_variants', $product->has_variants) ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_variants">
                                    <strong>Enable Product Variants</strong>
                                </label>
                                <small class="form-text text-muted d-block">
                                    Enable this to create different models/versions of this product
                                </small>
                            </div>
                        </div>

                        <!-- Variants Section -->
                        <div id="variantsSection" style="display: {{ $product->has_variants ? 'block' : 'none' }};">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label mb-0">
                                    <i class="fas fa-layer-group me-2"></i>Product Variants
                                </label>
                                <button type="button" id="addVariant" class="btn-add-variant">
                                    <i class="fas fa-plus"></i> Add Variant
                                </button>
                            </div>

                            <div class="variants-container">
                                <div id="variantsContainer">
                                    @if($product->has_variants && $variants->count() > 0)
                                        @foreach($variants as $index => $variant)
                                        <div class="variant-card" data-index="{{ $index }}">
                                            <div class="variant-header">
                                                <span class="variant-number">Variant #{{ $index + 1 }}</span>
                                                <button type="button" class="btn-remove-variant" onclick="removeVariant(this)">
                                                    <i class="fas fa-times"></i> Remove
                                                </button>
                                            </div>
                                            
                                            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                            
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Variant Name *</label>
                                                    <input type="text" class="form-control" 
                                                           name="variants[{{ $index }}][variant_name]" 
                                                           value="{{ old('variants.'.$index.'.variant_name', $variant->variant_name) }}" 
                                                           placeholder="e.g., Pro Model, Standard Edition" required>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label class="form-label">Stock Quantity *</label>
                                                    <input type="number" class="form-control stock-input" 
                                                           name="variants[{{ $index }}][stock]" 
                                                           value="{{ old('variants.'.$index.'.stock', $variant->stock_quantity) }}" 
                                                           min="0" required>
                                                </div>
                                                
                                                <div class="col-12">
                                                    <label class="form-label">Variant Description</label>
                                                    <textarea class="form-control" 
                                                              name="variants[{{ $index }}][variant_description]" 
                                                              rows="2" 
                                                              placeholder="Optional description for this variant">{{ old('variants.'.$index.'.variant_description', $variant->variant_description) }}</textarea>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label class="form-label">Price *</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₱</span>
                                                        <input type="number" step="0.01" class="form-control" 
                                                               name="variants[{{ $index }}][price]" 
                                                               value="{{ old('variants.'.$index.'.price', $variant->price) }}" 
                                                               min="0" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label class="form-label">Sale Price</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₱</span>
                                                        <input type="number" step="0.01" class="form-control" 
                                                               name="variants[{{ $index }}][sale_price]" 
                                                               value="{{ old('variants.'.$index.'.sale_price', $variant->sale_price) }}" 
                                                               min="0" placeholder="Optional">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label class="form-label">Variant Image</label>
                                                    <input type="file" class="form-control variant-image-input" 
                                                           name="variants[{{ $index }}][image]" 
                                                           accept="image/*">
                                                    
                                                    @if($variant->image)
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="variants[{{ $index }}][remove_image]" value="1">
                                                        <label class="form-check-label text-danger">
                                                            <i class="fas fa-trash me-1"></i>Remove current image
                                                        </label>
                                                    </div>
                                                    <div class="image-preview-container mt-2">
                                                        <img class="variant-image-preview" src="{{ $variant->image_url }}" alt="Current image">
                                                        <div class="mt-2">
                                                            <small class="text-muted">Current variant image</small>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <div class="image-preview-container mt-2" style="display: none;">
                                                        <img class="variant-image-preview" src="#" alt="Preview">
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="empty-state" id="noVariantsMessage">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <h5>No Variants Added</h5>
                                            <p class="mb-0">Click "Add Variant" to create different models of this product.</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Total Stock Summary -->
                                <div class="stock-summary">
                                    <div class="stock-stat">
                                        <span class="stock-label">Total Variants:</span>
                                        <span class="stock-value" id="totalVariants">{{ $product->has_variants ? $variants->count() : 0 }}</span>
                                    </div>
                                    <div class="stock-stat">
                                        <span class="stock-label">Total Stock:</span>
                                        <span class="stock-value" id="totalStock">{{ $product->total_stock }} units</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="col-lg-4">
                    <!-- Product Image -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: var(--primary-green);">
                            <i class="fas fa-image me-2"></i>Product Image
                        </h5>
                        
                        <div class="text-center mb-3">
                            <img src="{{ $product->image_url }}" alt="Current image" class="img-fluid rounded" style="max-height: 200px; object-fit: contain; border: 2px solid var(--gray-300);">
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Change Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="imagePreview" src="#" alt="Image preview" class="img-fluid rounded d-none" style="max-height: 150px;">
                            </div>
                            <small class="text-muted">Leave empty to keep current image</small>
                        </div>
                    </div>

                    <!-- Product Status -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: var(--primary-green);">
                            <i class="fas fa-cog me-2"></i>Product Status
                        </h5>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" 
                                       id="is_featured" name="is_featured" value="1" 
                                       {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    <strong>Featured Product</strong>
                                </label>
                                <small class="form-text text-muted d-block">
                                    Show this product in featured sections
                                </small>
                            </div>
                            
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Active</strong>
                                </label>
                                <small class="form-text text-muted d-block">
                                    Make product visible to customers
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- SKU Information -->
                    <div class="mb-4">
                        <h5 class="mb-3" style="color: var(--primary-green);">
                            <i class="fas fa-barcode me-2"></i>Product ID
                        </h5>
                        
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <strong>SKU:</strong> {{ $product->sku }}<br>
                                    <small>Product ID: {{ $product->id }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
                <a href="{{ route('admin.products.index') }}" class="action-btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="action-btn btn-primary">
                    <i class="fas fa-save"></i> Update Product
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let variantCount = {{ $product->has_variants ? $variants->count() : 0 }};

    // Toggle variants section
    document.getElementById('has_variants').addEventListener('change', function() {
        const variantsSection = document.getElementById('variantsSection');
        const stockQuantityInput = document.getElementById('stock_quantity');
        
        variantsSection.style.display = this.checked ? 'block' : 'none';
        stockQuantityInput.disabled = this.checked;
        stockQuantityInput.readonly = this.checked;
        stockQuantityInput.required = !this.checked;
        
        if (this.checked) {
            stockQuantityInput.value = 0;
        }
        
        updateSummary();
    });

    // Add variant
    document.getElementById('addVariant').addEventListener('click', function() {
        const container = document.getElementById('variantsContainer');
        const noVariantsMessage = document.getElementById('noVariantsMessage');
        
        if (noVariantsMessage) {
            noVariantsMessage.remove();
        }

        const variantHtml = `
            <div class="variant-card" data-index="${variantCount}">
                <div class="variant-header">
                    <span class="variant-number">Variant #${variantCount + 1}</span>
                    <button type="button" class="btn-remove-variant" onclick="removeVariant(this)">
                        <i class="fas fa-times"></i> Remove
                    </button>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Variant Name *</label>
                        <input type="text" class="form-control" 
                               name="variants[${variantCount}][variant_name]" 
                               placeholder="e.g., Pro Model, Standard Edition" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Stock Quantity *</label>
                        <input type="number" class="form-control stock-input" 
                               name="variants[${variantCount}][stock]" 
                               value="0" min="0" required>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Variant Description</label>
                        <textarea class="form-control" 
                                  name="variants[${variantCount}][variant_description]" 
                                  rows="2" 
                                  placeholder="Optional description for this variant"></textarea>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Price *</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" class="form-control" 
                                   name="variants[${variantCount}][price]" 
                                   value="{{ old('price', $product->price) }}" 
                                   min="0" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Sale Price</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" class="form-control" 
                                   name="variants[${variantCount}][sale_price]" 
                                   min="0" placeholder="Optional">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Variant Image</label>
                        <input type="file" class="form-control variant-image-input" 
                               name="variants[${variantCount}][image]" 
                               accept="image/*">
                        <div class="image-preview-container mt-2" style="display: none;">
                            <img class="variant-image-preview" src="#" alt="Preview">
                        </div>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', variantHtml);
        variantCount++;
        updateSummary();
        setupVariantImagePreview();
    });

    // Remove variant
    function removeVariant(button) {
        if (confirm('Are you sure you want to remove this variant?')) {
            const variantCard = button.closest('.variant-card');
            variantCard.remove();
            updateSummary();
            
            // Show no variants message if all are removed
            const container = document.getElementById('variantsContainer');
            if (container.children.length === 0) {
                container.innerHTML = `
                    <div class="empty-state" id="noVariantsMessage">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h5>No Variants Added</h5>
                        <p class="mb-0">Click "Add Variant" to create different models of this product.</p>
                    </div>
                `;
            }
        }
    }

    // Update summary
    function updateSummary() {
        const stockInputs = document.querySelectorAll('.stock-input');
        let totalStock = 0;
        let totalVariants = document.querySelectorAll('.variant-card').length;
        
        stockInputs.forEach(input => {
            const value = parseInt(input.value) || 0;
            totalStock += value;
        });

        document.getElementById('totalVariants').textContent = totalVariants;
        document.getElementById('totalStock').textContent = totalStock + ' units';
    }

    // Image preview for main image
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const file = e.target.files[0];
        
        if (file) {
            if (file.size > 5 * 1024 * 1024) { // 5MB limit
                alert('Image size must be less than 5MB');
                this.value = '';
                preview.classList.add('d-none');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('d-none');
        }
    });

    // Setup variant image preview listeners
    function setupVariantImagePreview() {
        document.querySelectorAll('.variant-image-input').forEach(input => {
            input.removeEventListener('change', handleVariantImageChange);
            input.addEventListener('change', handleVariantImageChange);
        });
    }

    function handleVariantImageChange(e) {
        const file = e.target.files[0];
        const previewContainer = e.target.nextElementSibling;
        const preview = previewContainer.querySelector('.variant-image-preview');
        
        if (file) {
            if (file.size > 5 * 1024 * 1024) { // 5MB limit
                alert('Image size must be less than 5MB');
                e.target.value = '';
                previewContainer.style.display = 'none';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }

    // Update stock inputs in real-time
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('stock-input')) {
            updateSummary();
        }
    });

    // Form validation
    document.getElementById('productForm').addEventListener('submit', function(e) {
        const hasVariants = document.getElementById('has_variants').checked;
        
        if (hasVariants) {
            const variants = document.querySelectorAll('.variant-card');
            if (variants.length === 0) {
                e.preventDefault();
                alert('Please add at least one variant when variants are enabled.');
                return false;
            }
        }
        
        // Validate prices
        const price = parseFloat(document.getElementById('price').value);
        const salePrice = parseFloat(document.getElementById('sale_price').value) || 0;
        
        if (salePrice > 0 && salePrice >= price) {
            e.preventDefault();
            alert('Sale price must be less than the base price.');
            return false;
        }
    });

    // Initialize variant image previews
    document.addEventListener('DOMContentLoaded', function() {
        setupVariantImagePreview();
        updateSummary();
    });
</script>
@endpush