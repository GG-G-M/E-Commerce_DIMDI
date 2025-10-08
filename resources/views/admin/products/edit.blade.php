@extends('layouts.admin')

@section('content')
<style>
    /* === PAGE HEADER === */
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

    /* === CARD STYLES === */
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        background: white;
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
    }

    /* === BUTTONS === */
    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    }

    .btn-secondary {
        background-color: #e0f2e0;
        color: #2C8F0C;
        border: 1px solid #2C8F0C;
    }

    .btn-secondary:hover {
        background-color: #2C8F0C;
        color: white;
    }

    /* === FORM INPUTS === */
    .form-label {
        font-weight: 600;
        color: #2C8F0C;
    }

    .form-control:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.25);
    }

    .form-check-input:checked {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
    }

    .img-thumbnail {
        border-radius: 10px;
        border: 2px solid #E8F5E6;
    }

    .text-muted {
        color: #6c757d !important;
    }

    /* === SIZE & STOCK MANAGEMENT STYLES === */
    .size-option {
        border: 2px solid #E8F5E6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        background: #F8FDF8;
    }

    .size-option.active {
        border-color: #2C8F0C;
        background: #E8F5E6;
    }

    .size-header {
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 6px;
        transition: background-color 0.2s ease;
    }

    .size-header:hover {
        background-color: #E8F5E6;
    }

    .size-badge {
        background: #2C8F0C;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        min-width: 80px;
        text-align: center;
    }

    .stock-controls {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #C8E6C9;
    }

    .stock-status {
        font-size: 0.875rem;
        font-weight: 500;
        margin-top: 0.5rem;
    }

    .in-stock { color: #2C8F0C; }
    .low-stock { color: #FBC02D; }
    .out-of-stock { color: #C62828; }

    .total-stock-summary {
        background: linear-gradient(135deg, #E8F5E6, #C8E6C9);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid #2C8F0C;
    }

    .price-validation-error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: none;
    }
</style>

<!-- Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Edit Product</h1>
        <p class="text-muted mb-0">Update product details and manage stock for each size.</p>
    </div>
</div>

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
                                <div class="price-validation-error" id="sale_price_error">
                                    Sale price must be lower than base price
                                </div>
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
                    </div>

                    <!-- Size Selection and Stock Management -->
                    <div class="mb-4">
                        <label class="form-label d-block mb-3">Size Selection & Stock Management *</label>
                        
                        @error('selected_sizes')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <!-- Size Selection Checkboxes -->
                        <div class="row mb-3">
                            @foreach($sizes as $size)
                            <div class="col-md-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input size-checkbox" type="checkbox" 
                                           name="selected_sizes[]" value="{{ $size }}" 
                                           id="size_{{ $size }}"
                                           {{ in_array($size, $selectedSizes) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="size_{{ $size }}">
                                        {{ $size }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Stock Management for Selected Sizes -->
                        <div id="stockManagementSection">
                            @foreach($sizes as $size)
                                @if(in_array($size, $selectedSizes))
                                    @php
                                        $variant = $product->variants->where('size', $size)->first();
                                        $currentStock = $variant ? $variant->stock_quantity : 0;
                                        $variantPrice = $variant ? $variant->price : null;
                                        $variantSalePrice = $variant ? $variant->sale_price : null;
                                    @endphp
                                    <div class="size-option active" data-size="{{ $size }}">
                                        <div class="size-header d-flex justify-content-between align-items-center">
                                            <span class="size-badge">{{ $size }}</span>
                                            <div class="form-check">
                                                <input class="form-check-input size-active-checkbox" type="checkbox" 
                                                       name="active_sizes[]" value="{{ $size }}" checked>
                                                <label class="form-check-label small">Active</label>
                                            </div>
                                        </div>
                                        
                                        <div class="stock-controls">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label small mb-1">Stock Quantity *</label>
                                                    <input type="number" class="form-control stock-quantity @error('stock.'.$size) is-invalid @enderror" 
                                                           name="stock[{{ $size }}]" 
                                                           value="{{ old('stock.'.$size, $currentStock) }}" 
                                                           min="0" required>
                                                    @error('stock.'.$size)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small mb-1">Size Price *</label>
                                                    <input type="number" step="0.01" class="form-control size-price @error('size_price.'.$size) is-invalid @enderror" 
                                                           name="size_price[{{ $size }}]" 
                                                           value="{{ old('size_price.'.$size, $variantPrice) }}"
                                                           required>
                                                    @error('size_price.'.$size)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small mb-1">Size Sale Price</label>
                                                    <input type="number" step="0.01" class="form-control size-sale-price @error('size_sale_price.'.$size) is-invalid @enderror" 
                                                           name="size_sale_price[{{ $size }}]" 
                                                           value="{{ old('size_sale_price.'.$size, $variantSalePrice) }}"
                                                           placeholder="Optional">
                                                    @error('size_sale_price.'.$size)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="price-validation-error" id="sale_price_error_{{ $size }}">
                                                        Sale price must be lower than size price
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="stock-status">
                                                @if($currentStock > 10)
                                                    <span class="in-stock">✓ In Stock ({{ $currentStock }} available)</span>
                                                @elseif($currentStock > 0)
                                                    <span class="low-stock">⚠ Low Stock ({{ $currentStock }} left)</span>
                                                @else
                                                    <span class="out-of-stock">✗ Out of Stock</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Total Stock Summary -->
                        <div class="total-stock-summary">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Total Stock:</strong> 
                                    <span class="badge bg-{{ $product->total_stock > 10 ? 'success' : ($product->total_stock > 0 ? 'warning' : 'danger') }}">
                                        {{ $product->total_stock }} units
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Active Sizes:</strong> 
                                    <span class="badge bg-info">{{ count($selectedSizes) }} sizes</span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <strong>Status:</strong> 
                                    <span class="badge bg-{{ $product->in_stock ? 'success' : 'danger' }}">
                                        {{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}
                                    </span>
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
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Image preview
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

    // Size selection and stock management
    document.addEventListener('DOMContentLoaded', function() {
        const stockManagementSection = document.getElementById('stockManagementSection');
        const sizeCheckboxes = document.querySelectorAll('.size-checkbox');
        const basePriceInput = document.getElementById('price');
        const baseSalePriceInput = document.getElementById('sale_price');
        
        // Template for new size stock management
        const sizeOptionTemplate = (size) => `
            <div class="size-option active" data-size="${size}">
                <div class="size-header d-flex justify-content-between align-items-center">
                    <span class="size-badge">${size}</span>
                    <div class="form-check">
                        <input class="form-check-input size-active-checkbox" type="checkbox" 
                               name="active_sizes[]" value="${size}" checked>
                        <label class="form-check-label small">Active</label>
                    </div>
                </div>
                
                <div class="stock-controls">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label small mb-1">Stock Quantity *</label>
                            <input type="number" class="form-control stock-quantity" 
                                   name="stock[${size}]" value="0" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small mb-1">Size Price *</label>
                            <input type="number" step="0.01" class="form-control size-price" 
                                   name="size_price[${size}]" value="${basePriceInput.value || ''}"
                                   required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small mb-1">Size Sale Price</label>
                            <input type="number" step="0.01" class="form-control size-sale-price" 
                                   name="size_sale_price[${size}]" value="${baseSalePriceInput.value || ''}"
                                   placeholder="Optional">
                            <div class="price-validation-error" id="sale_price_error_${size}">
                                Sale price must be lower than size price
                            </div>
                        </div>
                    </div>
                    <div class="stock-status">
                        <span class="out-of-stock">✗ Out of Stock</span>
                    </div>
                </div>
            </div>
        `;

        // Handle size checkbox changes
        sizeCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const size = this.value;
                const existingSizeOption = stockManagementSection.querySelector(`[data-size="${size}"]`);
                
                if (this.checked && !existingSizeOption) {
                    // Add new size stock management
                    stockManagementSection.insertAdjacentHTML('beforeend', sizeOptionTemplate(size));
                    updateTotalStockSummary();
                } else if (!this.checked && existingSizeOption) {
                    // Remove size stock management
                    existingSizeOption.remove();
                    updateTotalStockSummary();
                }
            });
        });

        // Price validation functions
        function validateBasePrices() {
            const basePrice = parseFloat(basePriceInput.value) || 0;
            const baseSalePrice = parseFloat(baseSalePriceInput.value) || 0;
            const errorElement = document.getElementById('sale_price_error');

            if (baseSalePrice > 0 && baseSalePrice >= basePrice) {
                baseSalePriceInput.classList.add('is-invalid');
                errorElement.style.display = 'block';
                return false;
            } else {
                baseSalePriceInput.classList.remove('is-invalid');
                errorElement.style.display = 'none';
                return true;
            }
        }

        function validateSizePrices(sizeElement) {
            const sizePriceInput = sizeElement.querySelector('.size-price');
            const sizeSalePriceInput = sizeElement.querySelector('.size-sale-price');
            const size = sizeElement.dataset.size;
            const errorElement = document.getElementById(`sale_price_error_${size}`);

            const sizePrice = parseFloat(sizePriceInput.value) || 0;
            const sizeSalePrice = parseFloat(sizeSalePriceInput.value) || 0;

            if (sizeSalePrice > 0 && sizeSalePrice >= sizePrice) {
                sizeSalePriceInput.classList.add('is-invalid');
                if (errorElement) errorElement.style.display = 'block';
                return false;
            } else {
                sizeSalePriceInput.classList.remove('is-invalid');
                if (errorElement) errorElement.style.display = 'none';
                return true;
            }
        }

        // Event listeners for price validation
        basePriceInput.addEventListener('input', validateBasePrices);
        baseSalePriceInput.addEventListener('input', validateBasePrices);

        // Update stock status and validate prices in real-time
        stockManagementSection.addEventListener('input', function(e) {
            if (e.target.classList.contains('stock-quantity')) {
                const stockValue = parseInt(e.target.value) || 0;
                const statusElement = e.target.closest('.stock-controls').querySelector('.stock-status');
                
                if (stockValue > 10) {
                    statusElement.innerHTML = `<span class="in-stock">✓ In Stock (${stockValue} available)</span>`;
                } else if (stockValue > 0) {
                    statusElement.innerHTML = `<span class="low-stock">⚠ Low Stock (${stockValue} left)</span>`;
                } else {
                    statusElement.innerHTML = `<span class="out-of-stock">✗ Out of Stock</span>`;
                }
                
                updateTotalStockSummary();
            }

            if (e.target.classList.contains('size-price') || e.target.classList.contains('size-sale-price')) {
                const sizeElement = e.target.closest('.size-option');
                validateSizePrices(sizeElement);
            }
        });

        // Handle size active checkbox
        stockManagementSection.addEventListener('change', function(e) {
            if (e.target.classList.contains('size-active-checkbox')) {
                const sizeOption = e.target.closest('.size-option');
                if (e.target.checked) {
                    sizeOption.classList.add('active');
                    sizeOption.querySelectorAll('input').forEach(input => input.removeAttribute('disabled'));
                } else {
                    sizeOption.classList.remove('active');
                    sizeOption.querySelectorAll('input').forEach(input => input.setAttribute('disabled', 'disabled'));
                }
                updateTotalStockSummary();
            }
        });

        // Update total stock summary
        function updateTotalStockSummary() {
            const stockInputs = document.querySelectorAll('.stock-quantity:not([disabled])');
            let totalStock = 0;
            let activeSizes = 0;
            
            stockInputs.forEach(input => {
                totalStock += parseInt(input.value) || 0;
                activeSizes++;
            });
            
            // Update the summary display (you can enhance this to update the actual summary element)
            console.log('Total Stock:', totalStock, 'Active Sizes:', activeSizes);
        }

        // Form validation
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const selectedSizes = document.querySelectorAll('.size-checkbox:checked');
            if (selectedSizes.length === 0) {
                e.preventDefault();
                alert('Please select at least one size.');
                return false;
            }
            
            // Validate base prices
            if (!validateBasePrices()) {
                e.preventDefault();
                alert('Please fix base price validation errors.');
                return false;
            }

            // Validate all size prices
            let hasSizePriceErrors = false;
            document.querySelectorAll('.size-option.active').forEach(sizeElement => {
                if (!validateSizePrices(sizeElement)) {
                    hasSizePriceErrors = true;
                }
            });

            if (hasSizePriceErrors) {
                e.preventDefault();
                alert('Please fix size price validation errors.');
                return false;
            }
            
            // Validate that all selected sizes have stock quantity and price
            const stockInputs = document.querySelectorAll('.stock-quantity');
            const priceInputs = document.querySelectorAll('.size-price');
            let hasErrors = false;
            
            stockInputs.forEach(input => {
                if (!input.disabled && (input.value === '' || parseInt(input.value) < 0)) {
                    input.classList.add('is-invalid');
                    hasErrors = true;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            priceInputs.forEach(input => {
                if (!input.disabled && (input.value === '' || parseFloat(input.value) < 0)) {
                    input.classList.add('is-invalid');
                    hasErrors = true;
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            if (hasErrors) {
                e.preventDefault();
                alert('Please enter valid stock quantities and prices for all selected sizes.');
                return false;
            }
        });

        // Initial validation
        validateBasePrices();
        document.querySelectorAll('.size-option').forEach(validateSizePrices);
    });
</script>
@endpush