@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-green: #2C8F0C;
        --dark-green: #1E6A08;
        --light-green: #E8F5E6;
        --accent-green: #4CAF50;
        --light-gray: #F8F9FA;
        --medium-gray: #E9ECEF;
        --dark-gray: #6C757D;
        --text-dark: #212529;
    }
    
    .profile-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .profile-header {
        background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000" opacity="0.1"><polygon fill="white" points="0,1000 1000,0 1000,1000"/></svg>');
        background-size: cover;
    }
    
    .profile-avatar {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        border: 3px solid rgba(255,255,255,0.3);
        font-size: 2rem;
        color: white;
    }
    
    .profile-body {
        background: white;
        border-radius: 0 0 16px 16px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .form-section {
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 2px solid var(--light-green);
    }
    
    .form-section:last-of-type {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .section-title {
        color: var(--primary-green);
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--light-green);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .section-title i {
        background: var(--light-green);
        color: var(--primary-green);
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .form-control {
        border: 2px solid var(--medium-gray);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .form-control:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(44, 143, 12, 0.1);
        background: white;
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-green), var(--accent-green));
        border: none;
        border-radius: 10px;
        padding: 0.875rem 2rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 0.5rem;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(44, 143, 12, 0.3);
    }
    
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--dark-gray);
        cursor: pointer;
        font-size: 1rem;
        padding: 0.5rem;
    }
    
    .form-text {
        font-size: 0.8rem;
        color: var(--dark-gray) !important;
        margin-top: 0.25rem;
    }
    
    .input-group {
        position: relative;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .profile-body {
            padding: 1.5rem;
        }
        
        .profile-header {
            padding: 1.5rem;
        }
        
        .profile-avatar {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .section-title {
            font-size: 1.25rem;
        }
        
        .btn-primary {
            padding: 0.75rem 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .profile-container {
            margin: 0 -0.5rem;
        }
        
        .profile-body {
            border-radius: 0;
            padding: 1rem;
        }
        
        .profile-header {
            border-radius: 0;
        }
        
        .row {
            margin-left: -0.25rem;
            margin-right: -0.25rem;
        }
        
        .row > [class*="col-"] {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }
    }
</style>

<div class="container py-4">
    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2 class="mb-2">My Profile</h2>
            <p class="mb-0 opacity-75">Manage your personal information and password</p>
        </div>
        
        <!-- Profile Body -->
        <div class="profile-body">
            <!-- Personal Information Form -->
            <form method="POST" action="{{ route('profile.update') }}" class="form-section">
                @csrf
                @method('PUT')

                <h4 class="section-title">
                    <i class="fas fa-user-edit"></i>
                    Personal Information
                </h4>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="first_name" class="form-label">First Name *</label>
                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" 
                               name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                               placeholder="Enter your first name">
                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                               name="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
                               placeholder="Enter your middle name">
                        @error('middle_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="form-text text-muted">Optional</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="last_name" class="form-label">Last Name *</label>
                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" 
                               name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                               placeholder="Enter your last name">
                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email', $user->email) }}" required
                               placeholder="your@email.com">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               name="phone" value="{{ old('phone', $user->phone) }}" required
                               placeholder="Your phone number">
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address *</label>
                    <textarea id="address" class="form-control @error('address') is-invalid @enderror" 
                              name="address" required rows="3"
                              placeholder="Enter your complete address">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="city" class="form-label">City *</label>
                        <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" 
                               name="city" value="{{ old('city', $user->city) }}" required
                               placeholder="Your city">
                        @error('city')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="state" class="form-label">State/Province *</label>
                        <input id="state" type="text" class="form-control @error('state') is-invalid @enderror" 
                               name="state" value="{{ old('state', $user->state) }}" required
                               placeholder="Your state/province">
                        @error('state')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="zip_code" class="form-label">ZIP Code *</label>
                        <input id="zip_code" type="text" class="form-control @error('zip_code') is-invalid @enderror" 
                               name="zip_code" value="{{ old('zip_code', $user->zip_code) }}" required
                               placeholder="Your ZIP code">
                        @error('zip_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="country" class="form-label">Country *</label>
                    <input id="country" type="text" class="form-control @error('country') is-invalid @enderror" 
                           name="country" value="{{ old('country', $user->country) }}" required
                           placeholder="Your country">
                    @error('country')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Profile
                </button>
            </form>

            <!-- Password Update Form -->
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <h4 class="section-title">
                    <i class="fas fa-lock"></i>
                    Change Password
                </h4>
                
                <div class="mb-3 input-group">
                    <label for="current_password" class="form-label">Current Password *</label>
                    <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" 
                           name="current_password" required
                           placeholder="Enter current password">
                    <button type="button" class="password-toggle" id="toggleCurrentPassword">
                        <i class="fas fa-eye"></i>
                    </button>
                    @error('current_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3 input-group">
                        <label for="password" class="form-label">New Password *</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required
                               placeholder="Enter new password">
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3 input-group">
                        <label for="password_confirmation" class="form-label">Confirm New Password *</label>
                        <input id="password_confirmation" type="password" class="form-control" 
                               name="password_confirmation" required
                               placeholder="Confirm new password">
                        <button type="button" class="password-toggle" id="togglePasswordConfirm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key me-2"></i>Update Password
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    function setupPasswordToggle(inputId, buttonId) {
        const toggleBtn = document.getElementById(buttonId);
        const passwordInput = document.getElementById(inputId);
        
        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }
    }

    setupPasswordToggle('current_password', 'toggleCurrentPassword');
    setupPasswordToggle('password', 'togglePassword');
    setupPasswordToggle('password_confirmation', 'togglePasswordConfirm');

    // Form validation enhancements
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input[required], textarea[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
            
            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.classList.remove('is-invalid');
                }
            });
        });
    });

    // Password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    
    function validatePassword() {
        if (password.value && passwordConfirm.value && password.value !== passwordConfirm.value) {
            passwordConfirm.classList.add('is-invalid');
        } else {
            passwordConfirm.classList.remove('is-invalid');
        }
    }
    
    if (password && passwordConfirm) {
        password.addEventListener('input', validatePassword);
        passwordConfirm.addEventListener('input', validatePassword);
    }
});
</script>
@endsection