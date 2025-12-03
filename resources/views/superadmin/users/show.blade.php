@extends('layouts.superadmin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user text-green me-2"></i>User Details
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
        <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
        <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" 
                    onclick="return confirm('Are you sure you want to delete this user?')">
                <i class="fas fa-trash me-1"></i> Delete
            </button>
        </form>
        @endif
    </div>
</div>

<div class="row">
    <!-- User Information -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Profile</h5>
            </div>
            <div class="card-body text-center">
                <div class="avatar-circle bg-green text-white mx-auto mb-3" 
                     style="width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                </div>
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                
                <div class="mb-3">
                    @if($user->role == 'super_admin')
                        <span class="badge bg-danger fs-6"><i class="fas fa-crown me-1"></i> Super Admin</span>
                    @elseif($user->role == 'admin')
                        <span class="badge bg-primary fs-6"><i class="fas fa-user-shield me-1"></i> Admin</span>
                    @elseif($user->role == 'delivery')
                        <span class="badge bg-warning fs-6"><i class="fas fa-truck me-1"></i> Delivery Staff</span>
                    @else
                        <span class="badge bg-secondary fs-6"><i class="fas fa-user me-1"></i> Customer</span>
                    @endif
                </div>
                
                <div class="mb-3">
                    @if($user->is_active)
                        <span class="badge bg-success fs-6">Active</span>
                    @else
                        <span class="badge bg-danger fs-6">Inactive</span>
                    @endif
                </div>
                
                @if($user->id === auth()->id())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i> This is your account
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Account Actions -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Account Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($user->id !== auth()->id())
                        @if($user->is_active)
                            <form action="{{ route('superadmin.users.toggle-status', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-user-slash me-1"></i> Deactivate Account
                                </button>
                            </form>
                        @else
                            <form action="{{ route('superadmin.users.toggle-status', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-user-check me-1"></i> Activate Account
                                </button>
                            </form>
                        @endif
                        
                        <!-- Reset Password Form -->
                        <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                            <i class="fas fa-key me-1"></i> Reset Password
                        </button>
                    @else
                        <button class="btn btn-outline-secondary w-100" disabled>
                            <i class="fas fa-user-cog me-1"></i> Manage Your Profile
                        </button>
                        <a href="{{ route('profile.show') }}" class="btn btn-info w-100">
                            <i class="fas fa-user-edit me-1"></i> Edit Your Profile
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Details -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">First Name</label>
                        <p class="fs-5">{{ $user->first_name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Last Name</label>
                        <p class="fs-5">{{ $user->last_name }}</p>
                    </div>
                    @if($user->middle_name)
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted">Middle Name</label>
                        <p class="fs-5">{{ $user->middle_name }}</p>
                    </div>
                    @endif
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="fs-5">{{ $user->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Phone</label>
                        <p class="fs-5">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted">Address</label>
                        <p class="fs-6">{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">City</label>
                        <p class="fs-6">{{ $user->city ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">State</label>
                        <p class="fs-6">{{ $user->state ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">ZIP Code</label>
                        <p class="fs-6">{{ $user->zip_code ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-muted">Country</label>
                        <p class="fs-6">{{ $user->country ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Delivery Information (if applicable) -->
        @if($user->role == 'delivery')
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Delivery Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Vehicle Type</label>
                        <p class="fs-6">{{ $user->vehicle_type ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Vehicle Number</label>
                        <p class="fs-6">{{ $user->vehicle_number ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-muted">License Number</label>
                        <p class="fs-6">{{ $user->license_number ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Account Information -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Account Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Account Created</label>
                        <p class="fs-6">{{ $user->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Last Updated</label>
                        <p class="fs-6">{{ $user->updated_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Email Verified</label>
                        <p class="fs-6">
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Yes - {{ $user->email_verified_at->format('M d, Y') }}</span>
                            @else
                                <span class="badge bg-danger">Not verified</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Account Status</label>
                        <p class="fs-6">
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password for {{ $user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('superadmin.users.reset-password', $user) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="password" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="password_confirmation" required minlength="8">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-green">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection