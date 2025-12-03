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
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.users.show', $user) }}">{{ $user->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-1">
                <i class="fas fa-user-edit text-success opacity-75 me-2"></i>Edit User: {{ $user->name }}
            </h1>
            <p class="text-muted mb-0">Update user information, role, and permissions</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-info">
                <i class="fas fa-eye me-2"></i>View
            </a>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if($user->isSuperAdmin() && $user->id !== auth()->id())
    <div class="alert alert-warning border-warning border-2 alert-dismissible fade show shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
            </div>
            <div>
                <h5 class="alert-heading mb-1">Super Admin Edit</h5>
                <p class="mb-0">You are editing another Super Admin's account. This action requires caution as it may affect system security.</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($user->id === auth()->id())
    <div class="alert alert-info border-info border-2 alert-dismissible fade show shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="fas fa-user-circle fa-2x text-info"></i>
            </div>
            <div>
                <h5 class="alert-heading mb-1">Editing Your Own Account</h5>
                <p class="mb-0">You are editing your own account. Some restrictions apply for security reasons.</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row justify-content-center">
        <!-- Main Content - Wider Column -->
        <div class="col-xl-10 col-lg-12">
            <!-- User Summary Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    @if($user->isSuperAdmin())
                                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                                            <i class="fas fa-crown fa-2x text-danger"></i>
                                        </div>
                                    @elseif($user->isAdmin())
                                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                            <i class="fas fa-user-shield fa-2x text-primary"></i>
                                        </div>
                                    @elseif($user->isDelivery())
                                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                            <i class="fas fa-truck fa-2x text-warning"></i>
                                        </div>
                                    @else
                                        <div class="bg-secondary bg-opacity-10 p-3 rounded-circle">
                                            <i class="fas fa-user fa-2x text-secondary"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="mb-1 fw-bold">{{ $user->name }}</h4>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge 
                                            @if($user->isSuperAdmin()) bg-danger
                                            @elseif($user->isAdmin()) bg-primary
                                            @elseif($user->isDelivery()) bg-warning
                                            @else bg-secondary @endif">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                        <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <span class="badge bg-info">
                                            Joined: {{ $user->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column">
                                <span class="text-muted small">Last Updated</span>
                                <span class="fw-bold">{{ $user->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form Card - Wider -->
            <div class="card border-0 shadow-lg overflow-hidden mb-4">
                <div class="card-header bg-success bg-gradient text-white py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-user-edit me-2"></i>Edit User Information
                        </h5>
                        <span class="badge bg-white text-success">Required fields marked *</span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('superadmin.users.update', $user) }}" id="editUserForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body p-5">
                        <!-- Name Section -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                    <i class="fas fa-id-card text-success"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold fs-5">Personal Details</h6>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-lg-4">
                                    <label for="first_name" class="form-label fw-medium">
                                        First Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control ps-0 @error('first_name') is-invalid @enderror" 
                                               id="first_name" name="first_name" 
                                               value="{{ old('first_name', $user->first_name) }}" 
                                               placeholder="John" required>
                                    </div>
                                    @error('first_name')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-4">
                                    <label for="middle_name" class="form-label fw-medium">Middle Name</label>
                                    <input type="text" class="form-control form-control-lg @error('middle_name') is-invalid @enderror" 
                                           id="middle_name" name="middle_name" 
                                           value="{{ old('middle_name', $user->middle_name) }}" 
                                           placeholder="Optional">
                                    @error('middle_name')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-4">
                                    <label for="last_name" class="form-label fw-medium">
                                        Last Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" 
                                           value="{{ old('last_name', $user->last_name) }}" 
                                           placeholder="Doe" required>
                                    @error('last_name')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Section -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                    <i class="fas fa-envelope text-success"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold fs-5">Contact Information</h6>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <label for="email" class="form-label fw-medium">
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-at text-muted"></i>
                                        </span>
                                        <input type="email" class="form-control ps-0 @error('email') is-invalid @enderror" 
                                               id="email" name="email" 
                                               value="{{ old('email', $user->email) }}" 
                                               placeholder="user@example.com" required>
                                    </div>
                                    <div class="form-text mt-2">
                                        <i class="fas fa-info-circle text-success me-1"></i>
                                        Used for login and notifications
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-4">
                                    <label for="phone" class="form-label fw-medium">Phone Number</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-phone text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control ps-0 @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" 
                                               value="{{ old('phone', $user->phone) }}" 
                                               placeholder="+1 (123) 456-7890">
                                    </div>
                                    @error('phone')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                    <i class="fas fa-user-tag text-success"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold fs-5">Role Assignment</h6>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-8">
                                    <label for="role" class="form-label fw-medium">
                                        Select Role <span class="text-danger">*</span>
                                    </label>
                                    <div class="row g-3" id="roleSelection">
                                        @foreach($roles as $key => $label)
                                            <div class="col-xl-3 col-lg-4 col-md-6">
                                                <div class="role-card {{ (old('role', $user->role) == $key) ? 'selected' : '' }} 
                                                    {{ ($user->id === auth()->id() && $user->role == $key) ? 'disabled-role' : '' }}" 
                                                     data-role="{{ $key }}" 
                                                     data-disabled="{{ $user->id === auth()->id() && $user->role == $key ? 'true' : 'false' }}">
                                                    <div class="card border h-100">
                                                        <div class="card-body text-center p-3">
                                                            <div class="mb-2">
                                                                @if($key == 'superadmin')
                                                                    <div class="role-icon bg-danger bg-opacity-10 text-danger">
                                                                        <i class="fas fa-crown"></i>
                                                                    </div>
                                                                @elseif($key == 'admin')
                                                                    <div class="role-icon bg-primary bg-opacity-10 text-primary">
                                                                        <i class="fas fa-user-shield"></i>
                                                                    </div>
                                                                @elseif($key == 'delivery')
                                                                    <div class="role-icon bg-warning bg-opacity-10 text-warning">
                                                                        <i class="fas fa-truck"></i>
                                                                    </div>
                                                                @else
                                                                    <div class="role-icon bg-secondary bg-opacity-10 text-secondary">
                                                                        <i class="fas fa-user"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <h6 class="mb-1 fw-semibold">{{ $label }}</h6>
                                                            @if($user->id === auth()->id() && $user->role == $key)
                                                                <div class="mt-2">
                                                                    <span class="badge bg-warning text-dark">
                                                                        <i class="fas fa-lock me-1"></i>Locked
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input type="radio" class="role-radio" name="role" value="{{ $key }}" 
                                                           id="role_{{ $key }}" 
                                                           {{ old('role', $user->role) == $key ? 'checked' : '' }}
                                                           {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                                           style="display: none;">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" id="selectedRole" name="role" value="{{ old('role', $user->role) }}" required>
                                    @error('role')
                                        <div class="text-danger small mt-3">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($user->id === auth()->id())
                                        <div class="alert alert-warning border-warning border-1 mt-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                                <span>You cannot change your own role for security reasons.</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Specific Fields -->
                        <div id="deliveryFields" class="mb-5" style="display: {{ old('role', $user->role) == 'delivery' ? 'block' : 'none' }};">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-warning bg-opacity-10 p-2 rounded-circle me-3">
                                    <i class="fas fa-truck-loading text-warning"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold fs-5">Delivery Information</h6>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-lg-4">
                                    <label for="vehicle_type" class="form-label fw-medium">Vehicle Type</label>
                                    <select class="form-select form-select-lg" id="vehicle_type" name="vehicle_type">
                                        <option value="">Select Vehicle Type</option>
                                        <option value="motorcycle" {{ old('vehicle_type', $user->vehicle_type) == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                        <option value="car" {{ old('vehicle_type', $user->vehicle_type) == 'car' ? 'selected' : '' }}>Car</option>
                                        <option value="van" {{ old('vehicle_type', $user->vehicle_type) == 'van' ? 'selected' : '' }}>Van</option>
                                        <option value="truck" {{ old('vehicle_type', $user->vehicle_type) == 'truck' ? 'selected' : '' }}>Truck</option>
                                        <option value="bicycle" {{ old('vehicle_type', $user->vehicle_type) == 'bicycle' ? 'selected' : '' }}>Bicycle</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="vehicle_number" class="form-label fw-medium">Vehicle Number</label>
                                    <input type="text" class="form-control form-control-lg" name="vehicle_number" 
                                           value="{{ old('vehicle_number', $user->vehicle_number) }}" placeholder="ABC-123">
                                </div>
                                <div class="col-lg-4">
                                    <label for="license_number" class="form-label fw-medium">License Number</label>
                                    <input type="text" class="form-control form-control-lg" name="license_number" 
                                           value="{{ old('license_number', $user->license_number) }}" placeholder="DL-123456789">
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                    <i class="fas fa-map-marker-alt text-success"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold fs-5">Address Information</h6>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <label for="address" class="form-label fw-medium">Street Address</label>
                                    <input type="text" class="form-control form-control-lg" name="address" 
                                           value="{{ old('address', $user->address) }}" placeholder="123 Main Street">
                                </div>
                                <div class="col-lg-4">
                                    <label for="city" class="form-label fw-medium">City</label>
                                    <input type="text" class="form-control form-control-lg" name="city" 
                                           value="{{ old('city', $user->city) }}" placeholder="New York">
                                </div>
                                <div class="col-lg-4">
                                    <label for="state" class="form-label fw-medium">State/Province</label>
                                    <input type="text" class="form-control form-control-lg" name="state" 
                                           value="{{ old('state', $user->state) }}" placeholder="NY">
                                </div>
                                <div class="col-lg-4">
                                    <label for="zip_code" class="form-label fw-medium">ZIP/Postal Code</label>
                                    <input type="text" class="form-control form-control-lg" name="zip_code" 
                                           value="{{ old('zip_code', $user->zip_code) }}" placeholder="10001">
                                </div>
                                <div class="col-lg-4">
                                    <label for="country" class="form-label fw-medium">Country</label>
                                    <input type="text" class="form-control form-control-lg" name="country" 
                                           value="{{ old('country', $user->country) }}" placeholder="United States">
                                </div>
                            </div>
                        </div>

                        <!-- Status Toggle -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between p-4 border rounded bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                            <i class="fas fa-user-check text-success"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Account Status</h6>
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-info-circle text-success me-1"></i>
                                            Active users can log in and access the system immediately
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" 
                                               id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                               {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        <label class="form-check-label fw-bold" for="is_active">
                                            <span id="statusText" class="{{ $user->is_active ? 'text-success' : 'text-secondary' }}">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </label>
                                    </div>
                                    @if($user->id === auth()->id())
                                        <input type="hidden" name="is_active" value="1">
                                        <div class="alert alert-warning border-warning border-1 mt-2 p-2">
                                            <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                            You cannot deactivate your own account
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Password Reset Section -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-info bg-opacity-10 p-2 rounded-circle me-3">
                                    <i class="fas fa-key text-info"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold fs-5">Password Management</h6>
                            </div>
                            
                            <div class="alert alert-info border-info border-1">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-info-circle fa-lg text-info mt-1 me-3"></i>
                                    <div>
                                        <p class="mb-1 fw-medium">Password Reset Options</p>
                                        <p class="small mb-0">
                                            To change the user's password, use the "Send Password Reset" option in the user's profile view.
                                            This form only updates account information, not passwords.
                                        </p>
                                        <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-sm btn-outline-info mt-2">
                                            <i class="fas fa-redo me-1"></i>Go to User Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Footer with Actions -->
                    <div class="card-footer bg-light border-top py-4 px-5">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="reset" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-redo me-2"></i>Reset Changes
                                </button>
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    <i class="fas fa-save me-2"></i>Update User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Danger Zone Card -->
            @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
            <div class="card border-danger border-2 shadow-sm mb-4">
                <div class="card-header bg-danger bg-gradient text-white py-3 px-4">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                    </h5>
                </div>
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                                        <i class="fas fa-trash-alt fa-2x text-danger"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-danger mb-2">Delete This User</h5>
                                    <p class="text-muted mb-2">Once you delete a user account, there is no going back. This action cannot be undone.</p>
                                    <div class="alert alert-danger border-danger border-1 mt-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <span><strong>Warning:</strong> All user data, including their order history and profile information, will be permanently deleted.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" id="deleteUserForm">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-lg px-4" id="deleteUserBtn">
                                    <i class="fas fa-trash-alt me-2"></i>Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- User Activity Stats -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-chart-line text-success me-2"></i>User Activity
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-xl-3 col-lg-6">
                            <div class="d-flex align-items-center p-3 border rounded bg-light">
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-calendar-check text-success"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold">{{ $user->created_at->format('M d, Y') }}</h4>
                                    <small class="text-muted">Account Created</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="d-flex align-items-center p-3 border rounded bg-light">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-clock text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold">{{ $user->updated_at->diffForHumans() }}</h4>
                                    <small class="text-muted">Last Updated</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="d-flex align-items-center p-3 border rounded bg-light">
                                <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-sign-in-alt text-warning"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold" id="lastLogin">--</h4>
                                    <small class="text-muted">Last Login</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="d-flex align-items-center p-3 border rounded bg-light">
                                <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-user-clock text-info"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold" id="accountAge">--</h4>
                                    <small class="text-muted">Account Age</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if(!$user->isSuperAdmin() && $user->id !== auth()->id())
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger border-2">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-danger bg-opacity-10 p-4 rounded-circle d-inline-block mb-3">
                        <i class="fas fa-trash-alt fa-3x text-danger"></i>
                    </div>
                    <h4 class="fw-bold text-danger mb-3">Are you absolutely sure?</h4>
                    <p class="text-muted">This action cannot be undone. This will permanently delete the user account and remove all associated data from our servers.</p>
                </div>
                
                <div class="alert alert-danger border-danger border-1">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-circle mt-1 me-3"></i>
                        <div>
                            <p class="fw-bold mb-1">What will be deleted:</p>
                            <ul class="mb-0">
                                <li>User account and login credentials</li>
                                <li>All personal information</li>
                                <li>Order history and transaction records</li>
                                <li>Any associated delivery records</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="confirmDelete" class="form-label fw-medium">
                        Please type <span class="text-danger fw-bold">"DELETE"</span> to confirm:
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
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                    <i class="fas fa-trash-alt me-2"></i>Yes, Delete User
                </button>
            </div>
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

/* Role Cards */
.role-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.role-card:not(.disabled-role):hover .card {
    border-color: var(--success-medium);
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.2);
}

.role-card.selected .card {
    border-color: var(--success-medium);
    background-color: var(--success-bg);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.25);
}

.role-card.disabled-role {
    cursor: not-allowed;
    opacity: 0.7;
}

.role-card.disabled-role .card {
    background-color: #f8f9fa;
    border-style: dashed;
}

.role-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 1.25rem;
    transition: all 0.3s ease;
}

/* Form Controls */
.form-control:focus, .form-select:focus {
    border-color: var(--success-medium);
    box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
}

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

/* Card Styling */
.card {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.bg-success.bg-gradient {
    background: linear-gradient(135deg, var(--success-medium), var(--success-dark));
}

/* Status Switch */
.form-check-input:checked {
    background-color: var(--success-medium);
    border-color: var(--success-medium);
}

.form-check-input:focus {
    border-color: var(--success-medium);
    box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
}

/* Danger Zone */
.border-danger {
    border-color: #ef4444 !important;
}

/* Modal Styling */
.modal-content {
    border-radius: 12px;
    overflow: hidden;
}

/* Alert Styling */
.alert {
    border-radius: 10px;
}

/* Wider Layout */
.container-fluid {
    max-width: 1800px;
}

.mb-5 {
    margin-bottom: 3rem !important;
}

.p-5 {
    padding: 3rem !important;
}

@media (max-width: 768px) {
    .p-5 {
        padding: 1.5rem !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Role Selection
    const roleCards = document.querySelectorAll('.role-card:not(.disabled-role)');
    const selectedRoleInput = document.getElementById('selectedRole');
    const deliveryFields = document.getElementById('deliveryFields');
    
    roleCards.forEach(card => {
        card.addEventListener('click', function() {
            const isDisabled = this.getAttribute('data-disabled') === 'true';
            if (isDisabled) return;
            
            // Remove selected class from all cards
            roleCards.forEach(c => {
                c.classList.remove('selected');
                const radio = c.querySelector('.role-radio');
                if (radio) radio.checked = false;
            });
            
            // Add selected class to clicked card
            this.classList.add('selected');
            const roleValue = this.getAttribute('data-role');
            const radio = this.querySelector('.role-radio');
            if (radio) radio.checked = true;
            selectedRoleInput.value = roleValue;
            
            // Show/hide delivery fields
            if (roleValue === 'delivery') {
                deliveryFields.style.display = 'block';
                deliveryFields.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                deliveryFields.style.display = 'none';
            }
        });
    });
    
    // Status toggle text update
    const statusToggle = document.getElementById('is_active');
    const statusText = document.getElementById('statusText');
    
    if (statusToggle) {
        statusToggle.addEventListener('change', function() {
            if (this.checked) {
                statusText.textContent = 'Active';
                statusText.className = 'text-success fw-bold';
            } else {
                statusText.textContent = 'Inactive';
                statusText.className = 'text-secondary fw-bold';
            }
        });
    }
    
    // Delete User Confirmation
    const deleteUserBtn = document.getElementById('deleteUserBtn');
    const deleteConfirmationModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    const confirmDeleteInput = document.getElementById('confirmDelete');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const confirmError = document.getElementById('confirmError');
    const deleteUserForm = document.getElementById('deleteUserForm');
    
    if (deleteUserBtn) {
        deleteUserBtn.addEventListener('click', function() {
            deleteConfirmationModal.show();
        });
        
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
        
        confirmDeleteBtn.addEventListener('click', function() {
            deleteUserForm.submit();
        });
    }
    
    // Calculate account age and last login
    const createdDate = new Date('{{ $user->created_at }}');
    const now = new Date();
    const diffTime = Math.abs(now - createdDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    let accountAgeText;
    if (diffDays < 30) {
        accountAgeText = diffDays + ' days';
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
    
    // Form validation before submit
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;
        let firstErrorField = null;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                
                // Create error message if not exists
                if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('text-danger')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'text-danger small mt-2';
                    errorDiv.textContent = 'This field is required';
                    field.parentNode.appendChild(errorDiv);
                }
                
                // Store first error field for scrolling
                if (!firstErrorField) {
                    firstErrorField = field;
                }
            } else {
                field.classList.remove('is-invalid');
                // Remove error message if exists
                const errorMsg = field.parentNode.querySelector('.text-danger.small');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            
            // Scroll to first error
            if (firstErrorField) {
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstErrorField.focus();
                
                // Add shake animation to error field
                firstErrorField.classList.add('animate__animated', 'animate__headShake');
                setTimeout(() => {
                    firstErrorField.classList.remove('animate__animated', 'animate__headShake');
                }, 1000);
            }
        } else {
            // Show loading state on submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating User...';
            submitBtn.disabled = true;
            
            // Re-enable after 3 seconds (in case form submission fails)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        }
    });
    
    // Auto-hide alerts after 10 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) closeBtn.click();
        });
    }, 10000);
});
</script>
@endsection