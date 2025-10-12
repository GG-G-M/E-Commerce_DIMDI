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

    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
    }

    .btn-success {
        background: linear-gradient(135deg, #4CAF50, #66BB6A);
        border: none;
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
</style>

<!-- Header -->
<div class="page-header">
    <h1 class="h3 mb-1">Add New Product</h1>
    <p class="text-muted mb-0">Fill out the form to add a new product to your store.</p>
</div>

<!-- Product Form -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <i class="fas fa-box me-2"></i> Product Information
    </div>

    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Base Price *</label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sale_price" class="form-label">Sale Price</label>
                                <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" 
                                       id="sale_price" name="sale_price" value="{{ old('sale_price') }}">
                                @error('sale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock_quantity" class="form-label">Base Stock Quantity *</label>
                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                       id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Used when variants are disabled</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
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
                        </div>
                    </div>

                    <!-- Variants Toggle -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="has_variants" name="has_variants" value="1" {{ old('has_variants') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_variants">
                                <strong>Enable Product Variants</strong>
                            </label>
                            <small class="form-text text-muted d-block">
                                Enable this to create different models/versions of this product
                            </small>
                        </div>
                    </div>

                    <!-- Variants Section -->
                    <div id="variantsSection" style="display: {{ old('has_variants') ? 'block' : 'none' }};">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label mb-0">Product Variants</label>
                            <button type="button" id="addVariant" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-1"></i> Add Variant
                            </button>
                        </div>

                        <div id="variantsContainer">
                            @if(old('variants'))
                                @foreach(old('variants') as $index => $variant)
                                <div class="variant-card" data-index="{{ $index }}">
                                    <div class="variant-header">
                                        <span class="variant-number">Variant #{{ $index + 1 }}</span>
                                        <button type="button" class="remove-variant" onclick="removeVariant(this)">
                                            <i class="fas fa-times me-1"></i> Remove
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Variant Name *</label>
                                                <input type="text" class="form-control" 
                                                       name="variants[{{ $index }}][variant_name]" 
                                                       value="{{ $variant['variant_name'] ?? '' }}" 
                                                       placeholder="e.g., Pro Model, Standard Edition" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Stock Quantity *</label>
                                                <input type="number" class="form-control" 
                                                       name="variants[{{ $index }}][stock]" 
                                                       value="{{ $variant['stock'] ?? 0 }}" 
                                                       min="0" required>
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
                                                          placeholder="Optional description for this variant">{{ $variant['variant_description'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Price *</label>
                                                <input type="number" step="0.01" class="form-control" 
                                                       name="variants[{{ $index }}][price]" 
                                                       value="{{ $variant['price'] ?? '' }}" 
                                                       min="0" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Sale Price</label>
                                                <input type="number" step="0.01" class="form-control" 
                                                       name="variants[{{ $index }}][sale_price]" 
                                                       value="{{ $variant['sale_price'] ?? '' }}" 
                                                       min="0" placeholder="Optional">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Variant Image</label>
                                                <input type="file" class="form-control variant-image-input" 
                                                       name="variants[{{ $index }}][image]" 
                                                       accept="image/*">
                                                <div class="image-preview-container mt-2" style="display: none;">
                                                    <img class="variant-image-preview" src="#" alt="Preview">
                                                </div>
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
                                    <span class="badge bg-info" id="totalVariants">0</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Total Stock:</strong> 
                                    <span class="badge bg-success" id="totalStock">0 units</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Main Image -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image *</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*" required>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-2">
                            <img id="imagePreview" src="#" alt="Image preview" class="img-thumbnail d-none" style="max-height: 200px;">
                        </div>
                        <small class="form-text text-muted">Supported formats: JPEG, PNG, JPG, GIF, WEBP. Max size: 2MB</small>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Featured Product</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Product</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let variantCount = {{ old('variants') ? count(old('variants')) : 0 }};

    // Toggle variants section
    document.getElementById('has_variants').addEventListener('change', function() {
        const variantsSection = document.getElementById('variantsSection');
        variantsSection.style.display = this.checked ? 'block' : 'none';
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
                                   value="0" min="0" required>
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
                                   value="{{ old('price', '') }}" 
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
            } else {
                previewContainer.style.display = 'none';
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
</script>
@endpush