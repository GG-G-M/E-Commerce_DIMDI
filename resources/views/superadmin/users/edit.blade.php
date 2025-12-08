@extends('layouts.superadmin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="dashboard-header mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('superadmin.users.index') }}">Users</a></li>
                <li class="breadcrumb-item"><a href="{{ route('superadmin.users.show', $user) }}">{{ $user->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">
                    <i class="fas fa-user-edit text-success me-2"></i>Edit User: {{ $user->name }}
                </h1>
                <p class="text-muted mb-0">Update user information, role, and permissions</p>
            </div>
            <div class="header-buttons">
                <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-outline-success-custom">
                    <i class="fas fa-eye me-2"></i>View
                </a>
                <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-success-custom">
                    <i class="fas fa-arrow-left me-2"></i>Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if($user->isSuperAdmin() && $user->id !== auth()->id())
    <div class="alert alert-warning border-warning border-2 alert-dismissible fade show shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="fas fa-exclamation-triangle fa-lg text-warning"></i>
            </div>
            <div>
                <h6 class="alert-heading mb-1">Super Admin Edit</h6>
                <p class="mb-0 small">You are editing another Super Admin's account. This action requires caution as it may affect system security.</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($user->id === auth()->id())
    <div class="alert alert-info border-info border-2 alert-dismissible fade show shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="fas fa-user-circle fa-lg text-info"></i>
            </div>
            <div>
                <h6 class="alert-heading mb-1">Editing Your Own Account</h6>
                <p class="mb-0 small">You are editing your own account. Some restrictions apply for security reasons.</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row justify-content-center">
        <!-- Main Content -->
        <div class="col-xl-10 col-lg-12">
            <!-- User Summary Card -->
            <div class="card card-custom mb-4">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    @if($user->isSuperAdmin())
                                        <div class="bg-danger bg-opacity-10 p-2 rounded-circle">
                                            <i class="fas fa-crown text-danger"></i>
                                        </div>
                                    @elseif($user->isAdmin())
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle">
                                            <i class="fas fa-user-shield text-primary"></i>
                                        </div>
                                    @elseif($user->isDelivery())
                                        <div class="bg-warning bg-opacity-10 p-2 rounded-circle">
                                            <i class="fas fa-truck text-warning"></i>
                                        </div>
                                    @else
                                        <div class="bg-secondary bg-opacity-10 p-2 rounded-circle">
                                            <i class="fas fa-user text-secondary"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="mb-1 fw-bold">{{ $user->name }}</h4>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if($user->isSuperAdmin())
                                            <span class="badge-text badge-superadmin">Super Admin</span>
                                        @elseif($user->isAdmin())
                                            <span class="badge-text badge-admin">Admin</span>
                                        @elseif($user->isDelivery())
                                            <span class="badge-text badge-delivery">Delivery</span>
                                        @else
                                            <span class="badge-text badge-customer">Customer</span>
                                        @endif
                                        <span class="status-text {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                            <i class="fas fa-calendar me-1"></i>{{ $user->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="d-flex flex-column">
                                <span class="text-muted small">Last Updated</span>
                                <span class="fw-bold text-success">{{ $user->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="card card-custom mb-4">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>Edit User Information
                        </h5>
                        <span class="badge bg-white text-success border border-success border-opacity-25">
                            Required fields marked <span class="text-required">*</span>
                        </span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('superadmin.users.update', $user) }}" id="editUserForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body p-4 p-md-5">
                        <!-- Name Section -->
                        <div class="form-section mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="section-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold">Personal Details</h6>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <label for="first_name" class="form-label">
                                        First Name <span class="text-required">*</span>
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
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control form-control-lg @error('middle_name') is-invalid @enderror" 
                                           id="middle_name" name="middle_name" 
                                           value="{{ old('middle_name', $user->middle_name) }}" 
                                           placeholder="Optional">
                                    @error('middle_name')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-4">
                                    <label for="last_name" class="form-label">
                                        Last Name <span class="text-required">*</span>
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
                        <div class="form-section mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="section-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold">Contact Information</h6>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-lg-8">
                                    <label for="email" class="form-label">
                                        Email Address <span class="text-required">*</span>
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
                                    <label for="phone" class="form-label">Phone Number</label>
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
                        <div class="form-section mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="section-icon">
                                    <i class="fas fa-user-tag"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold">Role Assignment</h6>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-8">
                                    <label for="role" class="form-label">
                                        Select Role <span class="text-required">*</span>
                                    </label>
                                    <div class="row g-2" id="roleSelection">
                                        @foreach($roles as $key => $label)
                                            <div class="col-xl-3 col-lg-4 col-md-6">
                                                <div class="role-card {{ (old('role', $user->role) == $key) ? 'selected' : '' }} 
                                                    {{ ($user->id === auth()->id() && $user->role == $key) ? 'disabled-role' : '' }}" 
                                                     data-role="{{ $key }}" 
                                                     data-disabled="{{ $user->id === auth()->id() && $user->role == $key ? 'true' : 'false' }}">
                                                    <div class="card border h-100">
                                                        <div class="card-body text-center p-2">
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
                                                            <h6 class="mb-1 fw-semibold small">{{ $label }}</h6>
                                                            @if($user->id === auth()->id() && $user->role == $key)
                                                                <div class="mt-2">
                                                                    <span class="badge bg-warning text-dark border border-warning border-opacity-25">
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
                                        <div class="alert alert-warning border-warning border-1 mt-3 p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                                <span class="small">You cannot change your own role for security reasons.</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Specific Fields -->
                        <div id="deliveryFields" class="form-section mb-4" style="display: {{ old('role', $user->role) == 'delivery' ? 'block' : 'none' }};">
                            <div class="d-flex align-items-center mb-3">
                                <div class="section-icon">
                                    <i class="fas fa-truck-loading"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold">Delivery Information</h6>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <label for="vehicle_type" class="form-label">Vehicle Type</label>
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
                                    <label for="vehicle_number" class="form-label">Vehicle Number</label>
                                    <input type="text" class="form-control form-control-lg" name="vehicle_number" 
                                           value="{{ old('vehicle_number', $user->vehicle_number) }}" placeholder="ABC-123">
                                </div>
                                <div class="col-lg-4">
                                    <label for="license_number" class="form-label">License Number</label>
                                    <input type="text" class="form-control form-control-lg" name="license_number" 
                                           value="{{ old('license_number', $user->license_number) }}" placeholder="DL-123456789">
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="form-section mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="section-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold">Address Information</h6>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-lg-8">
                                    <label for="address" class="form-label">Street Address</label>
                                    <input type="text" class="form-control form-control-lg" name="address" 
                                           value="{{ old('address', $user->address) }}" placeholder="123 Main Street">
                                </div>
                                <div class="col-lg-4">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control form-control-lg" name="city" 
                                           value="{{ old('city', $user->city) }}" placeholder="New York">
                                </div>
                                <div class="col-lg-4">
                                    <label for="state" class="form-label">State/Province</label>
                                    <input type="text" class="form-control form-control-lg" name="state" 
                                           value="{{ old('state', $user->state) }}" placeholder="NY">
                                </div>
                                <div class="col-lg-4">
                                    <label for="zip_code" class="form-label">ZIP/Postal Code</label>
                                    <input type="text" class="form-control form-control-lg" name="zip_code" 
                                           value="{{ old('zip_code', $user->zip_code) }}" placeholder="10001">
                                </div>
                                <div class="col-lg-4">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control form-control-lg" name="country" 
                                           value="{{ old('country', $user->country) }}" placeholder="United States">
                                </div>
                            </div>
                        </div>

                        <!-- Status Toggle -->
                        <div class="form-section mb-4">
                            <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="section-icon">
                                            <i class="fas fa-user-check"></i>
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
                                            <span id="statusText" class="status-text {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </label>
                                    </div>
                                    @if($user->id === auth()->id())
                                        <input type="hidden" name="is_active" value="1">
                                        <div class="alert alert-warning border-warning border-1 mt-2 p-2">
                                            <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                            <span class="small">You cannot deactivate your own account</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Password Reset Section -->
                        <div class="form-section mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="section-icon">
                                    <i class="fas fa-key"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold">Password Management</h6>
                            </div>
                            
                            <div class="alert alert-info border-info border-1">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-info-circle text-info mt-1 me-3"></i>
                                    <div>
                                        <p class="mb-1 fw-medium">Password Reset Options</p>
                                        <p class="small mb-2">
                                            To change the user's password, use the "Send Password Reset" option in the user's profile view.
                                            This form only updates account information, not passwords.
                                        </p>
                                        <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-redo me-1"></i>Go to User Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Footer with Actions -->
                    <div class="card-footer bg-light border-top py-4 px-4 px-md-5">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-outline-success-custom btn-lg">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="reset" class="btn btn-outline-success-custom btn-lg">
                                    <i class="fas fa-redo me-2"></i>Reset Changes
                                </button>
                                <button type="submit" class="btn btn-success-custom btn-lg px-5">
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
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <div class="bg-danger bg-opacity-10 p-2 rounded-circle">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-danger mb-2">Delete This User</h5>
                                    <p class="text-muted small mb-2">Once you delete a user account, there is no going back. This action cannot be undone.</p>
                                    <div class="alert alert-danger border-danger border-1 mt-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <span class="small"><strong>Warning:</strong> All user data, including their order history and profile information, will be permanently deleted.</span>
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
            <div class="card card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>User Activity
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-xl-3 col-lg-6">
                            <div class="d-flex align-items-center p-3 border rounded bg-light">
                                <div class="bg-success-custom p-2 rounded-circle me-3">
                                    <i class="fas fa-calendar-check text-success"></i>
                                </div>
                                <div>
                                    <h4 class="stats-number mb-0">{{ $user->created_at->format('M d, Y') }}</h4>
                                    <small class="stats-label">Account Created</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="d-flex align-items-center p-3 border rounded bg-light">
                                <div class="bg-primary-custom p-2 rounded-circle me-3">
                                    <i class="fas fa-clock text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="stats-number mb-0">{{ $user->updated_at->diffForHumans() }}</h4>
                                    <small class="stats-label">Last Updated</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="d-flex align-items-center p-3 border rounded bg-light">
                                <div class="bg-warning-custom p-2 rounded-circle me-3">
                                    <i class="fas fa-sign-in-alt text-warning"></i>
                                </div>
                                <div>
                                    <h4 class="stats-number mb-0" id="lastLogin">--</h4>
                                    <small class="stats-label">Last Login</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="d-flex align-items-center p-3 border rounded bg-light">
                                <div class="bg-info-custom p-2 rounded-circle me-3">
                                    <i class="fas fa-user-clock text-info"></i>
                                </div>
                                <div>
                                    <h4 class="stats-number mb-0" id="accountAge">--</h4>
                                    <small class="stats-label">Account Age</small>
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
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                        <i class="fas fa-trash-alt fa-2x text-danger"></i>
                    </div>
                    <h4 class="fw-bold text-danger mb-3">Are you absolutely sure?</h4>
                    <p class="text-muted small">This action cannot be undone. This will permanently delete the user account and remove all associated data from our servers.</p>
                </div>
                
                <div class="alert alert-danger border-danger border-1">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-circle mt-1 me-2"></i>
                        <div>
                            <p class="fw-bold small mb-1">What will be deleted:</p>
                            <ul class="small mb-0">
                                <li>User account and login credentials</li>
                                <li>All personal information</li>
                                <li>Order history and transaction records</li>
                                <li>Any associated delivery records</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="confirmDelete" class="form-label fw-medium small">
                        Please type <span class="text-danger fw-bold">"DELETE"</span> to confirm:
                    </label>
                    <input type="text" class="form-control form-control-lg" id="confirmDelete" 
                           placeholder="Type DELETE here" autocomplete="off">
                    <div class="form-text text-danger small" id="confirmError"></div>
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
    /* === Consistent Green Theme === */
    :root {
        --green-primary: #2C8F0C;
        --green-secondary: #4CAF50;
        --green-light: #E8F5E6;
        --green-lighter: #F8FDF8;
        --gray-light: #f8f9fa;
        --gray-medium: #6c757d;
        --gray-dark: #495057;
        --danger: #C62828;
        --warning: #856404;
    }

    /* Dashboard Header */
    .dashboard-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-left: 4px solid var(--green-primary);
    }

    /* Card Styling - Consistent with theme */
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        background: white;
    }

    .card-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--green-primary), var(--green-secondary));
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px !important;
        border-top-right-radius: 12px !important;
        padding: 1rem 1.5rem;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Header Buttons */
    .header-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    /* Role Cards */
    .role-card {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .role-card:not(.disabled-role):hover .card {
        border-color: var(--green-primary);
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(44, 143, 12, 0.2);
    }

    .role-card.selected .card {
        border-color: var(--green-primary);
        background-color: var(--green-light);
        box-shadow: 0 4px 15px rgba(44, 143, 12, 0.25);
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
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    /* Button Styles - Consistent */
    .btn-success-custom {
        background: linear-gradient(135deg, var(--green-primary), var(--green-secondary));
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(44, 143, 12, 0.2);
    }
    
    .btn-success-custom:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
        color: white;
    }

    .btn-outline-success-custom {
        background: white;
        border: 2px solid var(--green-primary);
        color: var(--green-primary);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-success-custom:hover {
        background: var(--green-primary);
        color: white;
        transform: translateY(-2px);
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

    /* Form Controls */
    .form-control-lg, .form-select-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 8px;
        border: 1px solid #C8E6C9;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--green-primary);
        box-shadow: 0 0 0 0.25rem rgba(44, 143, 12, 0.2);
    }

    .input-group-lg > .form-control,
    .input-group-lg > .form-select {
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }

    /* Background Color Classes */
    .bg-success-custom {
        background-color: var(--green-light) !important;
    }

    .bg-warning-custom {
        background-color: rgba(245, 158, 11, 0.1) !important;
    }

    .bg-primary-custom {
        background-color: rgba(59, 130, 246, 0.1) !important;
    }

    .bg-info-custom {
        background-color: rgba(6, 182, 212, 0.1) !important;
    }

    /* Badges - Consistent with theme */
    .badge-text {
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        display: inline-block;
        text-align: center;
        min-width: 80px;
    }
    
    .badge-superadmin {
        background-color: #FFEBEE;
        color: #C62828;
        border: 1px solid #FFCDD2;
    }
    
    .badge-admin {
        background-color: #E8F5E6;
        color: var(--green-primary);
        border: 1px solid #C8E6C9;
    }
    
    .badge-delivery {
        background-color: #FFF3CD;
        color: var(--warning);
        border: 1px solid #FFEAA7;
    }
    
    .badge-customer {
        background-color: #F8F9FA;
        color: var(--gray-dark);
        border: 1px solid #E9ECEF;
    }

    /* Status Badges */
    .status-text {
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        display: inline-block;
        text-align: center;
        min-width: 70px;
    }
    
    .status-active {
        background-color: var(--green-light);
        color: var(--green-primary);
        border: 1px solid #C8E6C9;
    }
    
    .status-inactive {
        background-color: #FFEBEE;
        color: var(--danger);
        border: 1px solid #FFCDD2;
    }

    /* Section Icons */
    .section-icon {
        background-color: var(--green-light);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .section-icon i {
        color: var(--green-primary);
        font-size: 1rem;
    }

    /* Breadcrumb */
    .breadcrumb {
        background: none;
        padding: 0;
        margin-bottom: 0.5rem;
    }

    .breadcrumb-item a {
        color: var(--green-primary);
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: var(--gray-medium);
    }

    /* Form Labels */
    .form-label {
        font-weight: 600;
        color: var(--gray-dark);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    /* Required field indicator */
    .text-required {
        color: var(--danger);
        font-weight: 700;
    }

    /* Form Help Text */
    .form-text {
        color: var(--gray-medium);
        font-size: 0.85rem;
    }

    /* Card Footer */
    .card-footer {
        background-color: var(--green-lighter);
        border-top: 1px solid var(--green-light);
    }

    /* Form Sections */
    .form-section {
        transition: all 0.3s ease;
        padding: 1.5rem;
        border-radius: 8px;
    }

    .form-section:hover {
        background-color: var(--green-lighter);
    }

    /* Stats Cards */
    .stats-card {
        background: linear-gradient(135deg, var(--green-light), var(--green-lighter));
        border: none;
        height: 100%;
    }

    .stats-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--green-primary);
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .stats-label {
        font-size: 0.85rem;
        color: var(--gray-medium);
        font-weight: 600;
    }

    /* Animation for form validation */
    @keyframes shake {
        0%, 100% {transform: translateX(0);}
        10%, 30%, 50%, 70%, 90% {transform: translateX(-5px);}
        20%, 40%, 60%, 80% {transform: translateX(5px);}
    }

    .is-invalid {
        border-color: var(--danger) !important;
        animation: shake 0.5s ease-in-out;
    }

    /* Danger Zone */
    .border-danger {
        border-color: var(--danger) !important;
    }

    /* Status Switch */
    .form-check-input {
        width: 3rem;
        height: 1.5rem;
    }

    .form-check-input:checked {
        background-color: var(--green-primary);
        border-color: var(--green-primary);
    }

    .form-check-input:focus {
        border-color: var(--green-primary);
        box-shadow: 0 0 0 0.25rem rgba(44, 143, 12, 0.2);
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

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .p-4, .p-5 {
            padding: 1rem !important;
        }
        
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        
        .section-icon {
            width: 36px;
            height: 36px;
            margin-right: 0.75rem;
        }
        
        .form-section {
            padding: 1rem !important;
        }
        
        .stats-number {
            font-size: 1.1rem;
        }
        
        .card-header-custom h5 {
            font-size: 1.1rem;
        }
        
        .header-buttons {
            flex-direction: column;
            gap: 5px;
            margin-top: 1rem;
        }
        
        .card-footer .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
        
        .card-footer .d-flex > div {
            width: 100%;
        }
    }

    /* Small mobile */
    @media (max-width: 576px) {
        .role-icon {
            width: 32px;
            height: 32px;
            font-size: 0.9rem;
        }
        
        .form-control-lg, .form-select-lg {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }
        
        .badge-text, .status-text {
            font-size: 0.7rem;
            min-width: 60px;
        }
        
        .modal-body p {
            font-size: 0.9rem;
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
                statusText.className = 'status-text status-active';
            } else {
                statusText.textContent = 'Inactive';
                statusText.className = 'status-text status-inactive';
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