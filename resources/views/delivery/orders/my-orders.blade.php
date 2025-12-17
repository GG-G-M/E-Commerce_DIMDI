@extends('layouts.delivery')

@section('title', 'My Orders - Delivery Dashboard')

@section('content')
    <style>
        /* === Consistent Green Theme === */
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
            font-size: 1.25rem;
        }

        /* Dashboard Header */
        .dashboard-header {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #2C8F0C;
        }

        /* Table Styling - Compact */
        .table {
            margin-bottom: 0;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.9rem;
        }

        .table th {
            background-color: #E8F5E6;
            color: #2C8F0C;
            font-weight: 600;
            border-bottom: 2px solid #2C8F0C;
            padding: 0.75rem 0.5rem;
            white-space: nowrap;
        }

        .table td {
            padding: 0.75rem 0.5rem;
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

        /* Update the button styles to match pick up button design */
        .btn-outline-success-custom {
            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(44, 143, 12, 0.2);
            text-decoration: none;
        }

        .btn-outline-success-custom:hover {
            background: linear-gradient(135deg, #1E6A08, #2C8F0C);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
        }

        /* If you want the active button to look different */
        .btn-outline-success-custom.active {
            background: linear-gradient(135deg, #1E6A08, #2C8F0C);
            box-shadow: 0 0 0 2px rgba(44, 143, 12, 0.3);
        }

        /* Update quick filter buttons to also match */
        .btn-outline-success-custom {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
        }

        /* Make sure header buttons have proper spacing */
        .header-buttons .btn {
            margin: 0;
            font-size: 0.9rem;
            min-height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Search Box */
        .search-box {
            border-radius: 8px;
            border: 1px solid #C8E6C9;
            transition: border-color 0.3s ease;
            font-size: 0.9rem;
        }

        .search-box:focus {
            border-color: #2C8F0C;
            box-shadow: 0 0 0 0.15rem rgba(44, 143, 12, 0.2);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-state i {
            font-size: 3rem;
            color: #C8E6C9;
            margin-bottom: 1rem;
        }

        /* Table Container */
        .table-container {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            max-width: 100%;
        }

        /* Column widths - More compact */
        .order-col {
            width: 100px;
            min-width: 100px;
        }

        .customer-col {
            width: 150px;
            min-width: 150px;
        }

        .contact-col {
            width: 130px;
            min-width: 130px;
        }

        .address-col {
            width: 180px;
            min-width: 180px;
        }

        .amount-col {
            width: 100px;
            min-width: 100px;
        }

        .status-col {
            width: 140px;
            min-width: 140px;
        }

        .date-col {
            width: 120px;
            min-width: 120px;
        }

        .action-col {
            width: 100px;
            min-width: 100px;
        }

        /* Customer Info */
        .customer-name {
            font-weight: 600;
            color: #333;
            font-size: 0.85rem;
            line-height: 1.2;
        }

        .customer-phone {
            color: #6c757d;
            font-size: 0.75rem;
        }

        .customer-email {
            color: #6c757d;
            font-size: 0.75rem;
        }

        /* Items Count */
        .items-count {
            font-size: 0.75rem;
            color: #6c757d;
        }

        /* Amount Styling */
        .amount-text {
            font-weight: 700;
            color: #2C8F0C;
            font-size: 0.9rem;
        }

        /* Address Styling */
        .address-text {
            font-size: 0.85rem;
            color: #495057;
            line-height: 1.3;
        }

        /* Date Styling */
        .date-text {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .picked-up-text {
            font-size: 0.75rem;
            color: #adb5bd;
        }

        /* Progress Bar */
        .progress {
            height: 6px;
            background-color: #e9ecef;
            border-radius: 3px;
            margin-top: 4px;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: nowrap;
            justify-content: center;
        }


        .btn-view {
            background-color: white;
            border-color: #2C8F0C;
            color: #2C8F0C;
        }

        .btn-view:hover {
            background-color: #2C8F0C;
            color: white;
        }

        /* Deliver Button Styling */
        .btn-deliver {
            background-color: white;
            border-color: #2C8F0C;
            color: #2C8F0C;
            border-radius: 8px;
            padding: 0.5rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            font-size: 0.9rem;
        }

        .btn-deliver:hover {
            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
            color: white;
            border-color: #2C8F0C;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
        }

        /* Delivered Button Styling */
        .btn-delivered {
            background: linear-gradient(135deg, #E8F5E6, #C8E6C9);
            border-color: #2C8F0C;
            color: #2C8F0C;
            border-radius: 8px;
            padding: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            font-size: 0.9rem;
            cursor: default;
            opacity: 0.8;
        }

        /* Pagination styling - Consistent */
        .pagination .page-item .page-link {
            color: #2C8F0C;
            border: 1px solid #dee2e6;
            margin: 0 1px;
            border-radius: 4px;
            transition: all 0.3s ease;
            padding: 0.4rem 0.7rem;
            font-size: 0.85rem;
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



        /* Header button group */
        .header-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .header-buttons .btn {
            margin: 0;
            font-size: 0.9rem;
        }

        /* Form Styling */
        .form-label {
            font-weight: 600;
            color: #2C8F0C;
            font-size: 0.9rem;
        }

        /* Alert Styling */
        .alert-info-custom {
            background-color: #D1ECF1;
            border-color: #BEE5EB;
            color: #0C5460;
            border-left: 4px solid #17a2b8;
        }

        /* Success Alert */
        .alert-success-custom {
            background-color: #E8F5E6;
            border-color: #C8E6C9;
            color: #2C8F0C;
            border-left: 4px solid #2C8F0C;
        }

        /* Danger Alert */
        .alert-danger-custom {
            background-color: #FFEBEE;
            border-color: #FFCDD2;
            color: #C62828;
            border-left: 4px solid #C62828;
        }

        /* Make table more compact on mobile */
        @media (max-width: 768px) {
            .header-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .table th,
            .table td {
                padding: 0.5rem 0.25rem;
            }


            .customer-name {
                font-size: 0.8rem;
            }

            .btn-outline-success-custom,
            .btn-success-custom {
                padding: 0.4rem 0.7rem;
                font-size: 0.8rem;
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
            }

            .action-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
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

            /* Specific Styling */
            .btn-deliver {
                background-color: white;
                border-color: #FBC02D;
                color: #FBC02D;
            }

            .btn-deliver:hover {
                background-color: #FBC02D;
                color: white;
            }

            /* .btn-unarchive {
                    background-color: white;
                    border-color: #2C8F0C;
                    color: #2C8F0C;
                }

                .btn-unarchive:hover {
                    background-color: #2C8F0C;
                    color: white;
                } */
        }
    </style>



    @if (session('success'))
        <div class="alert alert-success-custom alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2" style="color: #2C8F0C;"></i>
            <strong style="color: #2C8F0C;">{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger-custom alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2" style="color: #C62828;"></i>
            <strong style="color: #C62828;">{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <!-- Orders Table -->
    <div class="card card-custom">
        <div class="card-header card-header-custom">
            <h5 class="mb-0">My Orders Management</h5>
            <div class="header-buttons">
                <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-outline-success-custom">
                    <i class="fas fa-box"></i> Available
                </a>
                <a href="{{ route('delivery.orders.index') }}" class="btn btn-outline-success-custom">
                    <i class="fas fa-list"></i> All Orders
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            @if ($orders->count() > 0)
                <div class="alert alert-info-custom m-3 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    You have {{ $orders->count() }} active order(s) assigned to you. Deliver them to customers and mark as
                    delivered when completed.
                </div>

                <div class="table-container">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="order-col">Order #</th>
                                <th class="customer-col">Customer</th>
                                <th class="contact-col">Contact</th>
                                <th class="address-col">Delivery Address</th>
                                <th class="amount-col">Amount</th>
                                <th class="status-col">Status</th>
                                <th class="date-col">Assigned Date</th>
                                <th class="action-col">Delivered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="order-col">
                                        <strong class="text-dark">#{{ $order->order_number }}</strong>
                                        <div class="items-count">{{ $order->orderItems->count() }} items</div>
                                    </td>
                                    <td class="customer-col">
                                        <div class="customer-name">{{ $order->customer_name }}</div>
                                    </td>
                                    <td class="contact-col">
                                        <div class="customer-phone">
                                            <i class="fas fa-phone me-1"></i>{{ $order->customer_phone }}
                                        </div>
                                        <div class="customer-email">
                                            <i class="fas fa-envelope me-1"></i>{{ Str::limit($order->customer_email, 15) }}
                                        </div>
                                    </td>
                                    <td class="address-col">
                                        <div class="address-text">{{ Str::limit($order->shipping_address, 40) }}</div>
                                    </td>
                                    <td class="amount-col">
                                        <div class="amount-text">₱{{ number_format($order->total_amount, 2) }}</div>
                                    </td>
                                    <td class="status-col">
                                        @php
                                            $statusClass = str_replace('_', '-', $order->order_status);
                                            $statusText = ucfirst(str_replace('_', ' ', $order->order_status));
                                        @endphp
                                        <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                        @if (in_array($order->order_status, ['shipped', 'out_for_delivery']))
                                            <div class="progress">
                                                @if ($order->order_status == 'shipped')
                                                    <div class="progress-bar" style="width: 50%"></div>
                                                @elseif($order->order_status == 'out_for_delivery')
                                                    <div class="progress-bar" style="width: 75%"></div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="date-col">
                                        <div class="date-text">
                                            @if ($order->assigned_at)
                                                {{ $order->assigned_at->format('M j, Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                        @if ($order->picked_up_at)
                                            <div class="picked-up-text">
                                                Picked: {{ $order->picked_up_at->format('M j') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="action-col">
                                        @if (in_array($order->order_status, ['shipped', 'out_for_delivery']))
                                            <button type="button" class="action-btn btn-deliver" 
                                                    title="Mark as Delivered"
                                                    data-order-id="{{ $order->id }}"
                                                    data-order-number="{{ $order->order_number }}"
                                                    data-customer-name="{{ $order->customer_name }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @elseif($order->order_status == 'delivered')
                                            <span class="action-btn btn-delivered" title="Already Delivered">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        @endif
                                    </td>
            @endforeach
            </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="d-flex justify-content-between align-items-center p-3">
                <div>
                    <small class="text-muted">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                    </small>
                </div>
                <div>
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    @else
        <div class="empty-state p-5">
            <i class="fas fa-clipboard-list"></i>
            <h5 class="text-muted">No Active Orders</h5>
            <p class="text-muted mb-4">
                @if (request()->hasAny(['search', 'status', 'date_filter']))
                    No orders match your current filters.
                @else
                    You don't have any active orders assigned to you.
                @endif
            </p>
            <div class="d-flex gap-3 justify-content-center">
                @if (request()->hasAny(['search', 'status', 'date_filter']))
                    <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-success-custom">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                @endif
                <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-success-custom">
                    <i class="fas fa-box me-1"></i> Pick Up Orders
                </a>
                <a href="{{ route('delivery.orders.index') }}" class="btn btn-outline-success-custom">
                    <i class="fas fa-list me-1"></i> All Orders
                </a>
            </div>
        </div>
        @endif
    </div>
    </div>

    <!-- Delivery Proof Modal -->
    <div class="modal fade" id="deliveryProofModal" tabindex="-1" aria-labelledby="deliveryProofModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2C8F0C, #4CAF50); color: white;">
                    <h5 class="modal-title" id="deliveryProofModalLabel">
                        <i class="fas fa-camera me-2"></i>Delivery Proof
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deliveryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-info-circle me-1"></i>Order Information
                            </label>
                            <div class="alert alert-info" style="background-color: #E8F5E6; border-color: #C8E6C9; color: #2C8F0C;">
                                <strong id="modalOrderNumber"></strong><br>
                                <small>Customer: <span id="modalCustomerName"></span></small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" style="color: #2C8F0C; font-weight: 600;">
                                <i class="fas fa-camera me-1"></i>Delivery Proof Photo
                            </label>
                            <div class="text-muted small mb-2">Please take a photo or upload an image as proof of delivery</div>
                            
                            <!-- Camera/Upload Options -->
                            <div class="d-grid gap-2 mb-3">
                                <button type="button" class="btn btn-outline-success" id="cameraBtn">
                                    <i class="fas fa-camera me-2"></i>Take Photo
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="uploadBtn">
                                    <i class="fas fa-upload me-2"></i>Upload from Gallery
                                </button>
                            </div>
                            
                            <!-- Hidden file inputs -->
                            <input type="file" id="cameraInput" accept="image/*" capture="environment" style="display: none;">
                            <input type="file" id="fileInput" accept="image/*" style="display: none;">
                            
                            <!-- Image preview -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <div class="card">
                                    <div class="card-body p-2">
                                        <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: contain;">
                                        <div class="mt-2 d-flex justify-content-between align-items-center">
                                            <small class="text-muted" id="imageInfo"></small>
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="removeImageBtn">
                                                <i class="fas fa-times me-1"></i>Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="deliveryNotes" class="form-label" style="color: #2C8F0C; font-weight: 600;">
                                <i class="fas fa-comment me-1"></i>Delivery Notes (Optional)
                            </label>
                            <textarea class="form-control" id="deliveryNotes" name="delivery_notes" rows="3" 
                                placeholder="Add any notes about the delivery (e.g., left at door, handed to recipient, etc.)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn" style="background: linear-gradient(135deg, #2C8F0C, #4CAF50); color: white;" id="submitBtn">
                            <i class="fas fa-check me-1"></i>Confirm Delivery
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal and form elements
            const modalElement = document.getElementById('deliveryProofModal');
            const deliveryForm = document.getElementById('deliveryForm');
            const cameraBtn = document.getElementById('cameraBtn');
            const uploadBtn = document.getElementById('uploadBtn');
            const cameraInput = document.getElementById('cameraInput');
            const fileInput = document.getElementById('fileInput');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const removeImageBtn = document.getElementById('removeImageBtn');
            const submitBtn = document.getElementById('submitBtn');
            
            // Initialize Bootstrap modal
            const modal = new bootstrap.Modal(modalElement);
            
            let currentOrderId = null;
            let selectedFile = null;
            
            // Replace direct form submission with modal
            const deliverButtons = document.querySelectorAll('.btn-deliver');
            
            deliverButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Get order information from data attributes
                    const orderId = this.dataset.orderId;
                    const orderNumber = this.dataset.orderNumber;
                    const customerName = this.dataset.customerName;
                    
                    // Set modal content
                    document.getElementById('modalOrderNumber').textContent = orderNumber;
                    document.getElementById('modalCustomerName').textContent = customerName;
                    
                    // Store the order ID for form submission
                    currentOrderId = orderId;
                    
                    // Reset form
                    resetForm();
                    
                    // Show modal
                    modal.show();
                });
            });
            
            // Camera button click
            cameraBtn.addEventListener('click', function() {
                cameraInput.click();
            });
            
            // Upload button click
            uploadBtn.addEventListener('click', function() {
                fileInput.click();
            });
            
            // Handle camera input change
            cameraInput.addEventListener('change', function(e) {
                handleFileSelection(e.target.files[0]);
            });
            
            // Handle file input change
            fileInput.addEventListener('change', function(e) {
                handleFileSelection(e.target.files[0]);
            });
            
            // Handle file selection
            function handleFileSelection(file) {
                if (file) {
                    selectedFile = file;
                    
                    // Create preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        document.getElementById('imageInfo').textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }
            
            // Remove image button
            removeImageBtn.addEventListener('click', function() {
                resetForm();
            });
            
            // Reset form
            function resetForm() {
                selectedFile = null;
                cameraInput.value = '';
                fileInput.value = '';
                imagePreview.style.display = 'none';
                document.getElementById('deliveryNotes').value = '';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check me-1"></i>Confirm Delivery';
            }
            
            // Form submission
            deliveryForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // For testing - make photo optional for now
                // if (!selectedFile) {
                //     alert('Please select a photo as proof of delivery.');
                //     return;
                // }
                
                // Disable submit button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
                
                // Create form data
                const formData = new FormData();
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                
                // Add file only if selected
                if (selectedFile) {
                    formData.append('delivery_photo', selectedFile);
                }
                
                formData.append('delivery_notes', document.getElementById('deliveryNotes').value);
                
                // Submit via AJAX
                fetch(`/delivery/orders/${currentOrderId}/deliver-order`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        return response.text().then(text => {
                            throw new Error(`Expected JSON, got: ${text.substring(0, 100)}`);
                        });
                    }
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success-custom alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            <i class="fas fa-check-circle me-2" style="color: #2C8F0C;"></i>
                            <strong style="color: #2C8F0C;">Order delivered successfully!</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        
                        // Insert alert before the card
                        const card = document.querySelector('.card-custom');
                        card.parentNode.insertBefore(alertDiv, card);
                        
                        // Hide modal and reload page
                        modal.hide();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Failed to deliver order');
                    }
                })
                .catch(error => {
                    console.error('Error details:', error);
                    
                    // Show more detailed error information
                    let errorMessage = 'Failed to deliver order: ' + error.message;
                    if (error.message.includes('Unexpected token') && error.message.includes('<') && error.message.includes('!DOCTYPE')) {
                        errorMessage = 'Server returned an error page instead of JSON. This usually indicates a server-side error. Please check the server logs.';
                    }
                    
                    alert(errorMessage);
                    
                    // Reset submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check me-1"></i>Confirm Delivery';
                });
            });
            
            // Close modal cleanup
            document.getElementById('deliveryProofModal').addEventListener('hidden.bs.modal', function () {
                resetForm();
            });
        });
    </script>
@endsection
