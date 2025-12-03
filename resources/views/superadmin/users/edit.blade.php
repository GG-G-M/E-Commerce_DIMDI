@extends('layouts.superadmin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit text-green me-2"></i>Edit User: {{ $user->name }}
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-info me-2">
            <i class="fas fa-eye me-1"></i> View
        </a>
        <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
    </div>
</div>

@if($user->isSuperAdmin() && $user->id !== auth()->id())
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Note:</strong> You are editing another Super Admin's account. Please proceed with caution.
</div>
@endif

@if($user->id === auth()->id())
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Note:</strong> You are editing your own account.
</div>
@endif

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Edit User Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('superadmin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name *</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                   id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">
                            @error('middle_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name *</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role *</label>
                        <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <option value="">Select Role</option>
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @if($user->id === auth()->id())
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <div class="form-text text-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                You cannot change your own role.
                            </div>
                        @endif
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address Information</label>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <input type="text" class="form-control" name="address" 
                                       placeholder="Address" value="{{ old('address', $user->address) }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control" name="city" 
                                       placeholder="City" value="{{ old('city', $user->city) }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control" name="state" 
                                       placeholder="State" value="{{ old('state', $user->state) }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control" name="zip_code" 
                                       placeholder="ZIP Code" value="{{ old('zip_code', $user->zip_code) }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control" name="country" 
                                       placeholder="Country" value="{{ old('country', $user->country) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Delivery-specific fields -->
                    <div id="deliveryFields" class="mb-3" style="display: {{ $user->role == 'delivery' ? 'block' : 'none' }};">
                        <label class="form-label">Delivery Information</label>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control" name="vehicle_type" 
                                       placeholder="Vehicle Type" value="{{ old('vehicle_type', $user->vehicle_type) }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control" name="vehicle_number" 
                                       placeholder="Vehicle Number" value="{{ old('vehicle_number', $user->vehicle_number) }}">
                            </div>
                            <div class="col-md-12 mb-2">
                                <input type="text" class="form-control" name="license_number" 
                                       placeholder="License Number" value="{{ old('license_number', $user->license_number) }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                   {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Account
                            </label>
                        </div>
                        @if($user->id === auth()->id())
                            <input type="hidden" name="is_active" value="1">
                            <div class="form-text text-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                You cannot deactivate your own account.
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-green">
                            <i class="fas fa-save me-1"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Danger Zone -->
        @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
        <div class="card mt-4 border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Danger Zone</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Once you delete a user, there is no going back. Please be certain.</p>
                <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" 
                            onclick="return confirm('Are you absolutely sure you want to delete this user? This action cannot be undone.')">
                        <i class="fas fa-trash me-1"></i> Delete This User
                    </button>
                </form>
            </div>
        </div>
        @endif
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
    
    // Add event listener if role select is not disabled
    if (!roleSelect.disabled) {
        roleSelect.addEventListener('change', toggleDeliveryFields);
    }
});
</script>
@endsection