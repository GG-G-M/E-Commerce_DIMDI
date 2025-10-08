@extends('layouts.admin')

@section('content')
<style>
    /* 🌿 Green Theme — Consistent with Category Management */
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

    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    }

    .btn-outline-success {
        border: 2px solid #2C8F0C;
        color: #2C8F0C;
        font-weight: 500;
    }

    .btn-outline-success:hover {
        background-color: #2C8F0C;
        color: white;
    }

    .form-label {
        color: #2C8F0C;
        font-weight: 600;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #E0E0E0;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.2);
    }

    .form-check-input:checked {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
    }

    .tips-box {
        background-color: #F8FDF8;
        border-left: 4px solid #2C8F0C;
        border-radius: 8px;
        padding: 1rem;
        font-size: 0.9rem;
        color: #2C8F0C;
    }

    .tips-box i {
        color: #2C8F0C;
        margin-right: 5px;
    }
</style>

<!-- Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-1">Add Category</h1>
        <p class="text-muted mb-0">Create and manage product categories efficiently.</p>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-success">
        <i class="fas fa-arrow-left me-1"></i> Back to Categories
    </a>
</div>

<!-- Add Category Form -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <i class="fas fa-plus me-2"></i> Create New Category
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <!-- Left Side -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name *</label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter category name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Write a short description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Right Side -->
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light">
                        <h6 class="fw-bold text-success mb-3">Status</h6>

                        <div class="form-check mb-3">
                            <input type="checkbox" id="is_active" name="is_active" class="form-check-input"
                                   value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">Active Category</label>
                        </div>

                        <hr>

                        <div class="tips-box mb-4">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Tips:</strong> Keep category names simple and clear. Use descriptions to help users
                            identify what products belong here.
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-success w-50">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary w-50">
                                <i class="fas fa-save"></i> Create
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
