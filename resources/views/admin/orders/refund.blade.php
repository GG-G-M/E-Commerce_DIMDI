@extends('layouts.admin')

@section('content')
<style>
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #2C8F0C;
    }

    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .btn-success {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
    }

    .order-info-card {
        background: #F8FDF8;
        border: 1px solid #E8F5E6;
        border-radius: 8px;
        padding: 1.5rem;
    }

    .refund-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2C8F0C;
    }
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1" style="color: #2C8F0C; font-weight: 700;">
                <i class="fas fa-money-bill-wave me-2"></i>Process Refund
            </h1>
            <p class="text-muted mb-0">Process refund for canceled order #{{ $order->order_number }}</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Orders
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-custom mb-4">
            <div class="card-header card-header-custom">
                <i class="fas fa-credit-card me-2"></i> Refund Details
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.refund.process', $order) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="alert alert-info border-0 mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        You are about to process a refund for this canceled order.
                    </div>

                    <div class="order-info-card mb-4">
                        <h6 class="mb-3 text-dark fw-bold">Order Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Order #:</strong><br>
                                <span class="text-dark">{{ $order->order_number }}</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Customer:</strong><br>
                                <span class="text-dark">{{ $order->customer_name }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Order Date:</strong><br>
                                <span class="text-dark">{{ $order->created_at->format('M d, Y g:i A') }}</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Total Amount:</strong><br>
                                <span class="refund-amount">₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                        @if($order->cancellation_reason)
                        <div class="row mt-2">
                            <div class="col-12">
                                <strong>Cancellation Reason:</strong><br>
                                <span class="text-dark">{{ $order->cancellation_reason }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="refund_amount" class="form-label fw-bold">Refund Amount ₱</label>
                                <input type="number" class="form-control" id="refund_amount" name="refund_amount" 
                                       step="0.01" min="0" max="{{ $order->total_amount }}" 
                                       value="{{ $order->total_amount }}" required>
                                <div class="form-text">Maximum refundable amount: ₱{{ number_format($order->total_amount, 2) }}</div>
                                @error('refund_amount')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="refund_method" class="form-label fw-bold">Refund Method</label>
                                <select class="form-select" id="refund_method" name="refund_method" required>
                                    <option value="">Select refund method</option>
                                    <option value="original_payment" {{ old('refund_method') == 'original_payment' ? 'selected' : '' }}>
                                        Original Payment Method
                                    </option>
                                    <option value="bank_transfer" {{ old('refund_method') == 'bank_transfer' ? 'selected' : '' }}>
                                        Bank Transfer
                                    </option>
                                    <option value="store_credit" {{ old('refund_method') == 'store_credit' ? 'selected' : '' }}>
                                        Store Credit
                                    </option>
                                    <option value="cash" {{ old('refund_method') == 'cash' ? 'selected' : '' }}>
                                        Cash
                                    </option>
                                </select>
                                @error('refund_method')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="refund_notes" class="form-label fw-bold">Refund Notes</label>
                        <textarea class="form-control" id="refund_notes" name="refund_notes" 
                                  rows="3" placeholder="Add any notes about this refund...">{{ old('refund_notes') }}</textarea>
                        @error('refund_notes')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="send_notification" name="send_notification" value="1" checked>
                        <label class="form-check-label" for="send_notification">
                            <i class="fas fa-bell me-1"></i>Send notification to customer
                        </label>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Process Refund
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-custom mb-4">
            <div class="card-header card-header-custom">
                <i class="fas fa-info-circle me-2"></i> Refund Guidelines
            </div>
            <div class="card-body">
                <div class="alert alert-warning border-0">
                    <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Important</h6>
                    <ul class="mb-0 ps-3">
                        <li>Verify the order is eligible for refund</li>
                        <li>Ensure the refund amount is correct</li>
                        <li>Double-check the refund method</li>
                        <li>Notify the customer after processing</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection