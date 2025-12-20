@extends('layouts.superadmin')

@section('content')
<div class="container-fluid px-4">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-success-custom">
            <i class="fas fa-arrow-left me-2"></i>Back to Users
        </a>
    </div>

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
        <div class="col-12">
            <!-- Wizard Steps -->
            <div class="card card-custom mb-4">
                <div class="card-body p-4">
                    <div class="wizard-steps">
                        <div class="step active" data-step="1">
                            <div class="step-circle">1</div>
                            <div class="step-label">Basic Info</div>
                        </div>
                        <div class="step-line active"></div>
                        <div class="step" data-step="2">
                            <div class="step-circle">2</div>
                            <div class="step-label">Contact & Role</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step" data-step="3">
                            <div class="step-circle">3</div>
                            <div class="step-label">Address & Status</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step" data-step="4">
                            <div class="step-circle">4</div>
                            <div class="step-label">Review & Save</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wizard Form -->
            <form method="POST" action="{{ route('superadmin.users.update', $user) }}" id="editUserForm">
                @csrf
                @method('PUT')
                
                <!-- Step 1: Basic Information -->
                <div class="wizard-step active" id="step1">
                    <div class="card card-custom mb-4">
                        <div class="card-header-custom d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0">
                                    <i class="fas fa-id-card me-2"></i>Basic Information
                                </h5>
                                <p class="mb-0 text-white-50 small">Step 1 of 4: Update personal details</p>
                            </div>
                            <span class="badge bg-white text-success border border-success border-opacity-25">
                                Required fields marked <span class="text-required">*</span>
                            </span>
                        </div>
                        
                        <div class="card-body p-4 p-md-5">
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
                        </div>
                        
                        <div class="card-footer bg-light border-top py-4 px-4 px-md-5">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-success-custom btn-lg px-5 next-step" data-next="2">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Contact & Role -->
                <div class="wizard-step" id="step2">
                    <div class="card card-custom mb-4">
                        <div class="card-header-custom d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0">
                                    <i class="fas fa-envelope me-2"></i>Contact & Role
                                </h5>
                                <p class="mb-0 text-white-50 small">Step 2 of 4: Update contact information and role</p>
                            </div>
                            <span class="badge bg-white text-success border border-success border-opacity-25">
                                Required fields marked <span class="text-required">*</span>
                            </span>
                        </div>
                        
                        <div class="card-body p-4 p-md-5">
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
                                    <div class="col-lg-12">
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
                        </div>
                        
                        <div class="card-footer bg-light border-top py-4 px-4 px-md-5">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-success-custom btn-lg prev-step" data-prev="1">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </button>
                                <button type="button" class="btn btn-success-custom btn-lg px-5 next-step" data-next="3">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Address & Status -->
                <div class="wizard-step" id="step3">
                    <div class="card card-custom mb-4">
                        <div class="card-header-custom d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0">
                                    <i class="fas fa-map-marker-alt me-2"></i>Address & Status
                                </h5>
                                <p class="mb-0 text-white-50 small">Step 3 of 4: Update address and account status</p>
                            </div>
                            <span class="badge bg-white text-success border border-success border-opacity-25">
                                Required fields marked <span class="text-required">*</span>
                            </span>
                        </div>
                        
                        <div class="card-body p-4 p-md-5">
                            <!-- Address Information -->
                            <div class="form-section mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="section-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h6 class="mb-0 text-dark fw-bold">Address Information</h6>
                                </div>
                                
                                <div class="row g-3">
                                    <!-- Street Address -->
                                    <div class="col-lg-8">
                                        <label for="street_address" class="form-label">Street Address</label>
                                        <input type="text" class="form-control form-control-lg" name="street_address" 
                                               value="{{ old('street_address', $user->street_address) }}" placeholder="123 Main Street">
                                    </div>
                                    
                                    <!-- Province -->
                                    <div class="col-lg-4">
                                        <label for="province" class="form-label">Province <span class="text-required">*</span></label>
                                        <select id="province" name="province" class="form-control form-control-lg" required>
                                            <option value="">Select Province</option>
                                        </select>
                                    </div>
                                    
                                    <!-- City -->
                                    <div class="col-lg-4">
                                        <label for="city" class="form-label">City <span class="text-required">*</span></label>
                                        <select id="city" name="city" class="form-control form-control-lg" required>
                                            <option value="">Select City</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Barangay -->
                                    <div class="col-lg-4">
                                        <label for="barangay" class="form-label">Barangay <span class="text-required">*</span></label>
                                        <select id="barangay" name="barangay" class="form-control form-control-lg" required>
                                            <option value="">Select Barangay</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Hidden Region Field -->
                                    <input type="hidden" name="region" id="region">
                                    
                                    <!-- Country -->
                                    <div class="col-lg-4">
                                        <label for="country" class="form-label">Country <span class="text-required">*</span></label>
                                        <input type="text" class="form-control form-control-lg" name="country" 
                                               value="{{ old('country', $user->country ?? 'Philippines') }}" required readonly>
                                    </div>
                                    
                                    <!-- ZIP/Postal Code -->
                                    <div class="col-lg-4">
                                        <label for="zip_code" class="form-label">ZIP/Postal Code</label>
                                        <input type="text" class="form-control form-control-lg" name="zip_code" 
                                               value="{{ old('zip_code', $user->zip_code) }}" placeholder="10001">
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

                            <!-- Password Management -->
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
                        
                        <div class="card-footer bg-light border-top py-4 px-4 px-md-5">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-success-custom btn-lg prev-step" data-prev="2">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </button>
                                <button type="button" class="btn btn-success-custom btn-lg px-5 next-step" data-next="4">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Review & Save -->
                <div class="wizard-step" id="step4">
                    <div class="card card-custom mb-4">
                        <div class="card-header-custom d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0">
                                    <i class="fas fa-clipboard-check me-2"></i>Review & Save Changes
                                </h5>
                                <p class="mb-0 text-white-50 small">Step 4 of 4: Review changes before updating</p>
                            </div>
                            <span class="badge bg-white text-success border border-success border-opacity-25">
                                Final Step
                            </span>
                        </div>
                        
                        <div class="card-body p-4 p-md-5">
                            <div class="alert alert-success-custom border border-success border-opacity-25 mb-5">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success fs-4 me-3"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Review Your Changes</h6>
                                        <p class="mb-0 text-success">Please review all updates before saving changes.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Review Summary -->
                            <div class="review-summary">
                                <h6 class="mb-4 fw-bold text-dark">Updated Information Summary</h6>
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="review-section mb-4">
                                            <h6 class="text-success mb-3 fw-bold">
                                                <i class="fas fa-user me-2"></i>Personal Details
                                            </h6>
                                            <div class="review-item mb-2">
                                                <span class="text-muted small">Full Name:</span>
                                                <span class="fw-bold" id="reviewFullName">-</span>
                                            </div>
                                        </div>
                                        
                                        <div class="review-section mb-4">
                                            <h6 class="text-success mb-3 fw-bold">
                                                <i class="fas fa-envelope me-2"></i>Contact Information
                                            </h6>
                                            <div class="review-item mb-2">
                                                <span class="text-muted small">Email:</span>
                                                <span class="fw-bold" id="reviewEmail">-</span>
                                            </div>
                                            <div class="review-item mb-2">
                                                <span class="text-muted small">Phone:</span>
                                                <span class="fw-bold" id="reviewPhone">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="review-section mb-4">
                                            <h6 class="text-success mb-3 fw-bold">
                                                <i class="fas fa-user-tag me-2"></i>Role & Access
                                            </h6>
                                            <div class="review-item mb-2">
                                                <span class="text-muted small">Assigned Role:</span>
                                                <span class="fw-bold" id="reviewRole">-</span>
                                            </div>
                                            <div class="review-item mb-2">
                                                <span class="text-muted small">Account Status:</span>
                                                <span class="fw-bold" id="reviewStatus">-</span>
                                            </div>
                                        </div>
                                        
                                        <div class="review-section mb-4">
                                            <h6 class="text-success mb-3 fw-bold">
                                                <i class="fas fa-map-marker-alt me-2"></i>Address
                                            </h6>
                                            <div class="review-item mb-2">
                                                <span class="text-muted small">Address:</span>
                                                <span class="fw-bold" id="reviewAddress">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Warning Alert for Self-Editing -->
                                @if($user->id === auth()->id())
                                <div class="alert alert-warning border-warning border-1 mt-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                        <div>
                                            <p class="mb-1 fw-semibold">Editing Your Own Account</p>
                                            <p class="small mb-0">You are making changes to your own account. Note that role changes and account deactivation are restricted for security reasons.</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card-footer bg-light border-top py-4 px-4 px-md-5">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <button type="button" class="btn btn-outline-success-custom btn-lg prev-step" data-prev="3">
                                        <i class="fas fa-arrow-left me-2"></i>Back
                                    </button>
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
                    </div>
                </div>
            </form>
            
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

    /* Wizard Steps */
    .wizard-steps {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 100px;
        position: relative;
        opacity: 0.5;
        transition: all 0.3s ease;
    }

    .step.active {
        opacity: 1;
    }

    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #e9ecef;
        border: 3px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--gray-medium);
        transition: all 0.3s ease;
        margin-bottom: 10px;
    }

    .step.active .step-circle {
        background: var(--green-light);
        border-color: var(--green-primary);
        color: var(--green-primary);
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.2);
    }

    .step-label {
        font-size: 0.9rem;
        color: var(--gray-medium);
        font-weight: 500;
        white-space: nowrap;
        text-align: center;
    }

    .step.active .step-label {
        color: var(--green-primary);
        font-weight: 700;
    }

    .step-line {
        flex: 1;
        height: 3px;
        background: #e9ecef;
        min-width: 40px;
        max-width: 100px;
        transition: all 0.3s ease;
    }

    .step-line.active {
        background: var(--green-primary);
    }

    /* Wizard Steps Container */
    .wizard-step {
        display: none;
        animation: fadeIn 0.5s ease;
    }

    .wizard-step.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
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

    /* Alert */
    .alert-success-custom {
        background-color: var(--green-lighter);
        border-left: 4px solid var(--green-primary);
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

    /* Review Summary */
    .review-summary {
        background: var(--green-lighter);
        border-radius: 12px;
        padding: 2rem;
    }

    .review-section {
        padding: 1.5rem;
        background: white;
        border-radius: 8px;
        border: 1px solid var(--green-light);
    }

    .review-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .wizard-steps {
            gap: 5px;
        }
        
        .step {
            min-width: 80px;
        }
        
        .step-circle {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        
        .step-label {
            font-size: 0.8rem;
        }
        
        .step-line {
            min-width: 20px;
            max-width: 30px;
        }
        
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
        
        .card-footer .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
        
        .card-footer .d-flex > div {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .wizard-steps {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .step {
            flex-direction: row;
            min-width: auto;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .step-circle {
            margin-bottom: 0;
            margin-right: 15px;
        }
        
        .step-line {
            display: none;
        }
        
        .form-control-lg, .form-select-lg {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wizard Navigation
    const steps = document.querySelectorAll('.wizard-step');
    const stepIndicators = document.querySelectorAll('.step');
    const stepLines = document.querySelectorAll('.step-line');
    
    function goToStep(stepNumber) {
        // Hide all steps
        steps.forEach(step => step.classList.remove('active'));
        // Show target step
        document.getElementById(`step${stepNumber}`).classList.add('active');
        
        // Update step indicators
        stepIndicators.forEach((indicator, index) => {
            indicator.classList.remove('active');
            if (index < stepNumber) {
                indicator.classList.add('active');
            }
        });
        
        // Update step lines
        stepLines.forEach((line, index) => {
            line.classList.remove('active');
            if (index < stepNumber - 1) {
                line.classList.add('active');
            }
        });
        
        // Update review summary if on step 4
        if (stepNumber === 4) {
            updateReviewSummary();
        }
        
        // Scroll to top of step
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    // Next step buttons
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function() {
            const nextStep = this.getAttribute('data-next');
            const currentStep = nextStep - 1;
            
            // Validate current step before proceeding
            if (validateStep(currentStep)) {
                goToStep(parseInt(nextStep));
            }
        });
    });
    
    // Previous step buttons
    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function() {
            const prevStep = this.getAttribute('data-prev');
            goToStep(parseInt(prevStep));
        });
    });
    
    // Step validation
    function validateStep(stepNumber) {
        let isValid = true;
        let firstErrorField = null;
        
        switch(stepNumber) {
            case 1:
                // Validate basic info
                const firstName = document.getElementById('first_name');
                const lastName = document.getElementById('last_name');
                
                if (!firstName.value.trim()) {
                    isValid = false;
                    showError(firstName, 'First name is required');
                    firstErrorField = firstName;
                }
                
                if (!lastName.value.trim()) {
                    isValid = false;
                    showError(lastName, 'Last name is required');
                    if (!firstErrorField) firstErrorField = lastName;
                }
                break;
                
            case 2:
                // Validate contact and role
                const email = document.getElementById('email');
                
                if (!email.value.trim()) {
                    isValid = false;
                    showError(email, 'Email is required');
                    firstErrorField = email;
                } else if (!isValidEmail(email.value)) {
                    isValid = false;
                    showError(email, 'Please enter a valid email address');
                    firstErrorField = email;
                }
                
                // Check role selection
                const selectedRole = document.getElementById('selectedRole');
                if (!selectedRole.value) {
                    isValid = false;
                    alert('Please select a role for the user');
                    return false;
                }
                break;
                
            case 3:
                // Validate address fields
                const province = document.getElementById('province');
                const city = document.getElementById('city');
                const barangay = document.getElementById('barangay');
                
                if (province && !province.value) {
                    isValid = false;
                    showError(province, 'Province is required');
                    if (!firstErrorField) firstErrorField = province;
                }
                
                if (city && !city.value) {
                    isValid = false;
                    showError(city, 'City is required');
                    if (!firstErrorField) firstErrorField = city;
                }
                
                if (barangay && !barangay.value) {
                    isValid = false;
                    showError(barangay, 'Barangay is required');
                    if (!firstErrorField) firstErrorField = barangay;
                }
                break;
        }
        
        if (!isValid && firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstErrorField.focus();
            firstErrorField.classList.add('animate__animated', 'animate__headShake');
            setTimeout(() => {
                firstErrorField.classList.remove('animate__animated', 'animate__headShake');
            }, 1000);
        }
        
        return isValid;
    }
    
    function showError(field, message) {
        field.classList.add('is-invalid');
        // Remove existing error message
        const existingError = field.parentNode.querySelector('.text-danger.small');
        if (existingError) {
            existingError.remove();
        }
        
        // Add new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-danger small mt-2';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
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
    
    // Update review summary
    function updateReviewSummary() {
        const firstName = document.getElementById('first_name').value;
        const middleName = document.getElementById('middle_name').value;
        const lastName = document.getElementById('last_name').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const address = document.querySelector('input[name="address"]').value;
        const city = document.querySelector('input[name="city"]').value;
        const state = document.querySelector('input[name="state"]').value;
        const country = document.querySelector('input[name="country"]').value;
        const role = selectedRoleInput.value;
        const isActive = document.getElementById('is_active').checked;
        
        // Update review fields
        document.getElementById('reviewFullName').textContent = 
            `${firstName} ${middleName ? middleName + ' ' : ''}${lastName}`;
        
        document.getElementById('reviewEmail').textContent = email || '-';
        document.getElementById('reviewPhone').textContent = phone || '-';
        
        // Get role label
        const roleCard = document.querySelector(`.role-card[data-role="${role}"]`);
        let roleLabel = '-';
        if (roleCard) {
            const roleName = roleCard.querySelector('h6');
            if (roleName) roleLabel = roleName.textContent;
        }
        document.getElementById('reviewRole').textContent = roleLabel;
        
        document.getElementById('reviewStatus').textContent = isActive ? 'Active' : 'Inactive';
        document.getElementById('reviewStatus').className = isActive ? 'status-text status-active' : 'status-text status-inactive';
        
        // Build address string
        let addressParts = [];
        if (address) addressParts.push(address);
        
        // Get selected dropdown values for address
        const barangaySelect = document.getElementById('barangay');
        const citySelect = document.getElementById('city');
        const provinceSelect = document.getElementById('province');
        
        if (barangaySelect && barangaySelect.options[barangaySelect.selectedIndex]) {
            const barangayText = barangaySelect.options[barangaySelect.selectedIndex].text;
            if (barangayText && barangayText !== 'Select Barangay') {
                addressParts.push(barangayText);
            }
        }
        
        if (citySelect && citySelect.options[citySelect.selectedIndex]) {
            const cityText = citySelect.options[citySelect.selectedIndex].text;
            if (cityText && cityText !== 'Select City') {
                addressParts.push(cityText);
            }
        }
        
        if (provinceSelect && provinceSelect.options[provinceSelect.selectedIndex]) {
            const provinceText = provinceSelect.options[provinceSelect.selectedIndex].text;
            if (provinceText && provinceText !== 'Select Province') {
                addressParts.push(provinceText);
            }
        }
        
        if (country) addressParts.push(country);
        
        document.getElementById('reviewAddress').textContent = 
            addressParts.length > 0 ? addressParts.join(', ') : '-';
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
    
    // Form submission
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate all steps
        for (let i = 1; i <= 4; i++) {
            if (!validateStep(i)) {
                alert('Please complete all required fields before submitting.');
                goToStep(i);
                return;
            }
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating User...';
        submitBtn.disabled = true;
        
        // Submit form
        setTimeout(() => {
            this.submit();
        }, 1000);
    });
    
    // Form reset
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        if (confirm('Are you sure you want to reset the form? All changes will be lost.')) {
            // Reset to step 1
            goToStep(1);
            
            // Reset role selection
            roleCards.forEach(card => {
                card.classList.remove('selected');
                const radio = card.querySelector('.role-radio');
                if (radio) radio.checked = false;
            });
            
            // Select original role
            const originalRole = '{{ $user->role }}';
            const originalRoleCard = document.querySelector(`.role-card[data-role="${originalRole}"]`);
            if (originalRoleCard) {
                originalRoleCard.classList.add('selected');
                const radio = originalRoleCard.querySelector('.role-radio');
                if (radio) radio.checked = true;
                selectedRoleInput.value = originalRole;
            }
            
            // Show/hide delivery fields
            if (originalRole === 'delivery') {
                deliveryFields.style.display = 'block';
            } else {
                deliveryFields.style.display = 'none';
            }
            
            // Reset status
            const originalStatus = {{ $user->is_active ? 'true' : 'false' }};
            if (statusToggle) {
                statusToggle.checked = originalStatus;
                statusText.textContent = originalStatus ? 'Active' : 'Inactive';
                statusText.className = originalStatus ? 'status-text status-active' : 'status-text status-inactive';
            }
            
            // Reset review summary
            updateReviewSummary();
        }
    });
    
    // Address API functionality
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const barangaySelect = document.getElementById('barangay');
    const regionInput = document.getElementById('region');

    // Fetch provinces
    if (provinceSelect) {
        fetch('/address/provinces')
            .then(res => res.json())
            .then(data => {
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.code;
                    option.dataset.region = province.region || '';
                    option.text = province.name;
                    provinceSelect.appendChild(option);
                });
                
                // If there's an existing province value, set it and trigger loading of cities
                const existingProvince = '{{ old('province', $user->province ?? '') }}';
                if (existingProvince) {
                    provinceSelect.value = existingProvince;
                    provinceSelect.dispatchEvent(new Event('change'));
                }
            });
    }

    // Fetch cities when province is selected
    if (provinceSelect) {
        provinceSelect.addEventListener('change', function() {
            const provinceCode = this.value;
            regionInput.value = this.options[this.selectedIndex].dataset.region || '';
            citySelect.innerHTML = '<option value="">Select City</option>';
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            
            if (provinceCode) {
                fetch(`/address/cities/${provinceCode}`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.code;
                            option.text = city.name;
                            citySelect.appendChild(option);
                        });
                        
                        // If there's an existing city value, set it and trigger loading of barangays
                        const existingCity = '{{ old('city', $user->city ?? '') }}';
                        if (existingCity) {
                            citySelect.value = existingCity;
                            citySelect.dispatchEvent(new Event('change'));
                        }
                    });
            }
        });
    }

    // Fetch barangays when city is selected
    if (citySelect) {
        citySelect.addEventListener('change', function() {
            const cityCode = this.value;
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            
            if (cityCode) {
                fetch(`/address/barangays/${cityCode}`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(barangay => {
                            const option = document.createElement('option');
                            option.value = barangay.code;
                            option.text = barangay.name;
                            barangaySelect.appendChild(option);
                        });
                        
                        // If there's an existing barangay value, set it
                        const existingBarangay = '{{ old('barangay', $user->barangay ?? '') }}';
                        if (existingBarangay) {
                            barangaySelect.value = existingBarangay;
                        }
                    });
            }
        });
    }

    // Initialize
    goToStep(1);
    
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