@extends('layouts.admin')

@section('title', 'Delivery Management')

@section('content')
<style>
    /* === Consistent Green Theme === */
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-3px);
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
        font-size: 1.25rem;
    }

    /* Improved Add Button */
    .btn-add-delivery {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(44, 143, 12, 0.2);
        height: 46px;
    }
    
    .btn-add-delivery:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
    }
    
    .btn-add-delivery:active {
        transform: translateY(0);
    }

    .btn-primary {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    }

    /* Enhanced Action Buttons - Consistent with other pages */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: nowrap;
        justify-content: center;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        border: 2px solid;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .btn-edit {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-edit:hover {
        background-color: #2C8F0C;
        color: white;
    }
    
    .btn-delete {
        background-color: white;
        border-color: #C62828;
        color: #C62828;
    }
    
    .btn-delete:hover {
        background-color: #C62828;
        color: white;
    }

    /* Table Styling - Consistent */
    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 1rem 0.75rem;
        white-space: nowrap;
    }

    .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    /* Alternating row colors */
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #F8FDF8;
    }

    /* Status styling - Consistent with other pages */
    .status-text {
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-text-active {
        color: #2C8F0C;
    }
    
    .status-text-active::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #2C8F0C;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }

    .status-badge-inactive {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 80px;
        background-color: #FFF3CD;
        color: #856404;
        border: 1px solid #FFEAA7;
    }

    /* Vehicle Type Badge */
    .vehicle-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        background-color: #E3F2FD;
        color: #1565C0;
        border: 1px solid #BBDEFB;
    }

    /* Modal Styling - Consistent */
    .modal-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1.25rem;
    }

    .modal-title {
        font-weight: 700;
    }

    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    /* Form Styling */
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

    /* Filter Section - Consistent */
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

    /* Delivery Avatar */
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

    /* Tips Box */
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

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #C8E6C9;
        margin-bottom: 1rem;
    }

    /* Table Container for consistency */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }
    
    /* Responsive adjustments */
    @media (min-width: 1200px) {
        .table-container {
            overflow-x: visible;
        }
        
        .table {
            table-layout: fixed;
        }
    }

    /* Column widths for consistency */
    .name-col { min-width: 200px; width: 250px; }
    .id-col { min-width: 80px; width: 100px; }
    .email-col { min-width: 200px; width: 250px; }
    .phone-col { min-width: 120px; width: 150px; }
    .vehicle-col { min-width: 120px; width: 150px; }
    .status-col { min-width: 100px; width: 120px; }
    .action-col { min-width: 120px; width: 140px; }

    /* Delivery Info Cell */
    .delivery-info-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .delivery-name {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }
    
    .delivery-vehicle {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 2px;
    }
    
    .delivery-email {
        color: #2C8F0C;
        font-size: 0.85rem;
        word-break: break-word;
    }
    
    .delivery-phone {
        color: #495057;
        font-size: 0.9rem;
    }

    /* Pagination styling - Consistent */
    .pagination .page-item .page-link {
        color: #2C8F0C;
        border: 1px solid #dee2e6;
        margin: 0 2px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-color: #2C8F0C;
        color: white;
    }
    
    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #E8FDF8;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
    }

    /* Remove old badge styles */
    .badge-success,
    .badge-danger,
    .badge-info,
    .badge-warning {
        display: none;
    }


</style>

<!-- Filters and Search - Consistent -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.deliveries.index') }}" id="filterForm">
            <div class="row">
                <!-- Search by Name or Email -->
                <div class="col-md-5">
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

                <!-- Filter by Status -->
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

                <!-- Filter by Vehicle Type -->
                <div class="col-md-2">
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

                <!-- Items per page selection -->
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="per_page" class="form-label fw-bold">Items per page</label>
                        <select class="form-select" id="per_page" name="per_page">
                            @foreach([2, 5, 10, 15, 25, 50] as $option)
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
        <h5 class="mb-0">Delivery Management</h5>
        <button class="btn btn-add-delivery" data-bs-toggle="modal" data-bs-target="#addDeliveryModal">
        Add Delivery
        </button>
    </div>
    <div class="card-body p-0">
        @if($deliveries->count())
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="name-col">Name</th>
                            <th class="id-col">ID</th>
                            <th class="email-col">Email</th>
                            <th class="phone-col">Phone</th>
                            <th class="vehicle-col">Vehicle Type</th>
                            <th class="status-col">Status</th>
                            <th class="action-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveries as $delivery)
                        <tr data-id="{{ $delivery->id }}">
                            <td class="name-col">
                                <div class="delivery-info-cell">
                                    <div class="delivery-avatar">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div>
                                        <div class="delivery-name">{{ $delivery->name }}</div>
                                        @if($delivery->vehicle_number)
                                            <div class="delivery-vehicle" title="Vehicle: {{ $delivery->vehicle_number }}">
                                                {{ $delivery->vehicle_number }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="id-col">
                                <span class="text-muted">#{{ $delivery->id }}</span>
                            </td>
                            <td class="email-col">
                                <a href="mailto:{{ $delivery->email }}" class="delivery-email" title="{{ $delivery->email }}">
                                    {{ $delivery->email }}
                                </a>
                            </td>
                            <td class="phone-col">
                                <div class="delivery-phone">{{ $delivery->phone }}</div>
                            </td>
                            <td class="vehicle-col">
                                <span class="vehicle-badge">{{ $delivery->vehicle_type }}</span>
                            </td>
                            <td class="status-col">
                                @if ($delivery->is_active)
                                    <span class="status-text status-text-active">Active</span>
                                @else
                                    <span class="status-badge-inactive">Inactive</span>
                                @endif
                            </td>
                            <td class="action-col">
                                <div class="action-buttons">
                                    <button class="action-btn btn-edit editBtn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editDeliveryModal"
                                            data-delivery='@json($delivery)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.deliveries.destroy', $delivery) }}" 
                                          method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="action-btn btn-delete deleteBtn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($deliveries->hasPages())
            <div class="d-flex justify-content-center p-4">
                {{ $deliveries->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-truck"></i>
                <h5 class="text-muted">No Delivery Personnel Found</h5>
                <p class="text-muted mb-4">Add your first delivery person to get started</p>
                <button class="btn btn-add-delivery" data-bs-toggle="modal" data-bs-target="#addDeliveryModal">
                    <i class="fas fa-user-plus"></i> Add First Delivery
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Delivery Modal -->
<div class="modal fade" id="addDeliveryModal" tabindex="-1" aria-labelledby="addDeliveryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="addDeliveryForm" action="{{ route('admin.deliveries.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDeliveryModalLabel">Add New Delivery Person</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="add_first_name" class="form-label">First Name *</label>
                            <input type="text" id="add_first_name" name="first_name" class="form-control" 
                                   placeholder="Enter first name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="add_last_name" class="form-label">Last Name *</label>
                            <input type="text" id="add_last_name" name="last_name" class="form-control" 
                                   placeholder="Enter last name" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="add_email" class="form-label">Email *</label>
                            <input type="email" id="add_email" name="email" class="form-control" 
                                   placeholder="Enter email address" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="add_phone" class="form-label">Phone Number *</label>
                            <input type="text" id="add_phone" name="phone" class="form-control" 
                                   placeholder="Enter phone number" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="add_vehicle_type" class="form-label">Vehicle Type *</label>
                            <select class="form-select" id="add_vehicle_type" name="vehicle_type" required>
                                <option value="">Select Vehicle Type</option>
                                <option value="Motorcycle">Motorcycle</option>
                                <option value="Car">Car</option>
                                <option value="Van">Van</option>
                                <option value="Truck">Truck</option>
                                <option value="Bicycle">Bicycle</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="add_vehicle_number" class="form-label">Vehicle Number</label>
                            <input type="text" id="add_vehicle_number" name="vehicle_number" class="form-control" 
                                   placeholder="Enter vehicle number">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="add_license_number" class="form-label">License Number</label>
                            <input type="text" id="add_license_number" name="license_number" class="form-control" 
                                   placeholder="Enter license number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="add_is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="add_is_active">Active Account</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="add_password" class="form-label">Password *</label>
                            <input type="password" id="add_password" name="password" class="form-control" 
                                   placeholder="Enter password" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="add_password_confirmation" class="form-label">Confirm Password *</label>
                            <input type="password" id="add_password_confirmation" name="password_confirmation" class="form-control" 
                                   placeholder="Confirm password" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="tips-box mt-3">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Tips:</strong> Make sure to provide accurate contact information. The delivery person will use this email and password to login to the delivery app.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Delivery Person</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Delivery Modal -->
<div class="modal fade" id="editDeliveryModal" tabindex="-1" aria-labelledby="editDeliveryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editDeliveryForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDeliveryModalLabel">Edit Delivery Person</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_first_name" class="form-label">First Name *</label>
                            <input type="text" id="edit_first_name" name="first_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_last_name" class="form-label">Last Name *</label>
                            <input type="text" id="edit_last_name" name="last_name" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email *</label>
                            <input type="email" id="edit_email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_phone" class="form-label">Phone Number *</label>
                            <input type="text" id="edit_phone" name="phone" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_vehicle_type" class="form-label">Vehicle Type *</label>
                            <select class="form-select" id="edit_vehicle_type" name="vehicle_type" required>
                                <option value="">Select Vehicle Type</option>
                                <option value="Motorcycle">Motorcycle</option>
                                <option value="Car">Car</option>
                                <option value="Van">Van</option>
                                <option value="Truck">Truck</option>
                                <option value="Bicycle">Bicycle</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_vehicle_number" class="form-label">Vehicle Number</label>
                            <input type="text" id="edit_vehicle_number" name="vehicle_number" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_license_number" class="form-label">License Number</label>
                            <input type="text" id="edit_license_number" name="license_number" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="edit_is_active" name="is_active" value="1">
                                <label class="form-check-label" for="edit_is_active">Active Account</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_password" class="form-label">Password (Leave blank to keep current)</label>
                            <input type="password" id="edit_password" name="password" class="form-control" 
                                   placeholder="Enter new password">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" id="edit_password_confirmation" name="password_confirmation" class="form-control" 
                                   placeholder="Confirm new password">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="tips-box mt-3">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Note:</strong> Only fill the password fields if you want to change the password. The vehicle type and license information are important for delivery tracking.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Delivery Person</button>
                </div>
            </div>
        </form>
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

    /* === Add Delivery === */
    document.getElementById('addDeliveryForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Close modal and reload
                const modal = bootstrap.Modal.getInstance(document.getElementById('addDeliveryModal'));
                modal.hide();
                location.reload();
            } else {
                alert('Error adding delivery person: ' + (data.message || 'Unknown error'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    /* === Edit Delivery === */
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const delivery = JSON.parse(this.dataset.delivery);
            const form = document.getElementById('editDeliveryForm');
            
            // Set form action
            form.action = `/admin/deliveries/${delivery.id}`;
            
            // Fill form fields
            document.getElementById('edit_first_name').value = delivery.first_name || '';
            document.getElementById('edit_last_name').value = delivery.last_name || '';
            document.getElementById('edit_email').value = delivery.email || '';
            document.getElementById('edit_phone').value = delivery.phone || '';
            document.getElementById('edit_vehicle_type').value = delivery.vehicle_type || '';
            document.getElementById('edit_vehicle_number').value = delivery.vehicle_number || '';
            document.getElementById('edit_license_number').value = delivery.license_number || '';
            document.getElementById('edit_is_active').checked = delivery.is_active;
        });
    });

    document.getElementById('editDeliveryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';

        fetch(form.action, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'X-HTTP-Method-Override': 'PUT' 
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Close modal and reload
                const modal = bootstrap.Modal.getInstance(document.getElementById('editDeliveryModal'));
                modal.hide();
                location.reload();
            } else {
                alert('Error updating delivery person: ' + (data.message || 'Unknown error'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    /* === Delete Delivery === */
    document.querySelectorAll('.deleteBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Are you sure you want to delete this delivery person? This action cannot be undone.')) return;
            
            const form = this.closest('.delete-form');
            
            // Show loading state
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(form.action, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'DELETE'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to delete delivery person: ' + (data.message || 'Unknown error'));
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-trash"></i>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-trash"></i>';
            });
        });
    });

    // Fix table layout on window resize
    window.addEventListener('resize', function() {
        const tableContainer = document.querySelector('.table-container');
        const table = document.querySelector('.table');
        
        // Only apply horizontal scroll on mobile
        if (window.innerWidth < 1200) {
            tableContainer.style.overflowX = 'auto';
            table.style.tableLayout = 'auto';
        } else {
            tableContainer.style.overflowX = 'visible';
            table.style.tableLayout = 'fixed';
        }
    });
});
</script>
@endpush

@endsection