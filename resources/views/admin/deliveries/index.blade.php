@extends('layouts.admin')

@section('title', 'Delivery Management')

@section('content')
<style>
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-1px);
    }

    .btn-info {
        background: #17a2b8;
        border: none;
    }

    .btn-warning {
        background: #FBC02D;
        border: none;
        color: #fff;
    }

    .btn-danger {
        background: #C62828;
        border: none;
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 1rem;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    /* Status badges */
    .badge-success {
        font-weight: 600;
        color: #000000ff;
    }

    .badge-danger {
        color: #000000ff;
        font-weight: 600;
    }

    .badge-info {
        font-weight: 600;
        color: #000000ff;
    }

    .badge-warning {
        background-color: #FF9800 !important;
        color: #000;
    }

    /* Button group styling */
    .btn-group .btn {
        margin-right: 0.25rem;
        border-radius: 6px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    /* Empty state styling */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #6c757d;
    }

    /* Delivery person avatar */
    .delivery-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .search-loading {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }

    .position-relative {
        position: relative;
    }

    /* Theme consistent buttons */
    .btn-outline-success {
        border-color: #2C8F0C;
        color: #2C8F0C;
    }

    .btn-outline-success:hover {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
        color: white;
    }

    .btn-outline-warning {
        border-color: #FBC02D;
        color: #FBC02D;
    }

    .btn-outline-warning:hover {
        background-color: #FBC02D;
        border-color: #FBC02D;
        color: white;
    }

    .btn-outline-danger {
        border-color: #C62828;
        color: #C62828;
    }

    .btn-outline-danger:hover {
        background-color: #C62828;
        border-color: #C62828;
        color: white;
    }

    /* Modal Styles */
    .modal-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1.25rem;
    }

    .modal-header-custom .modal-title {
        font-weight: 700;
    }

    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .form-label {
        font-weight: 600;
        color: #2C8F0C;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #C8E6C9;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.15rem rgba(44,143,12,0.2);
    }

    .form-select:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.15rem rgba(44,143,12,0.2);
    }

    .form-check-input:checked {
        background-color: #2C8F0C;
        border-color: #2C8F0C;
    }

    .tips-box {
        background-color: #F8FDF8;
        border-left: 4px solid #2C8F0C;
        border-radius: 8px;
        padding: 1rem;
        font-size: 0.9rem;
        color: #2C8F0C;
    }

    .tips-box i {
        color: #2C8F0C;
        margin-right: 5px;
    }

    .status-toggle {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .status-toggle .form-check {
        margin-bottom: 0;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.25);
    }
</style>

<!-- Filters -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.deliveries.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Delivery Personnel</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Search by name, email, or phone...">
                        <div class="search-loading" id="searchLoading">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Filter by Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="vehicle_type" class="form-label fw-bold">Vehicle Type</label>
                        <select class="form-select" id="vehicle_type" name="vehicle_type">
                            <option value="">All Vehicles</option>
                            <option value="motorcycle" {{ request('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                            <option value="car" {{ request('vehicle_type') == 'car' ? 'selected' : '' }}>Car</option>
                            <option value="van" {{ request('vehicle_type') == 'van' ? 'selected' : '' }}>Van</option>
                            <option value="truck" {{ request('vehicle_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="per_page" class="form-label fw-bold">Items per page</label>
                        <select class="form-select" id="per_page" name="per_page">
                            @foreach([5, 10, 15, 25, 50] as $option)
                                <option value="{{ $option }}" {{ request('per_page', 10) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delivery Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Delivery Personnel</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeliveryModal">
            <i class="fas fa-plus me-1"></i> Add Delivery Person
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Vehicle Type</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $delivery)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="delivery-avatar">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="ms-3">
                                    <strong>{{ $delivery->name }}</strong>
                                    @if($delivery->vehicle_number)
                                    <br>
                                    <small class="text-muted">{{ $delivery->vehicle_number }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $delivery->email }}</td>
                        <td>{{ $delivery->phone }}</td>
                        <td>
                            <span class="badge badge-info">{{ $delivery->vehicle_type }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $delivery->is_active ? 'success' : 'danger' }}">
                                {{ $delivery->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-success edit-delivery-btn"
                                        data-bs-toggle="modal" data-bs-target="#editDeliveryModal"
                                        data-id="{{ $delivery->id }}"
                                        data-first_name="{{ $delivery->first_name }}"
                                        data-last_name="{{ $delivery->last_name }}"
                                        data-email="{{ $delivery->email }}"
                                        data-phone="{{ $delivery->phone }}"
                                        data-vehicle_type="{{ $delivery->vehicle_type }}"
                                        data-vehicle_number="{{ $delivery->vehicle_number }}"
                                        data-license_number="{{ $delivery->license_number }}"
                                        data-is_active="{{ $delivery->is_active }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.deliveries.destroy', $delivery) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this delivery person?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-truck"></i>
                                <h4 class="text-muted">No Delivery Personnel Found</h4>
                                <p class="text-muted mb-4">Get started by adding your first delivery person</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeliveryModal">
                                    <i class="fas fa-plus me-2"></i>Add First Delivery Person
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deliveries->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $deliveries->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- Add Delivery Modal -->
<div class="modal fade" id="addDeliveryModal" tabindex="-1" aria-labelledby="addDeliveryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="addDeliveryModalLabel">
                    <i class="fas fa-user-plus me-2"></i> Add New Delivery Person
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.deliveries.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_first_name" class="form-label">First Name *</label>
                                <input type="text" id="add_first_name" name="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    placeholder="Enter first name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_last_name" class="form-label">Last Name *</label>
                                <input type="text" id="add_last_name" name="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    placeholder="Enter last name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_email" class="form-label">Email *</label>
                                <input type="email" id="add_email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Enter email address" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_phone" class="form-label">Phone Number *</label>
                                <input type="text" id="add_phone" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Enter phone number" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Vehicle Information -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_vehicle_type" class="form-label">Vehicle Type *</label>
                                <select class="form-select @error('vehicle_type') is-invalid @enderror" 
                                        id="add_vehicle_type" name="vehicle_type" required>
                                    <option value="">Select Vehicle Type</option>
                                    <option value="Motorcycle" {{ old('vehicle_type') == 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                    <option value="Car" {{ old('vehicle_type') == 'Car' ? 'selected' : '' }}>Car</option>
                                    <option value="Van" {{ old('vehicle_type') == 'Van' ? 'selected' : '' }}>Van</option>
                                    <option value="Truck" {{ old('vehicle_type') == 'Truck' ? 'selected' : '' }}>Truck</option>
                                    <option value="Bicycle" {{ old('vehicle_type') == 'Bicycle' ? 'selected' : '' }}>Bicycle</option>
                                </select>
                                @error('vehicle_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_vehicle_number" class="form-label">Vehicle Number</label>
                                <input type="text" id="add_vehicle_number" name="vehicle_number"
                                    class="form-control @error('vehicle_number') is-invalid @enderror"
                                    placeholder="Enter vehicle number" value="{{ old('vehicle_number') }}">
                                @error('vehicle_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- License and Status -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_license_number" class="form-label">License Number</label>
                                <input type="text" id="add_license_number" name="license_number"
                                    class="form-control @error('license_number') is-invalid @enderror"
                                    placeholder="Enter license number" value="{{ old('license_number') }}">
                                @error('license_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_is_active" class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="add_is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="add_is_active">Active Account</label>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_password" class="form-label">Password *</label>
                                <input type="password" id="add_password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_password_confirmation" class="form-label">Confirm Password *</label>
                                <input type="password" id="add_password_confirmation" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Confirm password" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="tips-box mt-4">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Tips:</strong> Make sure to provide accurate contact information. The delivery person will use this email and password to login to the delivery app.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Delivery Person
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Delivery Modal -->
<div class="modal fade" id="editDeliveryModal" tabindex="-1" aria-labelledby="editDeliveryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="editDeliveryModalLabel">
                    <i class="fas fa-edit me-2"></i> Edit Delivery Person
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDeliveryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_first_name" class="form-label">First Name *</label>
                                <input type="text" id="edit_first_name" name="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    placeholder="Enter first name" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_last_name" class="form-label">Last Name *</label>
                                <input type="text" id="edit_last_name" name="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    placeholder="Enter last name" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email *</label>
                                <input type="email" id="edit_email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Enter email address" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_phone" class="form-label">Phone Number *</label>
                                <input type="text" id="edit_phone" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Enter phone number" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Vehicle Information -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_vehicle_type" class="form-label">Vehicle Type *</label>
                                <select class="form-select @error('vehicle_type') is-invalid @enderror" 
                                        id="edit_vehicle_type" name="vehicle_type" required>
                                    <option value="">Select Vehicle Type</option>
                                    <option value="Motorcycle">Motorcycle</option>
                                    <option value="Car">Car</option>
                                    <option value="Van">Van</option>
                                    <option value="Truck">Truck</option>
                                    <option value="Bicycle">Bicycle</option>
                                </select>
                                @error('vehicle_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_vehicle_number" class="form-label">Vehicle Number</label>
                                <input type="text" id="edit_vehicle_number" name="vehicle_number"
                                    class="form-control @error('vehicle_number') is-invalid @enderror"
                                    placeholder="Enter vehicle number">
                                @error('vehicle_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- License and Status -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_license_number" class="form-label">License Number</label>
                                <input type="text" id="edit_license_number" name="license_number"
                                    class="form-control @error('license_number') is-invalid @enderror"
                                    placeholder="Enter license number">
                                @error('license_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_is_active" class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="edit_is_active" name="is_active" value="1">
                                    <label class="form-check-label" for="edit_is_active">Active Account</label>
                                </div>
                            </div>
                        </div>

                        <!-- Password (Optional) -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_password" class="form-label">Password (Leave blank to keep current)</label>
                                <input type="password" id="edit_password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter new password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" id="edit_password_confirmation" name="password_confirmation"
                                    class="form-control"
                                    placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>

                    <div class="tips-box mt-4">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Note:</strong> Only fill the password fields if you want to change the password. The vehicle type and license information are important for delivery tracking.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Delivery Person
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const vehicleTypeSelect = document.getElementById('vehicle_type');
    const perPageSelect = document.getElementById('per_page');
    const searchLoading = document.getElementById('searchLoading');
    
    let searchTimeout;

    // Auto-submit search with delay
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchLoading.style.display = 'block';
        
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 800);
    });

    // Auto-submit status filter immediately
    statusSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    // Auto-submit vehicle type filter immediately
    vehicleTypeSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    // Auto-submit per page selection immediately
    perPageSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    // Clear loading indicator when form submits
    filterForm.addEventListener('submit', function() {
        searchLoading.style.display = 'none';
    });

    // Edit delivery modal handling
    const editDeliveryButtons = document.querySelectorAll('.edit-delivery-btn');
    const editDeliveryForm = document.getElementById('editDeliveryForm');
    const editFirstNameInput = document.getElementById('edit_first_name');
    const editLastNameInput = document.getElementById('edit_last_name');
    const editEmailInput = document.getElementById('edit_email');
    const editPhoneInput = document.getElementById('edit_phone');
    const editVehicleTypeSelect = document.getElementById('edit_vehicle_type');
    const editVehicleNumberInput = document.getElementById('edit_vehicle_number');
    const editLicenseNumberInput = document.getElementById('edit_license_number');
    const editIsActiveInput = document.getElementById('edit_is_active');

    editDeliveryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const firstName = this.getAttribute('data-first_name');
            const lastName = this.getAttribute('data-last_name');
            const email = this.getAttribute('data-email');
            const phone = this.getAttribute('data-phone');
            const vehicleType = this.getAttribute('data-vehicle_type');
            const vehicleNumber = this.getAttribute('data-vehicle_number');
            const licenseNumber = this.getAttribute('data-license_number');
            const isActive = this.getAttribute('data-is_active') === '1';

            // Set form action URL
            editDeliveryForm.action = `/admin/deliveries/${id}`;
            
            // Populate form fields
            editFirstNameInput.value = firstName;
            editLastNameInput.value = lastName;
            editEmailInput.value = email;
            editPhoneInput.value = phone;
            editVehicleTypeSelect.value = vehicleType;
            editVehicleNumberInput.value = vehicleNumber;
            editLicenseNumberInput.value = licenseNumber;
            editIsActiveInput.checked = isActive;
        });
    });

    // Handle modal form validation
    const addDeliveryForm = document.querySelector('#addDeliveryModal form');
    const editDeliveryFormElement = document.querySelector('#editDeliveryModal form');

    [addDeliveryForm, editDeliveryFormElement].forEach(form => {
        if (form) {
            form.addEventListener('submit', function(e) {
                const requiredFields = this.querySelectorAll('[required]');
                let hasError = false;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        e.preventDefault();
                        field.classList.add('is-invalid');
                        hasError = true;
                        
                        // Remove invalid class when user starts typing
                        field.addEventListener('input', function() {
                            this.classList.remove('is-invalid');
                        }, { once: true });
                    }
                });

                // Check password confirmation for add form
                if (form === addDeliveryForm) {
                    const password = this.querySelector('#add_password');
                    const confirmPassword = this.querySelector('#add_password_confirmation');
                    
                    if (password.value !== confirmPassword.value) {
                        e.preventDefault();
                        confirmPassword.classList.add('is-invalid');
                        confirmPassword.nextElementSibling.textContent = 'Passwords do not match';
                        hasError = true;
                        
                        confirmPassword.addEventListener('input', function() {
                            if (password.value === this.value) {
                                this.classList.remove('is-invalid');
                            }
                        }, { once: true });
                    }
                }

                if (hasError) {
                    // Focus on first invalid field
                    const firstInvalid = this.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                }
            });
        }
    });

    // Clear form when modal is closed
    const addDeliveryModal = document.getElementById('addDeliveryModal');
    addDeliveryModal.addEventListener('hidden.bs.modal', function () {
        const form = this.querySelector('form');
        if (form) {
            form.reset();
            const invalidFields = form.querySelectorAll('.is-invalid');
            invalidFields.forEach(field => {
                field.classList.remove('is-invalid');
            });
        }
    });

    // Clear edit form when modal is closed
    const editDeliveryModal = document.getElementById('editDeliveryModal');
    editDeliveryModal.addEventListener('hidden.bs.modal', function () {
        const form = this.querySelector('form');
        if (form) {
            const invalidFields = form.querySelectorAll('.is-invalid');
            invalidFields.forEach(field => {
                field.classList.remove('is-invalid');
            });
        }
    });

    // Show modal if there are validation errors
    @if($errors->any())
        @if($errors->has('first_name') || $errors->has('email') || $errors->has('password'))
            @if(request()->routeIs('admin.deliveries.index') || request()->routeIs('admin.deliveries.store'))
                const addModal = new bootstrap.Modal(document.getElementById('addDeliveryModal'));
                addModal.show();
            @elseif(request()->routeIs('admin.deliveries.update'))
                const editModal = new bootstrap.Modal(document.getElementById('editDeliveryModal'));
                editModal.show();
            @endif
        @endif
    @endif
});
</script>
@endpush

@endsection