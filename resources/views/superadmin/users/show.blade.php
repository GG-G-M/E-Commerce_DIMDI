@extends('layouts.superadmin')

@section('content')
<style>
    :root {
        --primary: #2C8F0C;
        --primary-light: #E8F5E9;
        --primary-dark: #1B5E20;
        --gray-50: #F9FAFB;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-300: #D1D5DB;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
    }
<<<<<<< HEAD

    /* Remove page header card */
    .page-header {
        background: transparent;
        border: none;
        box-shadow: none;
        padding: 0;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        color: var(--gray-800);
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.75rem;
    }

    /* Cards */
    .card-clean {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        background: white;
    }

    .card-header-clean {
        background: white;
        border-bottom: 2px solid var(--gray-200);
        padding: 1.25rem 1.5rem;
        border-radius: 12px 12px 0 0;
    }

    .card-header-clean h5 {
        font-weight: 600;
        color: var(--gray-800);
        margin: 0;
    }

    .card-body-clean {
        padding: 1.5rem;
    }

    /* Profile Avatar */
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-light), var(--primary));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin: 0 auto 1.5rem;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.2);
    }

    .user-name {
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 0.25rem;
        text-align: center;
    }

    .user-email {
        color: var(--gray-600);
        font-size: 0.95rem;
        margin-bottom: 1rem;
        text-align: center;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .info-item {
        background: var(--gray-50);
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid var(--gray-200);
    }

    .info-label {
        color: var(--gray-600);
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .info-value {
        color: var(--gray-800);
        font-weight: 600;
        font-size: 1rem;
    }

    /* Quick Stats */
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        background: white;
    }

    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-top: 1.5rem;
        width: 100%;
    }

    .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.75rem 1rem;
        border: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark), #1B5E20);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.2);
    }

    .btn-outline {
        background: white;
        color: var(--gray-700);
        border: 2px solid var(--gray-300);
    }

    .btn-outline:hover {
        background: var(--gray-50);
        border-color: var(--gray-400);
        color: var(--gray-800);
    }

    .btn-success {
        background: linear-gradient(135deg, #10B981, #059669);
        color: white;
    }

    .btn-warning {
        background: linear-gradient(135deg, #F59E0B, #D97706);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        color: white;
    }

    /* Self Account Alert */
    .self-account-alert {
        background: linear-gradient(135deg, #E0F2FE, #BAE6FD);
        border: 2px solid #0EA5E9;
        border-radius: 8px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
        width: 100%;
    }

    .self-account-alert i {
        color: #0EA5E9;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .self-account-alert h6 {
        margin: 0;
        color: #0369A1;
        font-weight: 600;
    }

    .self-account-alert p {
        margin: 0.25rem 0 0;
        color: #0C4A6E;
        font-size: 0.875rem;
    }

    /* Tabs */
    .detail-tabs {
        background: var(--gray-50);
        border-radius: 8px;
        padding: 0.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 0.25rem;
    }

    .detail-tab {
        flex: 1;
        padding: 0.75rem 1rem;
        border: none;
        background: transparent;
        color: var(--gray-600);
        font-weight: 500;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .detail-tab.active {
        background: white;
        color: var(--primary);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .detail-tab:hover:not(.active) {
        background: rgba(255, 255, 255, 0.5);
        color: var(--gray-700);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Modal */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .modal-header {
        border-bottom: 1px solid var(--gray-200);
        padding: 1.25rem 1.5rem;
    }

    .modal-title {
        font-weight: 600;
        color: var(--gray-800);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid var(--gray-200);
        padding: 1.25rem 1.5rem;
    }

    /* Form Elements */
    .form-label {
        font-weight: 500;
        color: var(--gray-700);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        padding: 0.75rem;
        font-size: 0.875rem;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(44, 143, 12, 0.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .quick-stats {
            grid-template-columns: 1fr;
        }
        
        .detail-tabs {
            flex-direction: column;
        }
    }
</style>

=======

    /* Page Header */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .page-header h1 {
        color: var(--gray-800);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-header .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 0.5rem;
    }

    .page-header .breadcrumb-item a {
        color: var(--primary);
        text-decoration: none;
    }

    /* Cards */
    .card-clean {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        background: white;
    }

    .card-header-clean {
        background: white;
        border-bottom: 2px solid var(--gray-200);
        padding: 1.25rem 1.5rem;
        border-radius: 12px 12px 0 0;
    }

    .card-header-clean h5 {
        font-weight: 600;
        color: var(--gray-800);
        margin: 0;
    }

    .card-body-clean {
        padding: 1.5rem;
    }

    /* Profile Avatar */
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-light), var(--primary));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin: 0 auto 1.5rem;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.2);
    }

  
    .user-name {
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 0.25rem;
    }

    .user-email {
        color: var(--gray-600);
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }

    /* Role Badge */
    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
        gap: 0.5rem;
    }

    .role-super-admin { background: #FEE2E2; color: #991B1B; }
    .role-admin { background: #E0F2FE; color: #0369A1; }
    .role-delivery { background: #FEF3C7; color: #92400E; }
    .role-customer { background: var(--gray-100); color: var(--gray-700); }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
        gap: 0.5rem;
    }

    .status-active { background: #DCFCE7; color: #166534; }
    .status-inactive { background: #FEE2E2; color: #991B1B; }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .info-item {
        background: var(--gray-50);
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid var(--gray-200);
    }

    .info-label {
        color: var(--gray-600);
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .info-value {
        color: var(--gray-800);
        font-weight: 600;
        font-size: 1rem;
    }

    /* Quick Stats */
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        background: white;
    }

    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.75rem 1rem;
        border: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark), #1B5E20);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.2);
    }

    .btn-outline {
        background: white;
        color: var(--gray-700);
        border: 2px solid var(--gray-300);
    }

    .btn-outline:hover {
        background: var(--gray-50);
        border-color: var(--gray-400);
        color: var(--gray-800);
    }

    .btn-success {
        background: linear-gradient(135deg, #10B981, #059669);
        color: white;
    }

    .btn-warning {
        background: linear-gradient(135deg, #F59E0B, #D97706);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        color: white;
    }

    /* Self Account Alert */
    .self-account-alert {
        background: linear-gradient(135deg, #E0F2FE, #BAE6FD);
        border: 2px solid #0EA5E9;
        border-radius: 8px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .self-account-alert i {
        color: #0EA5E9;
        font-size: 1.25rem;
    }

    .self-account-alert h6 {
        margin: 0;
        color: #0369A1;
        font-weight: 600;
    }

    .self-account-alert p {
        margin: 0.25rem 0 0;
        color: #0C4A6E;
        font-size: 0.875rem;
    }

    /* Tabs */
    .detail-tabs {
        background: var(--gray-50);
        border-radius: 8px;
        padding: 0.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 0.25rem;
    }

    .detail-tab {
        flex: 1;
        padding: 0.75rem 1rem;
        border: none;
        background: transparent;
        color: var(--gray-600);
        font-weight: 500;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .detail-tab.active {
        background: white;
        color: var(--primary);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .detail-tab:hover:not(.active) {
        background: rgba(255, 255, 255, 0.5);
        color: var(--gray-700);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Modal */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .modal-header {
        border-bottom: 1px solid var(--gray-200);
        padding: 1.25rem 1.5rem;
    }

    .modal-title {
        font-weight: 600;
        color: var(--gray-800);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid var(--gray-200);
        padding: 1.25rem 1.5rem;
    }

    /* Form Elements */
    .form-label {
        font-weight: 500;
        color: var(--gray-700);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        padding: 0.75rem;
        font-size: 0.875rem;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(44, 143, 12, 0.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .quick-stats {
            grid-template-columns: 1fr;
        }
        
        .detail-tabs {
            flex-direction: column;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('superadmin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">{{ $user->name }}</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>User Details</h1>
            <p class="text-muted mb-0">View and manage user information</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit User
            </a>
        </div>
    </div>
</div>

>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
<div class="row">
    <!-- Left Column - Profile Overview -->
    <div class="col-xl-4 col-lg-5">
        <!-- Profile Card -->
        <div class="card-clean">
            <div class="card-body-clean text-center">
                <!-- Avatar -->
                <div class="profile-avatar">
                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                </div>
                
                <!-- User Info -->
                <h3 class="user-name">{{ $user->name }}</h3>
                <p class="user-email">{{ $user->email }}</p>
<<<<<<< HEAD

                <!-- Role - Simple Text -->
                <div class="mb-3">
                    <div style="font-weight: 600; color: var(--gray-800);">
                        @if($user->isSuperAdmin())
                            Super Admin
                        @elseif($user->isAdmin())
                            Admin
                        @elseif($user->isDelivery())
                            Delivery Staff
                        @else
                            Customer
                        @endif
                    </div>
                </div>
                
                <!-- Status - Simple Text -->
                <div class="mb-3">
                    <div style="font-weight: 600; @if($user->is_active) color: #166534; @else color: #991B1B; @endif">
                        @if($user->is_active)
                            Active
                        @else
                            Inactive
                        @endif
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="quick-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $user->created_at->diffForHumans() }}</div>
                        <div class="stat-label">Account Age</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">
                            @if($user->email_verified_at)
                                Verified
                            @else
                                Not Verified
                            @endif
                        </div>
                        <div class="stat-label">Email Status</div>
                    </div>
                </div>
                
                <!-- Self Account Alert -->
                @if($user->id === auth()->id())
                <div class="self-account-alert">
                    <div>
                        <h6>Your Account</h6>
                        <p>You are viewing your own account details</p>
                    </div>
                </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    @if($user->id !== auth()->id())
                        <!-- Toggle Status -->
                        @if($user->is_active)
                            <form action="{{ route('superadmin.users.toggle-status', $user) }}" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    Deactivate User
                                </button>
                            </form>
                        @else
                            <form action="{{ route('superadmin.users.toggle-status', $user) }}" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    Activate User
                                </button>
                            </form>
                        @endif
                        
                        <!-- Reset Password -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                            Reset Password
=======
                
                <!-- Role Badge -->
                <div class="mb-3">
                    @if($user->isSuperAdmin())
                        <span class="role-badge role-super-admin">
                            <i class="fas fa-crown"></i>Super Admin
                        </span>
                    @elseif($user->isAdmin())
                        <span class="role-badge role-admin">
                            <i class="fas fa-user-shield"></i>Admin
                        </span>
                    @elseif($user->isDelivery())
                        <span class="role-badge role-delivery">
                            <i class="fas fa-truck"></i>Delivery Staff
                        </span>
                    @else
                        <span class="role-badge role-customer">
                            <i class="fas fa-user"></i>Customer
                        </span>
                    @endif
                </div>
                
                <!-- Status Badge -->
                <div class="mb-3">
                    @if($user->is_active)
                        <span class="status-badge status-active">
                            <i class="fas fa-check-circle"></i>Active
                        </span>
                    @else
                        <span class="status-badge status-inactive">
                            <i class="fas fa-times-circle"></i>Inactive
                        </span>
                    @endif
                </div>
                
                <!-- Quick Stats -->
                <div class="quick-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $user->created_at->diffForHumans() }}</div>
                        <div class="stat-label">Account Age</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">
                            @if($user->email_verified_at)
                                Verified
                            @else
                                Not Verified
                            @endif
                        </div>
                        <div class="stat-label">Email Status</div>
                    </div>
                </div>
                
                <!-- Self Account Alert -->
                @if($user->id === auth()->id())
                <div class="self-account-alert">
                    <i class="fas fa-user-circle"></i>
                    <div>
                        <h6>Your Account</h6>
                        <p>You are viewing your own account details</p>
                    </div>
                </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    @if($user->id !== auth()->id())
                        <!-- Toggle Status -->
                        @if($user->is_active)
                            <form action="{{ route('superadmin.users.toggle-status', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-user-slash me-2"></i>Deactivate
                                </button>
                            </form>
                        @else
                            <form action="{{ route('superadmin.users.toggle-status', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-user-check me-2"></i>Activate
                                </button>
                            </form>
                        @endif
                        
                        <!-- Reset Password -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                            <i class="fas fa-key me-2"></i>Reset Password
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
                        </button>
                        
                        <!-- Delete User -->
                        @if(!$user->isSuperAdmin())
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
<<<<<<< HEAD
                            Delete User
=======
                            <i class="fas fa-trash-alt me-2"></i>Delete User
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
                        </button>
                        @endif
                    @else
                        <!-- Self Account Actions -->
                        <a href="{{ route('profile.show') }}" class="btn btn-primary">
<<<<<<< HEAD
                            Edit Profile
                        </a>
                        <a href="{{ route('profile.password') }}" class="btn btn-primary">
                            Change Password
=======
                            <i class="fas fa-user-edit me-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('profile.password') }}" class="btn btn-primary">
                            <i class="fas fa-key me-2"></i>Change Password
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column - User Details -->
    <div class="col-xl-8 col-lg-7">
        <!-- Detail Tabs -->
        <div class="detail-tabs">
            <button class="detail-tab active" data-tab="personal">Personal Info</button>
            <button class="detail-tab" data-tab="contact">Contact Info</button>
            @if($user->role == 'delivery')
            <button class="detail-tab" data-tab="delivery">Delivery Info</button>
            @endif
            <button class="detail-tab" data-tab="account">Account Info</button>
        </div>
        
        <!-- Personal Information -->
        <div class="tab-content active" id="personal-tab">
            <div class="card-clean">
                <div class="card-header-clean">
                    <h5>Personal Information</h5>
                </div>
                <div class="card-body-clean">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">First Name</div>
                            <div class="info-value">{{ $user->first_name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Last Name</div>
                            <div class="info-value">{{ $user->last_name }}</div>
                        </div>
                        @if($user->middle_name)
                        <div class="info-item">
                            <div class="info-label">Middle Name</div>
                            <div class="info-value">{{ $user->middle_name }}</div>
                        </div>
                        @endif
                        <div class="info-item">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value">{{ $user->phone ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="tab-content" id="contact-tab">
            <div class="card-clean">
                <div class="card-header-clean">
                    <h5>Contact Information</h5>
                </div>
                <div class="card-body-clean">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Street Address</div>
                            <div class="info-value">{{ $user->street_address ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Barangay</div>
                            <div class="info-value">{{ $user->barangay ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">City</div>
                            <div class="info-value">{{ $user->city ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Province</div>
                            <div class="info-value">{{ $user->province ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Region</div>
                            <div class="info-value">{{ $user->region ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Country</div>
                            <div class="info-value">{{ $user->country ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">ZIP Code</div>
                            <div class="info-value">{{ $user->zip_code ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Delivery Information -->
        @if($user->role == 'delivery')
        <div class="tab-content" id="delivery-tab">
            <div class="card-clean">
                <div class="card-header-clean">
                    <h5>Delivery Information</h5>
                </div>
                <div class="card-body-clean">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Vehicle Type</div>
                            <div class="info-value">{{ $user->vehicle_type ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Vehicle Number</div>
                            <div class="info-value">{{ $user->vehicle_number ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">License Number</div>
                            <div class="info-value">{{ $user->license_number ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Account Information -->
        <div class="tab-content" id="account-tab">
            <div class="card-clean">
                <div class="card-header-clean">
                    <h5>Account Information</h5>
                </div>
                <div class="card-body-clean">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Account Created</div>
                            <div class="info-value">{{ $user->created_at->format('M d, Y h:i A') }}</div>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Last Updated</div>
                            <div class="info-value">{{ $user->updated_at->format('M d, Y h:i A') }}</div>
                            <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email Verification</div>
                            <div class="info-value">
                                @if($user->email_verified_at)
<<<<<<< HEAD
                                    <span style="color: #166534; font-weight: 600;">Verified</span>
                                @else
                                    <span style="color: #991B1B; font-weight: 600;">Not Verified</span>
=======
                                    <span class="status-badge status-active">Verified</span>
                                @else
                                    <span class="status-badge status-inactive">Not Verified</span>
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Account Status</div>
                            <div class="info-value">
                                @if($user->is_active)
<<<<<<< HEAD
                                    <span style="color: #166534; font-weight: 600;">Active</span>
                                @else
                                    <span style="color: #991B1B; font-weight: 600;">Inactive</span>
=======
                                    <span class="status-badge status-active">Active</span>
                                @else
                                    <span class="status-badge status-inactive">Inactive</span>
>>>>>>> e21b2ced8e67d9b402d56afb9e279460b25cb527
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">User ID</div>
                            <div class="info-value">
                                <code class="text-muted">{{ $user->id }}</code>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Role</div>
                            <div class="info-value">{{ ucfirst($user->role) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.users.reset-password', $user) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="password" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required minlength="8">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
@if(!$user->isSuperAdmin() && $user->id !== auth()->id())
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete User Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" id="deleteUserForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong>{{ $user->name }}</strong>? This action cannot be undone.</p>
                    <div class="mb-3">
                        <label class="form-label">
                            Type <span class="text-danger fw-bold">"DELETE"</span> to confirm:
                        </label>
                        <input type="text" class="form-control" id="confirmDelete" placeholder="Type DELETE here">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn" disabled>Delete User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabs = document.querySelectorAll('.detail-tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Update active tab
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Show active content
            tabContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === `${tabId}-tab`) {
                    content.classList.add('active');
                }
            });
        });
    });
    
    // Delete confirmation for modal
    const deleteUserModal = document.getElementById('deleteUserModal');
    if (deleteUserModal) {
        const confirmDeleteInput = document.getElementById('confirmDelete');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        
        confirmDeleteInput.addEventListener('input', function() {
            const confirmText = this.value.trim().toUpperCase();
            confirmDeleteBtn.disabled = confirmText !== 'DELETE';
        });
        
        // Reset form when modal is hidden
        deleteUserModal.addEventListener('hidden.bs.modal', function() {
            confirmDeleteInput.value = '';
            confirmDeleteBtn.disabled = true;
        });
    }
});
</script>
@endsection