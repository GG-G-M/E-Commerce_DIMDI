@extends('layouts.delivery')

@section('title', 'Ready for Pickup - Delivery Dashboard')

@section('content')
<style>
    .dashboard-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #2C8F0C;
    }
    
    .section-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }
    
    .section-card .card-header {
        background: white;
        border-bottom: 2px solid #E8F5E6;
        font-weight: 600;
        color: #2C8F0C;
        padding: 1rem 1.5rem;
    }
    
    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        font-weight: 500;
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-1px);
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #FFA000, #FFB300);
        border: none;
        font-weight: 500;
        color: white;
    }
    
    .search-box {
        border-radius: 8px;
        border: 1px solid #E8F5E6;
        padding: 0.5rem 1rem;
    }
    
    .search-box:focus {
        border-color: #2C8F0C;
        box-shadow: 0 0 0 0.2rem rgba(44, 143, 12, 0.25);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(44, 143, 12, 0.05);
    }
    
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .status-ready {
        color: #FFA000;
        font-weight: 600;
    }
    
    .priority-high {
        border-left: 4px solid #dc3545;
    }
    
    .priority-medium {
        border-left: 4px solid #FFA000;
    }
    
    .priority-low {
        border-left: 4px solid #28a745;
    }
    
    /* Bulk Action Styles */
    .bulk-action-bar {
        background: #E8F5E6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .bulk-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #2C8F0C;
    }
    
    .bulk-select-all {
        font-weight: 600;
        color: #2C8F0C;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .selected-count {
        background: #2C8F0C;
        color: white;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }
    
    .bulk-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .bulk-action-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }
    
    .table-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #2C8F0C;
    }
    
    /* Modal Styles */
    .bulk-modal .modal-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
    }
    
    .selected-order-list {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .selected-order-item {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-left: 3px solid #2C8F0C;
    }
    
    .selected-order-item:last-child {
        margin-bottom: 0;
    }
    
    .order-number-badge {
        background: #2C8F0C;
        color: white;
        border-radius: 4px;
        padding: 0.25rem 0.75rem;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    /* Loading Overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        display: none;
    }
    
    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #2C8F0C;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Unified action icon (green) */
    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: all 0.2s ease;
        text-decoration: none;
        border: 1px solid transparent;
    }

    .action-icon {
        background: transparent;
        color: #2C8F0C;
        border: 1.5px solid #2C8F0C;
        padding: 0.35rem;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 0.95rem;
    }

    .action-icon:hover {
        background: #E8F5E9;
        color: #1B5E20;
        text-decoration: none;
        transform: translateY(-1px);
    }
</style>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">Orders Ready for Pickup</h1>
            <p class="mb-0 text-muted">Pick up orders from the warehouse and start delivery process.</p>
        </div>
        <div class="text-end">
            <small class="text-muted">Available for Pickup: {{ $orders->total() }}</small>
        </div>
    </div>
</div>

<!-- Bulk Action Bar (Initially Hidden) -->
<div class="bulk-action-bar" id="bulkActionBar" style="display: none;">
    <div class="d-flex align-items-center">
        <div class="bulk-select-all">
            <input type="checkbox" class="bulk-checkbox" id="selectAllCheckbox">
            <label for="selectAllCheckbox" class="mb-0 cursor-pointer">Select All</label>
            <span class="selected-count" id="selectedCount">0</span>
        </div>
    </div>
    
    <div class="bulk-actions">
        <button type="button" class="btn btn-success btn-sm bulk-action-btn" id="bulkPickupBtn">
            <i class="fas fa-box me-1"></i> Bulk Pickup
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm bulk-action-btn" id="clearSelectionBtn">
            <i class="fas fa-times me-1"></i> Clear Selection
        </button>
    </div>
</div>

<!-- Filter and Search Section -->
<div class="card section-card mb-4">
    <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('delivery.orders.pickup') }}">
            <div class="row align-items-end">
                <!-- Search Box -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold text-muted mb-2">Search Orders</label>
                    <div class="input-group">
                        <input type="text" class="form-control search-box" name="search" 
                               placeholder="Search by order number, customer name..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-success" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Amount Filter -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold text-muted mb-2">Order Amount</label>
                    <select class="form-select search-box" name="amount_filter" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Any Amount</option>
                        <option value="low" {{ request('amount_filter') == 'low' ? 'selected' : '' }}>Under ₱500</option>
                        <option value="medium" {{ request('amount_filter') == 'medium' ? 'selected' : '' }}>₱500 - ₱2,000</option>
                        <option value="high" {{ request('amount_filter') == 'high' ? 'selected' : '' }}>Over ₱2,000</option>
                    </select>
                </div>

                <!-- Items Filter -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold text-muted mb-2">Number of Items</label>
                    <select class="form-select search-box" name="items_filter" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Any Number</option>
                        <option value="1-3" {{ request('items_filter') == '1-3' ? 'selected' : '' }}>1-3 Items</option>
                        <option value="4-6" {{ request('items_filter') == '4-6' ? 'selected' : '' }}>4-6 Items</option>
                        <option value="7+" {{ request('items_filter') == '7+' ? 'selected' : '' }}>7+ Items</option>
                    </select>
                </div>
            </div>

            <!-- Reset Filters -->
            @if(request()->hasAny(['search', 'amount_filter', 'items_filter']))
            <div class="row mt-2">
                <div class="col-12">
                    <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Bulk Pickup Form -->
<form id="bulkPickupForm" action="{{ route('delivery.orders.bulkPickup') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="order_ids" id="bulkOrderIds">
</form>

<!-- Orders Table -->
<div class="card section-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-box me-2"></i>Available for Pickup
            @if(request()->hasAny(['search', 'amount_filter', 'items_filter']))
                <small class="text-muted ms-2">(Filtered Results)</small>
            @endif
        </h6>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-success" id="enableBulkModeBtn">
                <i class="fas fa-layer-group me-1"></i> Bulk Mode
            </button>
            <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-list me-1"></i> My Orders
            </a>
            <a href="{{ route('delivery.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-list-alt me-1"></i> All Orders
            </a>
        </div>
    </div>
    
    <div class="card-body p-0">
        @if($orders->count() > 0)
            <div class="alert alert-info m-3 mb-0">
                <i class="fas fa-info-circle me-2"></i> 
                You have {{ $orders->count() }} order(s) ready for pickup. Please pick them up from the warehouse.
                <span id="bulkModeInfo" style="display: none;"> | 
                    <strong>Bulk Mode Active:</strong> Select multiple orders for bulk pickup
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50" id="bulkCheckboxHeader" style="display: none;">
                                <!-- Checkbox column header -->
                            </th>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Delivery Address</th>
                            <th>Amount</th>
                            <th>Items</th>
                            <th>Order Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        @php
                            $itemCount = $order->orderItems->count();
                            $priority = $order->total_amount > 2000 ? 'high' : ($order->total_amount > 500 ? 'medium' : 'low');
                        @endphp
                        <tr class="priority-{{ $priority }}" data-order-id="{{ $order->id }}">
                            <td style="display: none;" class="bulk-checkbox-cell">
                                <input type="checkbox" class="table-checkbox order-checkbox" 
                                       value="{{ $order->id }}" 
                                       data-order-number="{{ $order->order_number }}"
                                       data-customer-name="{{ $order->customer_name }}">
                            </td>
                            <td>
                                <strong class="text-dark">#{{ $order->order_number }}</strong>
                                <br>
                                <span class="status-ready">
                                    <i class="fas fa-box me-1"></i>Ready for Pickup
                                </span>
                            </td>
                            <td>
                                <strong>{{ $order->customer_name }}</strong>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-phone me-1"></i>{{ $order->customer_phone }}<br>
                                    <i class="fas fa-envelope me-1"></i>{{ Str::limit($order->customer_email, 15) }}
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">{{ Str::limit($order->shipping_address, 50) }}</small>
                            </td>
                            <td class="fw-bold text-success">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td>
                                <span class="fw-bold">{{ $itemCount }}</span> items
                                <br>
                                <small class="text-muted">
                                    @foreach($order->orderItems->take(2) as $item)
                                    {{ $item->product->name ?? 'Item' }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                    @if($itemCount > 2)
                                    +{{ $itemCount - 2 }} more
                                    @endif
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $order->created_at->format('M j, Y') }}
                                    <br>
                                    {{ $order->created_at->format('g:i A') }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('delivery.orders.show', $order) }}" class="action-btn action-icon" title="View Details for {{ $order->order_number }}" aria-label="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <form action="{{ route('delivery.orders.markAsPickedUp', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning single-pickup-btn" 
                                                onclick="return confirm('Mark order #{{ $order->order_number }} as picked up?')"
                                                title="Mark as Picked Up">
                                            <i class="fas fa-box"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders ready for pickup
                        </small>
                    </div>
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-box"></i>
                <h5 class="text-muted">No Orders Ready for Pickup</h5>
                <p class="text-muted mb-3">
                    @if(request()->hasAny(['search', 'amount_filter', 'items_filter']))
                        No orders match your current filters.
                    @else
                        All available orders have been picked up or are in delivery.
                    @endif
                </p>
                <div class="mt-3">
                    @if(request()->hasAny(['search', 'amount_filter', 'items_filter']))
                        <a href="{{ route('delivery.orders.pickup') }}" class="btn btn-success">
                            <i class="fas fa-times me-1"></i> Clear Filters
                        </a>
                    @endif
                    <a href="{{ route('delivery.orders.index') }}" class="btn btn-success">
                        <i class="fas fa-list me-1"></i> View All Orders
                    </a>
                    <a href="{{ route('delivery.orders.my-orders') }}" class="btn btn-outline-success">
                        <i class="fas fa-clipboard-list me-1"></i> My Orders
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Bulk Pickup Modal -->
<div class="modal fade bulk-modal" id="bulkPickupModal" tabindex="-1" aria-labelledby="bulkPickupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkPickupModalLabel">
                    <i class="fas fa-boxes me-2"></i>Bulk Pickup Orders
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    You are about to mark <span id="modalOrderCount" class="fw-bold">0</span> orders as picked up.
                    This action cannot be undone.
                </div>
                
                <div class="selected-order-list" id="selectedOrderList">
                    <!-- Selected orders will be listed here -->
                </div>
                
                <div class="mt-4">
                    <h6 class="fw-bold text-success mb-3">
                        <i class="fas fa-clipboard-check me-2"></i>Pickup Details
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pickupNotes" class="form-label fw-semibold">Pickup Notes (Optional)</label>
                            <textarea class="form-control" id="pickupNotes" rows="3" 
                                      placeholder="Add any notes about this bulk pickup..."></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Summary</label>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Total Orders:</small><br>
                                            <span class="fw-bold" id="summaryTotalOrders">0</span>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Total Items:</small><br>
                                            <span class="fw-bold" id="summaryTotalItems">0</span>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <small class="text-muted">Estimated Volume:</small><br>
                                            <span class="fw-bold" id="summaryVolume">Calculating...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmBulkPickupBtn">
                    <i class="fas fa-box-check me-1"></i> Confirm Bulk Pickup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // State variables
    let isBulkMode = false;
    let selectedOrders = new Set();
    
    // DOM Elements
    const enableBulkModeBtn = document.getElementById('enableBulkModeBtn');
    const bulkActionBar = document.getElementById('bulkActionBar');
    const bulkCheckboxHeader = document.getElementById('bulkCheckboxHeader');
    const bulkCheckboxCells = document.querySelectorAll('.bulk-checkbox-cell');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const selectedCount = document.getElementById('selectedCount');
    const bulkPickupBtn = document.getElementById('bulkPickupBtn');
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    const bulkPickupModal = new bootstrap.Modal(document.getElementById('bulkPickupModal'));
    const bulkPickupForm = document.getElementById('bulkPickupForm');
    const bulkOrderIds = document.getElementById('bulkOrderIds');
    const modalOrderCount = document.getElementById('modalOrderCount');
    const selectedOrderList = document.getElementById('selectedOrderList');
    const summaryTotalOrders = document.getElementById('summaryTotalOrders');
    const summaryTotalItems = document.getElementById('summaryTotalItems');
    const confirmBulkPickupBtn = document.getElementById('confirmBulkPickupBtn');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const bulkModeInfo = document.getElementById('bulkModeInfo');
    
    // Toggle Bulk Mode
    enableBulkModeBtn.addEventListener('click', function() {
        isBulkMode = !isBulkMode;
        
        if (isBulkMode) {
            enableBulkModeBtn.innerHTML = '<i class="fas fa-times me-1"></i> Exit Bulk Mode';
            enableBulkModeBtn.classList.remove('btn-outline-success');
            enableBulkModeBtn.classList.add('btn-warning');
            bulkActionBar.style.display = 'flex';
            bulkCheckboxHeader.style.display = 'table-cell';
            bulkCheckboxCells.forEach(cell => cell.style.display = 'table-cell');
            bulkModeInfo.style.display = 'inline';
            
            // Hide individual pickup buttons
            document.querySelectorAll('.single-pickup-btn').forEach(btn => {
                btn.style.display = 'none';
            });
        } else {
            exitBulkMode();
        }
    });
    
    // Exit Bulk Mode function
    function exitBulkMode() {
        isBulkMode = false;
        selectedOrders.clear();
        updateSelectedCount();
        
        enableBulkModeBtn.innerHTML = '<i class="fas fa-layer-group me-1"></i> Bulk Mode';
        enableBulkModeBtn.classList.remove('btn-warning');
        enableBulkModeBtn.classList.add('btn-outline-success');
        bulkActionBar.style.display = 'none';
        bulkCheckboxHeader.style.display = 'none';
        bulkCheckboxCells.forEach(cell => cell.style.display = 'none');
        bulkModeInfo.style.display = 'none';
        
        // Show individual pickup buttons
        document.querySelectorAll('.single-pickup-btn').forEach(btn => {
            btn.style.display = 'inline-block';
        });
        
        // Uncheck all checkboxes
        orderCheckboxes.forEach(checkbox => checkbox.checked = false);
        selectAllCheckbox.checked = false;
    }
    
    // Update selected count
    function updateSelectedCount() {
        selectedCount.textContent = selectedOrders.size;
        
        if (selectedOrders.size > 0) {
            bulkPickupBtn.disabled = false;
        } else {
            bulkPickupBtn.disabled = true;
        }
    }
    
    // Handle individual checkbox clicks
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                selectedOrders.add(this.value);
            } else {
                selectedOrders.delete(this.value);
                selectAllCheckbox.checked = false;
            }
            updateSelectedCount();
        });
    });
    
    // Select All checkbox
    selectAllCheckbox.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            if (this.checked) {
                selectedOrders.add(checkbox.value);
            } else {
                selectedOrders.delete(checkbox.value);
            }
        });
        updateSelectedCount();
    });
    
    // Clear selection
    clearSelectionBtn.addEventListener('click', function() {
        selectedOrders.clear();
        orderCheckboxes.forEach(checkbox => checkbox.checked = false);
        selectAllCheckbox.checked = false;
        updateSelectedCount();
    });
    
    // Bulk Pickup button click
    bulkPickupBtn.addEventListener('click', function() {
        if (selectedOrders.size === 0) {
            alert('Please select at least one order for bulk pickup.');
            return;
        }
        
        // Update modal content
        modalOrderCount.textContent = selectedOrders.size;
        summaryTotalOrders.textContent = selectedOrders.size;
        
        // Clear and populate selected orders list
        selectedOrderList.innerHTML = '';
        let totalItems = 0;
        
        selectedOrders.forEach(orderId => {
            const checkbox = document.querySelector(`.order-checkbox[value="${orderId}"]`);
            
            // Check if checkbox exists
            if (!checkbox) {
                console.error(`Checkbox for order ${orderId} not found`);
                return;
            }
            
            const orderNumber = checkbox.dataset.orderNumber || `#${orderId}`;
            const customerName = checkbox.dataset.customerName || 'Customer';
            
            const orderItem = document.createElement('div');
            orderItem.className = 'selected-order-item';
            orderItem.innerHTML = `
                <div>
                    <span class="order-number-badge">#${orderNumber}</span>
                    <span class="ms-2">${customerName}</span>
                </div>
                <div>
                    <small class="text-muted">
                        <i class="fas fa-box me-1"></i>
                        <span class="order-items-count">Loading...</span> items
                    </small>
                </div>
            `;
            selectedOrderList.appendChild(orderItem);
            
            // Get item count for this order - with error handling
            try {
                const row = checkbox.closest('tr');
                if (row) {
                    // Get the Items cell (7th td, index 6 because we added checkbox column)
                    const itemsCell = row.querySelector('td:nth-child(7)');
                    
                    if (itemsCell) {
                        // Find the item count from the cell text
                        const cellText = itemsCell.textContent;
                        const match = cellText.match(/(\d+)/);
                        
                        if (match) {
                            const itemCount = parseInt(match[0]);
                            totalItems += itemCount;
                            
                            // Update the item count in the list
                            setTimeout(() => {
                                const countElement = orderItem.querySelector('.order-items-count');
                                if (countElement) {
                                    countElement.textContent = itemCount;
                                }
                            }, 100);
                        } else {
                            // If no number found, default to 1
                            totalItems += 1;
                            setTimeout(() => {
                                const countElement = orderItem.querySelector('.order-items-count');
                                if (countElement) {
                                    countElement.textContent = '1';
                                }
                            }, 100);
                        }
                    } else {
                        // Items cell not found, default to 1
                        totalItems += 1;
                        setTimeout(() => {
                            const countElement = orderItem.querySelector('.order-items-count');
                            if (countElement) {
                                countElement.textContent = '1';
                            }
                        }, 100);
                    }
                } else {
                    // Row not found, default to 1
                    totalItems += 1;
                    setTimeout(() => {
                        const countElement = orderItem.querySelector('.order-items-count');
                        if (countElement) {
                            countElement.textContent = '1';
                        }
                    }, 100);
                }
            } catch (error) {
                console.error('Error getting item count for order', orderId, ':', error);
                // Default to 1 on error
                totalItems += 1;
                setTimeout(() => {
                    const countElement = orderItem.querySelector('.order-items-count');
                    if (countElement) {
                        countElement.textContent = '1';
                    }
                }, 100);
            }
        });
        
        // Update summary after a short delay to allow all async updates
        setTimeout(() => {
            summaryTotalItems.textContent = totalItems;
            
            // Calculate estimated volume
            let estimatedVolume = 'Small';
            if (totalItems > 20) {
                estimatedVolume = 'Large';
            } else if (totalItems > 10) {
                estimatedVolume = 'Medium';
            }
            const summaryVolumeElement = document.getElementById('summaryVolume');
            if (summaryVolumeElement) {
                summaryVolumeElement.textContent = estimatedVolume;
            }
            
            // Show modal
            bulkPickupModal.show();
        }, 300);
    });
    
    // Confirm Bulk Pickup
    confirmBulkPickupBtn.addEventListener('click', function() {
        // Disable button and show loading
        this.disabled = true;
        const originalButtonText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
        
        if (loadingOverlay) {
            loadingOverlay.style.display = 'flex';
        }
        
        // Prepare order IDs for submission
        const orderIdsArray = Array.from(selectedOrders);
        if (bulkOrderIds) {
            bulkOrderIds.value = JSON.stringify(orderIdsArray);
        }
        
        // Get pickup notes
        const pickupNotesInput = document.getElementById('pickupNotes');
        if (pickupNotesInput && pickupNotesInput.value.trim() !== '') {
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'pickup_notes';
            notesInput.value = pickupNotesInput.value;
            if (bulkPickupForm) {
                bulkPickupForm.appendChild(notesInput);
            }
        }
        
        // Submit form
        setTimeout(() => {
            if (bulkPickupForm) {
                bulkPickupForm.submit();
            } else {
                // Re-enable button if form not found
                this.disabled = false;
                this.innerHTML = originalButtonText;
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'none';
                }
                alert('Error: Could not find bulk pickup form. Please try again.');
            }
        }, 1000);
    });
    
    // Add confirmation for individual pickups
    const pickupForms = document.querySelectorAll('form[action*="markAsPickedUp"]');
    pickupForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to mark this order as picked up?')) {
                e.preventDefault();
            }
        });
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isBulkMode) {
            exitBulkMode();
        }
        
        // Ctrl/Cmd + A to select all in bulk mode
        if ((e.ctrlKey || e.metaKey) && e.key === 'a' && isBulkMode) {
            e.preventDefault();
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = !selectAllCheckbox.checked;
                selectAllCheckbox.dispatchEvent(new Event('change'));
            }
        }
    });
    
    // Initialize
    updateSelectedCount();
    
    // Add some debug logging
    console.log('Bulk pickup system initialized');
    console.log('Total orders on page:', orderCheckboxes.length);
    console.log('Total rows with checkboxes:', document.querySelectorAll('.bulk-checkbox-cell').length);
});
</script>

@endsection