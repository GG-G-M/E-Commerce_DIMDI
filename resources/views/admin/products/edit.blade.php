@extends('layouts.admin')

@section('content')
<style>
    /* Clean Minimal Design */
    .page-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #E8F5E6;
    }
    
    .page-header h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #2C8F0C;
        margin-bottom: 0.5rem;
    }
    
    .page-header p {
        color: #6c757d;
        font-size: 0.95rem;
    }
    
    .form-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .form-section {
        padding: 2rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .form-section:last-child {
        border-bottom: none;
    }
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2C8F0C;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #E8F5E6;
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .form-control, .form-select {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.75rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.15);
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
    }
    
    /* Variants Section */
    .variants-toggle {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .variants-container {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1rem;
        border: 1px solid #dee2e6;
    }
    
    .variant-item {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1rem;
    }
    
    .variant-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .variant-title {
        font-weight: 600;
        color: #495057;
    }
    
    .btn-remove-variant {
        background: none;
        border: none;
        color: #dc3545;
        font-size: 0.85rem;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    
    .btn-remove-variant:hover {
        background-color: #f8d7da;
    }
    
    .btn-add-variant {
        background: #2C8F0C;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    
    .btn-add-variant:hover {
        background: #25750A;
    }
    
    /* Stock Summary */
    .stock-summary {
        display: flex;
        gap: 2rem;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #dee2e6;
    }
    
    .stock-item {
        text-align: center;
    }
    
    .stock-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    
    .stock-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2C8F0C;
    }
    
    /* Image Preview */
    .image-preview {
        max-width: 200px;
        max-height: 200px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .image-upload {
        margin-bottom: 1rem;
    }
    
    /* Product Status */
    .status-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .status-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .status-item:last-child {
        margin-bottom: 0;
    }
    
    /* Form Actions */
    .form-actions {
        padding: 1.5rem 2rem;
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }
    
    .btn-secondary {
        background: white;
        color: #495057;
        border: 1px solid #dee2e6;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-secondary:hover {
        background: #f8f9fa;
        color: #212529;
        text-decoration: none;
    }
    
    .btn-primary {
        background: #2C8F0C;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-primary:hover {
        background: #25750A;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 2rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .form-section {
            padding: 1.5rem;
        }
        
        .stock-summary {
            flex-direction: column;
            gap: 1rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-secondary, .btn-primary {
            width: 100%;
            text-align: center;
        }
    }

    /* === Green Gradient Buttons (matching Add Product button from index) === */
    .btn-custom-green {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(44, 143, 12, 0.2);
        height: 46px;
        text-decoration: none;
    }
    
    .btn-custom-green:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
    }

    .btn-custom-green:active {
        transform: translateY(0);
    }

    /* Secondary green button for cancel */
    .btn-custom-green-secondary {
        background: #f8f9fa;
        border: 2px solid #dee2e6;
        color: #6c757d;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        height: 46px;
        text-decoration: none;
    }
    
    .btn-custom-green-secondary:hover {
        background: #e9ecef;
        border-color: #adb5bd;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        color: #495057;
    }
</style>

<!-- Header -->
<<<<<<< HEAD
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h1>Edit Product</h1>
            <p>Update product information and manage variants</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn-secondary">
            Back to Products
        </a>
=======
{{-- <div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Edit Product</h1>
        <p class="text-muted mb-0">Update product details and manage variants.</p>
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
    </div>
</div> --}}

<!-- Product Edit Form -->
<div class="form-container">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="form-section">
            <div class="section-title">Basic Information</div>
            
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Product Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           name="name" value="{{ old('name', $product->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">SKU</label>
                    <div class="form-control-plaintext">
                        <code>{{ $product->sku }}</code>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description *</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <!-- Pricing -->
        <div class="form-section">
            <div class="section-title">Pricing</div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Price *</label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                               name="price" value="{{ old('price', $product->price) }}" required>
                    </div>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sale Price</label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror"
                               name="sale_price" value="{{ old('sale_price', $product->sale_price )}}"
                               placeholder="Leave empty if not on sale">
                    </div>
                    @error('sale_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Categories -->
        <div class="form-section">
            <div class="section-title">Categories & Brand</div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Category *</label>
                    <select class="form-select @error('category_id') is-invalid @enderror"
                            name="category_id" required>
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
                    <label class="form-label">Brand *</label>
                    <select class="form-select @error('brand_id') is-invalid @enderror"
                            name="brand_id" required>
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
        <div class="form-section">
            <div class="section-title">Stock Management</div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Base Stock Quantity</label>
                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                           name="stock_quantity" 
                           value="{{ old('stock_quantity', $product->stock_quantity) }}"
                           {{ $product->has_variants ? 'readonly disabled' : '' }}>
                    @error('stock_quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Used when variants are disabled</small>
                </div>
            </div>
            
            <!-- Variants Toggle -->
            <div class="mb-4">
                <div class="variants-toggle">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" 
                               id="has_variants" name="has_variants" value="1" 
                               {{ old('has_variants', $product->has_variants) ? 'checked' : '' }}>
                        <label class="form-check-label" for="has_variants">
                            Enable Product Variants
                        </label>
                    </div>
<<<<<<< HEAD
                    <small class="text-muted">Create different models/versions of this product</small>
                </div>
            </div>
            
            <!-- Variants Section -->
            <div id="variantsSection" style="display: {{ $product->has_variants ? 'block' : 'none' }};">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="form-label mb-0">Product Variants</label>
                    <button type="button" id="addVariant" class="btn-add-variant">
                        + Add Variant
                    </button>
                </div>
                
                <div class="variants-container">
                    <div id="variantsContainer">
                        @if($product->has_variants && $variants->count() > 0)
                            @foreach($variants as $index => $variant)
                            <div class="variant-item" data-index="{{ $index }}">
                                <div class="variant-header">
                                    <div class="variant-title">Variant #{{ $index + 1 }}</div>
                                    <button type="button" class="btn-remove-variant" onclick="removeVariant(this)">
                                        Remove
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
=======

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock_quantity" class="form-label">Base Stock Quantity *</label>
                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                       id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                                       {{ $product->has_variants ? 'disabled' : 'required' }} readonly @disabled(true) @readonly(true)>
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
                            <input class="form-check-input" type="checkbox" id="has_variants" name="has_variants" value="1" 
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
                            <label class="form-label mb-0">Product Variants</label>
                            <button type="button" id="addVariant" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-1"></i> Add Variant
                            </button>
                        </div>

                        <div id="variantsContainer">
                            @if($product->has_variants && $variants->count() > 0)
                                @foreach($variants as $index => $variant)
                                <div class="variant-card" data-index="{{ $index }}">
                                    <div class="variant-header">
                                        <span class="variant-number">Variant #{{ $index + 1 }}</span>
                                        <button type="button" class="remove-variant" onclick="removeVariant(this)">
                                            <i class="fas fa-times me-1"></i> Remove
                                        </button>
                                    </div>
                                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Variant Name *</label>
                                                <input type="text" class="form-control" 
                                                       name="variants[{{ $index }}][variant_name]" 
                                                       value="{{ old('variants.'.$index.'.variant_name', $variant->variant_name) }}" 
                                                       placeholder="e.g., Pro Model, Standard Edition" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Stock Quantity *</label>
                                                <input type="number" class="form-control stock-input" 
                                                       name="variants[{{ $index }}][stock]" 
                                                       value="{{ old('variants.'.$index.'.stock', $variant->stock_quantity) }}" 
                                                       min="0" readonly>
                                            </div>
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
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
                                        <input type="file" class="form-control" 
                                               name="variants[{{ $index }}][image]" 
                                               accept="image/*">
                                        
                                        @if($variant->image)
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="variants[{{ $index }}][remove_image]" value="1">
                                            <label class="form-check-label" style="font-size: 0.85rem;">
                                                Remove current image
                                            </label>
                                        </div>
                                        <div class="mt-2">
                                            <img src="{{ $variant->image_url }}" alt="Current image" 
                                                 style="max-height: 80px; object-fit: contain; border: 1px solid #dee2e6; border-radius: 4px;">
                                        </div>
                                        @endif
                                    </div>
                                </div>
<<<<<<< HEAD
=======
                                @endforeach
                            @else
                                <div class="no-variants-message" id="noVariantsMessage">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <h5>No Variants Added</h5>
                                    <p class="mb-0">Click "Add Variant" to create different models of this product.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Total Stock Summary -->
                        <div class="total-stock-summary">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Total Variants:</strong> 
                                    <span id="totalVariants">{{ $product->has_variants ? $variants->count() : 0 }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Total Stock:</strong> 
                                    <span id="totalStock">{{ $product->total_stock }} units</span>
                                </div>
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
                            </div>
                            @endforeach
                        @else
                            <div class="empty-state" id="noVariantsMessage">
                                <i class="fas fa-info-circle"></i>
                                <p>No variants added yet</p>
                                <small>Click "Add Variant" to create different models</small>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Stock Summary -->
                    <div class="stock-summary">
                        <div class="stock-item">
                            <div class="stock-label">Total Variants</div>
                            <div class="stock-value" id="totalVariants">{{ $product->has_variants ? $variants->count() : 0 }}</div>
                        </div>
                        <div class="stock-item">
                            <div class="stock-label">Total Stock</div>
                            <div class="stock-value" id="totalStock">{{ $product->total_stock }} units</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Image -->
        <div class="form-section">
            <div class="section-title">Product Image</div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        @if($product->image)
                        <img src="{{ $product->image_url }}" alt="Current image" 
                             class="image-preview">
                        @endif
                        
                        <label class="form-label">Change Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Leave empty to keep current image</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Status -->
        <div class="form-section">
            <div class="section-title">Product Status</div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="status-section">
                        <div class="status-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       id="is_featured" name="is_featured" value="1" 
                                       {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Featured Product
                                </label>
                            </div>
                        </div>
                        
                        <div class="status-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<<<<<<< HEAD
        </div>
        
        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn-primary">
                Update Product
            </button>
        </div>
    </form>
=======

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.products.index') }}" class="btn-custom-green-secondary me-2">
                    {{-- <i class="fas fa-times"></i> --}}
                    Cancel
                </a>
                <button type="submit" class="btn-custom-green">
                    {{-- <i class="fas fa-save"></i> --}}
                    Update Product
                </button>
            </div>
        </form>
    </div>
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
</div>

@endsection

@push('scripts')
<script>
    let variantCount = {{ $product->has_variants ? $variants->count() : 0 }};

    // Toggle variants section
    document.getElementById('has_variants').addEventListener('change', function() {
        const variantsSection = document.getElementById('variantsSection');
        const stockQuantityInput = document.querySelector('input[name="stock_quantity"]');
        
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
            <div class="variant-item" data-index="${variantCount}">
                <div class="variant-header">
                    <div class="variant-title">Variant #${variantCount + 1}</div>
                    <button type="button" class="btn-remove-variant" onclick="removeVariant(this)">
                        Remove
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
<<<<<<< HEAD
                        <label class="form-label">Stock Quantity *</label>
                        <input type="number" class="form-control stock-input" 
                               name="variants[${variantCount}][stock]" 
                               value="0" min="0" required>
=======
                        <div class="mb-3">
                            <label class="form-label">Stock Quantity *</label>
                            <input type="number" class="form-control stock-input" 
                                   name="variants[${variantCount}][stock]" 
                                   value="0" min="0" readonly>
                        </div>
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
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
                        <input type="file" class="form-control" 
                               name="variants[${variantCount}][image]" 
                               accept="image/*">
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', variantHtml);
        variantCount++;
        updateSummary();
    });

    // Remove variant
    function removeVariant(button) {
        if (confirm('Are you sure you want to remove this variant?')) {
            const variantCard = button.closest('.variant-item');
            variantCard.remove();
            updateSummary();
            
            // Show no variants message if all are removed
            const container = document.getElementById('variantsContainer');
            if (container.children.length === 0) {
                container.innerHTML = `
                    <div class="empty-state" id="noVariantsMessage">
                        <i class="fas fa-info-circle"></i>
                        <p>No variants added yet</p>
                        <small>Click "Add Variant" to create different models</small>
                    </div>
                `;
            }
        }
    }

    // Update summary
    function updateSummary() {
        const stockInputs = document.querySelectorAll('.stock-input');
        let totalStock = 0;
        let totalVariants = document.querySelectorAll('.variant-item').length;
        
        stockInputs.forEach(input => {
            const value = parseInt(input.value) || 0;
            totalStock += value;
        });

        document.getElementById('totalVariants').textContent = totalVariants;
        document.getElementById('totalStock').textContent = totalStock + ' units';
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
            const variants = document.querySelectorAll('.variant-item');
            if (variants.length === 0) {
                e.preventDefault();
                alert('Please add at least one variant when variants are enabled.');
                return false;
            }
        }
        
        // Validate prices
        const price = parseFloat(document.querySelector('input[name="price"]').value);
        const salePrice = parseFloat(document.querySelector('input[name="sale_price"]').value) || 0;
        
        if (salePrice > 0 && salePrice >= price) {
            e.preventDefault();
            alert('Sale price must be less than the base price.');
            return false;
        }
    });

<<<<<<< HEAD
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateSummary();
    });
=======
    // Initial summary update
    updateSummary();

    // Toast notification function
    function showToast(message, type = 'success') {
        // Remove existing toasts
        document.querySelectorAll('.upper-middle-toast').forEach(toast => toast.remove());
        
        const bgColors = {
            'success': '#2C8F0C',
            'error': '#dc3545',
            'warning': '#ffc107',
            'info': '#17a2b8'
        };
        
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-triangle',
            'warning': 'fa-exclamation-circle',
            'info': 'fa-info-circle'
        };
        
        const bgColor = bgColors[type] || bgColors.success;
        const icon = icons[type] || icons.success;
        const textColor = type === 'warning' ? 'text-dark' : 'text-white';
        
        const toast = document.createElement('div');
        toast.className = 'upper-middle-toast position-fixed start-50 translate-middle-x p-3';
        toast.style.cssText = `
            top: 100px;
            z-index: 9999;
            min-width: 300px;
            text-align: center;
        `;
        
        toast.innerHTML = `
            <div class="toast align-items-center border-0 show shadow-lg" role="alert" style="background-color: ${bgColor}; border-radius: 10px;">
                <div class="d-flex justify-content-center align-items-center p-3">
                    <div class="toast-body ${textColor} d-flex align-items-center">
                        <i class="fas ${icon} me-2 fs-5"></i>
                        <span class="fw-semibold">${message}</span>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 3000);
    }
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
</script>
@endpush