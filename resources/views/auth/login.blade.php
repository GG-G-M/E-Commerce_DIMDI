@extends('layouts.login')

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

    .form-control {
        border: 1px solid #c7e6c3;
        border-radius: 8px;
        padding: 10px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
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

    /* Shadow + smooth appearance */
    .card.shadow {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0">Login to Your Account</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" 
                                   required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="remember" id="remember" 
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Login
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0">
                                Don't have an account? 
                                <a href="{{ route('register') }}" class="text-decoration-none">Register here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
