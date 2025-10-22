@extends('layouts.app')

@section('content')
<style>
    /* === Green Theme Styles === */
    body {
        background: #e9f7ef;
    }

    .card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #28a745, #218838);
        color: #fff;
        text-align: center;
        padding: 1.5rem;
    }

    .card-header h4 {
        font-weight: 600;
        margin: 0;
    }

    .card-body {
        background: #ffffff;
        border-top: 4px solid #28a745;
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: #2d572c;
    }

    .form-control,
    textarea {
        border: 1px solid #c7e6c3;
        border-radius: 8px;
        padding: 10px;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    textarea:focus {
        border-color: #28a745;
        box-shadow: 0 0 5px rgba(40, 167, 69, 0.3);
    }

    .btn-primary {
        background: linear-gradient(135deg, #28a745, #218838);
        border: none;
        border-radius: 8px;
        font-weight: bold;
        letter-spacing: 0.5px;
        padding: 10px;
        transition: background 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
    }

    .form-check-label {
        color: #2d572c;
    }

    .text-center a {
        color: #28a745;
        font-weight: 600;
    }

    .text-center a:hover {
        text-decoration: underline;
    }

    .card.shadow {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    small.form-text.text-muted {
        color: #6c757d !important;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0">Create Your Account</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input id="first_name" type="text" 
                                       class="form-control @error('first_name') is-invalid @enderror" 
                                       name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" autofocus>
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input id="middle_name" type="text" 
                                       class="form-control @error('middle_name') is-invalid @enderror" 
                                       name="middle_name" value="{{ old('middle_name') }}" autocomplete="additional-name">
                                @error('middle_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">Optional</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input id="last_name" type="text" 
                                       class="form-control @error('last_name') is-invalid @enderror" 
                                       name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name">
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
                                <input id="email" type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input id="phone" type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone') }}" required autocomplete="tel">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password-confirm" class="form-label">Confirm Password *</label>
                                <input id="password-confirm" type="password" 
                                       class="form-control" 
                                       name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea id="address" 
                                      class="form-control @error('address') is-invalid @enderror" 
                                      name="address" required rows="3" autocomplete="street-address">{{ old('address') }}</textarea>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input id="city" type="text" 
                                       class="form-control @error('city') is-invalid @enderror" 
                                       name="city" value="{{ old('city') }}" required autocomplete="address-level2">
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">Province *</label>
                                <input id="state" type="text" 
                                       class="form-control @error('state') is-invalid @enderror" 
                                       name="state" value="{{ old('state') }}" required autocomplete="address-level1">
                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="zip_code" class="form-label">ZIP Code *</label>
                                <input id="zip_code" type="text" 
                                       class="form-control @error('zip_code') is-invalid @enderror" 
                                       name="zip_code" value="{{ old('zip_code') }}" required autocomplete="postal-code">
                                @error('zip_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="country" class="form-label">Country *</label>
                            <input id="country" type="text" 
                                   class="form-control @error('country') is-invalid @enderror" 
                                   name="country" value="{{ old('country') }}" required autocomplete="country-name">
                            @error('country')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Create Account
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="text-decoration-none">Login here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
