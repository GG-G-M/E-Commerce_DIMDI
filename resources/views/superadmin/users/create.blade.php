@extends('layouts.superadmin')

@section('content')
<div class="container-fluid px-4">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-success-custom">
            <i class="fas fa-arrow-left me-2"></i>Back to Users
        </a>
    </div>

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
                            <div class="step-label">Contact & Security</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step" data-step="3">
                            <div class="step-circle">3</div>
                            <div class="step-label">Role & Address</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step" data-step="4">
                            <div class="step-circle">4</div>
                            <div class="step-label">Review & Create</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wizard Form -->
            <form method="POST" action="{{ route('superadmin.users.store') }}" id="createUserForm">
                @csrf
                
                <!-- Step 1: Basic Information -->
                <div class="wizard-step active" id="step1">
                    <div class="card card-custom mb-4">
                        <div class="card-header-custom d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0">
                                    <i class="fas fa-user-circle me-2"></i>Basic Information
                                </h5>
                                <p class="mb-0 text-white-50 small">Step 1 of 4: Enter user's personal details</p>
                            </div>
                            <span class="badge bg-white text-success border border-success border-opacity-25">
                                Required fields marked <span class="text-required">*</span>
                            </span>
                        </div>
                        
                        <div class="card-body p-4 p-md-5">
                            <div class="form-section mb-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="section-icon">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <h6 class="mb-0 text-dark fw-bold fs-5">Personal Details</h6>
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
                                                   id="first_name" name="first_name" value="{{ old('first_name') }}" 
                                                   placeholder="John" required>
                                        </div>
                                        @error('first_name')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-lg-4">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control form-control-lg @error('middle_name') is-invalid @enderror" 
                                               id="middle_name" name="middle_name" value="{{ old('middle_name') }}" 
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
                                               id="last_name" name="last_name" value="{{ old('last_name') }}" 
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

                <!-- Step 2: Contact & Security -->
                <div class="wizard-step" id="step2">
                    <div class="card card-custom mb-4">
                        <div class="card-header-custom d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0">
                                    <i class="fas fa-envelope me-2"></i>Contact & Security
                                </h5>
                                <p class="mb-0 text-white-50 small">Step 2 of 4: Enter contact info and set password</p>
                            </div>
                            <span class="badge bg-white text-success border border-success border-opacity-25">
                                Required fields marked <span class="text-required">*</span>
                            </span>
                        </div>
                        
                        <div class="card-body p-4 p-md-5">
                            <!-- Contact Section -->
                            <div class="form-section mb-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="section-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <h6 class="mb-0 text-dark fw-bold fs-5">Contact Information</h6>
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
                                                   id="email" name="email" value="{{ old('email') }}" 
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
                                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                                   placeholder="+1 (123) 456-7890">
                                        </div>
                                        @error('phone')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Security Section -->
                            <div class="form-section mb-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="section-icon">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <h6 class="mb-0 text-dark fw-bold fs-5">Security & Access</h6>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <label for="password" class="form-label">
                                            Password <span class="text-required">*</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-key text-muted"></i>
                                            </span>
                                            <input type="password" class="form-control ps-0 @error('password') is-invalid @enderror" 
                                                   id="password" name="password" required>
                                            <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                                <i class="fas fa-eye ms-1"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength mt-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <small class="text-muted">Password strength:</small>
                                                <small id="strengthText" class="fw-bold">None</small>
                                            </div>
                                            <div class="progress">
                                                <div id="strengthBar" class="progress-bar rounded" role="progressbar" 
                                                     style="width: 0%; transition: width 0.3s ease;"></div>
                                            </div>
                                        </div>
                                        @error('password')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <label for="password_confirmation" class="form-label">
                                            Confirm Password <span class="text-required">*</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-key text-muted"></i>
                                            </span>
                                            <input type="password" class="form-control ps-0" id="password_confirmation" 
                                                   name="password_confirmation" required>
                                        </div>
                                        <div id="passwordMatch" class="form-text mt-2">
                                            <i class="fas fa-info-circle text-muted me-1"></i>
                                            Passwords must match
                                        </div>
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

                <!-- Step 3: Role & Address -->
                <div class="wizard-step" id="step3">
                    <div class="card card-custom mb-4">
                        <div class="card-header-custom d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0">
                                    <i class="fas fa-user-tag me-2"></i>Role & Address Information
                                </h5>
                                <p class="mb-0 text-white-50 small">Step 3 of 4: Assign role and address details</p>
                            </div>
                            <span class="badge bg-white text-success border border-success border-opacity-25">
                                Required fields marked <span class="text-required">*</span>
                            </span>
                        </div>
                        
                        <div class="card-body p-4 p-md-5">
                            <!-- Role Selection -->
                            <div class="form-section mb-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="section-icon">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                    <h6 class="mb-0 text-dark fw-bold fs-5">Role Assignment</h6>
                                </div>
                                
                                <label for="role" class="form-label">
                                    Select Role <span class="text-required">*</span>
                                </label>
                                <div class="row g-3" id="roleSelection">
                                    @foreach($roles as $key => $label)
                                        <div class="col-xl-3 col-lg-6">
                                            <div class="role-card {{ old('role') == $key ? 'selected' : '' }}" 
                                                 data-role="{{ $key }}">
                                                <div class="card border h-100">
                                                    <div class="card-body text-center p-3 p-md-4">
                                                        <div class="mb-3">
                                                            @if($key == 'superadmin')
                                                                <div class="role-icon bg-danger bg-opacity-10 text-danger">
                                                                    <i class="fas fa-crown fa-2x"></i>
                                                                </div>
                                                            @elseif($key == 'admin')
                                                                <div class="role-icon bg-primary bg-opacity-10 text-primary">
                                                                    <i class="fas fa-user-shield fa-2x"></i>
                                                                </div>
                                                            @elseif($key == 'delivery')
                                                                <div class="role-icon bg-warning bg-opacity-10 text-warning">
                                                                    <i class="fas fa-truck fa-2x"></i>
                                                                </div>
                                                            @else
                                                                <div class="role-icon bg-secondary bg-opacity-10 text-secondary">
                                                                    <i class="fas fa-user fa-2x"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <h5 class="mb-2 fw-bold">{{ $label }}</h5>
                                                        <p class="small text-muted mb-0">
                                                            @if($key == 'superadmin')
                                                                Full system access
                                                            @elseif($key == 'admin')
                                                                Manage all operations
                                                            @elseif($key == 'delivery')
                                                                Delivery operations 
                                                            @else
                                                                Customer access
                                                            @endif
                                                        </p>
                                                        <div class="mt-3">
                                                            @if($key == 'superadmin')
                                                                <span class="badge-text badge-superadmin">Full Access</span>
                                                            @elseif($key == 'admin')
                                                                <span class="badge-text badge-admin">Management</span>
                                                            @elseif($key == 'delivery')
                                                                <span class="badge-text badge-delivery">Delivery</span>
                                                            @else
                                                                <span class="badge-text badge-customer">Basic</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="radio" class="role-radio" name="role" value="{{ $key }}" 
                                                       id="role_{{ $key }}" {{ old('role') == $key ? 'checked' : '' }} 
                                                       style="display: none;">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" id="selectedRole" name="role" value="{{ old('role') }}" required>
                                @error('role')
                                    <div class="text-danger small mt-3">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Delivery Specific Fields -->
                            <div id="deliveryFields" class="form-section mb-5" style="display: none;">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="section-icon">
                                        <i class="fas fa-truck-loading"></i>
                                    </div>
                                    <h6 class="mb-0 text-dark fw-bold fs-5">Delivery Information</h6>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-lg-4">
                                        <label for="vehicle_type" class="form-label">Vehicle Type</label>
                                        <select class="form-select form-select-lg" id="vehicle_type" name="vehicle_type">
                                            <option value="">Select Vehicle Type</option>
                                            <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                            <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>Car</option>
                                            <option value="van" {{ old('vehicle_type') == 'van' ? 'selected' : '' }}>Van</option>
                                            <option value="truck" {{ old('vehicle_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                                            <option value="bicycle" {{ old('vehicle_type') == 'bicycle' ? 'selected' : '' }}>Bicycle</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="vehicle_number" class="form-label">Vehicle Number</label>
                                        <input type="text" class="form-control form-control-lg" name="vehicle_number" 
                                               value="{{ old('vehicle_number') }}" placeholder="ABC-123">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="license_number" class="form-label">License Number</label>
                                        <input type="text" class="form-control form-control-lg" name="license_number" 
                                               value="{{ old('license_number') }}" placeholder="DL-123456789">
                                    </div>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="form-section mb-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="section-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h6 class="mb-0 text-dark fw-bold fs-5">Address Information</h6>
                                </div>
                                
                                <div class="row g-3">
                                    <!-- Street Address -->
                                    <div class="col-lg-8">
                                        <label for="street_address" class="form-label">Street Address</label>
                                        <input type="text" class="form-control form-control-lg" name="street_address" 
                                               value="{{ old('street_address') }}" placeholder="123 Main Street">
                                        @error('street_address')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Barangay -->
                                    <div class="col-lg-4">
                                        <label for="barangay" class="form-label">Barangay</label>
                                        <input type="text" class="form-control form-control-lg" name="barangay" 
                                               value="{{ old('barangay') }}" placeholder="Barangay Name">
                                        @error('barangay')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- City -->
                                    <div class="col-lg-4">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control form-control-lg" name="city" 
                                               value="{{ old('city') }}" placeholder="City Name">
                                        @error('city')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Province -->
                                    <div class="col-lg-4">
                                        <label for="province" class="form-label">Province</label>
                                        <input type="text" class="form-control form-control-lg" name="province" 
                                               value="{{ old('province') }}" placeholder="Province Name">
                                        @error('province')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Region -->
                                    <div class="col-lg-4">
                                        <label for="region" class="form-label">Region</label>
                                        <input type="text" class="form-control form-control-lg" name="region" 
                                               value="{{ old('region') }}" placeholder="Region Name">
                                        @error('region')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Country -->
                                    <div class="col-lg-4">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" class="form-control form-control-lg" name="country" 
                                               value="{{ old('country', 'Philippines') }}" placeholder="Country">
                                        @error('country')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- ZIP/Postal Code -->
                                    <div class="col-lg-4">
                                        <label for="zip_code" class="form-label">ZIP/Postal Code</label>
                                        <input type="text" class="form-control form-control-lg" name="zip_code" 
                                               value="{{ old('zip_code') }}" placeholder="10001">
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

                <!-- Step 4: Review & Create -->
                <div class="wizard-step" id="step4">
                    <div class="card card-custom mb-4">
                        <div class="card-header-custom d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0">
                                    <i class="fas fa-clipboard-check me-2"></i>Review & Create User
                                </h5>
                                <p class="mb-0 text-white-50 small">Step 4 of 4: Review details and create user</p>
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
                                        <h6 class="mb-1 fw-bold">All required information completed!</h6>
                                        <p class="mb-0 text-success">Review the user details below before creating the account.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Review Summary -->
                            <div class="review-summary">
                                <h6 class="mb-4 fw-bold text-dark">User Information Summary</h6>
                                
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
                            </div>

                            <!-- Status Toggle -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between p-4 border rounded bg-light">
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
                                                   {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="is_active">
                                                <span id="statusText" class="status-text status-active">Active</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
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
                                        Reset Form
                                    </button>
                                    <button type="submit" class="btn btn-success-custom btn-lg px-5">
                                        <i class="fas fa-user-plus me-2"></i>Create User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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

    /* Wider layout */
    .container-fluid {
        max-width: 1800px;
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

    /* Role Cards */
    .role-card {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .role-card:hover .card {
        border-color: var(--green-primary);
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(44, 143, 12, 0.2);
    }

    .role-card.selected .card {
        border-color: var(--green-primary);
        background-color: var(--green-light);
        box-shadow: 0 8px 25px rgba(44, 143, 12, 0.25);
        transform: translateY(-2px);
    }

    .role-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        transition: all 0.3s ease;
    }

    .role-card:hover .role-icon {
        transform: scale(1.1);
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

    /* Password Strength */
    .progress-bar {
        background: linear-gradient(90deg, #ef4444, #f59e0b, var(--green-secondary));
        border-radius: 3px;
    }

    .progress {
        height: 6px;
        background-color: var(--gray-light);
        border-radius: 3px;
    }

    /* Status Switch */
    .form-check-input {
        width: 3.5rem;
        height: 1.75rem;
    }

    .form-check-input:checked {
        background-color: var(--green-primary);
        border-color: var(--green-primary);
    }

    .form-check-input:focus {
        border-color: var(--green-primary);
        box-shadow: 0 0 0 0.25rem rgba(44, 143, 12, 0.2);
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

    /* Alert */
    .alert-success-custom {
        background-color: var(--green-lighter);
        border-left: 4px solid var(--green-primary);
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
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .section-icon i {
        color: var(--green-primary);
        font-size: 1.25rem;
    }

    /* Form Labels */
    .form-label {
        font-weight: 600;
        color: var(--gray-dark);
        margin-bottom: 0.5rem;
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

    /* Placeholder styling */
    ::placeholder {
        color: #adb5bd !important;
    }

    /* Input group focus state */
    .input-group:focus-within {
        box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.1);
        border-radius: 8px;
    }

    /* Success message style */
    .text-success {
        color: var(--green-primary) !important;
    }

    /* Mobile Responsive */
    @media (max-width: 1200px) {
        .step-line {
            max-width: 50px;
        }
        
        .col-xl-3 {
            width: 50%;
        }
    }

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
        
        .role-card .card-body {
            padding: 1rem !important;
        }
        
        .section-icon {
            width: 40px;
            height: 40px;
            margin-right: 0.75rem;
        }
        
        .form-section {
            padding: 1rem !important;
        }
        
        .col-xl-3, .col-lg-6 {
            width: 100%;
        }
        
        .card-footer .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
        
        .card-footer .d-flex > div {
            width: 100%;
        }
        
        .header-buttons {
            margin-top: 1rem;
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
                // Validate contact and security
                const email = document.getElementById('email');
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('password_confirmation');
                
                if (!email.value.trim()) {
                    isValid = false;
                    showError(email, 'Email is required');
                    firstErrorField = email;
                } else if (!isValidEmail(email.value)) {
                    isValid = false;
                    showError(email, 'Please enter a valid email address');
                    firstErrorField = email;
                }
                
                if (!password.value.trim()) {
                    isValid = false;
                    showError(password, 'Password is required');
                    if (!firstErrorField) firstErrorField = password;
                } else if (password.value.length < 8) {
                    isValid = false;
                    showError(password, 'Password must be at least 8 characters');
                    if (!firstErrorField) firstErrorField = password;
                }
                
                if (password.value !== confirmPassword.value) {
                    isValid = false;
                    showError(confirmPassword, 'Passwords do not match');
                    if (!firstErrorField) firstErrorField = confirmPassword;
                }
                break;
                
            case 3:
                // Validate role selection
                const selectedRole = document.getElementById('selectedRole');
                if (!selectedRole.value) {
                    isValid = false;
                    alert('Please select a role for the user');
                    return false;
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
    const roleCards = document.querySelectorAll('.role-card');
    const selectedRoleInput = document.getElementById('selectedRole');
    const deliveryFields = document.getElementById('deliveryFields');
    
    roleCards.forEach(card => {
        card.addEventListener('click', function() {
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
    
    // Password visibility toggle
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            confirmPasswordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            this.classList.toggle('btn-outline-secondary');
            this.classList.toggle('btn-success-custom');
        });
    }
    
    // Password strength indicator
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
        const password = passwordInput.value;
        const strength = checkPasswordStrength(password);
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        
        let width = 0;
        let color = '#ef4444';
        let text = 'Very Weak';
        
        switch(strength) {
            case 0:
                width = 0;
                color = '#ef4444';
                text = 'Very Weak';
                break;
            case 1:
                width = 20;
                color = '#ef4444';
                text = 'Weak';
                break;
            case 2:
                width = 40;
                color = '#f59e0b';
                text = 'Fair';
                break;
            case 3:
                width = 60;
                color = '#f59e0b';
                text = 'Good';
                break;
            case 4:
                width = 80;
                color = '#10b981';
                text = 'Strong';
                break;
            case 5:
                width = 100;
                color = '#10b981';
                text = 'Very Strong';
                break;
        }
        
        strengthBar.style.width = `${width}%`;
        strengthBar.style.backgroundColor = color;
        strengthText.textContent = text;
        strengthText.style.color = color;
    }
    
    // Password match indicator
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const matchIndicator = document.getElementById('passwordMatch');
        
        if (confirmPassword === '') {
            matchIndicator.innerHTML = '<i class="fas fa-info-circle text-muted me-1"></i> Passwords must match';
            matchIndicator.className = 'form-text mt-2';
            return;
        }
        
        if (password === confirmPassword) {
            matchIndicator.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i> Passwords match';
            matchIndicator.className = 'form-text mt-2 text-success fw-bold';
        } else {
            matchIndicator.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i> Passwords do not match';
            matchIndicator.className = 'form-text mt-2 text-danger fw-bold';
        }
    }
    
    passwordInput.addEventListener('input', function() {
        updatePasswordStrength();
        checkPasswordMatch();
    });
    
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    
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
        const streetAddress = document.querySelector('input[name="street_address"]').value;
        const city = document.querySelector('input[name="city"]').value;
        const province = document.querySelector('input[name="province"]').value;
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
            const roleName = roleCard.querySelector('h5');
            if (roleName) roleLabel = roleName.textContent;
        }
        document.getElementById('reviewRole').textContent = roleLabel;
        
        document.getElementById('reviewStatus').textContent = isActive ? 'Active' : 'Inactive';
        document.getElementById('reviewStatus').className = isActive ? 'status-text status-active' : 'status-text status-inactive';
        
        // Build address string
        let addressParts = [];
        if (streetAddress) addressParts.push(streetAddress);
        if (city) addressParts.push(city);
        if (province) addressParts.push(province);
        if (country) addressParts.push(country);
        
        document.getElementById('reviewAddress').textContent = 
            addressParts.length > 0 ? addressParts.join(', ') : '-';
    }
    
    // Form submission
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
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
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating User...';
        submitBtn.disabled = true;
        
        // Submit form
        setTimeout(() => {
            this.submit();
        }, 1000);
    });
    
    // Form reset
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
            // Reset to step 1
            goToStep(1);
            
            // Clear all form data
            document.getElementById('createUserForm').reset();
            
            // Reset role selection
            roleCards.forEach(card => card.classList.remove('selected'));
            selectedRoleInput.value = '';
            deliveryFields.style.display = 'none';
            
            // Reset status toggle
            document.getElementById('is_active').checked = true;
            statusText.textContent = 'Active';
            statusText.className = 'status-text status-active';
            
            // Reset review summary
            updateReviewSummary();
        }
    });
    
    // Initialize
    goToStep(1);
});
</script>
@endsection