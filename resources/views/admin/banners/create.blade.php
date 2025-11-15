@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Create New Banner</h2>
    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Banners
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-plus me-2"></i>Banner Details
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="target_url" class="form-label">Target URL</label>
                        <input type="url" class="form-control @error('target_url') is-invalid @enderror" 
                               id="target_url" name="target_url" value="{{ old('target_url') }}" 
                               placeholder="https://example.com">
                        @error('target_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="order" class="form-label">Order</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                       id="order" name="order" value="{{ old('order', 0) }}" min="0">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Lower numbers appear first</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="alt_text" class="form-label">Alt Text</label>
                                <input type="text" class="form-control @error('alt_text') is-invalid @enderror" 
                                       id="alt_text" name="alt_text" value="{{ old('alt_text') }}">
                                @error('alt_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="image" class="form-label">Banner Image *</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*" required>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Supported formats: JPEG, PNG, JPG, GIF, WEBP. Max size: 2MB.
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        <div class="form-text">Inactive banners won't be displayed on the website</div>
                    </div>

                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Image Preview</h6>
                            <div id="imagePreview" class="text-center p-3 border rounded" style="display: none;">
                                <img id="preview" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                            <div id="noImage" class="text-center p-3 text-muted">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <p class="mb-0">No image selected</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Create Banner
                </button>
                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('preview');
    const imagePreview = document.getElementById('imagePreview');
    const noImage = document.getElementById('noImage');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.style.display = 'block';
                noImage.style.display = 'none';
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
            noImage.style.display = 'block';
        }
    });
});
</script>
@endsection