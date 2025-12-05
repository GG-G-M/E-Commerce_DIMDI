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

    .logo-preview {
        max-width: 150px;
        max-height: 100px;
        border-radius: 8px;
        display: none;
        margin-top: 10px;
    }
</style>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Add New Brand</h1>
        <p class="text-muted mb-0">Create a new product brand.</p>
    </div>
    <a href="{{ route('admin.brands.index') }}" style="background-color: #2C8F0C" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Brands
    </a>
</div>

<div class="card card-custom">
    <div class="card-header card-header-custom">
        <i class="fas fa-plus-circle me-2"></i> Brand Information
    </div>
    <div class="card-body">
        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Brand Name *</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required placeholder="Enter brand name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="slug" class="form-label">Slug *</label>
                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" 
                               value="{{ old('slug') }}" required placeholder="brand-slug">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">URL-friendly version of the name</small>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="4" placeholder="Enter brand description (optional)">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="logo" class="form-label">Brand Logo</label>
                <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" 
                       accept="image/*">
                @error('logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Recommended size: 200x100 pixels</small>
                <img id="logoPreview" class="logo-preview img-thumbnail" src="#" alt="Logo preview">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" 
                               value="{{ old('sort_order', 0) }}" min="0" placeholder="0">
                        <small class="text-muted">Lower numbers appear first</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <div class="form-check form-switch mt-4">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">Active Brand</label>
                        </div>
                        <small class="text-muted">Inactive brands won't be visible on the store</small>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" style="background-color: #2C8F0C" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Create Brand
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '') // Remove invalid chars
            .replace(/\s+/g, '-')        // Replace spaces with -
            .replace(/-+/g, '-');        // Replace multiple - with single -
        
        document.getElementById('slug').value = slug;
    });

    // Logo preview
    document.getElementById('logo').addEventListener('change', function(e) {
        const preview = document.getElementById('logoPreview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const slug = document.getElementById('slug').value.trim();
        
        if (!name) {
            e.preventDefault();
            alert('Please enter a brand name.');
            return false;
        }
        
        if (!slug) {
            e.preventDefault();
            alert('Please enter a slug or let it auto-generate from the name.');
            return false;
        }
    });
</script>
@endsection