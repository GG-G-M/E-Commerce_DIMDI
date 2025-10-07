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
    
    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .form-card .card-body {
        padding: 2rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #2C8F0C;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        border: 2px solid #E8F5E6;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: #DC3545;
    }
    
    .invalid-feedback {
        color: #DC3545;
        font-weight: 500;
    }
    
    .form-text {
        color: #6c757d;
        font-size: 0.875rem;
    }
    
    .img-thumbnail {
        border: 2px solid #E8F5E6;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .img-thumbnail:hover {
        border-color: #2C8F0C;
    }
    
    .form-check-input {
        border: 2px solid #E8F5E6;
    }
    
    .form-check-input:checked {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
    }
    
    .form-check-label {
        color: #495057;
        font-weight: 500;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
    }
    
    .btn-secondary {
        background: #6c757d;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }
    
    .required-field::after {
        content: " *";
        color: #DC3545;
    }
    
    .image-upload-area {
        border: 2px dashed #E8F5E6;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        background: #F8FDF8;
        cursor: pointer;
    }
    
    .image-upload-area:hover {
        border-color: #2C8F0C;
        background: #F0F9F0;
    }
    
    .upload-icon {
        font-size: 2rem;
        color: #2C8F0C;
        margin-bottom: 1rem;
    }
    
    .settings-card {
        background: #F8FDF8;
        border: 2px solid #E8F5E6;
        border-radius: 8px;
        padding: 1.5rem;
    }
    
    .current-image-container {
        text-align: center;
        padding: 1rem;
        background: #F8FDF8;
        border-radius: 8px;
        border: 2px solid #E8F5E6;
    }
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">Edit Category</h1>
            <p class="mb-0 text-muted">Update category information and settings</p>
        </div>
    </div>
</div>

<div class="card form-card">
    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <!-- Category Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label required-field">Category Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $category->name) }}" 
                               placeholder="Enter category name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4"
                                  placeholder="Enter category description (optional)">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Current Image -->
                    @if($category->image)
                    <div class="mb-4">
                        <label class="form-label">Current Image</label>
                        <div class="current-image-container">
                            <img src="{{ $category->image_url }}" alt="Current category image" 
                                 class="img-thumbnail mb-2" style="max-height: 150px; max-width: 100%;">
                            <p class="text-muted mb-0">Current category image</p>
                        </div>
                    </div>
                    @endif

                    <!-- New Image Upload -->
                    <div class="mb-4">
                        <label for="image" class="form-label">Change Image</label>
                        <div class="image-upload-area">
                            <div class="upload-icon">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <p class="mb-2">Click to change category image</p>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*"
                                   style="opacity: 0; position: absolute; width: 100%; height: 100%; cursor: pointer;">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3 text-center">
                            <img id="imagePreview" src="#" alt="New image preview" class="img-thumbnail d-none" style="max-height: 150px; max-width: 100%;">
                        </div>
                        <small class="form-text text-muted mt-2 d-block">
                            <i class="fas fa-info-circle me-1"></i>
                            Leave empty to keep current image
                        </small>
                    </div>

                    <!-- Settings -->
                    <div class="mb-4">
                        <label class="form-label">Category Settings</label>
                        <div class="settings-card">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-check-circle me-2 text-success"></i>Active Category
                                </label>
                                <small class="form-text text-muted d-block mt-1">
                                    Active categories will be visible to customers
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-4">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-md-2">
                    <i class="fas fa-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Image preview and upload area interaction
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const uploadArea = document.querySelector('.image-upload-area');
        
        // Image preview
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('d-none');
            }
        });
        
        // Click on upload area to trigger file input
        uploadArea.addEventListener('click', function() {
            imageInput.click();
        });
    });
</script>
@endpush