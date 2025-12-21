@extends('layouts.admin')

@section('content')
<style>
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
    }

    .card-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .variant-card {
        border: 2px solid #E8F5E6;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background: #F8FDF8;
    }

    .variant-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #C8E6C9;
    }

    .variant-number {
        background: #2C8F0C;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
    }

    .remove-variant {
        background: #C62828;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        cursor: pointer;
    }

    .image-preview-container {
        border: 2px dashed #C8E6C9;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        background: #F8FDF8;
    }

    .variant-image-preview {
        max-height: 120px;
        max-width: 100%;
        border-radius: 6px;
    }

    .no-variants-message {
        background: #FFF3E0;
        border: 2px dashed #FFB74D;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        color: #E65100;
    }

    .total-stock-summary {
        background: linear-gradient(135deg, #E8F5E6, #C8E6C9);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid #2C8F0C;
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
{{-- <div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Edit Product</h1>
        <p class="text-muted mb-0">Update product details and manage variants.</p>
    </div>
</div> --}}

<!-- Product Edit Form -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <i class="fas fa-box me-2"></i> Product Details
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Base Price *</label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                       id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sale_price" class="form-label">Sale Price</label>
                                <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror"
                                       id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}"
                                       placeholder="Optional">
                                @error('sale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select class="form-control @error('category_id') is-invalid @enderror"
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
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Brand *</label>
                                <select class="form-control @error('brand_id') is-invalid @enderror"
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
                                <small class="text-muted">Select the product brand</small>
                            </div>
                        </div>
                    </div>

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
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Variant Description</label>
                                                <textarea class="form-control" 
                                                          name="variants[{{ $index }}][variant_description]" 
                                                          rows="2" 
                                                          placeholder="Optional description for this variant">{{ old('variants.'.$index.'.variant_description', $variant->variant_description) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Price *</label>
                                                <input type="number" step="0.01" class="form-control" 
                                                       name="variants[{{ $index }}][price]" 
                                                       value="{{ old('variants.'.$index.'.price', $variant->price) }}" 
                                                       min="0" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Sale Price</label>
                                                <input type="number" step="0.01" class="form-control" 
                                                       name="variants[{{ $index }}][sale_price]" 
                                                       value="{{ old('variants.'.$index.'.sale_price', $variant->sale_price) }}" 
                                                       min="0" placeholder="Optional">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Variant Image</label>
                                                <input type="file" class="form-control variant-image-input" 
                                                       name="variants[{{ $index }}][image]" 
                                                       accept="image/*">
                                                @if($variant->image)
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="variants[{{ $index }}][remove_image]" value="1">
                                                    <label class="form-check-label text-danger">
                                                        Remove current image
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
                                </div>
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
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div>
                            <img src="{{ $product->image_url }}" alt="Current image" class="img-thumbnail mb-2" style="max-height: 200px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Change Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-2">
                            <img id="imagePreview" src="#" alt="Image preview" class="img-thumbnail d-none" style="max-height: 200px;">
                        </div>
                        <small class="text-muted">Leave empty to keep current image</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Featured Product</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
            </div>

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
        stockQuantityInput.required = !this.checked;
        
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
                    <button type="button" class="remove-variant" onclick="removeVariant(this)">
                        <i class="fas fa-times me-1"></i> Remove
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Variant Name *</label>
                            <input type="text" class="form-control" 
                                   name="variants[${variantCount}][variant_name]" 
                                   placeholder="e.g., Pro Model, Standard Edition" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Stock Quantity *</label>
                            <input type="number" class="form-control stock-input" 
                                   name="variants[${variantCount}][stock]" 
                                   value="0" min="0" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Variant Description</label>
                            <textarea class="form-control" 
                                      name="variants[${variantCount}][variant_description]" 
                                      rows="2" 
                                      placeholder="Optional description for this variant"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Price *</label>
                            <input type="number" step="0.01" class="form-control" 
                                   name="variants[${variantCount}][price]" 
                                   value="{{ old('price', $product->price) }}" 
                                   min="0" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Sale Price</label>
                            <input type="number" step="0.01" class="form-control" 
                                   name="variants[${variantCount}][sale_price]" 
                                   min="0" placeholder="Optional">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
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
            </div>
        `;

        container.insertAdjacentHTML('beforeend', variantHtml);
        variantCount++;
        updateSummary();
    });

    // Remove variant
    function removeVariant(button) {
        const variantCard = button.closest('.variant-card');
        variantCard.remove();
        updateSummary();
        
        // Show no variants message if all are removed
        const container = document.getElementById('variantsContainer');
        if (container.children.length === 0) {
            container.innerHTML = `
                <div class="no-variants-message" id="noVariantsMessage">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h5>No Variants Added</h5>
                    <p class="mb-0">Click "Add Variant" to create different models of this product.</p>
                </div>
            `;
        }
    }

    // Update summary
    function updateSummary() {
        const stockInputs = document.querySelectorAll('.stock-input');
        let totalStock = 0;
        let totalVariants = document.querySelectorAll('.variant-card').length;
        
        stockInputs.forEach(input => {
            totalStock += parseInt(input.value) || 0;
        });

        document.getElementById('totalVariants').textContent = totalVariants;
        document.getElementById('totalStock').textContent = totalStock + ' units';
    }

    // Image preview for main image
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const file = e.target.files[0];
        
        if (file) {
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

    // Image preview for variant images
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('variant-image-input')) {
            const file = e.target.files[0];
            const previewContainer = e.target.nextElementSibling;
            const preview = previewContainer.querySelector('.variant-image-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
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
            const variants = document.querySelectorAll('.variant-card');
            if (variants.length === 0) {
                e.preventDefault();
                alert('Please add at least one variant when variants are enabled.');
                return false;
            }
        }
    });

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
</script>
@endpush