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
        margin-bottom: 0.3rem;
    }

    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .form-label {
        font-weight: 600;
        color: #2C8F0C;
    }

    .form-control, .form-check-input {
        border-radius: 8px;
        border: 1px solid #C8E6C9;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.15rem rgba(44,143,12,0.2);
    }

    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
    }

    .btn-secondary {
        border-radius: 8px;
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border: 1px solid #C8E6C9;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #D0E8CE;
        transform: translateY(-2px);
    }

    .divider-line {
        height: 2px;
        background: #E8F5E6;
        margin: 1.5rem 0;
    }
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Edit Category</h1>
            <p class="text-muted mb-0">Update the details and status of this category.</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-success">
            <i class="fas fa-arrow-left me-1"></i> Back to Categories
        </a>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-4">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <!-- Category Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4"
                                  placeholder="Enter a short description...">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Status -->
                    <div class="mb-4">
                        <label class="form-label d-block">Status</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active Category</label>
                        </div>
                    </div>

                    <div class="divider-line"></div>

                    <!-- Quick Info -->
                    <div class="p-3 bg-light rounded">
                        <h6 class="text-success fw-bold mb-2"><i class="fas fa-info-circle me-2"></i>Tips</h6>
                        <p class="small text-muted mb-0">
                            Keep category names short and clear. 
                            Use descriptions to help users understand what kind of products belong here.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
