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
            background: white;
            color: #2C8F0C;
            border: 2px solid rgba(44, 143, 12, 0.3);
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            text-decoration: none;
            white-space: nowrap;
            min-width: fit-content;
            height: auto;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
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
                                        <i class="fas fa-search"></i>
                                    </button>
                                    {{-- <button class="action-btn btn-edit editBtn" data-bs-toggle="modal" data-bs-target="#editCustomerModal" data-customer='@json($customer)' data-title="Edit Customer">
                                    <i class="fas fa-edit"></i>
                                </button> --}}
                                    @if ($customer->is_archived)
                                        <button type="button" class="action-btn btn-unarchive unarchiveBtn" 
                                            data-customer-id="{{ $customer->id }}"
                                            onclick="unarchiveCustomer({{ $customer->id }}, this)"
                                            data-title="Unarchive Customer">
                                            <i class="fas fa-box-open"></i>
                                        </button>
                                    @else
                                        <button type="button" class="action-btn btn-archive archiveBtn" 
                                            data-customer-id="{{ $customer->id }}"
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

    <!-- Working JavaScript for Archive/Unarchive and Filters -->
    <script>
        console.log('JavaScript is starting...');
        
        // Handle filter form auto-submission
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');
            const statusSelect = document.getElementById('status');
            const perPageSelect = document.getElementById('per_page');
            const searchInput = document.getElementById('search');
            
            // Auto-submit when status changes
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    console.log('Status filter changed to:', this.value);
                    filterForm.submit();
                });
            }
            
            // Auto-submit when per_page changes
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    console.log('Per page changed to:', this.value);
                    filterForm.submit();
                });
            }
            
            // Auto-submit search with delay
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        console.log('Search changed to:', this.value);
                        filterForm.submit();
                    }, 500); // 500ms delay
                });
            }
        });
        
        // Define global functions that can be called from onclick attributes
        window.archiveCustomer = function(id, button) {
            console.log('Archive function called for ID:', id);
            
            if (!confirm('Are you sure you want to archive this customer?')) {
                return;
            }
            
            // Visual feedback
            const originalContent = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.style.opacity = '0.6';
            
            fetch(`/admin/customers/${id}/archive`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Archive response:', data);
                if (data.success) {
                    alert('Customer archived successfully!');
                    location.reload();
                } else {
                    throw new Error(data.message || 'Archive failed');
                }
            })
            .catch(error => {
                console.error('Archive error:', error);
                button.disabled = false;
                button.innerHTML = originalContent;
                button.style.opacity = '1';
                alert('Archive failed: ' + error.message);
            });
        };
        
        window.unarchiveCustomer = function(id, button) {
            console.log('Unarchive function called for ID:', id);
            
            if (!confirm('Are you sure you want to unarchive this customer?')) {
                return;
            }
            
            // Visual feedback
            const originalContent = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.style.opacity = '0.6';
            
            fetch(`/admin/customers/${id}/unarchive`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Unarchive response:', data);
                if (data.success) {
                    alert('Customer unarchived successfully!');
                    location.reload();
                } else {
                    throw new Error(data.message || 'Unarchive failed');
                }
            })
            .catch(error => {
                console.error('Unarchive error:', error);
                button.disabled = false;
                button.innerHTML = originalContent;
                button.style.opacity = '1';
                alert('Unarchive failed: ' + error.message);
            });
        };
        
        console.log('Global archive/unarchive functions defined');
        
        // Handle View Customer Modal Population
        document.addEventListener('DOMContentLoaded', function() {
            // Handle view button clicks
            const viewButtons = document.querySelectorAll('.viewBtn');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const customerData = JSON.parse(this.getAttribute('data-customer'));
                    populateViewModal(customerData);
                });
            });
        });
        
        function populateViewModal(customer) {
            // Populate avatar and basic info
            document.getElementById('viewFirstName').textContent = customer.first_name ? customer.first_name.charAt(0) : '';
            document.getElementById('viewLastName').textContent = customer.last_name ? customer.last_name.charAt(0) : '';
            document.getElementById('viewFullName').textContent = `${customer.first_name || ''} ${customer.last_name || ''}`.trim() || 'N/A';
            document.getElementById('viewCustomerId').textContent = `Customer ID: #${customer.id}`;
            
            // Update status
            const statusElement = document.getElementById('viewStatus');
            if (customer.is_archived) {
                statusElement.textContent = 'Archived';
                statusElement.className = 'status-text status-text-archived';
            } else {
                statusElement.textContent = 'Active';
                statusElement.className = 'status-text status-text-active';
            }
            
            // Populate personal information
            document.getElementById('viewFirstNameField').textContent = customer.first_name || '-';
            document.getElementById('viewMiddleName').textContent = customer.middle_name || '-';
            document.getElementById('viewLastNameField').textContent = customer.last_name || '-';
            
            // Populate contact information
            document.getElementById('viewEmail').textContent = customer.email || '-';
            document.getElementById('viewPhone').textContent = customer.phone || '-';
            
            // Populate account information
            document.getElementById('viewRole').textContent = customer.role || 'Customer';
            document.getElementById('viewAccountStatus').textContent = customer.is_archived ? 'Archived' : 'Active';
            document.getElementById('viewCreatedAt').textContent = customer.created_at ? new Date(customer.created_at).toLocaleDateString() : '-';
            document.getElementById('viewEmailVerified').textContent = customer.email_verified_at ? 'Yes' : 'No';
            
            // Populate address information
            document.getElementById('viewStreetAddress').textContent = customer.street_address || '-';
            document.getElementById('viewBarangay').textContent = customer.barangay ? `Barangay ${customer.barangay}` : '-';
            document.getElementById('viewCity').textContent = customer.city || '-';
            document.getElementById('viewProvince').textContent = customer.province || '-';
            document.getElementById('viewRegion').textContent = customer.region || '-';
            document.getElementById('viewCountry').textContent = customer.country || 'Philippines';
        }
    </script>
@endsection