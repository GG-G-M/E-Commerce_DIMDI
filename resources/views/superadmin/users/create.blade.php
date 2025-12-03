@extends('layouts.superadmin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-plus text-green me-2"></i>Create New User
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('superadmin.users.store') }}">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name *</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                   id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                            @error('middle_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name *</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        <div class="form-text">A valid email address is required for login.</div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            <div class="form-text">Minimum 8 characters</div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role *</label>
                        <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            <i class="fas fa-info-circle text-info me-1"></i>
                            Super Admin: Full system access including creating other admins<br>
                            Admin: Manage deliveries, products, orders, etc.<br>
                            Delivery: Deliver orders only<br>
                            Customer: Regular store customer
                        </div>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Additional Information (Optional)</label>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control" name="address" 
                                       placeholder="Address" value="{{ old('address') }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control" name="city" 
                                       placeholder="City" value="{{ old('city') }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <input type="text" class="form-control" name="state" 
                                       placeholder="State" value="{{ old('state') }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <input type="text" class="form-control" name="zip_code" 
                                       placeholder="ZIP Code" value="{{ old('zip_code') }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <input type="text" class="form-control" name="country" 
                                       placeholder="Country" value="{{ old('country') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Delivery-specific fields (shown only when role is delivery) -->
                    <div id="deliveryFields" class="mb-3" style="display: none;">
                        <label class="form-label">Delivery Information</label>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control" name="vehicle_type" 
                                       placeholder="Vehicle Type" value="{{ old('vehicle_type') }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control" name="vehicle_number" 
                                       placeholder="Vehicle Number" value="{{ old('vehicle_number') }}">
                            </div>
                            <div class="col-md-12 mb-2">
                                <input type="text" class="form-control" name="license_number" 
                                       placeholder="License Number" value="{{ old('license_number') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Account
                            </label>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle text-info me-1"></i>
                            Active users can log in. Inactive users cannot.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-green">
                            <i class="fas fa-save me-1"></i> Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Role Information Card -->
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h6 class="mb-0">Role Permissions Overview</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 border rounded">
                            <h6 class="text-danger"><i class="fas fa-crown me-2"></i>Super Admin</h6>
                            <ul class="small text-muted mb-0">
                                <li>Create/Edit/Delete all users</li>
                                <li>Full system configuration access</li>
                                <li>Access to all admin features</li>
                                <li>System-wide settings management</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="p-3 border rounded">
                            <h6 class="text-primary"><i class="fas fa-user-shield me-2"></i>Admin</h6>
                            <ul class="small text-muted mb-0">
                                <li>Manage products, categories, brands</li>
                                <li>Manage orders and deliveries</li>
                                <li>View sales reports</li>
                                <li>Manage inventory</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded">
                            <h6 class="text-warning"><i class="fas fa-truck me-2"></i>Delivery</h6>
                            <ul class="small text-muted mb-0">
                                <li>View assigned orders</li>
                                <li>Update order delivery status</li>
                                <li>View delivery history</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded">
                            <h6 class="text-secondary"><i class="fas fa-user me-2"></i>Customer</h6>
                            <ul class="small text-muted mb-0">
                                <li>Browse and purchase products</li>
                                <li>View order history</li>
                                <li>Manage personal profile</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const deliveryFields = document.getElementById('deliveryFields');
    
    function toggleDeliveryFields() {
        if (roleSelect.value === 'delivery') {
            deliveryFields.style.display = 'block';
        } else {
            deliveryFields.style.display = 'none';
        }
    }
    
    // Initial check
    toggleDeliveryFields();
    
    // Add event listener
    roleSelect.addEventListener('change', toggleDeliveryFields);
    
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const passwordStrength = document.createElement('div');
    passwordStrength.className = 'mt-1';
    passwordInput.parentNode.appendChild(passwordStrength);
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let message = '';
        let color = 'text-danger';
        
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        switch(strength) {
            case 0:
            case 1:
                message = 'Very Weak';
                color = 'text-danger';
                break;
            case 2:
                message = 'Weak';
                color = 'text-warning';
                break;
            case 3:
                message = 'Good';
                color = 'text-info';
                break;
            case 4:
                message = 'Strong';
                color = 'text-success';
                break;
            case 5:
                message = 'Very Strong';
                color = 'text-success';
                break;
        }
        
        passwordStrength.innerHTML = `<small class="${color}">Password Strength: ${message}</small>`;
    });
});
</script>
@endsection