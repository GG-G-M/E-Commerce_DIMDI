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
            --step-inactive: #dee2e6;
            --step-completed: #6c757d;
        }

        body {
            background: linear-gradient(135deg, #f8fff9 0%, #f0f9f1 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Image */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.08;
            z-index: -1;
        }

        /* Additional decorative elements */
        body::after {
            content: '';
            position: fixed;
            bottom: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background-image: url('https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80');
            background-size: contain;
            background-position: bottom right;
            background-repeat: no-repeat;
            opacity: 0.04;
            z-index: -1;
            pointer-events: none;
        }

        .register-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 15px 50px rgba(40, 167, 69, 0.15);
            width: 100%;
            max-width: 950px;
            overflow: hidden;
            min-height: auto;
            position: relative;
            border: 1px solid rgba(40, 167, 69, 0.1);
            backdrop-filter: blur(5px);
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative elements in header */
        .register-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: rotate(45deg);
        }

        .register-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -30%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .register-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 0.75rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            position: relative;
            z-index: 2;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .register-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .register-subtitle {
            opacity: 0.9;
            font-size: 0.9rem;
            position: relative;
            z-index: 2;
        }

        /* Home/Back Button - Compact */
        .back-btn {
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
        
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }

        .back-btn i {
            font-size: 0.9rem;
        }

        /* Stepper Styles - More Compact */
        .stepper-container {
            padding: 1.75rem 2rem;
        }

        .stepper-progress {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 2rem;
        }

        .stepper-progress::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--step-inactive);
            z-index: 1;
        }

        .progress-bar {
            position: absolute;
            top: 15px;
            left: 0;
            height: 2px;
            background: var(--primary-color);
            transition: width 0.4s ease;
            z-index: 2;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 3;
        }

        .step-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: white;
            border: 2px solid var(--step-inactive);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--step-inactive);
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 0.35rem;
            transition: all 0.3s ease;
        }

        .step.active .step-icon {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .step.completed .step-icon {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .step-label {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--step-inactive);
            text-align: center;
            max-width: 80px;
            line-height: 1.1;
        }

        .step.active .step-label {
            color: var(--primary-color);
            font-weight: 600;
        }

        .step.completed .step-label {
            color: var(--step-completed);
        }

        /* Form Steps - More Compact */
        .form-step {
            display: none;
            animation: fadeIn 0.4s ease;
            min-height: 280px;
        }

        .form-step.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step-title {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 1.25rem;
            font-size: 1.2rem;
        }

        /* Compact Form Styles */
        .form-group {
            margin-bottom: 0.75rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.3rem;
            font-size: 0.85rem;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 6px;
            padding: 8px 10px;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            height: 38px;
        }

        textarea.form-control {
            min-height: 60px;
            height: auto;
        }

        select.form-control {
            padding: 8px 6px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }

        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 6px;
            font-weight: 500;
            padding: 8px 20px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(40, 167, 69, 0.2);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: 6px;
            font-weight: 500;
            padding: 8px 20px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-1px);
        }

        /* Compact Button Container */
        .step-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border-color);
        }

        /* NDA Section - Compact */
        .nda-section {
            background: #f8fff8;
            border: 1px solid rgba(40, 167, 69, 0.2);
            border-radius: 6px;
            padding: 12px;
            margin: 1.25rem 0;
        }

        .nda-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 6px;
            font-size: 0.85rem;
        }

        .nda-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 8px;
        }

        .nda-checkbox label {
            font-size: 0.75rem;
            line-height: 1.3;
            color: #555;
            cursor: pointer;
        }

        /* Summary Section */
        #summary {
            font-size: 0.8rem;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        /* Login Link */
        .login-link {
            text-align: center;
            color: #6c757d;
            font-size: 0.85rem;
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .login-link a {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .register-container {
                max-width: 900px;
            }
        }

        @media (max-width: 768px) {
            .register-container {
                max-width: 95%;
                border-radius: 12px;
            }
            
            .register-header {
                padding: 1.5rem 1.25rem;
            }
            
            .stepper-container {
                padding: 1.25rem;
            }
            
            .stepper-progress {
                margin-bottom: 1.5rem;
            }
            
            .step-label {
                font-size: 0.7rem;
                max-width: 60px;
            }
            
            .step-icon {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }
            
            .back-btn {
                top: 15px;
                left: 15px;
                font-size: 0.85rem;
                padding: 6px 10px;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 15px;
                align-items: flex-start;
            }
            
            .register-container {
                margin-top: 10px;
            }
            
            .register-header {
                padding: 1.25rem 1rem;
            }
            
            .stepper-container {
                padding: 1rem;
            }
            
            .register-title {
                font-size: 1.3rem;
            }
            
            .register-logo {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }
            
            .back-btn {
                top: 10px;
                left: 10px;
                font-size: 0.8rem;
                padding: 5px 8px;
            }
            
            .step-actions {
                flex-direction: column;
                gap: 8px;
            }
            
            .step-actions .btn {
                width: 100%;
            }
        }

        /* Tighten form rows for better space usage */
        .row {
            --bs-gutter-x: 0.75rem;
            --bs-gutter-y: 0.5rem;
        }

        .row > * {
            padding-right: calc(var(--bs-gutter-x) * 0.5);
            padding-left: calc(var(--bs-gutter-x) * 0.5);
        }

        /* Smaller help text */
        .form-text {
            font-size: 0.7rem !important;
            margin-top: 0.15rem !important;
        }

        /* Furniture icons in form */
        .form-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            opacity: 0.3;
            font-size: 1rem;
            pointer-events: none;
        }

        .form-group {
            position: relative;
        }

        /* Furniture decorative corners */
        .corner-decoration {
            position: absolute;
            width: 20px;
            height: 20px;
            opacity: 0.1;
        }

        .corner-decoration.top-left {
            top: 15px;
            left: 15px;
            border-top: 2px solid var(--primary-color);
            border-left: 2px solid var(--primary-color);
        }

        .corner-decoration.top-right {
            top: 15px;
            right: 15px;
            border-top: 2px solid var(--primary-color);
            border-right: 2px solid var(--primary-color);
        }

        .corner-decoration.bottom-left {
            bottom: 15px;
            left: 15px;
            border-bottom: 2px solid var(--primary-color);
            border-left: 2px solid var(--primary-color);
        }

        .corner-decoration.bottom-right {
            bottom: 15px;
            right: 15px;
            border-bottom: 2px solid var(--primary-color);
            border-right: 2px solid var(--primary-color);
        }
    </style>
</head>

<body>
    <!-- Decorative Corners -->
    <div class="corner-decoration top-left"></div>
    <div class="corner-decoration top-right"></div>
    <div class="corner-decoration bottom-left"></div>
    <div class="corner-decoration bottom-right"></div>

    <div class="register-container">
        <!-- Header -->
        <div class="register-header">
            <a href="{{ url('/') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> 
            </a>
            
            <div class="register-logo">
                <i class="fas fa-store"></i>
            </div>
            <h1 class="register-title">Create Your Account</h1>
            <p class="register-subtitle">Join DIMDI Furniture & Appliances Store</p>
        </div>

        <!-- Stepper Progress -->
        <div class="stepper-container">
            <div class="stepper-progress">
                <div class="progress-bar" id="progressBar"></div>
                
                <div class="step active" data-step="1">
                    <div class="step-icon">1</div>
                    <div class="step-label">Personal Info</div>
                </div>
                
                <div class="step" data-step="2">
                    <div class="step-icon">2</div>
                    <div class="step-label">Account Details</div>
                </div>
                
                <div class="step" data-step="3">
                    <div class="step-icon">3</div>
                    <div class="step-label">Address</div>
                </div>
                
                <div class="step" data-step="4">
                    <div class="step-icon">4</div>
                    <div class="step-label">Confirmation</div>
                </div>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Step 1: Personal Information -->
                <div class="form-step active" id="step1">

                    
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input id="first_name" type="text"
                                    class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                    value="{{ old('first_name') }}" required autocomplete="given-name">
                                
                                @error('first_name')
                                    <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input id="middle_name" type="text"
                                    class="form-control @error('middle_name') is-invalid @enderror" name="middle_name"
                                    value="{{ old('middle_name') }}" autocomplete="additional-name">
                             
                                @error('middle_name')
                                    <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                                <small class="text-muted form-text">Optional</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input id="last_name" type="text"
                                    class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                    value="{{ old('last_name') }}" required autocomplete="family-name">
                               
                                @error('last_name')
                                    <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="step-actions">
                        <div></div>
                        <button type="button" class="btn btn-primary" onclick="nextStep(2)">
                            Next
                        </button>
                    </div>
                </div>

                <!-- Step 2: Account Details -->
                <div class="form-step" id="step2">
                    
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address *</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email">
                              
                                @error('email')
                                    <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input id="phone" type="tel"
                                    class="form-control @error('phone') is-invalid @enderror" name="phone"
                                    value="{{ old('phone') }}" required autocomplete="tel">
                    
                                @error('phone')
                                    <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">Password *</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password">
                  
                                @error('password')
                                    <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password-confirm" class="form-label">Confirm Password *</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
      
                            </div>
                        </div>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn btn-outline-primary" onclick="prevStep(1)">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(3)">
                            Next 
                        </button>
                    </div>
                </div>

                <!-- Step 3: Address Information -->
                <div class="form-step" id="step3">
   
                    
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="province" class="form-label">Province *</label>
                                <select id="province" name="province" class="form-control" required>
                                    <option value="">Select Province</option>
                                </select>
                           
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city" class="form-label">City *</label>
                                <select id="city" name="city" class="form-control" required>
                                    <option value="">Select City</option>
                                </select>
                   
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="barangay" class="form-label">Barangay *</label>
                                <select id="barangay" name="barangay" class="form-control" required>
                                    <option value="">Select Barangay</option>
                                </select>
                 
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="region" id="region">

                    <div class="row g-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="street_address" class="form-label">Street Address *</label>
                                <textarea id="street_address" name="street_address" class="form-control" rows="2" required>{{ old('street_address') }}</textarea>
                       
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="country" class="form-label">Country *</label>
                                <input id="country" type="text" name="country" class="form-control"
                                    value="{{ old('country', 'Philippines') }}" required readonly>
                              
                            </div>
                        </div>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn btn-outline-primary" onclick="prevStep(2)">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(4)">
                            Next 
                        </button>
                    </div>
                </div>

                <!-- Step 4: Confirmation & NDA -->
                <div class="form-step" id="step4">
                  
                    <!-- Summary -->
                    <div class="mb-3">
                        <h5 class="mb-2" style="color: var(--text-dark); font-size: 1rem;">
                            <i class="fas fa-clipboard-list me-2"></i>Please review your information:
                        </h5>
                        <div id="summary" class="border rounded">
                            <!-- Summary will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- NDA Agreement -->
                    <div class="nda-section">
                        <div class="nda-title">
                            <i class="fas fa-shield-alt me-1"></i>
                            Confidentiality Agreement
                        </div>
                        <div class="nda-checkbox">
                            <input type="checkbox" id="ndaAgreement" name="nda_agreement" required>
                            <label for="ndaAgreement">
                                I agree to the <span class="nda-link" style="color: var(--primary-color); cursor: pointer;" 
                                    data-bs-toggle="modal" data-bs-target="#ndaModal">Non-Disclosure Agreement</span>
                                and understand that unauthorized disclosure may result in legal action.
                            </label>
                        </div>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn btn-outline-primary" onclick="prevStep(3)">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i> Create Account
                        </button>
                    </div>
                </div>
            </form>

            <!-- Login Link -->
            <div class="login-link">
                <p class="mb-0">
                    Already have an account?
                    <a href="{{ route('login') }}">Login here</a>
                </p>
            </div>
        </div>
    </div>

    <!-- NDA Modal -->
    <div class="modal fade nda-modal" id="ndaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-shield-alt me-2"></i>
                        Non-Disclosure Agreement
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div style="font-size: 0.85rem; line-height: 1.4;">
                        <h4>CONFIDENTIALITY AGREEMENT</h4>
                        <p><strong>Effective Date:</strong> {{ date('F d, Y') }}</p>
                        
                        <h4>1. PURPOSE</h4>
                        <p>The purpose of this Non-Disclosure Agreement (NDA) is to protect confidential information that may be disclosed between the parties.</p>
                        
                        <h4>2. DEFINITION OF CONFIDENTIAL INFORMATION</h4>
                        <p>For purposes of this Agreement, "Confidential Information" shall include all information or material that has or could have commercial value or other utility in the business in which Disclosing Party is engaged.</p>
                        
                        <h4>3. OBLIGATIONS OF RECEIVING PARTY</h4>
                        <p>Both parties agree to:</p>
                        <ul>
                            <li>Maintain the confidentiality of the Confidential Information</li>
                            <li>Not disclose any Confidential Information to any third parties</li>
                            <li>Use the Confidential Information only for authorized purposes</li>
                        </ul>
                        
                        <div class="mt-3 p-2 bg-light rounded">
                            <p class="mb-0"><strong>By checking the agreement box and creating an account, you acknowledge that you have read, understood, and agree to be bound by the terms of this Non-Disclosure Agreement.</strong></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentStep = 1;
        const totalSteps = 4;

        // Generate session token for security
        function generateSessionToken() {
            return btoa(Date.now() + '-' + Math.random());
        }

        // Initialize stepper
        function initStepper() {
            updateProgressBar();
        }

        // Enhanced form submission with password encryption
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!validateStep(4)) {
                alert('Please complete all required fields and agree to the NDA.');
                return;
            }
            
            const ndaCheckbox = document.getElementById('ndaAgreement');
            if (!ndaCheckbox.checked) {
                alert('Please agree to the Non-Disclosure Agreement.');
                ndaCheckbox.focus();
                return;
            }

            try {
                // Show loading state
                const submitBtn = document.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creating Account...';
                submitBtn.disabled = true;

                // Get form data
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password-confirm').value;
                
                // Encrypt passwords
                const encryptedPassword = btoa(password);
                const encryptedConfirmPassword = btoa(confirmPassword);
                const sessionToken = generateSessionToken();
                
                // Create form data with encrypted passwords
                const formData = new FormData();
                formData.append('first_name', document.getElementById('first_name').value);
                formData.append('middle_name', document.getElementById('middle_name').value);
                formData.append('last_name', document.getElementById('last_name').value);
                formData.append('email', document.getElementById('email').value);
                formData.append('password', encryptedPassword);
                formData.append('password_confirmation', encryptedConfirmPassword);
                formData.append('phone', document.getElementById('phone').value);
                formData.append('street_address', document.getElementById('street_address').value);
                formData.append('province', document.getElementById('province').value);
                formData.append('city', document.getElementById('city').value);
                formData.append('barangay', document.getElementById('barangay').value);
                formData.append('region', document.getElementById('region').value);
                formData.append('country', document.getElementById('country').value);
                formData.append('nda_agreement', '1');
                formData.append('session_token', sessionToken);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                // Send encrypted request
                const response = await fetch('{{ route("register") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                // Check if response is JSON (Laravel returns JSON for AJAX requests)
                const contentType = response.headers.get('content-type');
                
                if (contentType && contentType.includes('application/json')) {
                    const data = await response.json();
                    
                    if (response.ok) {
                        // Success: Laravel returns JSON with redirect URL or success message
                        if (data.redirect || data.url) {
                            window.location.href = data.redirect || data.url;
                        } else if (data.message) {
                            // Show success message and redirect
                            alert(data.message);
                            window.location.href = data.redirect_url || '{{ route("home") }}';
                        } else {
                            // Default redirect
                            window.location.href = '{{ route("home") }}';
                        }
                    } else {
                        // Laravel validation errors or other errors
                        throw new Error(data.message || 'Registration failed');
                    }
                } else {
                    // Non-JSON response (likely HTML redirect or error page)
                    if (response.ok) {
                        // Successful redirect - let the browser handle it
                        const redirectUrl = response.url || '{{ route("home") }}';
                        window.location.href = redirectUrl;
                    } else {
                        throw new Error('Registration failed. Please try again.');
                    }
                }
            } catch (error) {
                console.error('Registration error:', error);
                
                // Remove existing error messages
                const existingError = document.querySelector('.alert.alert-danger');
                if (existingError) {
                    existingError.remove();
                }
                
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger mt-3';
                errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${error.message || 'Registration failed. Please check your information and try again.'}`;
                
                // Add error message
                document.querySelector('.stepper-container').insertBefore(errorDiv, document.querySelector('.stepper-container').firstChild);
                
                // Reset button state
                const submitBtn = document.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-user-plus me-1"></i> Create Account';
                submitBtn.disabled = false;
                
                // Clear password fields for security
                document.getElementById('password').value = '';
                document.getElementById('password-confirm').value = '';
                
                // Scroll to top to show error
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        // Navigate to next step
        function nextStep(step) {
            if (validateStep(currentStep)) {
                document.getElementById(`step${currentStep}`).classList.remove('active');
                document.querySelector(`[data-step="${currentStep}"]`).classList.remove('active');
                
                currentStep = step;
                document.getElementById(`step${currentStep}`).classList.add('active');
                document.querySelector(`[data-step="${currentStep}"]`).classList.add('active');
                updateProgressBar();
                
                // Generate summary on step 4
                if (currentStep === 4) {
                    generateSummary();
                }
            }
        }

        // Navigate to previous step
        function prevStep(step) {
            document.getElementById(`step${currentStep}`).classList.remove('active');
            document.querySelector(`[data-step="${currentStep}"]`).classList.remove('active');
            
            currentStep = step;
            document.getElementById(`step${currentStep}`).classList.add('active');
            document.querySelector(`[data-step="${currentStep}"]`).classList.add('active');
            updateProgressBar();
        }

        // Update progress bar
        function updateProgressBar() {
            const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
            document.getElementById('progressBar').style.width = `${progress}%`;
            
            // Mark previous steps as completed
            document.querySelectorAll('.step').forEach((step, index) => {
                const stepNum = parseInt(step.dataset.step);
                if (stepNum < currentStep) {
                    step.classList.add('completed');
                    step.classList.remove('active');
                } else if (stepNum === currentStep) {
                    step.classList.add('active');
                    step.classList.remove('completed');
                } else {
                    step.classList.remove('active', 'completed');
                }
            });
        }

        // Validate current step
        function validateStep(step) {
            let isValid = true;
            const stepElement = document.getElementById(`step${step}`);
            const inputs = stepElement.querySelectorAll('input[required], select[required], textarea[required]');
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            // Special validation for password match
            if (step === 2) {
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('password-confirm');
                if (password.value !== confirmPassword.value) {
                    confirmPassword.classList.add('is-invalid');
                    isValid = false;
                }
            }
            
            return isValid;
        }

        // Generate summary for step 4
        function generateSummary() {
            const summaryDiv = document.getElementById('summary');
            const fields = [
                { id: 'first_name', label: 'First Name' },
                { id: 'middle_name', label: 'Middle Name' },
                { id: 'last_name', label: 'Last Name' },
                { id: 'email', label: 'Email' },
                { id: 'phone', label: 'Phone' },
                { id: 'street_address', label: 'Address' },
                { id: 'province', label: 'Province' },
                { id: 'city', label: 'City' },
                { id: 'barangay', label: 'Barangay' },
                { id: 'country', label: 'Country' }
            ];
            
            let html = '<div class="row g-1">';
            fields.forEach(field => {
                const element = document.getElementById(field.id);
                if (element) {
                    const value = element.value || (element.options ? element.options[element.selectedIndex].text : '');
                    if (value) {
                        html += `
                            <div class="col-md-6 p-2">
                                <strong>${field.label}:</strong> <span style="color: #555;">${value}</span>
                            </div>
                        `;
                    }
                }
            });
            html += '</div>';
            summaryDiv.innerHTML = html;
        }

        // Clear validation on input
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            initStepper();
            
            // Auto-check NDA when "I Understand" is clicked
            document.querySelector('#ndaModal .btn-primary').addEventListener('click', function() {
                document.getElementById('ndaAgreement').checked = true;
            });

            // Address API
            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');
            const barangaySelect = document.getElementById('barangay');
            const regionInput = document.getElementById('region');

            // Fetch provinces
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
                });

            // Fetch cities when province is selected
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
                        });
                }
            });

            // Fetch barangays when city is selected
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
                        });
                }
            });
        });
    </script>
</body>
</html>