@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: #2C8F0C; color: white;">
                    <div>
                        <h5 class="mb-0">Receipt for Order #{{ $order->order_number }}</h5>
                        <small class="text-white-50">Payment confirmed</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('notifications.receipt.download', $notification->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-download me-1"></i>Download PDF
                        </a>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-shopping-bag me-1"></i>View order
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        Hi {{ $order->customer_name }}, we received your payment. You can view or download your official receipt below.
                    </p>

                    <div class="ratio ratio-16x9 border rounded">
                        <iframe src="{{ route('notifications.receipt.preview', $notification->id) }}" title="Receipt preview" style="border:0;"></iframe>
                    </div>

                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('notifications.receipt.download', $notification->id) }}" class="btn btn-success">
                            <i class="fas fa-file-pdf me-2"></i>Download receipt
                        </a>
                        <a href="{{ route('notifications.receipt.preview', $notification->id) }}" target="_blank" class="btn btn-outline-success">
                            <i class="fas fa-external-link-alt me-2"></i>Open in new tab
                        </a>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to order
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

