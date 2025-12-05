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
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            margin: 0;
            overflow: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
            min-height: 550px;
        }
        
        .login-form-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 2.5rem;
            position: relative;
        }
        
        .login-image-section {
            flex: 1;
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
        
        /* Header Styles - Compact */
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo {
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
        
        .login-title {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
            font-size: 1.5rem;
        }
        
        .login-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Form Styles - Compact */
        .login-form {
            max-width: 100%;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 1rem;
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
            width: 400px;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
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
        .btn-login {
            background: #2C8F0C;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 10px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            width: 100%;
            margin: 1rem 0;
        }
        
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        
        /* Options Styles - Compact */
        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
            font-size: 0.85rem;
        }
        
        .form-check-input {
            width: 14px;
            height: 14px;
            margin-top: 0.15rem;
        }
        
        .form-check-label {
            font-size: 0.85rem;
            color: #555;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        /* Divider - Compact */
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #6c757d;
            font-size: 0.8rem;
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
        
        /* Social Login - Compact */
        .social-login {
            display: flex;
            gap: 8px;
            margin-bottom: 1.5rem;
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
            transition: all 0.3s ease;
            color: #555;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.8rem;
        }
        
        .social-btn:hover {
            border-color: var(--primary-color);
            background: var(--primary-light);
            transform: translateY(-1px);
        }
        
        .social-btn i {
            font-size: 1rem;
            margin-right: 5px;
        }
        
        /* Sign Up Link - Compact */
        .signup-link {
            text-align: center;
            color: #6c757d;
            font-size: 0.85rem;
            margin-top: 1rem;
        }
        
        .signup-link a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }
        
        .signup-link a:hover {
            text-decoration: underline;
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
        
        /* Ensure everything is visible */
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
        
        .bottom-links {
            margin-top: auto;
            padding-top: 1rem;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .login-image-section {
                display: none;
            }
            
            .login-card {
                max-width: 500px;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 10px;
            }
            
            .login-form-section {
                padding: 1.5rem;
            }
            
            .login-title {
                font-size: 1.3rem;
            }
            
            .login-logo {
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
            
            .social-login {
                flex-direction: column;
                gap: 6px;
            }
            
            .social-btn {
                padding: 6px;
            }
            
            .login-options {
                flex-direction: column;
                gap: 0.8rem;
                align-items: flex-start;
            }
            
            .login-form {
                max-width: 100%;
            }
        }
        
        @media (max-height: 700px) {
            .login-form-section {
                padding: 1rem 2rem;
                justify-content: flex-start;
            }
            
            .login-header {
                margin-bottom: 1.5rem;
            }
            
            .form-group {
                margin-bottom: 0.8rem;
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
                <div class="form-content">
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

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" 
                                   required autocomplete="email" autofocus
                                   placeholder="Enter your email">
                            @error('email')
                                <div class="invalid-feedback" style="font-size: 0.8rem;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="current-password"
                                   placeholder="Enter your password">
                            {{-- <button type="button" class="password-toggle" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button> --}}
                            @error('password')
                                <div class="invalid-feedback" style="font-size: 0.8rem;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-login" style="color: #f8f9fa">
                            Sign In
                        </button>

                        <div class="login-options" style="display: flex; justify-content: center;">
                            {{-- <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="remember" id="remember" 
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div> --}}
                            <a href="#" class="forgot-link">
                                Forgot Password?
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Bottom Links - Always Visible -->
                <div class="bottom-links">
                    <div class="divider">
                        <span>Or continue with</span>
                    </div>
                    
                    <div class="social-login">
                        <a href="#" class="social-btn">
                            <i class="bi bi-google"></i>
                            Google
                        </a>
                        <a href="#" class="social-btn">
                            <i class="bi bi-facebook"></i>
                            Facebook
                        </a>
                        <a href="#" class="social-btn">
                            <i class="bi bi-twitter"></i>
                            Twitter
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
    <script>
        // Password toggle functionality
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });

        // Form validation enhancement
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                if (!email) {
                    document.getElementById('email').classList.add('is-invalid');
                }
                if (!password) {
                    document.getElementById('password').classList.add('is-invalid');
                }
            }
        });

        // Ensure bottom links are always visible
        function adjustLayout() {
            const formContent = document.querySelector('.form-content');
            const container = document.querySelector('.login-form-section');
            
            if (window.innerHeight < 600) {
                formContent.style.justifyContent = 'flex-start';
                container.style.padding = '1rem';
            } else {
                formContent.style.justifyContent = 'center';
            }
        }

        window.addEventListener('load', adjustLayout);
        window.addEventListener('resize', adjustLayout);
    </script>
</body>
</html>