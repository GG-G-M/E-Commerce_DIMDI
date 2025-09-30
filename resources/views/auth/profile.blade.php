@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">My Profile</h4>
                </div>
                <div class="card-body p-4">
                    <!-- Profile Update Form -->
                    <form method="POST" action="{{ route('profile.update') }}" class="mb-5">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-3">Personal Information</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   name="phone" value="{{ old('phone', $user->phone) }}" required>
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea id="address" class="form-control @error('address') is-invalid @enderror" 
                                      name="address" required rows="3">{{ old('address', $user->address) }}</textarea>
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
                                       name="city" value="{{ old('city', $user->city) }}" required>
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State *</label>
                                <input id="state" type="text" class="form-control @error('state') is-invalid @enderror" 
                                       name="state" value="{{ old('state', $user->state) }}" required>
                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="zip_code" class="form-label">ZIP Code *</label>
                                <input id="zip_code" type="text" class="form-control @error('zip_code') is-invalid @enderror" 
                                       name="zip_code" value="{{ old('zip_code', $user->zip_code) }}" required>
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
                                   name="country" value="{{ old('country', $user->country) }}" required>
                            @error('country')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Update Profile
                            </button>
                        </div>
                    </form>

                    <!-- Password Update Form -->
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-3">Change Password</h5>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password *</label>
                            <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   name="current_password" required>
                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">New Password *</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password *</label>
                                <input id="password_confirmation" type="password" class="form-control" 
                                       name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection