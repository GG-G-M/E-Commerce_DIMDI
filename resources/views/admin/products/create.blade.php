@extends('layouts.admin')

@section('content')
<style>
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
        padding: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .form-section:last-child {
        border-bottom: none;
    }
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2C8F0C;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
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
        margin-bottom: 1rem;
    }
    
    .variants-container {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid #dee2e6;
    }
    
    .variant-item {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .variant-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .variant-title {
        font-weight: 600;
        color: #495057;
        font-size: 0.95rem;
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
        padding: 0.5rem 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s ease;
        font-size: 0.9rem;
    }
    
    .btn-add-variant:hover {
        background: #25750A;
    }
    
    /* Stock Summary */
    .stock-summary {
        display: flex;
        gap: 2rem;
        margin-top: 1rem;
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
        font-size: 1rem;
        font-weight: 600;
        color: #2C8F0C;
    }
    
    /* Image Preview */
    .image-preview {
        max-width: 200px;
        max-height: 150px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    /* Status Section */
    .status-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .status-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    
    .status-item:last-child {
        margin-bottom: 0;
    }
    
    /* Form Actions */
    .form-actions {
        padding: 1rem 1.5rem;
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
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
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
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }
    
    .btn-primary:hover {
        background: #25750A;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .form-section {
            padding: 1rem;
        }
        
        .stock-summary {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-secondary, .btn-primary {
            width: 100%;
            text-align: center;
        }
    }
</style>

<!-- Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h1>Add New Product</h1>
            <p>Fill out the form to add a new product to your store</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn-secondary">
            Back to Products
        </a>
    </div>
</div>

<!-- Product Form -->
<div class="form-container">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        
        <!-- Basic Information -->
        <div class="form-section">
            <div class="section-title">Basic Information</div>
            
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Product Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description *</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          name="description" rows="3" required>{{ old('description') }}</textarea>
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
                               name="price" value="{{ old('price') }}" required>
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
                               name="sale_price" value="{{ old('sale_price') }}" 
                               placeholder="Optional">
                    </div>
                    @error('sale_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Categories & Stock -->
        <div class="form-section">
            <div class="section-title">Categories & Stock</div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Category *</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" 
                            name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Brand</label>
                    <select class="form-select @error('brand_id') is-invalid @enderror" 
                            name="brand_id">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Base Stock Quantity</label>
                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                           name="stock_quantity" value="0" 
                           id="stock_quantity" readonly>
                    @error('stock_quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Stock is managed through Stock-In/Stock-Out system</small>
                </div>
            </div>
            
            <!-- Variants Toggle -->
            <div class="mt-3">
                <div class="variants-toggle">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               id="has_variants" name="has_variants" value="1" 
                               {{ old('has_variants') ? 'checked' : '' }}>
                        <label class="form-check-label" for="has_variants">
                            Enable Product Variants
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Variants Section -->
            <div id="variantsSection" style="display: {{ old('has_variants') ? 'block' : 'none' }};">
                <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                    <label class="form-label mb-0">Product Variants</label>
                    <button type="button" id="addVariant" class="btn-add-variant">
                        + Add Variant
                    </button>
                </div>
                
                <div class="variants-container">
                    <div id="variantsContainer">
                        @if(old('variants'))
                            @foreach(old('variants') as $index => $variant)
                            <div class="variant-item" data-index="{{ $index }}">
                                <div class="variant-header">
                                    <div class="variant-title">Variant #{{ $index + 1 }}</div>
                                    <button type="button" class="btn-remove-variant" onclick="removeVariant(this)">
                                        Remove
                                    </button>
                                </div>
                                
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Variant Name *</label>
                                        <input type="text" class="form-control" 
                                               name="variants[{{ $index }}][variant_name]" 
                                               value="{{ $variant['variant_name'] ?? '' }}" 
                                               placeholder="e.g., Pro Model, Standard Edition" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Stock Quantity</label>
                                        <input type="number" class="form-control stock-input" 
                                               name="variants[{{ $index }}][stock]" 
                                               value="0" 
                                               min="0" readonly>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label">Variant Description</label>
                                        <textarea class="form-control" 
                                                  name="variants[{{ $index }}][variant_description]" 
                                                  rows="2" 
                                                  placeholder="Optional description for this variant">{{ $variant['variant_description'] ?? '' }}</textarea>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label">Price *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" step="0.01" class="form-control" 
                                                   name="variants[{{ $index }}][price]" 
                                                   value="{{ $variant['price'] ?? '' }}" 
                                                   min="0" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label">Sale Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" step="0.01" class="form-control" 
                                                   name="variants[{{ $index }}][sale_price]" 
                                                   value="{{ $variant['sale_price'] ?? '' }}" 
                                                   min="0" placeholder="Optional">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label">Variant Image</label>
                                        <input type="file" class="form-control" 
                                               name="variants[{{ $index }}][image]" 
                                               accept="image/*">
                                    </div>
                                </div>
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
                            <div class="stock-value" id="totalVariants">{{ old('variants') ? count(old('variants')) : 0 }}</div>
                        </div>
                        <div class="stock-item">
                            <div class="stock-label">Stock Management</div>
                            <div class="stock-value">Stock-In/Out System</div>
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
                        <label class="form-label">Product Image *</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*" required>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Supported formats: JPEG, PNG, JPG, GIF, WEBP</small>
                        <div class="mt-2">
                            <img id="imagePreview" src="#" alt="Image preview" class="image-preview d-none">
                        </div>
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
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Featured Product
                                </label>
                            </div>
                        </div>
                        
                        <div class="status-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn-primary">
                Create Product
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    let variantCount = {{ old('variants') ? count(old('variants')) : 0 }};

    // Toggle variants section
    document.getElementById('has_variants').addEventListener('change', function() {
        const variantsSection = document.getElementById('variantsSection');
        const stockQuantityInput = document.getElementById('stock_quantity');
        
        variantsSection.style.display = this.checked ? 'block' : 'none';
        stockQuantityInput.disabled = this.checked;
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
                
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label">Variant Name *</label>
                        <input type="text" class="form-control" 
                               name="variants[${variantCount}][variant_name]" 
                               placeholder="e.g., Pro Model, Standard Edition" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control stock-input" 
                               name="variants[${variantCount}][stock]" 
                               value="0" min="0" readonly>
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
                                   value="{{ old('price', '') }}" 
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

    // Image preview for main image
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const file = e.target.files[0];
        
        if (file) {
            if (file.size > 2 * 1024 * 1024) { // 2MB limit
                alert('Image size must be less than 2MB');
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

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateSummary();
    });
</script>
@endpush