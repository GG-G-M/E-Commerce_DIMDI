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

        /* Header Styles */
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

        /* Form Styles */
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

        /* Button Styles */
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

        /* NDA Section Styles */
        .nda-section {
            background: #f8fff8;
            border: 1px solid rgba(40, 167, 69, 0.2);
            border-radius: 8px;
            padding: 15px;
            margin: 1rem 0;
            font-size: 0.75rem;
        }

        .nda-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
            font-size: 0.8rem;
        }

        .nda-text {
            line-height: 1.4;
            margin-bottom: 10px;
            color: #555;
        }

        .nda-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-top: 10px;
        }

        .nda-checkbox input {
            margin-top: 2px;
        }

        .nda-checkbox label {
            font-size: 0.75rem;
            line-height: 1.3;
            color: #555;
            cursor: pointer;
        }

        .nda-link {
            color: var(--primary-color);
            text-decoration: underline;
            cursor: pointer;
        }

        .nda-link:hover {
            color: var(--primary-dark);
        }

        /* NDA Modal Styles */
        .nda-modal .modal-content {
            border-radius: 12px;
            border: none;
        }

        .nda-modal .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-bottom: none;
        }

        .nda-modal .modal-body {
            max-height: 400px;
            overflow-y: auto;
            padding: 20px;
        }

        .nda-content {
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .nda-content h4 {
            color: var(--primary-color);
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .nda-content p {
            margin-bottom: 0.8rem;
        }

        .nda-content ul {
            padding-left: 1.2rem;
            margin-bottom: 0.8rem;
        }

        .nda-content li {
            margin-bottom: 0.3rem;
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

        /* Home/Back Button */
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

        /* Enhanced Responsive Design */
        @media (max-width: 1200px) {
            .register-card {
                max-width: 900px;
            }
        }

        @media (max-width: 992px) {
            .register-image-section {
                display: none;
            }

            .register-card {
                max-width: 700px;
                min-height: auto;
            }

            .register-form-section {
                padding: 2rem;
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

            /* Improved mobile form layout */
            .row-cols-md-3>.col {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .row>[class*="col-"] {
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 10px;
                align-items: flex-start;
            }

            .register-card {
                margin: 10px 0;
                min-height: auto;
            }

            .register-form-section {
                padding: 1.25rem;
            }

            .form-group {
                margin-bottom: 0.6rem;
            }

            .btn-register {
                margin: 0.75rem 0;
                padding: 10px;
                font-size: 0.95rem;
            }

            /* Stack all columns on very small screens */
            .row>[class*="col-"] {
                flex: 0 0 100%;
                max-width: 100%;
            }

            /* Adjust form spacing for mobile */
            .form-control {
                padding: 8px 10px;
                font-size: 0.85rem;
            }

            .form-label {
                font-size: 0.8rem;
            }

            /* NDA section mobile adjustments */
            .nda-section {
                padding: 12px;
                margin: 0.75rem 0;
            }

            .nda-checkbox {
                align-items: flex-start;
            }

            .nda-checkbox label {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 400px) {
            .register-form-section {
                padding: 1rem;
            }

            .register-header {
                margin-bottom: 1.5rem;
            }

            .register-title {
                font-size: 1.2rem;
            }

            .register-subtitle {
                font-size: 0.8rem;
            }

            .home-btn {
                top: 10px;
                left: 10px;
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }

            .btn-register {
                padding: 8px;
                font-size: 0.9rem;
            }
        }

        @media (max-height: 700px) {
            body {
                align-items: flex-start;
                padding-top: 20px;
                padding-bottom: 20px;
            }

            .register-form-section {
                padding: 1.5rem 2rem;
                justify-content: flex-start;
            }

            .register-header {
                margin-bottom: 1.5rem;
            }

            .form-content {
                justify-content: flex-start;
            }
        }

        /* Print styles */
        @media print {

            .register-image-section,
            .home-btn {
                display: none;
            }

            .register-card {
                box-shadow: none;
                border: 1px solid #ddd;
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
                    <form method="POST" action="{{ route('register') }}" class="register-form" id="registerForm">
                        @csrf

                        <div class="row row-cols-1 row-cols-md-3">
                            <div class="col mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input id="first_name" type="text"
                                    class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                    value="{{ old('first_name') }}" required autocomplete="given-name" autofocus
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
                                    class="form-control @error('middle_name') is-invalid @enderror" name="middle_name"
                                    value="{{ old('middle_name') }}" autocomplete="additional-name"
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
                                    class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                    value="{{ old('last_name') }}" required autocomplete="family-name"
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
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email"
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
                                    class="form-control @error('phone') is-invalid @enderror" name="phone"
                                    value="{{ old('phone') }}" required autocomplete="tel" placeholder="Phone number">
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
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password" placeholder="Create password">

                                @error('password')
                                    <div class="invalid-feedback" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3 position-relative">
                                <label for="password-confirm" class="form-label">Confirm Password *</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Confirm password">
                            </div>
                        </div>

                        <div class="row row-cols-1 row-cols-md-3">
                            <div class="col mb-3">
                                <label for="province" class="form-label">Province *</label>
                                <select id="province" name="province" class="form-control" required>
                                    <option value="">Select Province</option>
                                </select>
                            </div>

                            <div class="col mb-3">
                                <label for="city" class="form-label">City / Municipality *</label>
                                <select id="city" name="city" class="form-control" required>
                                    <option value="">Select City</option>
                                </select>
                            </div>

                            <div class="col mb-3">
                                <label for="barangay" class="form-label">Barangay *</label>
                                <select id="barangay" name="barangay" class="form-control" required>
                                    <option value="">Select Barangay</option>
                                </select>
                            </div>
                        </div>

                        <!-- Hidden region field -->
                        <input type="hidden" name="region" id="region">

                        <div class="mb-3">
                            <label for="street_address" class="form-label">Street Address *</label>
                            <textarea id="street_address" name="street_address" class="form-control" rows="2" required
                                placeholder="House number, Purok, Subdivision...">{{ old('street_address') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label">Country *</label>
                            <input id="country" type="text" name="country" class="form-control"
                                value="{{ old('country', 'Philippines') }}" required readonly>
                        </div>

                        <!-- NDA Agreement Section -->
                        <div class="nda-section">
                            <div class="nda-title">
                                <i class="fas fa-shield-alt me-1"></i>
                                Confidentiality Agreement
                            </div>
                            <div class="nda-text">
                                By creating an account, you agree to maintain the confidentiality of all proprietary
                                information and data you may encounter while using our services.
                            </div>
                            <div class="nda-checkbox">
                                <input type="checkbox" id="ndaAgreement" name="nda_agreement" required>
                                <label for="ndaAgreement">
                                    I agree to the <span class="nda-link" data-bs-toggle="modal"
                                        data-bs-target="#ndaModal">Non-Disclosure Agreement</span>
                                    and understand that unauthorized disclosure may result in legal action.
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-register" style="color: white">
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

    <!-- NDA Modal -->
    <div class="modal fade nda-modal" id="ndaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-shield-alt me-2"></i>
                        Non-Disclosure Agreement
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="nda-content">
                        <h4>CONFIDENTIALITY AGREEMENT</h4>
                        <p><strong>Effective Date:</strong> {{ date('F d, Y') }}</p>

                        <h4>1. PURPOSE</h4>
                        <p>The purpose of this Non-Disclosure Agreement (NDA) is to protect confidential information
                            that may be disclosed between the parties.</p>

                        <h4>2. DEFINITION OF CONFIDENTIAL INFORMATION</h4>
                        <p>For purposes of this Agreement, "Confidential Information" shall include all information or
                            material that has or could have commercial value or other utility in the business in which
                            Disclosing Party is engaged.</p>

                        <h4>3. OBLIGATIONS OF RECEIVING PARTY</h4>
                        <p>Both parties agree to:</p>
                        <ul>
                            <li>Maintain the confidentiality of the Confidential Information</li>
                            <li>Not disclose any Confidential Information to any third parties</li>
                            <li>Use the Confidential Information only for authorized purposes</li>
                            <li>Protect the Confidential Information with the same degree of care used to protect their
                                own confidential information</li>
                        </ul>

                        <h4>4. TIME PERIODS</h4>
                        <p>The obligations of confidentiality shall survive the termination of this Agreement and shall
                            continue for a period of 3 years from the date of disclosure.</p>

                        <h4>5. RETURN OF CONFIDENTIAL INFORMATION</h4>
                        <p>Upon request, all documents and materials containing Confidential Information shall be
                            returned to the Disclosing Party or destroyed.</p>

                        <h4>6. REMEDIES</h4>
                        <p>Any violation of this Agreement may result in irreparable injury to the Disclosing Party, who
                            shall be entitled to seek equitable relief, including injunctive relief and specific
                            performance.</p>

                        <h4>7. GENERAL PROVISIONS</h4>
                        <p>This Agreement constitutes the entire understanding between the parties and supersedes all
                            prior discussions, representations, and understandings.</p>

                        <div class="mt-4 p-3 bg-light rounded">
                            <p class="mb-1"><strong>By checking the agreement box and creating an account, you
                                    acknowledge that you have read, understood, and agree to be bound by the terms of
                                    this Non-Disclosure Agreement.</strong></p>
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
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('registerForm');
            const ndaCheckbox = document.getElementById('ndaAgreement');

            // Form validation enhancements
            registerForm.addEventListener('submit', function(e) {
                let hasError = false;
                const requiredFields = registerForm.querySelectorAll('input[required], textarea[required]');

                // Check required fields
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        hasError = true;
                    }
                });

                // Check NDA agreement
                if (!ndaCheckbox.checked) {
                    e.preventDefault();
                    alert('Please agree to the Non-Disclosure Agreement to create your account.');
                    hasError = true;
                    ndaCheckbox.closest('.nda-section').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }

                // Check password match
                const password = document.getElementById('password');
                const passwordConfirm = document.getElementById('password-confirm');
                if (password.value !== passwordConfirm.value) {
                    passwordConfirm.classList.add('is-invalid');
                    hasError = true;
                }

                if (hasError) e.preventDefault();
            });

            // Clear validation on input
            const inputs = registerForm.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    if (this.id === 'password' || this.id === 'password-confirm')
                validatePassword();
                });
            });

            function validatePassword() {
                const password = document.getElementById('password');
                const passwordConfirm = document.getElementById('password-confirm');
                if (password.value && passwordConfirm.value && password.value !== passwordConfirm.value) {
                    passwordConfirm.classList.add('is-invalid');
                } else {
                    passwordConfirm.classList.remove('is-invalid');
                }
            }

            // Auto-check NDA if user clicks "I Understand" in modal
            document.querySelector('#ndaModal .btn-primary').addEventListener('click', function() {
                ndaCheckbox.checked = true;
            });

            // Adjust layout
            function adjustLayout() {
                const formContent = document.querySelector('.form-content');
                const container = document.querySelector('.register-form-section');
                if (window.innerHeight < 600 || window.innerWidth < 576) {
                    formContent.style.justifyContent = 'flex-start';
                    container.style.padding = '1rem';
                } else {
                    formContent.style.justifyContent = 'center';
                }
            }
            window.addEventListener('load', adjustLayout);
            window.addEventListener('resize', adjustLayout);
            window.addEventListener('orientationchange', () => setTimeout(adjustLayout, 100));

            // ----------- ADDRESS API SECTION ----------------
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
                        option.dataset.region = province.region ?? ''; // store region code
                        option.text = province.name;
                        provinceSelect.appendChild(option);
                    });
                });

            // Fetch cities when a province is selected
            provinceSelect.addEventListener('change', function() {
                const provinceCode = this.value;
                regionInput.value = this.options[this.selectedIndex].dataset.region || '';
                citySelect.innerHTML = '<option value="">Select City</option>';
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                if (!provinceCode) return;

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
            });

            // Fetch barangays when a city is selected
            citySelect.addEventListener('change', function() {
                const cityCode = this.value;
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                if (!cityCode) return;

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
            });
        });
    </script>
</body>

</html>
