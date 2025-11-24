@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Banner Management</h2>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Add New Banner
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-images me-2"></i>Active Banners
        </h5>
    </div>
    <div class="card-body">
        @if($banners->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($banners as $banner)
                            <tr>
                                <td>{{ $banner->order }}</td>
                                <td>
                                    @if($banner->image_path)
                                        <img src="{{ asset($banner->image_path) }}" 
                                             alt="{{ $banner->alt_text }}" 
                                             style="width: 100px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <div class="text-muted" style="width: 100px; height: 60px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 4px;">
                                            <i class="fas fa-image fa-lg"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $banner->title }}</td>
                                <td>{{ Str::limit($banner->description, 50) }}</td>
                                <td>
                                    <span class="badge bg-{{ $banner->is_active ? 'success' : 'secondary' }}">
                                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <form action="{{ route('admin.banners.toggle-status', $banner) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-{{ $banner->is_active ? 'warning' : 'success' }}" title="{{ $banner->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $banner->is_active ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this banner?')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Banners Created</h4>
                <p class="text-muted">Create your first banner to get started.</p>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Create First Banner
                </a>
            </div>
        @endif
    </div>
</div>
@endsection