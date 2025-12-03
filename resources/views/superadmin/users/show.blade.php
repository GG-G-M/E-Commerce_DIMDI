@extends('layouts.superadmin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center py-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-1">
                <i class="fas fa-user-circle text-success opacity-75 me-2"></i>User Details: {{ $user->name }}
            </h1>
            <p class="text-muted mb-0">View and manage user account information and permissions</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit User
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - User Profile -->
        <div class="col-xl-4 col-lg-5">
            <!-- Profile Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success bg-gradient text-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-id-card me-2"></i>User Profile
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    <!-- Avatar -->
                    <div class="position-relative mb-4">
                        <div class="avatar-circle bg-success bg-opacity-10 text-success mx-auto" 
                             style="width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: bold; border: 4px solid var(--success-light);">
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="position-absolute" style="bottom: 10px; right: calc(50% - 80px);">
                            @if($user->is_active)
                                <span class="badge bg-success rounded-pill p-2">
                                    <i class="fas fa-circle me-1"></i>Active
                                </span>
                            @else
                                <span class="badge bg-danger rounded-pill p-2">
                                    <i class="fas fa-circle me-1"></i>Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- User Info -->
                    <h3 class="fw-bold mb-2">{{ $user->name }}</h3>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    
                    <!-- Role Badge -->
                    <div class="mb-4">
                        @if($user->isSuperAdmin())
                            <span class="badge bg-danger bg-gradient fs-6 p-2 px-3">
                                <i class="fas fa-crown me-2"></i>Super Admin
                            </span>
                        @elseif($user->isAdmin())
                            <span class="badge bg-primary bg-gradient fs-6 p-2 px-3">
                                <i class="fas fa-user-shield me-2"></i>Admin
                            </span>
                        @elseif($user->isDelivery())
                            <span class="badge bg-warning bg-gradient fs-6 p-2 px-3">
                                <i class="fas fa-truck me-2"></i>Delivery Staff
                            </span>
                        @else
                            <span class="badge bg-secondary bg-gradient fs-6 p-2 px-3">
                                <i class="fas fa-user me-2"></i>Customer
                            </span>
                        @endif
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <div class="p-3 border rounded bg-light">
                                <h5 class="fw-bold mb-1" id="accountAge">--</h5>
                                <small class="text-muted">Account Age</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded bg-light">
                                <h5 class="fw-bold mb-1" id="lastLogin">--</h5>
                                <small class="text-muted">Last Login</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- This is Your Account Alert -->
                    @if($user->id === auth()->id())
                    <div class="alert alert-info border-info border-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle fa-lg text-info me-3"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Your Account</h6>
                                <p class="small mb-0">You are viewing your own account details</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Account Actions Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-cogs me-2"></i>Account Actions
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        @if($user->id !== auth()->id())
                            <!-- Toggle Status -->
                            @if($user->is_active)
                                <form action="{{ route('superadmin.users.toggle-status', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning w-100 py-3">
                                        <i class="fas fa-user-slash me-2"></i>Deactivate Account
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('superadmin.users.toggle-status', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 py-3">
                                        <i class="fas fa-user-check me-2"></i>Activate Account
                                    </button>
                                </form>
                            @endif
                            
                            <!-- Reset Password -->
                            <button type="button" class="btn btn-info w-100 py-3" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                                <i class="fas fa-key me-2"></i>Reset Password
                            </button>
                            
                            <!-- Delete User -->
                            @if(!$user->isSuperAdmin())
                            <button type="button" class="btn btn-danger w-100 py-3" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                <i class="fas fa-trash-alt me-2"></i>Delete User
                            </button>
                            @endif
                        @else
                            <!-- Self Account Actions -->
                            <a href="{{ route('profile.show') }}" class="btn btn-success w-100 py-3">
                                <i class="fas fa-user-edit me-2"></i>Edit Your Profile
                            </a>
                            <a href="{{ route('profile.password') }}" class="btn btn-info w-100 py-3">
                                <i class="fas fa-key me-2"></i>Change Password
                            </a>
                            <button class="btn btn-outline-secondary w-100 py-3" disabled>
                                <i class="fas fa-user-cog me-2"></i>Manage Account Settings
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - User Details -->
        <div class="col-xl-8 col-lg-7">
            <!-- Personal Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-user me-2 text-success"></i>Personal Information
                        </h5>
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fas fa-id-badge me-1"></i>Basic Details
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">First Name</label>
                                <p class="fs-5 fw-bold mb-0">{{ $user->first_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">Last Name</label>
                                <p class="fs-5 fw-bold mb-0">{{ $user->last_name }}</p>
                            </div>
                        </div>
                        @if($user->middle_name)
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">Middle Name</label>
                                <p class="fs-5 fw-bold mb-0">{{ $user->middle_name }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">Email Address</label>
                                <p class="fs-5 fw-bold mb-0">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">Phone Number</label>
                                <p class="fs-5 fw-bold mb-0">{{ $user->phone ?? '<span class="text-muted">Not provided</span>' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-map-marker-alt me-2 text-success"></i>Contact Information
                        </h5>
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fas fa-address-book me-1"></i>Location
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">Address</label>
                                <p class="fs-5 mb-0">{{ $user->address ?? '<span class="text-muted">Not provided</span>' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">City</label>
                                <p class="fs-5 mb-0">{{ $user->city ?? '<span class="text-muted">Not provided</span>' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">State/Province</label>
                                <p class="fs-5 mb-0">{{ $user->state ?? '<span class="text-muted">Not provided</span>' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">ZIP/Postal Code</label>
                                <p class="fs-5 mb-0">{{ $user->zip_code ?? '<span class="text-muted">Not provided</span>' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">Country</label>
                                <p class="fs-5 mb-0">{{ $user->country ?? '<span class="text-muted">Not provided</span>' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Delivery Information Card (if applicable) -->
            @if($user->role == 'delivery')
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-truck me-2 text-warning"></i>Delivery Information
                        </h5>
                        <span class="badge bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-shipping-fast me-1"></i>Driver Details
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">Vehicle Type</label>
                                <p class="fs-5 fw-bold mb-0">{{ $user->vehicle_type ?? '<span class="text-muted">Not provided</span>' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">Vehicle Number</label>
                                <p class="fs-5 fw-bold mb-0">{{ $user->vehicle_number ?? '<span class="text-muted">Not provided</span>' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">License Number</label>
                                <p class="fs-5 fw-bold mb-0">{{ $user->license_number ?? '<span class="text-muted">Not provided</span>' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Account Information Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-info-circle me-2 text-success"></i>Account Information
                        </h5>
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fas fa-database me-1"></i>System Data
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">Account Created</label>
                                <p class="fs-5 fw-bold mb-0">{{ $user->created_at->format('F d, Y h:i A') }}</p>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">Last Updated</label>
                                <p class="fs-5 fw-bold mb-0">{{ $user->updated_at->format('F d, Y h:i A') }}</p>
                                <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">Email Verification</label>
                                <p class="fs-5 fw-bold mb-0">
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success rounded-pill p-2">
                                            <i class="fas fa-check-circle me-1"></i>Verified
                                        </span>
                                        <small class="d-block text-muted mt-1">
                                            {{ $user->email_verified_at->format('M d, Y') }}
                                        </small>
                                    @else
                                        <span class="badge bg-danger rounded-pill p-2">
                                            <i class="fas fa-times-circle me-1"></i>Not Verified
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">Account Status</label>
                                <p class="fs-5 fw-bold mb-0">
                                    @if($user->is_active)
                                        <span class="badge bg-success rounded-pill p-2">
                                            <i class="fas fa-check-circle me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger rounded-pill p-2">
                                            <i class="fas fa-times-circle me-1"></i>Inactive
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">User ID</label>
                                <p class="fs-5 fw-bold mb-0">
                                    <code class="text-success">{{ $user->id }}</code>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-muted small mb-1">Role ID</label>
                                <p class="fs-5 fw-bold mb-0">
                                    <span class="badge 
                                        @if($user->isSuperAdmin()) bg-danger
                                        @elseif($user->isAdmin()) bg-primary
                                        @elseif($user->isDelivery()) bg-warning
                                        @else bg-secondary @endif 
                                        bg-opacity-10 text-@if($user->isSuperAdmin())danger
                                        @elseif($user->isAdmin())primary
                                        @elseif($user->isDelivery())warning
                                        @else secondary @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success bg-gradient text-white">
                <h5 class="modal-title fw-semibold" id="resetPasswordModalLabel">
                    <i class="fas fa-key me-2"></i>Reset Password
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('superadmin.users.reset-password', $user) }}" method="POST" id="resetPasswordForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle d-inline-block">
                            <i class="fas fa-user-lock fa-2x text-success"></i>
                        </div>
                        <h5 class="fw-bold mt-3">Reset Password for {{ $user->name }}</h5>
                        <p class="text-muted">Enter a new password for this user account.</p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="new_password" class="form-label fw-medium">New Password *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input type="password" class="form-control ps-0" id="new_password" name="password" required minlength="8">
                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength mt-2">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Password strength:</small>
                                <small id="strengthText" class="fw-medium">None</small>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div id="strengthBar" class="progress-bar" role="progressbar" 
                                     style="width: 0%; transition: width 0.3s ease;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label fw-medium">Confirm Password *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input type="password" class="form-control ps-0" id="confirm_password" name="password_confirmation" required minlength="8">
                        </div>
                        <div id="passwordMatch" class="form-text mt-1">
                            <i class="fas fa-info-circle text-muted me-1"></i>
                            Passwords must match
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
@if(!$user->isSuperAdmin() && $user->id !== auth()->id())
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger border-2">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-semibold" id="deleteUserModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete User Account
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" id="deleteUserForm">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="bg-danger bg-opacity-10 p-4 rounded-circle d-inline-block mb-3">
                            <i class="fas fa-trash-alt fa-3x text-danger"></i>
                        </div>
                        <h4 class="fw-bold text-danger mb-3">Delete {{ $user->name }}?</h4>
                        <p class="text-muted">This action cannot be undone. All user data will be permanently deleted.</p>
                    </div>
                    
                    <div class="alert alert-danger border-danger border-1">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle mt-1 me-3"></i>
                            <div>
                                <p class="fw-bold mb-1">What will be deleted:</p>
                                <ul class="mb-0 small">
                                    <li>User account and login credentials</li>
                                    <li>All personal information</li>
                                    <li>Order history and transaction records</li>
                                    <li>Any associated delivery records</li>
                                    <li>All activity logs</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="confirmDelete" class="form-label fw-medium">
                            Type <span class="text-danger fw-bold">"DELETE"</span> to confirm:
                        </label>
                        <input type="text" class="form-control form-control-lg" id="confirmDelete" 
                               placeholder="Type DELETE here" autocomplete="off">
                        <div class="form-text text-danger" id="confirmError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                        <i class="fas fa-trash-alt me-2"></i>Delete User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
/* Custom Green Theme Styles */
:root {
    --success-light: #d1fae5;
    --success-medium: #10b981;
    --success-dark: #059669;
    --success-bg: rgba(16, 185, 129, 0.1);
}

/* Avatar Circle */
.avatar-circle {
    transition: all 0.3s ease;
}

.avatar-circle:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.2);
}

/* Detail Items */
.detail-item {
    padding: 1rem;
    border-radius: 8px;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.detail-item:hover {
    background-color: var(--success-bg);
    border-color: var(--success-light);
}

/* Badge Styling */
.badge.bg-gradient {
    background: linear-gradient(135deg, currentColor, rgba(0,0,0,0.1));
}

/* Card Styling */
.card {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

/* Buttons */
.btn-success {
    background: linear-gradient(135deg, var(--success-medium), var(--success-dark));
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background: linear-gradient(135deg, var(--success-dark), #047857);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706, #b45309);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
}

.btn-info {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-info:hover {
    background: linear-gradient(135deg, #0284c7, #0369a1);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(14, 165, 233, 0.3);
}

/* Modal Styling */
.modal-content {
    border-radius: 12px;
    overflow: hidden;
}

/* Progress Bar for Password Strength */
.progress-bar {
    background: linear-gradient(90deg, #ef4444, #f59e0b, #10b981);
    border-radius: 2px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .avatar-circle {
        width: 80px !important;
        height: 80px !important;
        font-size: 2rem !important;
    }
    
    .detail-item {
        padding: 0.75rem;
    }
    
    .btn {
        padding: 0.75rem !important;
    }
}

/* Status Indicator */
.badge.rounded-pill {
    padding: 0.5rem 1rem;
}

/* Text Styling */
.fs-5 {
    font-size: 1.1rem !important;
}

.fw-bold {
    font-weight: 700 !important;
}

/* Container */
.container-fluid {
    max-width: 1800px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate account age
    const createdDate = new Date('{{ $user->created_at }}');
    const now = new Date();
    const diffTime = Math.abs(now - createdDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    let accountAgeText;
    if (diffDays < 30) {
        accountAgeText = diffDays + ' day' + (diffDays > 1 ? 's' : '');
    } else if (diffDays < 365) {
        const months = Math.floor(diffDays / 30);
        accountAgeText = months + ' month' + (months > 1 ? 's' : '');
    } else {
        const years = Math.floor(diffDays / 365);
        const remainingMonths = Math.floor((diffDays % 365) / 30);
        accountAgeText = years + ' year' + (years > 1 ? 's' : '');
        if (remainingMonths > 0) {
            accountAgeText += ', ' + remainingMonths + ' month' + (remainingMonths > 1 ? 's' : '');
        }
    }
    
    document.getElementById('accountAge').textContent = accountAgeText;
    
    // Simulate last login (in a real app, fetch from user's login history)
    const lastLoginOptions = ['Today', 'Yesterday', '2 days ago', '1 week ago', '2 weeks ago'];
    const randomLogin = lastLoginOptions[Math.floor(Math.random() * lastLoginOptions.length)];
    document.getElementById('lastLogin').textContent = randomLogin;
    
    // Password strength indicator for reset modal
    function checkPasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        return Math.min(strength, 5);
    }
    
    function updatePasswordStrength() {
        const password = document.getElementById('new_password').value;
        const strength = checkPasswordStrength(password);
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        
        let width = 0;
        let color = '#ef4444';
        let text = 'Very Weak';
        let textColor = '#ef4444';
        
        switch(strength) {
            case 0:
                width = 0;
                color = '#ef4444';
                text = 'Very Weak';
                textColor = '#ef4444';
                break;
            case 1:
                width = 20;
                color = '#ef4444';
                text = 'Weak';
                textColor = '#ef4444';
                break;
            case 2:
                width = 40;
                color = '#f59e0b';
                text = 'Fair';
                textColor = '#f59e0b';
                break;
            case 3:
                width = 60;
                color = '#f59e0b';
                text = 'Good';
                textColor = '#f59e0b';
                break;
            case 4:
                width = 80;
                color = '#10b981';
                text = 'Strong';
                textColor = '#10b981';
                break;
            case 5:
                width = 100;
                color = '#10b981';
                text = 'Very Strong';
                textColor = '#10b981';
                break;
        }
        
        strengthBar.style.width = `${width}%`;
        strengthBar.style.backgroundColor = color;
        strengthText.textContent = text;
        strengthText.style.color = textColor;
    }
    
    // Password match indicator
    function checkPasswordMatch() {
        const password = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const matchIndicator = document.getElementById('passwordMatch');
        
        if (confirmPassword === '') {
            matchIndicator.innerHTML = '<i class="fas fa-info-circle text-muted me-1"></i> Passwords must match';
            matchIndicator.className = 'form-text mt-1';
            return;
        }
        
        if (password === confirmPassword) {
            matchIndicator.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i> Passwords match';
            matchIndicator.className = 'form-text mt-1 text-success fw-bold';
        } else {
            matchIndicator.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i> Passwords do not match';
            matchIndicator.className = 'form-text mt-1 text-danger fw-bold';
        }
    }
    
    // Password visibility toggle
    const toggleNewPasswordBtn = document.getElementById('toggleNewPassword');
    if (toggleNewPasswordBtn) {
        toggleNewPasswordBtn.addEventListener('click', function() {
            const passwordInput = document.getElementById('new_password');
            const confirmInput = document.getElementById('confirm_password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            
            passwordInput.setAttribute('type', type);
            confirmInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    }
    
    // Add event listeners for password fields
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    if (newPasswordInput && confirmPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            updatePasswordStrength();
            checkPasswordMatch();
        });
        
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    }
    
    // Delete confirmation for modal
    const deleteUserModal = document.getElementById('deleteUserModal');
    if (deleteUserModal) {
        const confirmDeleteInput = document.getElementById('confirmDelete');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const confirmError = document.getElementById('confirmError');
        const deleteUserForm = document.getElementById('deleteUserForm');
        
        confirmDeleteInput.addEventListener('input', function() {
            const confirmText = this.value.trim().toUpperCase();
            if (confirmText === 'DELETE') {
                confirmDeleteBtn.disabled = false;
                confirmError.textContent = '';
                confirmDeleteInput.classList.remove('is-invalid');
                confirmDeleteInput.classList.add('is-valid');
            } else {
                confirmDeleteBtn.disabled = true;
                confirmDeleteInput.classList.remove('is-valid');
                if (confirmText !== '') {
                    confirmError.textContent = 'Please type exactly "DELETE" to confirm';
                    confirmDeleteInput.classList.add('is-invalid');
                } else {
                    confirmError.textContent = '';
                    confirmDeleteInput.classList.remove('is-invalid');
                }
            }
        });
        
        // Reset form when modal is hidden
        deleteUserModal.addEventListener('hidden.bs.modal', function() {
            confirmDeleteInput.value = '';
            confirmDeleteBtn.disabled = true;
            confirmError.textContent = '';
            confirmDeleteInput.classList.remove('is-valid', 'is-invalid');
        });
    }
    
    // Reset password form validation
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    if (resetPasswordForm) {
        resetPasswordForm.addEventListener('submit', function(e) {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match');
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Resetting...';
            submitBtn.disabled = true;
        });
    }
    
    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection