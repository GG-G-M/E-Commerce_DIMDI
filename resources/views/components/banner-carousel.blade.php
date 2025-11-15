@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Banner Management</h2>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadBannerModal">
        <i class="fas fa-plus me-2"></i>Upload Banners
    </button>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-images me-2"></i>Current Banners
        </h5>
    </div>
    <div class="card-body">
        @if(count($bannerFiles) > 0)
            <div class="row">
                @foreach($bannerFiles as $file)
                    @php
                        $filename = basename($file);
                        // Fix the file URL generation
                        $fileUrl = asset('storage/banners/' . $filename);
                    @endphp
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ $fileUrl }}" class="card-img-top" alt="Banner" style="height: 200px; object-fit: cover;"
                                 onerror="this.src='https://via.placeholder.com/300x200?text=Banner+Image'">
                            <div class="card-body">
                                <p class="card-text small text-muted">{{ $filename }}</p>
                                <form action="{{ route('admin.banners.destroy', $filename) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this banner?')">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-3">
                <form action="{{ route('admin.banners.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete ALL banners?')">
                        <i class="fas fa-trash-alt me-2"></i>Clear All Banners
                    </button>
                </form>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Banners Uploaded</h4>
                <p class="text-muted">Upload some banners to get started.</p>
            </div>
        @endif
    </div>
</div>

<!-- Upload Banner Modal -->
<div class="modal fade" id="uploadBannerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Banner Images</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="banner_images" class="form-label">Select Banner Images</label>
                        <input type="file" class="form-control" id="banner_images" name="banner_images[]" multiple accept="image/*" required>
                        <div class="form-text">
                            Supported formats: JPEG, PNG, JPG, GIF, WEBP. Max file size: 2MB per image.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Upload Banners</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection