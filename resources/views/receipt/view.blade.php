@extends('layouts.Nofooter')

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
                        <a href="{{ route('notifications.receipt.download', $notification->id) }}" class="btn btn-success btn-sm">
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

                    <!-- Consistent button styling -->
                    <style>
                        /* Consistent button styles matching other pages */
                        .btn-success {
                            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
                            color: white;
                            border: 2px solid transparent;
                            border-radius: 10px;
                            padding: 10px 20px;
                            font-weight: 600;
                            font-size: 0.95rem;
                            transition: all 0.3s ease;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            gap: 0.5rem;
                            text-decoration: none;
                        }
                        
                        .btn-success:hover:not(:disabled) {
                            background: linear-gradient(135deg, #1E6A08, #2C8F0C);
                            transform: translateY(-2px);
                            box-shadow: 0 8px 20px rgba(44, 143, 12, 0.3);
                            text-decoration: none;
                        }
                        
                        .btn-outline-success {
                            background: transparent;
                            color: #2C8F0C;
                            border: 2px solid #2C8F0C;
                            border-radius: 10px;
                            padding: 10px 20px;
                            font-weight: 600;
                            font-size: 0.95rem;
                            transition: all 0.3s ease;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            gap: 0.5rem;
                            text-decoration: none;
                        }
                        
                        .btn-outline-success:hover {
                            background: #2C8F0C;
                            color: white;
                            transform: translateY(-2px);
                            text-decoration: none;
                        }
                        
                        .btn-secondary {
                            background: #6c757d;
                            color: white;
                            border: 2px solid transparent;
                            border-radius: 10px;
                            padding: 10px 20px;
                            font-weight: 600;
                            font-size: 0.95rem;
                            transition: all 0.3s ease;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            gap: 0.5rem;
                            text-decoration: none;
                        }
                        
                        .btn-secondary:hover:not(:disabled) {
                            background: #5a6268;
                            transform: translateY(-2px);
                            box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
                            text-decoration: none;
                        }
                        
                        .btn-outline-light {
                            background: transparent;
                            color: white;
                            border: 2px solid rgba(255, 255, 255, 0.3);
                            border-radius: 10px;
                            padding: 6px 12px;
                            font-weight: 500;
                            font-size: 0.875rem;
                            transition: all 0.3s ease;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            gap: 0.5rem;
                            text-decoration: none;
                        }
                        
                        .btn-outline-light:hover {
                            background: rgba(255, 255, 255, 0.1);
                            border-color: rgba(255, 255, 255, 0.5);
                            transform: translateY(-1px);
                            text-decoration: none;
                        }
                        
                        .btn-success.btn-sm {
                            padding: 6px 12px;
                            font-size: 0.875rem;
                            font-weight: 500;
                            border-radius: 10px;
                        }
                        
                        .btn-success.btn-sm:hover {
                            transform: translateY(-1px);
                        }
                        
                        /* Button icons */
                        .btn i {
                            font-size: 0.9rem;
                        }
                        
                        /* Responsive buttons */
                        @media (max-width: 768px) {
                            .btn, .btn-sm {
                                padding: 8px 16px;
                                font-size: 0.875rem;
                            }
                            
                            .d-flex.gap-2 {
                                flex-wrap: wrap;
                            }
                            
                            .d-flex.gap-2 .btn {
                                flex: 1;
                                min-width: 140px;
                            }
                        }
                        
                        @media (max-width: 576px) {
                            .d-flex.gap-2 {
                                flex-direction: column;
                            }
                            
                            .d-flex.gap-2 .btn {
                                width: 100%;
                            }
                        }
                    </style>

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