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
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-1">
                <i class="fas fa-user-plus text-success opacity-75 me-2"></i>Create New User
            </h1>
            <p class="text-muted mb-0">Add a new user to the system with appropriate role and permissions</p>
        </div>
        <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Users
        </a>
    </div>

    <div class="row justify-content-center">
        <!-- Main Content - Wider Column -->
        <div class="col-xl-10 col-lg-12">
            <!-- Progress Steps -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="step-indicator active">
                                <span class="step-number">1</span>
                                <span class="step-label">Basic Info</span>
                            </div>
                            <div class="step-line bg-success opacity-25"></div>
                            <div class="step-indicator">
                                <span class="step-number">2</span>
                                <span class="step-label">Credentials</span>
                            </div>
                            <div class="step-line bg-light"></div>
                            <div class="step-indicator">
                                <span class="step-number">3</span>
                                <span class="step-label">Role & Permissions</span>
                            </div>
                            <div class="step-line bg-light"></div>
                            <div class="step-indicator">
                                <span class="step-number">4</span>
                                <span class="step-label">Confirm</span>
                            </div>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fas fa-clock me-1"></i>Quick Setup
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Form Card - Wider -->
            <div class="card border-0 shadow-lg overflow-hidden mb-4">
                <div class="card-header bg-success bg-gradient text-white py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-user-circle me-2"></i>User Information
                        </h5>
                        <span class="badge bg-white text-success">Required fields marked *</span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('superadmin.users.store') }}" id="createUserForm">
                    @csrf
                    
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
                                               id="first_name" name="first_name" value="{{ old('first_name') }}" 
                                               placeholder="John" required>
                                    </div>
                                    @error('first_name')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-4">
                                    <label for="middle_name" class="form-label fw-medium">Middle Name</label>
                                    <input type="text" class="form-control form-control-lg @error('middle_name') is-invalid @enderror" 
                                           id="middle_name" name="middle_name" value="{{ old('middle_name') }}" 
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
                                           id="last_name" name="last_name" value="{{ old('last_name') }}" 
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
                                    <label for="phone" class="form-label fw-medium">Phone Number</label>
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
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                    <i class="fas fa-lock text-success"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold fs-5">Security & Access</h6>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="password" class="form-label fw-medium">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-key text-muted"></i>
                                        </span>
                                        <input type="password" class="form-control ps-0 @error('password') is-invalid @enderror" 
                                               id="password" name="password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength mt-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <small class="text-muted">Password strength:</small>
                                            <small id="strengthText" class="fw-bold">None</small>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div id="strengthBar" class="progress-bar rounded" role="progressbar" 
                                                 style="width: 0%; transition: width 0.3s ease;"></div>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-6">
                                    <label for="password_confirmation" class="form-label fw-medium">
                                        Confirm Password <span class="text-danger">*</span>
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

                        <!-- Role Selection -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                    <i class="fas fa-user-tag text-success"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold fs-5">Role Assignment</h6>
                            </div>
                            
                            <label for="role" class="form-label fw-medium">
                                Select Role <span class="text-danger">*</span>
                            </label>
                            <div class="row g-4" id="roleSelection">
                                @foreach($roles as $key => $label)
                                    <div class="col-xl-3 col-lg-6">
                                        <div class="role-card {{ old('role') == $key ? 'selected' : '' }}" 
                                             data-role="{{ $key }}">
                                            <div class="card border h-100">
                                                <div class="card-body text-center p-4">
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
                                                            Delivery operations only
                                                        @else
                                                            Customer access
                                                        @endif
                                                    </p>
                                                    <div class="mt-3">
                                                        <span class="badge 
                                                            @if($key == 'superadmin') bg-danger
                                                            @elseif($key == 'admin') bg-primary
                                                            @elseif($key == 'delivery') bg-warning
                                                            @else bg-secondary @endif 
                                                            bg-opacity-10 text-@if($key == 'superadmin')danger
                                                            @elseif($key == 'admin')primary
                                                            @elseif($key == 'delivery')warning
                                                            @else secondary @endif">
                                                            @if($key == 'superadmin') Full Access
                                                            @elseif($key == 'admin') Management
                                                            @elseif($key == 'delivery') Delivery
                                                            @else Basic @endif
                                                        </span>
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
                        <div id="deliveryFields" class="mb-5" style="display: none;">
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
                                        <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                        <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>Car</option>
                                        <option value="van" {{ old('vehicle_type') == 'van' ? 'selected' : '' }}>Van</option>
                                        <option value="truck" {{ old('vehicle_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                                        <option value="bicycle" {{ old('vehicle_type') == 'bicycle' ? 'selected' : '' }}>Bicycle</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="vehicle_number" class="form-label fw-medium">Vehicle Number</label>
                                    <input type="text" class="form-control form-control-lg" name="vehicle_number" 
                                           value="{{ old('vehicle_number') }}" placeholder="ABC-123">
                                </div>
                                <div class="col-lg-4">
                                    <label for="license_number" class="form-label fw-medium">License Number</label>
                                    <input type="text" class="form-control form-control-lg" name="license_number" 
                                           value="{{ old('license_number') }}" placeholder="DL-123456789">
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                    <i class="fas fa-map-marker-alt text-success"></i>
                                </div>
                                <h6 class="mb-0 text-dark fw-bold fs-5">Address Information (Optional)</h6>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <label for="address" class="form-label fw-medium">Street Address</label>
                                    <input type="text" class="form-control form-control-lg" name="address" 
                                           value="{{ old('address') }}" placeholder="123 Main Street">
                                </div>
                                <div class="col-lg-4">
                                    <label for="city" class="form-label fw-medium">City</label>
                                    <input type="text" class="form-control form-control-lg" name="city" 
                                           value="{{ old('city') }}" placeholder="New York">
                                </div>
                                <div class="col-lg-4">
                                    <label for="state" class="form-label fw-medium">State/Province</label>
                                    <input type="text" class="form-control form-control-lg" name="state" 
                                           value="{{ old('state') }}" placeholder="NY">
                                </div>
                                <div class="col-lg-4">
                                    <label for="zip_code" class="form-label fw-medium">ZIP/Postal Code</label>
                                    <input type="text" class="form-control form-control-lg" name="zip_code" 
                                           value="{{ old('zip_code') }}" placeholder="10001">
                                </div>
                                <div class="col-lg-4">
                                    <label for="country" class="form-label fw-medium">Country</label>
                                    <input type="text" class="form-control form-control-lg" name="country" 
                                           value="{{ old('country') }}" placeholder="United States">
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
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="is_active">
                                            <span id="statusText" class="text-success">Active</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Footer with Actions -->
                    <div class="card-footer bg-light border-top py-4 px-5">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="reset" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-redo me-2"></i>Reset Form
                                </button>
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    <i class="fas fa-user-plus me-2"></i>Create User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Quick Stats -->
            <div class="row g-4">
                <div class="col-xl-3 col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-users text-success fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold" id="totalUsersCount">1,247</h3>
                                    <small class="text-muted">Total Users</small>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-success">
                                    <i class="fas fa-arrow-up me-1"></i>12% increase
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-truck text-warning fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold" id="deliveryUsersCount">43</h3>
                                    <small class="text-muted">Delivery Staff</small>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-success">
                                    <i class="fas fa-arrow-up me-1"></i>5% increase
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-user-shield text-primary fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold" id="adminUsersCount">12</h3>
                                    <small class="text-muted">Admin Users</small>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-success">
                                    <i class="fas fa-arrow-up me-1"></i>8% increase
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-user-clock text-info fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold" id="activeUsersCount">1,024</h3>
                                    <small class="text-muted">Active Users</small>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-success">
                                    <i class="fas fa-arrow-up me-1"></i>15% increase
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Green Theme Styles */
:root {
    --success-light: #d1fae5;
    --success-medium: #10b981;
    --success-dark: #059669;
    --success-bg: rgba(16, 185, 129, 0.1);
}

/* Wider layout */
.container-fluid {
    max-width: 1800px;
}

/* Progress Steps */
.step-indicator {
    display: flex;
    align-items: center;
    flex-direction: column;
    position: relative;
    min-width: 90px;
}

.step-indicator.active .step-number {
    background: var(--success-medium);
    color: white;
    border-color: var(--success-medium);
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.step-indicator.active .step-label {
    color: var(--success-dark);
    font-weight: 700;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    margin-bottom: 8px;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.step-label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
    white-space: nowrap;
}

.step-line {
    width: 80px;
    height: 2px;
    margin: 0 15px;
}

@media (max-width: 1200px) {
    .step-line {
        width: 50px;
    }
}

@media (max-width: 768px) {
    .step-line {
        width: 30px;
    }
    .step-indicator {
        min-width: 70px;
    }
}

/* Role Cards */
.role-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.role-card:hover .card {
    border-color: var(--success-medium);
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.2);
}

.role-card.selected .card {
    border-color: var(--success-medium);
    background-color: var(--success-bg);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.25);
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

/* Form Controls - Larger */
.form-control-lg, .form-select-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    border-radius: 8px;
}

.input-group-lg > .form-control,
.input-group-lg > .form-select {
    padding: 0.75rem 1rem;
    font-size: 1rem;
}

.form-control:focus, .form-select:focus {
    border-color: var(--success-medium);
    box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
}

/* Buttons - Larger */
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    border-radius: 8px;
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
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

/* Password Strength */
.progress-bar {
    background: linear-gradient(90deg, #ef4444, #f59e0b, #10b981);
    border-radius: 3px;
}

/* Card Styling - Wider */
.card {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

/* Form Section Headers */
.bg-success.bg-gradient {
    background: linear-gradient(135deg, var(--success-medium), var(--success-dark));
}

/* Status Switch */
.form-check-input {
    width: 3.5rem;
    height: 1.75rem;
}

.form-check-input:checked {
    background-color: var(--success-medium);
    border-color: var(--success-medium);
}

.form-check-input:focus {
    border-color: var(--success-medium);
    box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
}

/* Stats Cards */
.bg-success.bg-opacity-10 {
    background-color: rgba(16, 185, 129, 0.1) !important;
}

.bg-warning.bg-opacity-10 {
    background-color: rgba(245, 158, 11, 0.1) !important;
}

.bg-primary.bg-opacity-10 {
    background-color: rgba(59, 130, 246, 0.1) !important;
}

.bg-info.bg-opacity-10 {
    background-color: rgba(6, 182, 212, 0.1) !important;
}

/* Section Spacing */
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

/* Better Typography */
.fs-5 {
    font-size: 1.25rem !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
            
            // Update progress indicator
            updateProgressIndicator();
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
            this.classList.toggle('btn-secondary');
            this.classList.toggle('btn-success');
        });
    }
    
    // Password strength indicator
    function checkPasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++; // Extra point for longer password
        if (/[A-Z]/.test(password)) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        return Math.min(strength, 5); // Max 5 points
    }
    
    function updatePasswordStrength() {
        const password = passwordInput.value;
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
                statusText.className = 'text-success';
            } else {
                statusText.textContent = 'Inactive';
                statusText.className = 'text-secondary';
            }
        });
    }
    
    // Progress indicator update
    function updateProgressIndicator() {
        const steps = document.querySelectorAll('.step-indicator');
        const firstName = document.getElementById('first_name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const role = selectedRoleInput.value;
        
        // Step 1: Basic info (name fields filled)
        if (firstName) {
            steps[0].classList.add('active');
        } else {
            steps[0].classList.remove('active');
        }
        
        // Step 2: Credentials (email and password filled)
        if (email && password) {
            steps[1].classList.add('active');
            steps[0].querySelector('.step-line').classList.remove('bg-light');
            steps[0].querySelector('.step-line').classList.add('bg-success', 'opacity-25');
        } else {
            steps[1].classList.remove('active');
            steps[0].querySelector('.step-line').classList.remove('bg-success', 'opacity-25');
            steps[0].querySelector('.step-line').classList.add('bg-light');
        }
        
        // Step 3: Role selected
        if (role) {
            steps[2].classList.add('active');
            steps[1].querySelector('.step-line').classList.remove('bg-light');
            steps[1].querySelector('.step-line').classList.add('bg-success', 'opacity-25');
        } else {
            steps[2].classList.remove('active');
            steps[1].querySelector('.step-line').classList.remove('bg-success', 'opacity-25');
            steps[1].querySelector('.step-line').classList.add('bg-light');
        }
        
        // Step 4: All required fields filled
        if (firstName && email && password && role) {
            steps[3].classList.add('active');
            steps[2].querySelector('.step-line').classList.remove('bg-light');
            steps[2].querySelector('.step-line').classList.add('bg-success', 'opacity-25');
        } else {
            steps[3].classList.remove('active');
            steps[2].querySelector('.step-line').classList.remove('bg-success', 'opacity-25');
            steps[2].querySelector('.step-line').classList.add('bg-light');
        }
    }
    
    // Update progress on form input
    const formInputs = document.querySelectorAll('#createUserForm input, #createUserForm select');
    formInputs.forEach(input => {
        input.addEventListener('input', updateProgressIndicator);
        input.addEventListener('change', updateProgressIndicator);
    });
    
    // Initialize progress indicator
    updateProgressIndicator();
    
    // Update active users count in stats
    document.getElementById('activeUsersCount').textContent = '1,024';
    
    // Form validation before submit
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
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
            
            if (firstErrorField) {
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstErrorField.focus();
                
                firstErrorField.classList.add('animate__animated', 'animate__headShake');
                setTimeout(() => {
                    firstErrorField.classList.remove('animate__animated', 'animate__headShake');
                }, 1000);
            }
        } else {

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating User...';
            submitBtn.disabled = true;

            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        }
    });
});
</script>
@endsection