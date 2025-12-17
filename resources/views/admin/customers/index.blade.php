@extends('layouts.admin')

@section('content')
    <style>
        /* === Green Theme and Card Styling === */
        .page-header {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #2C8F0C;
        }

        .page-header h1 {
            color: #2C8F0C;
            font-weight: 700;
        }

        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
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

        /* Improved Add Customer Button */
        .btn-add-customer {
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

        .btn-add-customer:hover {
            background: linear-gradient(135deg, #1E6A08, #2C8F0C);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
            color: white;
        }

        .btn-add-customer:active {
            transform: translateY(0);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1E6A08, #2C8F0C);
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
            position: sticky;
            top: 0;
            z-index: 10;
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

        /* Alternating row colors for better readability */
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table tbody tr:nth-child(even):hover {
            background-color: #F8FDF8;
        }

        .modal-header {
            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
            color: white;
        }

        .form-label {
            font-weight: 600;
            color: #2C8F0C;
        }

        /* Loading indicator for search */
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

        /* Improved table cell styling */
        .customer-name {
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }

        .customer-email {
            color: #2C8F0C;
            font-size: 0.85rem;
            word-break: break-word;
        }

        .customer-phone {
            color: #495057;
            font-size: 0.9rem;
        }

        .customer-address {
            color: #6c757d;
            font-size: 0.85rem;
            max-width: 200px;
            word-break: break-word;
        }

        /* Status styling - no badge for active */
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

        .status-text-archived {
            color: #6c757d;
        }

        .status-text-archived::before {
            content: "";
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #6c757d;
            border-radius: 50%;
            opacity: 0.6;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }

            100% {
                opacity: 1;
            }
        }

        /* Enhanced Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: nowrap;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            background: white;
            user-select: none;
            padding: 0;
            line-height: 1;
            text-decoration: none;
            outline: none;
        }
        
        .action-btn:hover {
            cursor: pointer;
        }
        
        .action-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .action-btn i {
            pointer-events: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
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

        .btn-archive {
            background-color: white;
            border-color: #FBC02D;
            color: #FBC02D;
        }

        .btn-archive:hover {
            background-color: #FBC02D;
            color: white;
        }

        .btn-unarchive {
            background-color: white;
            border-color: #2C8F0C;
            color: #2C8F0C;
        }

        .btn-unarchive:hover {
            background-color: #2C8F0C;
            color: white;
        }

        .btn-view {
            background-color: white;
            border-color: #4CAF50;
            color: #4CAF50;
        }

        .btn-view:hover {
            background-color: #4CAF50;
            color: white;
        }

        /* Table styling for no scroll bars */
        .table {
            width: 100%;
            max-width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        /* Prevent any scroll bars in the table card */
        .card-custom .card-body {
            overflow-x: hidden;
            overflow-y: hidden;
        }

        .card-custom {
            overflow: hidden;
        }

        /* Responsive table - always fixed layout for better fit */
        .table {
            table-layout: fixed;
        }

        /* Column width control - compact for no scroll */
        .id-col {
            min-width: 40px;
            width: 40px;
        }

        .name-col {
            min-width: 120px;
            width: 120px;
        }

        .email-col {
            min-width: 140px;
            width: 140px;
        }

        .phone-col {
            min-width: 100px;
            width: 100px;
        }

        .address-col {
            min-width: 160px;
            max-width: 160px;
            width: 160px;
        }

        .status-col {
            min-width: 80px;
            width: 80px;
        }

        .action-col {
            min-width: 80px;
            width: 80px;
        }

        /* Pagination styling */
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
            background-color: #E8F5E6;
            border-color: #2C8F0C;
            color: #2C8F0C;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
        }

        /* Tooltip styling for buttons */
        .action-btn {
            position: relative;
        }

        .action-btn::after {
            content: attr(data-title);
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease;
            z-index: 1000;
        }

        .action-btn:hover::after {
            opacity: 1;
            visibility: visible;
        }

        /* Customer Icon - Profile Icon */
        .customer-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
        }

        /* Customer Info Cell */
        .customer-info-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* View Modal Styling */
        .view-modal-content {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(44, 143, 12, 0.15);
        }

        .view-info-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1.25rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            height: 100%;
        }

        .view-info-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            border-color: #2C8F0C;
        }

        .view-info-card .form-label {
            color: #2C8F0C;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 1rem;
            display: block;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #E8F5E6;
        }

        .view-detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .view-detail-item:last-child {
            border-bottom: none;
        }

        .view-detail-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            flex: 0 0 100px;
        }

        .view-detail-value {
            color: #212529;
            font-size: 0.9rem;
            text-align: right;
            flex: 1;
            word-break: break-word;
            margin-left: 0.5rem;
        }

        .view-avatar-large {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.8rem;
            margin: 0 auto 1rem;
            box-shadow: 0 4px 12px rgba(44, 143, 12, 0.2);
        }

        .view-customer-name {
            color: #2C8F0C;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
            text-align: center;
        }

        .view-customer-id {
            color: #6c757d;
            font-size: 0.8rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        /* Compact grid layout for cards */
        .view-cards-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1rem;
        }

        .view-cards-grid .view-info-card:nth-child(1),
        .view-cards-grid .view-info-card:nth-child(2),
        .view-cards-grid .view-info-card:nth-child(3),
        .view-cards-grid .view-info-card:nth-child(4) {
            grid-column: span 1;
        }

        @media (max-width: 768px) {
            .view-cards-grid {
                grid-template-columns: 1fr;
            }

            .view-cards-grid .view-info-card {
                grid-column: 1 / -1;
            }
        }

        /* Ensure the table fits within the container */
    </style>

    <!-- Filters and Search -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.customers.index') }}" id="filterForm">
                <div class="row">
                    <!-- Search by Name or Email -->
                    <div class="col-md-7">
                        <div class="mb-3 position-relative">
                            <label for="search" class="form-label fw-bold">Search Customers</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Search by name or email...">
                            <div class="search-loading" id="searchLoading">
                                <div class="spinner-border spinner-border-sm text-success" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter by Status (Active / Archived) -->
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Filter by Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Items per page selection -->
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="per_page" class="form-label fw-bold">Items per page</label>
                            <select class="form-select" id="per_page" name="per_page">
                                @foreach ([5, 10, 15, 25, 50] as $option)
                                    <option value="{{ $option }}"
                                        {{ request('per_page', 10) == $option ? 'selected' : '' }}>
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

    <div class="card card-custom">
        <div class="card-header card-header-custom">
            <h5 class="mb-0">Customer List</h5>
            {{-- <button class="btn btn-add-customer" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            <i class="fas fa-user-plus"></i> 
            Add Customer
        </button> --}}
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="id-col">ID</th>
                        <th class="name-col">Full Name</th>
                        <th class="email-col">Email</th>
                        <th class="phone-col">Phone</th>
                        <th class="address-col">Address</th>
                        <th class="status-col">Status</th>
                        <th class="action-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr data-id="{{ $customer->id }}">
                            <td class="id-col">
                                <span class="text-muted">#{{ $customer->id }}</span>
                            </td>
                            <td class="name-col">
                                <div class="customer-info-cell">
                                    <div class="customer-icon">
                                        {{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="customer-name">{{ $customer->first_name }} {{ $customer->last_name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="email-col">
                                <a href="mailto:{{ $customer->email }}" class="customer-email"
                                    title="{{ $customer->email }}">
                                    {{ $customer->email }}
                                </a>
                            </td>
                            <td class="phone-col">
                                @if ($customer->phone)
                                    <div class="customer-phone">{{ $customer->phone }}</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="address-col">
                                @php
                                    $addressParts = [];
                                    if ($customer->street_address) {
                                        $addressParts[] = $customer->street_address;
                                    }
                                    if ($customer->barangay) {
                                        $addressParts[] = 'Barangay ' . $customer->barangay;
                                    }
                                    if ($customer->city) {
                                        $addressParts[] = $customer->city;
                                    }
                                    if ($customer->province) {
                                        $addressParts[] = $customer->province;
                                    }
                                    if ($customer->region) {
                                        $addressParts[] = $customer->region;
                                    }
                                    if ($customer->country && $customer->country !== 'Philippines') {
                                        $addressParts[] = $customer->country;
                                    }

                                    $fullAddress = implode(', ', $addressParts);
                                @endphp
                                @if ($fullAddress)
                                    <div class="customer-address" title="{{ $fullAddress }}">
                                        {{ Str::limit($fullAddress, 40) }}
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="status-col">
                                @if ($customer->is_archived)
                                    <span class="status-text status-text-archived">Archived</span>
                                @else
                                    <span class="status-text status-text-active">Active</span>
                                @endif
                            </td>
                            <td class="action-col">
                                <div class="action-buttons">
                                    <button class="action-btn btn-view viewBtn" data-bs-toggle="modal"
                                        data-bs-target="#viewCustomerModal" data-customer='@json($customer)'
                                        data-title="View Customer">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    {{-- <button class="action-btn btn-edit editBtn" data-bs-toggle="modal" data-bs-target="#editCustomerModal" data-customer='@json($customer)' data-title="Edit Customer">
                                    <i class="fas fa-edit"></i>
                                </button> --}}
                                    @if ($customer->is_archived)
                                        <button type="button" class="action-btn btn-unarchive unarchiveBtn" 
                                            onclick="unarchiveCustomer({{ $customer->id }}, this)"
                                            data-title="Unarchive Customer">
                                            <i class="fas fa-box-open"></i>
                                        </button>
                                    @else
                                        <button type="button" class="action-btn btn-archive archiveBtn" 
                                            onclick="archiveCustomer({{ $customer->id }}, this)"
                                            data-title="Archive Customer">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($customers->hasPages())
                <div class="d-flex justify-content-center p-4">
                    {{ $customers->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>

    <!-- Add Customer Modal -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="addCustomerForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                        @include('admin.customers.form-fields')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Customer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Customer Modal -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editCustomerForm">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                        @include('admin.customers.form-fields')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Customer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- View Customer Modal -->
    <div class="modal fade" id="viewCustomerModal" tabindex="-1" aria-labelledby="viewCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content view-modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCustomerModalLabel">Customer Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Customer Avatar and Basic Info -->
                        <div class="col-md-2 text-center">
                            <div class="view-avatar-large">
                                <span id="viewFirstName">J</span><span id="viewLastName">D</span>
                            </div>
                            <div class="view-customer-name" id="viewFullName">John Doe</div>
                            <div class="view-customer-id" id="viewCustomerId">Customer ID: #123</div>
                            <div id="viewStatus" class="status-text status-text-active">
                                Active
                            </div>
                        </div>

                        <!-- Information Cards Grid -->
                        <div class="col-md-10">
                            <div class="view-cards-grid">
                                <!-- Personal Information Card -->
                                <div class="view-info-card">
                                    <label class="form-label">
                                        <i class="fas fa-user me-2"></i>Personal Information
                                    </label>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">First Name:</span>
                                        <span class="view-detail-value" id="viewFirstNameField">-</span>
                                    </div>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">Middle Name:</span>
                                        <span class="view-detail-value" id="viewMiddleName">-</span>
                                    </div>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">Last Name:</span>
                                        <span class="view-detail-value" id="viewLastNameField">-</span>
                                    </div>
                                </div>

                                <!-- Contact Information Card -->
                                <div class="view-info-card">
                                    <label class="form-label">
                                        <i class="fas fa-address-book me-2"></i>Contact Information
                                    </label>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">Email:</span>
                                        <span class="view-detail-value" id="viewEmail">-</span>
                                    </div>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">Phone:</span>
                                        <span class="view-detail-value" id="viewPhone">-</span>
                                    </div>
                                </div>

                                <!-- Account Information Card -->
                                <div class="view-info-card">
                                    <label class="form-label">
                                        <i class="fas fa-shield-alt me-2"></i>Account Information
                                    </label>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">Role:</span>
                                        <span class="view-detail-value" id="viewRole">-</span>
                                    </div>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">Status:</span>
                                        <span class="view-detail-value" id="viewAccountStatus">-</span>
                                    </div>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">Member Since:</span>
                                        <span class="view-detail-value" id="viewCreatedAt">-</span>
                                    </div>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">Email Verified:</span>
                                        <span class="view-detail-value" id="viewEmailVerified">-</span>
                                    </div>
                                </div>

                                <!-- Address Information Card -->
                                <div class="view-info-card">
                                    <label class="form-label">
                                        <i class="fas fa-map-marker-alt me-2"></i>Address Information
                                    </label>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">Street Address:</span>
                                        <span class="view-detail-value" id="viewStreetAddress">-</span>
                                    </div>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">Barangay:</span>
                                        <span class="view-detail-value" id="viewBarangay">-</span>
                                    </div>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">City:</span>
                                        <span class="view-detail-value" id="viewCity">-</span>
                                    </div>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">Province:</span>
                                        <span class="view-detail-value" id="viewProvince">-</span>
                                    </div>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">Region:</span>
                                        <span class="view-detail-value" id="viewRegion">-</span>
                                    </div>
                                    <div class="view-detail-item">
                                        <span class="view-detail-label">Country:</span>
                                        <span class="view-detail-value" id="viewCountry">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-primary" id="editFromViewBtn">
                        <i class="fas fa-edit me-1"></i>Edit Customer
                    </button>
                </div> --}}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const filterForm = document.getElementById('filterForm');
                const searchInput = document.getElementById('search');
                const statusSelect = document.getElementById('status');
                const perPageSelect = document.getElementById('per_page');
                const searchLoading = document.getElementById('searchLoading');

                // Define route URLs for archive/unarchive operations
                const archiveUrl = (id) => `/admin/customers/${id}/archive`;
                const unarchiveUrl = (id) => `/admin/customers/${id}/unarchive`;

                let searchTimeout;

                // Auto-submit search with delay
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchLoading.style.display = 'block';

                    searchTimeout = setTimeout(() => {
                        filterForm.submit();
                    }, 800); // 800ms delay after typing stops
                });

                // Auto-submit status filter immediately
                statusSelect.addEventListener('change', function() {
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

                // Utility function for showing notifications
                function showNotification(message, type = 'info') {
                    // Create notification element
                    const notification = document.createElement('div');
                    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
                    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                    notification.innerHTML = `
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    
                    // Add to body
                    document.body.appendChild(notification);
                    
                    // Auto remove after 5 seconds
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 5000);
                }

                /* === View Customer === */
                document.querySelectorAll('.viewBtn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const customer = JSON.parse(this.dataset.customer);

                        // Populate avatar and basic info
                        const firstInitial = customer.first_name ? customer.first_name.charAt(0) : 'N';
                        const lastInitial = customer.last_name ? customer.last_name.charAt(0) : 'A';

                        document.getElementById('viewFirstName').textContent = firstInitial;
                        document.getElementById('viewLastName').textContent = lastInitial;
                        document.getElementById('viewFullName').textContent =
                            `${customer.first_name || ''} ${customer.middle_name || ''} ${customer.last_name || ''}`
                            .trim() || 'N/A';
                        document.getElementById('viewCustomerId').textContent =
                            `Customer ID: #${customer.id}`;

                        // Populate status
                        const statusElement = document.getElementById('viewStatus');
                        if (customer.is_archived) {
                            statusElement.className = 'status-text status-text-archived';
                            statusElement.innerHTML = 'Archived';
                        } else {
                            statusElement.className = 'status-text status-text-active';
                            statusElement.innerHTML = 'Active';
                        }

                        // Populate personal information
                        document.getElementById('viewFirstNameField').textContent = customer
                            .first_name || '-';
                        document.getElementById('viewMiddleName').textContent = customer.middle_name ||
                            '-';
                        document.getElementById('viewLastNameField').textContent = customer.last_name ||
                            '-';

                        // Populate contact information
                        const emailElement = document.getElementById('viewEmail');
                        if (customer.email) {
                            emailElement.innerHTML =
                                `<a href="mailto:${customer.email}" style="color: #2C8F0C; text-decoration: none;">${customer.email}</a>`;
                        } else {
                            emailElement.textContent = '-';
                        }
                        document.getElementById('viewPhone').textContent = customer.phone || '-';

                        // Populate address information
                        document.getElementById('viewStreetAddress').textContent = customer
                            .street_address || '-';
                        document.getElementById('viewBarangay').textContent = customer.barangay ?
                            `Barangay ${customer.barangay}` : '-';
                        document.getElementById('viewCity').textContent = customer.city || '-';
                        document.getElementById('viewProvince').textContent = customer.province || '-';
                        document.getElementById('viewRegion').textContent = customer.region || '-';
                        document.getElementById('viewCountry').textContent = customer.country ||
                            'Philippines';

                        // Populate account information
                        const roleName = customer.role ? customer.role.charAt(0).toUpperCase() +
                            customer.role.slice(1).replace('_', ' ') : 'Customer';
                        document.getElementById('viewRole').textContent = roleName;
                        document.getElementById('viewAccountStatus').textContent = customer
                            .is_archived ? 'Archived' : 'Active';
                        document.getElementById('viewCreatedAt').textContent = customer.created_at ?
                            new Date(customer.created_at).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            }) : '-';
                        document.getElementById('viewEmailVerified').textContent = customer
                            .email_verified_at ? 'Yes' : 'No';

                        // Set edit button data for later use
                        document.getElementById('editFromViewBtn').dataset.customer = this.dataset
                            .customer;
                    });
                });

                /* === Edit from View Modal === */
                document.getElementById('editFromViewBtn').addEventListener('click', function() {
                    const customer = JSON.parse(this.dataset.customer);

                    // Close view modal
                    const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewCustomerModal'));
                    viewModal.hide();

                    // Open edit modal and populate it
                    setTimeout(() => {
                        const editModal = new bootstrap.Modal(document.getElementById(
                            'editCustomerModal'));
                        editModal.show();

                        const form = document.getElementById('editCustomerForm');
                        form.action = `/admin/customers/${customer.id}`;

                        // Fill form fields
                        for (const key in customer) {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                if (input.type === 'checkbox') {
                                    input.checked = customer[key];
                                } else {
                                    input.value = customer[key];
                                }
                            }
                        }
                    }, 300);
                });

                /* === Add Customer === */
                document.getElementById('addCustomerForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const form = e.target;
                    const formData = new FormData(form);
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';

                    fetch('{{ route('admin.customers.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Close modal and reload
                                const modal = bootstrap.Modal.getInstance(document.getElementById(
                                    'addCustomerModal'));
                                modal.hide();
                                location.reload();
                            } else {
                                alert('Error adding customer: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Network error. Please try again.');
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        });
                });

                /* === Edit Customer === */
                document.querySelectorAll('.editBtn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const customer = JSON.parse(this.dataset.customer);
                        const form = document.getElementById('editCustomerForm');

                        // Set form action
                        form.action = `/admin/customers/${customer.id}`;

                        // Fill form fields
                        for (const key in customer) {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                if (input.type === 'checkbox') {
                                    input.checked = customer[key];
                                } else {
                                    input.value = customer[key];
                                }
                            }
                        }
                    });
                });

                document.getElementById('editCustomerForm').addEventListener('submit', function(e) {
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
                                const modal = bootstrap.Modal.getInstance(document.getElementById(
                                    'editCustomerModal'));
                                modal.hide();
                                location.reload();
                            } else {
                                alert('Error updating customer: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Network error. Please try again.');
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        });
                });

                /* === Archive Customer === */
                function archiveCustomer(id, button) {
                    console.log('Archive function called for customer ID:', id);
                    
                    if (!confirm('Are you sure you want to archive this customer? This will make them inactive but preserve their data.')) {
                        return;
                    }

                    const originalContent = button.innerHTML;
                    
                    // Visual feedback
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    button.style.opacity = '0.6';

                    // Make the actual request
                    fetch(`/admin/customers/${id}/archive`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Customer archived successfully', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            throw new Error(data.message || 'Archive failed');
                        }
                    })
                    .catch(error => {
                        console.error('Archive error:', error);
                        button.disabled = false;
                        button.innerHTML = originalContent;
                        button.style.opacity = '1';
                        showNotification('Failed to archive customer: ' + error.message, 'error');
                    });
                }

                /* === Unarchive Customer === */
                function unarchiveCustomer(id, button) {
                    console.log('Unarchive function called for customer ID:', id);
                    
                    if (!confirm('Are you sure you want to unarchive this customer? They will become active again.')) {
                        return;
                    }

                    const originalContent = button.innerHTML;
                    
                    // Visual feedback
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    button.style.opacity = '0.6';

                    // Make the actual request
                    fetch(`/admin/customers/${id}/unarchive`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Customer unarchived successfully', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            throw new Error(data.message || 'Unarchive failed');
                        }
                    })
                    .catch(error => {
                        console.error('Unarchive error:', error);
                        button.disabled = false;
                        button.innerHTML = originalContent;
                        button.style.opacity = '1';
                        showNotification('Failed to unarchive customer: ' + error.message, 'error');
                    });
                }

                // Ensure table fits container on all screen sizes
                window.addEventListener('resize', function() {
                    const table = document.querySelector('.table');
                    if (table) {
                        // Always keep fixed layout for consistent appearance
                        table.style.tableLayout = 'fixed';
                        // Ensure table doesn't overflow
                        table.style.width = '100%';
                        table.style.maxWidth = '100%';
                    }
                });
            });
        </script>
    @endpush
@endsection
