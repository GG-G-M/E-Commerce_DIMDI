@extends('layouts.admin')

@section('content')
<style>
    /* Page Header */
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

    /* Card Styling */
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
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    }

    .btn-secondary {
        background-color: #A5D6A7;
        border: none;
        color: #2C8F0C;
    }

    .btn-secondary:hover {
        background-color: #81C784;
        color: white;
    }

    /* Form Styles */
    .form-label {
        font-weight: 600;
        color: #2C8F0C;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #C8E6C9;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.25);
    }

    .form-check-label {
        color: #2C8F0C;
    }

    .form-check-input:checked {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
    }

    /* Image Preview */
    #imagePreview {
        border: 2px solid #C8E6C9;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    #imagePreview:hover {
        transform: scale(1.02);
    }

    body {
        background-color: #F5FFF7;
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
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Product Basic Info -->
            <div class="mb-3">
                <label for="name" class="form-label">Product Name *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

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

            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                       id="brand" name="brand" value="{{ old('brand') }}">
                @error('brand')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>

            <!-- Product Variants -->
            <div class="mb-3">
                <label class="form-label">Product Variants</label>
                @foreach($attributes as $attribute)
                <div class="mb-2">
                    <label class="form-label">{{ $attribute->name }}</label>
                    <select class="form-select" name="variants[{{ $attribute->id }}][]" multiple>
                        @foreach($attribute->values as $value)
                        <option value="{{ $value->id }}">{{ $value->value }}</option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>

            <div class="mb-3">
                <label class="form-label">Base Price (Optional)</label>
                <input type="number" step="0.01" class="form-control" name="base_price" value="{{ old('base_price') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Stock (Optional)</label>
                <input type="number" class="form-control" name="stock" value="{{ old('stock', 0) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Image</label>
                <input type="file" class="form-control" name="image" id="image">
                <img id="imagePreview" class="mt-2 d-none" width="150" />
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
</script>
@endpush