@extends('layouts.delivery')

@section('content')
<style>
    /* 🌿 Enhanced Green Theme - Consistent with Delivery Orders Page */
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

    /* Full-width header at the top - Title on left, avatar on right */
    .profile-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50) !important;
        color: white;
        border-radius: 0 0 16px 16px;
        padding: 2.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        margin-top: -1.5rem;
    }

    .profile-header h1 {
        font-weight: 700;
        font-size: 2rem;
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .profile-header .subtitle {
        margin-bottom: 0;
        opacity: 0.95;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    /* Profile Avatar - Right side */
    .profile-avatar {
        width: 100px;
        height: 100px;
        border: 4px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        overflow: hidden;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-avatar i {
        font-size: 3rem;
        color: white;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Success Modal Styling */
    .success-modal .modal-dialog {
        max-width: 500px;
    }

    .success-modal .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .success-modal .modal-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border-bottom: none;
        padding: 2rem;
        text-align: center;
        display: block;
    }

    .success-modal .modal-header .btn-close {
        filter: invert(1) brightness(2);
        opacity: 0.8;
        position: absolute;
        right: 1.5rem;
        top: 1.5rem;
    }

    .success-modal .modal-body {
        padding: 2.5rem 2rem 2rem;
        text-align: center;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #DCFCE7, #BBF7D0);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        border: 4px solid var(--primary-green);
    }

    .success-icon i {
        font-size: 2.5rem;
        color: var(--primary-green);
    }

    .success-modal .modal-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: white;
    }

    .success-modal .modal-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        margin-bottom: 0;
    }

    .success-message {
        font-size: 1.1rem;
        color: var(--text-dark);
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .modal-confirm-btn {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.875rem 2rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        min-width: 180px;
    }

    .modal-confirm-btn:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(44, 143, 12, 0.3);
    }

    /* Wider Profile Content Container */
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: 16px;
        border: 1px solid var(--medium-gray);
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    /* Form Sections with more width */
    .form-section {
        padding: 2.5rem 3rem;
    }

    .form-section:not(:last-of-type) {
        border-bottom: 1px solid var(--medium-gray);
    }

    .section-title {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 1.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--light-green);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
    }

    .section-title i {
        background: var(--light-green);
        color: var(--primary-green);
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    /* Form Elements - Consistent styling */
    .form-label {
        font-weight: 500;
        color: var(--text-dark);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid var(--medium-gray);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        color: var(--text-dark);
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(44, 143, 12, 0.1);
        outline: none;
        background: white;
        color: var(--text-dark);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    /* Primary button - consistent with delivery orders page */
    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border: 2px solid transparent;
        border-radius: 10px;
        padding: 0.875rem 2.5rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        cursor: pointer;
        min-height: 48px;
    }

    .btn-primary:hover:not(:disabled) {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(44, 143, 12, 0.3);
    }

    /* Password toggle */
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
        z-index: 10;
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--dark-gray) !important;
        margin-top: 0.25rem;
        display: block;
    }

    .input-group {
        position: relative;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .profile-container {
            max-width: 95%;
        }
        
        .form-section {
            padding: 2rem 2.5rem;
        }
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 1.75rem 1.5rem;
            border-radius: 0 0 12px 12px;
            margin-top: -0.75rem;
        }

        .profile-header h1 {
            font-size: 1.5rem;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
        }

        .profile-avatar i {
            font-size: 2rem;
        }

        .profile-container {
            border-radius: 12px;
            max-width: 100%;
        }

        .form-section {
            padding: 1.5rem;
        }

        .section-title {
            font-size: 1.1rem;
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .success-modal .modal-dialog {
            margin: 1rem;
        }

        .success-modal .modal-header {
            padding: 1.5rem;
        }

        .success-modal .modal-body {
            padding: 2rem 1.5rem;
        }

        .success-icon {
            width: 70px;
            height: 70px;
        }

        .success-icon i {
            font-size: 2rem;
        }
    }

    @media (max-width: 576px) {
        .profile-header {
            padding: 1.5rem 1.25rem;
        }

        .profile-header .row {
            flex-direction: column;
            text-align: center;
            gap: 1.5rem;
        }

        .profile-header .profile-info {
            order: 2;
        }

        .profile-header .profile-avatar-col {
            order: 1;
        }

        .profile-container {
            border-radius: 12px 12px 0 0;
            margin: 0 -0.5rem 2rem;
            width: calc(100% + 1rem);
        }

        .form-section {
            padding: 1.25rem;
        }

        .section-title {
            font-size: 1rem;
        }

        .section-title i {
            width: 36px;
            height: 36px;
            font-size: 0.9rem;
        }
    }
</style>

<!-- Success Modal -->
<div class="modal fade success-modal" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h5 class="modal-title">Success!</h5>
                <p class="modal-subtitle">Your profile has been updated</p>
            </div>
            <div class="modal-body">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <p class="success-message" id="successMessage"></p>
                <button type="button" class="btn modal-confirm-btn" data-bs-dismiss="modal">
                    Continue
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade success-modal" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #ef4444);">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h5 class="modal-title">Oops!</h5>
                <p class="modal-subtitle">Something went wrong</p>
            </div>
            <div class="modal-body">
                <div class="success-icon" style="border-color: #dc3545; background: linear-gradient(135deg, #FEE2E2, #FECACA);">
                    <i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i>
                </div>
                <p class="success-message" id="errorMessage"></p>
                <button type="button" class="btn modal-confirm-btn" style="background: linear-gradient(135deg, #dc3545, #ef4444);" data-bs-dismiss="modal">
                    Try Again
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Full-width header at the top - "My Profile" on left, Avatar on right -->
<div class="profile-header">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left side: Title and subtitle -->
            <div class="col-md-8 profile-info">
                <h1>
                    <i class="fas fa-user-circle me-2"></i>Delivery Profile
                </h1>
                <p class="subtitle">Manage your delivery profile information and password</p>
            </div>
            <!-- Right side: Avatar -->
            <div class="col-md-4 text-end profile-avatar-col">
                <div class="profile-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Container -->
<div class="container-fluid px-xxl-5 py-4">
    <div class="profile-container">
        <!-- Personal Information Form -->
        <form method="POST" action="{{ route('delivery.profile.update') }}" class="form-section" id="profileForm">
            @csrf
            @method('PUT')

            <h4 class="section-title">
                <i class="fas fa-user-edit"></i>
                Personal Information
            </h4>

            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name *</label>
                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror"
                               name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                               placeholder="Enter your first name">
                        @error('first_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror"
                               name="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
                               placeholder="Enter your middle name">
                        @error('middle_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Optional</small>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name *</label>
                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror"
                               name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                               placeholder="Enter your last name">
                        @error('last_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address *</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email', $user->email) }}" required
                               placeholder="your@email.com">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror"
                               name="phone" value="{{ old('phone', $user->phone) }}"
                               placeholder="Your phone number">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="street_address" class="form-label">Street Address</label>
                <textarea id="street_address" class="form-control @error('street_address') is-invalid @enderror"
                          name="street_address" rows="3"
                          placeholder="Enter your street address">{{ old('street_address', $user->street_address) }}</textarea>
                @error('street_address')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="province" class="form-label">Province</label>
                        <input id="province" type="text" class="form-control @error('province') is-invalid @enderror"
                               name="province" value="{{ old('province', $user->province) }}"
                               placeholder="Your province">
                        @error('province')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="city" class="form-label">City</label>
                        <input id="city" type="text" class="form-control @error('city') is-invalid @enderror"
                               name="city" value="{{ old('city', $user->city) }}"
                               placeholder="Your city">
                        @error('city')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label for="barangay" class="form-label">Barangay</label>
                        <input id="barangay" type="text" class="form-control @error('barangay') is-invalid @enderror"
                               name="barangay" value="{{ old('barangay', $user->barangay) }}"
                               placeholder="Your barangay">
                        @error('barangay')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <label for="region" class="form-label">Region</label>
                        <input id="region" type="text" class="form-control @error('region') is-invalid @enderror"
                               name="region" value="{{ old('region', $user->region) }}"
                               placeholder="Your region">
                        @error('region')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <label for="country" class="form-label">Country</label>
                        <input id="country" type="text" class="form-control @error('country') is-invalid @enderror"
                               name="country" value="{{ old('country', $user->country) }}"
                               placeholder="Your country">
                        @error('country')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-center text-lg-start mt-4">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i>Update Profile
                </button>
            </div>
        </form>

        <!-- Password Update Form -->
        <form method="POST" action="{{ route('delivery.profile.password') }}" class="form-section" id="passwordForm">
            @csrf
            @method('PUT')

            <h4 class="section-title">
                <i class="fas fa-lock"></i>
                Change Password
            </h4>

            <div class="row g-3">
                <div class="col-lg-12">
                    <div class="form-group input-group">
                        <label for="current_password" class="form-label">Current Password *</label>
                        <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror"
                               name="current_password" required placeholder="Enter current password">
                        <button type="button" class="password-toggle" id="toggleCurrentPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('current_password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group input-group">
                        <label for="password" class="form-label">New Password *</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" required placeholder="Enter new password">
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="form-group input-group">
                        <label for="password_confirmation" class="form-label">Confirm New Password *</label>
                        <input id="password_confirmation" type="password" class="form-control"
                               name="password_confirmation" required placeholder="Confirm new password">
                        <button type="button" class="password-toggle" id="togglePasswordConfirm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="text-center text-lg-start mt-4">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-key me-2"></i>Update Password
                </button>
            </div>
        </form>
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
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        }
    }

    setupPasswordToggle('current_password', 'toggleCurrentPassword');
    setupPasswordToggle('password', 'togglePassword');
    setupPasswordToggle('password_confirmation', 'togglePasswordConfirm');

    // Success Modal Handling
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');

    // Check for success/error messages and show modal
    @if(session('success'))
        successMessage.textContent = "{{ session('success') }}";
        successModal.show();
    @endif

    @if(session('error'))
        errorMessage.textContent = "{{ session('error') }}";
        errorModal.show();
    @endif

    // Handle form submission to show loading state
    const profileForm = document.getElementById('profileForm');
    const passwordForm = document.getElementById('passwordForm');
    
    if (profileForm) {
        profileForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
        });
    }

    if (passwordForm) {
        passwordForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating Password...';
        });
    }
});
</script>
@endsection