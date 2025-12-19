<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DIMDI Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #28a745;
            --primary-dark: #218838;
            --primary-light: #e8f5e9;
            --light-bg: #f8f9fa;
            --text-dark: #2d572c;
            --border-color: #e1e5e9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1050px;
            display: flex;
            min-height: 520px;
            max-height: 90vh;
        }

        .login-form-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .login-image-section {
            flex: 1.2;
            background-image: url('https://cgifurniture.com/_ipx/f_auto&s_1536x2304/cms/uploads/3d_visualization_of_furniture_lifestyle_chair_view2_2ba0b97949.webp');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .login-image-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.7), rgba(33, 136, 56, 0.8));
        }

        /* Header Styles */
        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
            margin-top: 0.5rem;
        }

        .login-logo {
            width: 50px;
            height: 50px;
            margin: 0 auto 0.75rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .login-title {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
            font-size: 1.4rem;
        }

        .login-subtitle {
            color: #6c757d;
            font-size: 0.85rem;
            color: #218838;
        }

        /* Form Styles - Full Width */
        .login-form {
            max-width: 100%;
            margin: 0 auto;
            flex-grow: 1;
            width: 100%;
        }

        .form-group {
            margin-bottom: 0.8rem;
            width: 100%;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
            font-size: 0.8rem;
            display: block;
            width: 100%;
        }

        /* Full Width Input Fields */
        .form-control {
            border: 1.5px solid #d1d9e0;
            border-radius: 6px;
            padding: 9px 16px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            background: #fff;
            width: 100%;
            /* Full width */
            height: 44px;
            line-height: 1.4;
            display: block;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.15);
            outline: none;
        }

        .form-control::placeholder {
            color: #9aa6b2;
            font-size: 0.85rem;
            opacity: 0.8;
        }

        /* Full Width Button */
        .btn-login {
            background: #2C8F0C;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            padding: 9px;
            font-size: 0.95rem;
            width: 100%;
            margin: 0.8rem 0;
            height: 42px;
            display: block;
            color: #f8f9fa;
            /* default text color */
            transition: color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(40, 167, 69, 0.3);
            color: #5f5f5f;
            /* black on hover */
        }

        /* Options Styles - Full Width */
        .login-options {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0.8rem 0;
            font-size: 0.8rem;
            width: 100%;
        }

        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Divider - Full Width */
        .divider {
            display: flex;
            align-items: center;
            margin: 1rem 0;
            color: #6c757d;
            font-size: 0.75rem;
            width: 100%;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }

        .divider span {
            padding: 0 10px;
        }

        /* Social Login - Full Width */
        .social-login {
            display: flex;
            gap: 8px;
            margin-bottom: 1rem;
            width: 100%;
        }

        .social-btn {
            flex: 1;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 8px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            color: #555;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.8rem;
            height: 38px;
            width: 100%;
        }

        .social-btn:hover {
            border-color: var(--primary-color);
            background: var(--primary-light);
            transform: translateY(-1px);
        }

        .social-btn i {
            font-size: 0.95rem;
            margin-right: 5px;
        }

        /* Sign Up Link - Full Width */
        .signup-link {
            text-align: center;
            color: #6c757d;
            font-size: 0.8rem;
            margin-top: 0.5rem;
            width: 100%;
        }

        .signup-link a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;

        }

        /* Home/Back Button */
        .home-btn {
            position: absolute;
            top: 15px;
            left: 15px;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            z-index: 100;
            font-size: 0.9rem;
        }

        .home-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
        }

        /* Layout adjustments */
        .content-wrapper {
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            flex-grow: 1;
            width: 100%;
        }

        .form-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            min-height: 0;
            overflow: hidden;
            width: 100%;
        }

        .bottom-links {
            margin-top: auto;
            padding-top: 0.5rem;
            flex-shrink: 0;
            width: 100%;
        }

        /* Invalid feedback styling */
        .invalid-feedback {
            font-size: 0.75rem !important;
            margin-top: 0.2rem;
            display: block;
            width: 100%;
        }

        /* Make sure everything stays within container */
        .login-form-section>.content-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-content>*,
        .bottom-links>* {
            width: 100%;
        }

        /* Responsive */
        @media (max-width: 1100px) {
            .login-card {
                max-width: 900px;
                min-height: 480px;
            }
        }

        @media (max-width: 992px) {
            .login-image-section {
                display: none;
            }

            .login-card {
                max-width: 450px;
                min-height: auto;
                max-height: 85vh;
            }

            .login-form-section {
                padding: 1.5rem;
            }

            /* Keep full width on mobile */
            .form-control,
            .btn-login,
            .social-btn,
            .login-options,
            .divider,
            .signup-link {
                width: 100%;
            }

            .social-login {
                flex-direction: column;
                gap: 8px;
            }
        }

        @media (max-height: 700px) {
            .login-card {
                max-height: 95vh;
            }

            .login-form-section {
                padding: 1.2rem;
                overflow-y: auto;
            }

            .login-header {
                margin-bottom: 1rem;
            }

            .form-group {
                margin-bottom: 0.6rem;
            }

            .btn-login {
                margin: 0.8rem 0;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 10px;
                overflow: auto;
            }

            .login-card {
                max-width: 100%;
                max-height: 95vh;
                min-height: auto;
            }

            .login-form-section {
                padding: 1.2rem;
                overflow-y: auto;
            }

            .login-title {
                font-size: 1.3rem;
            }

            .login-logo {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .home-btn {
                top: 10px;
                left: 10px;
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }

            .social-login {
                flex-direction: column;
                gap: 6px;
            }

            .social-btn {
                padding: 7px;
                height: 36px;
            }

            /* Adjust input sizes for mobile */
            .form-control {
                height: 42px;
                padding: 8px 14px;
            }

            .btn-login {
                height: 42px;
                padding: 8px;
            }
        }

        /* For very wide screens */
        @media (min-width: 1400px) {
            .login-card {
                max-width: 1200px;
            }

            .login-form-section {
                padding: 2.5rem;
            }

            /* Keep full width but with more padding */
            .form-control,
            .btn-login {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-form-section">
            <!-- Home/Back Button -->
            <a href="/" class="home-btn" title="Back to Home">
                <i class="bi bi-arrow-left"></i>
            </a>

            <div class="content-wrapper">
                <!-- Header -->
                <div class="login-header">
                    <div class="login-logo">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <h1 class="login-title">Welcome Back</h1>
                    <p class="login-subtitle">Sign in to your DIMDI account</p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf
                    <input type="hidden" id="secure_password" name="secure_password" value="">

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                            placeholder="Enter your email">
                        @error('email')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password" required
                            autocomplete="current-password" placeholder="Enter your password">
                        @error('password')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-login">
                        Sign In
                    </button>

                    <div class="login-options">
                        <a href="#" class="forgot-link">
                            Forgot Password?
                        </a>
                    </div>
                </form>

                <!-- Bottom Links -->
                <div class="bottom-links">
                    <div class="divider">
                        <span>Or continue with</span>
                    </div>

                    <div class="social-login">
                        <a href="{{ route('login.google') }}" class="social-btn">
                            <i class="bi bi-google"></i>
                            Google
                        </a>
                        <a href="{{ route('login.facebook') }}" class="social-btn">
                            <i class="bi bi-facebook"></i>
                            Facebook
                        </a>
                    </div>

                    <div class="signup-link">
                        <p class="mb-0">
                            Don't have an account?
                            <a href="{{ route('register') }}">Create account</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="login-image-section">
            <!-- Background image applied via CSS -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <script>
        // Enhanced security with client-side password encryption
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const form = document.querySelector('form');
            const submitBtn = document.querySelector('.btn-login');

            // Clear error states when user starts typing
            emailInput.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });

            passwordInput.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });

            // Simple password obfuscation
            function obfuscatePassword(password) {
                // Just base64 encode to hide from casual viewing
                return btoa(password);
            }

            // Form submission handling with password obfuscation
            form.addEventListener('submit', function(e) {
                const email = emailInput.value.trim();
                const password = passwordInput.value;

                // Clear previous error states
                emailInput.classList.remove('is-invalid');
                passwordInput.classList.remove('is-invalid');

                // Basic validation
                let hasError = false;
                if (!email) {
                    emailInput.classList.add('is-invalid');
                    hasError = true;
                }
                if (!password) {
                    passwordInput.classList.add('is-invalid');
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                    return;
                }

                // Obfuscate password for basic security
                const obfuscatedPassword = obfuscatePassword(password);
                passwordInput.value = obfuscatedPassword;
                
                // Show loading state
                submitBtn.textContent = 'Signing In...';
                submitBtn.disabled = true;
                
                // Let the form submit normally - Laravel will handle authentication and errors
            });

            // Prevent scrolling on body for better UX
            document.body.style.overflow = 'hidden';

            // Reset on small screens
            function checkScreenSize() {
                if (window.innerWidth <= 576) {
                    document.body.style.overflow = 'auto';
                } else {
                    document.body.style.overflow = 'hidden';
                }
            }

            checkScreenSize();
            window.addEventListener('resize', checkScreenSize);
        });
    </script>
</body>

</html>
