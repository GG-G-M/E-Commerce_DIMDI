<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DIMDI Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #28a745;
            --primary-dark: #218838;
            --primary-light: #e8f5e9;
            --light-bg: #f8f9fa;
            --text-dark: #2d572c;
            --border-color: #e1e5e9;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 950px;
            display: flex;
            min-height: 600px;
        }
        
        .register-form-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 2.5rem;
            position: relative;
        }
        
        .register-image-section {
            flex: 1;
            background-image: url('https://i.pinimg.com/736x/5e/28/09/5e28091833c771377cba21cfa0838998.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .register-image-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.7), rgba(33, 136, 56, 0.8));
        }
        
        /* Header Styles - Compact */
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .register-title {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
            font-size: 1.5rem;
        }
        
        .register-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Form Styles - Compact */
        .register-form {
            max-width: 100%;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 0.8rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.4rem;
            font-size: 0.85rem;
        }
        
        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 12px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            background: #fff;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 70px;
            font-size: 0.9rem;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        /* Button Styles - Compact */
        .btn-register {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 12px;
            transition: all 0.3s ease;
            font-size: 1rem;
            width: 100%;
            margin: 1rem 0;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        
        /* Options and Links */
        .login-link {
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 1rem;
        }
        
        .login-link a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        small.form-text {
            font-size: 0.75rem;
            color: #6c757d !important;
            margin-top: 0.25rem;
        }
        
        /* Home/Back Button - Compact */
        .home-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            z-index: 100;
            font-size: 1rem;
        }
        
        .home-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }
        
        /* Layout for form scrolling */
        .content-wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .form-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .bottom-section {
            margin-top: auto;
            padding-top: 1rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .register-image-section {
                display: none;
            }
            
            .register-card {
                max-width: 600px;
            }
        }
        
        @media (max-width: 768px) {
            .register-form-section {
                padding: 1.5rem;
            }
            
            .register-title {
                font-size: 1.3rem;
            }
            
            .register-logo {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
            
            .home-btn {
                top: 15px;
                left: 15px;
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }
            
            .register-form {
                max-width: 100%;
            }
            
            .row {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
            }
            
            .row > [class*="col-"] {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 10px;
            }
            
            .register-form-section {
                padding: 1rem;
            }
            
            .form-group {
                margin-bottom: 0.6rem;
            }
            
            .btn-register {
                margin: 0.5rem 0;
                padding: 10px;
            }
        }
        
        @media (max-height: 700px) {
            .register-form-section {
                padding: 1rem 2rem;
                justify-content: flex-start;
            }
            
            .register-header {
                margin-bottom: 1.5rem;
            }
        }
        
        /* Custom column adjustments for better mobile layout */
        @media (max-width: 400px) {
            .row-cols-md-3 > [class*="col-"] {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-form-section">
            <!-- Home/Back Button -->
            <a href="{{ url('/') }}" class="home-btn" title="Back to Home">
                <i class="fas fa-arrow-left"></i>
            </a>
            
            <div class="content-wrapper">
                <div class="form-content">
                    <!-- Header -->
                    <div class="register-header">
                        <div class="register-logo">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h1 class="register-title">Create Account</h1>
                        <p class="register-subtitle">Join DIMDI Store today</p>
                    </div>
                    
                    <!-- Registration Form -->
                    <form method="POST" action="{{ route('register') }}" class="register-form">
                        @csrf

                        <div class="row row-cols-1 row-cols-md-3">
                            <div class="col mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input id="first_name" type="text" 
                                       class="form-control @error('first_name') is-invalid @enderror" 
                                       name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" autofocus
                                       placeholder="First name">
                                @error('first_name')
                                    <div class="invalid-feedback" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input id="middle_name" type="text" 
                                       class="form-control @error('middle_name') is-invalid @enderror" 
                                       name="middle_name" value="{{ old('middle_name') }}" autocomplete="additional-name"
                                       placeholder="Middle name">
                                @error('middle_name')
                                    <div class="invalid-feedback" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">Optional</small>
                            </div>

                            <div class="col mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input id="last_name" type="text" 
                                       class="form-control @error('last_name') is-invalid @enderror" 
                                       name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name"
                                       placeholder="Last name">
                                @error('last_name')
                                    <div class="invalid-feedback" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input id="email" type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autocomplete="email"
                                       placeholder="your@email.com">
                                @error('email')
                                    <div class="invalid-feedback" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input id="phone" type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone') }}" required autocomplete="tel"
                                       placeholder="Phone number">
                                @error('phone')
                                    <div class="invalid-feedback" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3 position-relative">
                                <label for="password" class="form-label">Password *</label>
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="new-password"
                                       placeholder="Create password">
                                
                                @error('password')
                                    <div class="invalid-feedback" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3 position-relative">
                                <label for="password-confirm" class="form-label">Confirm Password *</label>
                                <input id="password-confirm" type="password" 
                                       class="form-control" 
                                       name="password_confirmation" required autocomplete="new-password"
                                       placeholder="Confirm password">
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea id="address" 
                                      class="form-control @error('address') is-invalid @enderror" 
                                      name="address" required rows="2" autocomplete="street-address"
                                      placeholder="Your full address">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback" style="font-size: 0.8rem;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row row-cols-1 row-cols-md-3">
                            <div class="col mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input id="city" type="text" 
                                       class="form-control @error('city') is-invalid @enderror" 
                                       name="city" value="{{ old('city') }}" required autocomplete="address-level2"
                                       placeholder="City">
                                @error('city')
                                    <div class="invalid-feedback" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col mb-3">
                                <label for="state" class="form-label">Province *</label>
                                <input id="state" type="text" 
                                       class="form-control @error('state') is-invalid @enderror" 
                                       name="state" value="{{ old('state') }}" required autocomplete="address-level1"
                                       placeholder="Province">
                                @error('state')
                                    <div class="invalid-feedback" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col mb-3">
                                <label for="zip_code" class="form-label">ZIP Code *</label>
                                <input id="zip_code" type="text" 
                                       class="form-control @error('zip_code') is-invalid @enderror" 
                                       name="zip_code" value="{{ old('zip_code') }}" required autocomplete="postal-code"
                                       placeholder="ZIP code">
                                @error('zip_code')
                                    <div class="invalid-feedback" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label">Country *</label>
                            <input id="country" type="text" 
                                   class="form-control @error('country') is-invalid @enderror" 
                                   name="country" value="{{ old('country') }}" required autocomplete="country-name"
                                   placeholder="Country">
                            @error('country')
                                <div class="invalid-feedback" style="font-size: 0.8rem;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-register">
                            Create Account
                        </button>
                    </form>
                </div>
                
                <!-- Bottom Links -->
                <div class="bottom-section">
                    <div class="login-link">
                        <p class="mb-0">
                            Already have an account? 
                            <a href="{{ route('login') }}">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="register-image-section">
            <!-- Background image applied via CSS -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        setupPasswordToggle('password', 'togglePassword');
        setupPasswordToggle('password-confirm', 'togglePasswordConfirm');

        // Form validation enhancements
        const form = document.querySelector('form');
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
        
        // Password confirmation validation
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password-confirm');
        
        function validatePassword() {
            if (password.value && passwordConfirm.value && password.value !== passwordConfirm.value) {
                passwordConfirm.classList.add('is-invalid');
            } else {
                passwordConfirm.classList.remove('is-invalid');
            }
        }
        
        password.addEventListener('input', validatePassword);
        passwordConfirm.addEventListener('input', validatePassword);

        // Adjust layout for small screens
        function adjustLayout() {
            const formContent = document.querySelector('.form-content');
            const container = document.querySelector('.register-form-section');
            
            if (window.innerHeight < 600) {
                formContent.style.justifyContent = 'flex-start';
                container.style.padding = '1rem';
            } else {
                formContent.style.justifyContent = 'center';
            }
        }

        window.addEventListener('load', adjustLayout);
        window.addEventListener('resize', adjustLayout);
    });
    </script>
</body>
</html>