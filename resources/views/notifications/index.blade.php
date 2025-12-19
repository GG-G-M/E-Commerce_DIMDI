@extends('layouts.Nofooter')

@section('content')
    <style>
        /* 🌿 Enhanced Green Theme - Fully Consistent with Products Page */
        :root {
            --primary-green: #2C8F0C;
            --dark-green: #1E6A08;
            --light-green: #E8F5E6;
            --accent-green: #4CAF50;
            --light-gray: #F8F9FA;
            --medium-gray: #E9ECEF;
            --dark-gray: #6C757D;
            --text-dark: #212529;
        }

        .notifications-container {
            max-width: 1200px; /* Made wider like profile page */
            margin: 0 auto;
            padding: 0 15px;
            margin-top: 1rem;
        }

        /* Full-width header at the top - matching products page exactly */
        .notifications-header {
            background: linear-gradient(135deg, #2C8F0C, #4CAF50) !important;
            color: white;
            border-radius: 0 0 16px 16px;
            padding: 2.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            margin-top: -1.5rem;
        }

        .notifications-header h2 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.75rem;
            display: flex;
            align-items: center;
        }

        .notifications-header p {
            margin-bottom: 0;
            opacity: 0.95;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .notifications-body {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2.5rem 3rem; /* Increased padding for wider card */
            min-height: 400px;
            border: 1px solid var(--medium-gray);
        }

        /* Enhanced notification cards matching product cards - Made wider */
        .notification-card {
            background: white;
            border: 1px solid var(--medium-gray);
            border-radius: 16px;
            padding: 2rem; /* Increased padding */
            margin-bottom: 1.5rem; /* Slightly more spacing */
            transition: all 0.3s ease;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .notification-card:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            transform: translateY(-8px);
            border-color: var(--primary-green);
        }

        .notification-card.unread {
            background: rgba(44, 143, 12, 0.04);
            border-left: 5px solid var(--primary-green);
            box-shadow: 0 4px 12px rgba(44, 143, 12, 0.1);
        }

        .notification-card.read {
            opacity: 0.88;
            background: var(--light-gray);
        }

        /* Enhanced icon wrapper - slightly larger */
        .notification-icon-wrapper {
            width: 60px; /* Increased from 56px */
            height: 60px; /* Increased from 56px */
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light-green);
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(44, 143, 12, 0.15);
        }

        .notification-icon-wrapper i {
            font-size: 1.5rem; /* Slightly larger */
            color: var(--primary-green);
        }

        .notification-content {
            flex: 1;
            min-width: 0;
            padding-left: 1.25rem; /* Slightly more padding */
        }

        .notification-title {
            font-size: 1.15rem; /* Slightly larger */
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.875rem; /* Slightly more spacing */
            line-height: 1.4;
        }

        .notification-message {
            font-size: 1rem; /* Slightly larger */
            color: var(--dark-gray);
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .notification-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem; /* Slightly more spacing */
            padding-top: 0.875rem; /* Slightly more spacing */
            border-top: 1px solid var(--medium-gray);
        }

        .notification-time {
            font-size: 0.9rem; /* Slightly larger */
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Enhanced badges matching product page */
        .notification-badge {
            font-size: 0.85rem; /* Slightly larger */
            padding: 0.45rem 1rem; /* Slightly more padding */
            border-radius: 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .bg-success {
            background: linear-gradient(135deg, #2C8F0C, #4CAF50) !important;
            border: none;
        }

        /* Enhanced action buttons - MATCHING PRODUCT PAGE STYLE */
        .notification-actions {
            display: flex;
            gap: 1rem; /* Slightly more spacing */
            flex-wrap: wrap;
            margin-top: 0.75rem; /* Slightly more spacing */
        }

        /* Consistent button styles with products page */
        .btn-view-details {
            background: transparent;
            color: #2C8F0C;
            border: 2px solid #2C8F0C;
            border-radius: 10px;
            padding: 10px 16px; /* Slightly larger */
            font-size: 0.9rem; /* Slightly larger */
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            cursor: pointer;
            min-height: 44px; /* Slightly taller */
        }

        .btn-view-details:hover {
            background: #2C8F0C;
            color: white;
            transform: translateY(-1px);
            text-decoration: none;
        }

        .btn-add-cart {
            background: linear-gradient(135deg, #2C8F0C, #4CAF50);
            color: white;
            border: 2px solid transparent;
            border-radius: 10px;
            padding: 10px 16px; /* Slightly larger */
            font-size: 0.9rem; /* Slightly larger */
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            cursor: pointer;
            min-height: 44px; /* Slightly taller */
        }

        .btn-add-cart:hover:not(:disabled) {
            background: linear-gradient(135deg, #1E6A08, #2C8F0C);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(44, 143, 12, 0.3);
            text-decoration: none;
        }

        /* Success button variant */
        .btn-success-hover {
            background: transparent;
            color: #28a745;
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 10px 16px; /* Slightly larger */
            font-size: 0.9rem; /* Slightly larger */
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            cursor: pointer;
            min-height: 44px; /* Slightly taller */
        }

        .btn-success-hover:hover {
            background: #28a745;
            color: white;
            transform: translateY(-1px);
            text-decoration: none;
        }

        /* Outline button variant */
        .btn-outline-rounded {
            background: transparent;
            color: var(--text-dark);
            border: 2px solid var(--medium-gray);
            border-radius: 10px;
            padding: 10px 16px; /* Slightly larger */
            font-size: 0.9rem; /* Slightly larger */
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            cursor: pointer;
            min-height: 44px; /* Slightly taller */
        }

        .btn-outline-rounded:hover {
            border-color: var(--primary-green);
            background: var(--light-green);
            color: var(--primary-green);
            transform: translateY(-1px);
            text-decoration: none;
        }

        /* Light button variant */
        .btn-light-rounded {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 10px 16px; /* Slightly larger */
            font-size: 0.9rem; /* Slightly larger */
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            cursor: pointer;
            min-height: 44px; /* Slightly taller */
        }

        .btn-light-rounded:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            color: var(--dark-gray);
            background: linear-gradient(135deg, #f8fff8, #f0f9f0);
            border-radius: 20px;
            margin: 2rem 0;
        }

        .empty-state i {
            font-size: 5rem;
            color: var(--primary-green);
            margin-bottom: 2rem;
            opacity: 0.7;
        }

        .empty-state h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-green);
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1.05rem;
            color: var(--dark-gray);
            margin-bottom: 2rem;
            line-height: 1.6;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .header-actions {
            display: flex;
            gap: 0.875rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .pagination-wrapper {
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid var(--medium-gray);
        }

        .pagination {
            justify-content: center;
        }

        .pagination .page-link {
            border-radius: 25px;
            margin: 0 0.25rem;
            padding: 0.5rem 1rem;
            border: 1px solid var(--medium-gray);
            color: var(--text-dark);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: var(--light-green);
            border-color: var(--primary-green);
            color: var(--primary-green);
            transform: translateY(-2px);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-green), var(--accent-green));
            border-color: var(--primary-green);
            color: white;
        }
        

        /* Responsive Design - Adjusted for wider layout */
        @media (max-width: 1400px) {
            .notifications-container {
                max-width: 1100px;
            }
        }

        @media (max-width: 1200px) {
            .notifications-container {
                max-width: 95%;
            }
            
            .notifications-body {
                padding: 2rem 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .notifications-container {
                padding: 0 0.5rem;
                margin-top: 0.5rem;
                max-width: 100%;
            }

            .notifications-header {
                padding: 1.75rem 1.5rem;
                border-radius: 0 0 12px 12px;
                margin-top: -0.75rem;
            }

            .notifications-header h2 {
                font-size: 1.5rem;
            }

            .notifications-body {
                padding: 1.5rem 1rem;
                border-radius: 12px;
            }

            .notification-card {
                padding: 1.5rem 1.25rem;
                margin-bottom: 1rem;
            }

            .notification-icon-wrapper {
                width: 48px;
                height: 48px;
            }

            .notification-content {
                padding-left: 0.875rem;
            }

            .notification-title {
                font-size: 1rem;
                margin-bottom: 0.625rem;
            }

            .notification-message {
                font-size: 0.9rem;
                margin-bottom: 0.875rem;
            }

            .notification-meta {
                margin-bottom: 1rem;
                gap: 0.75rem;
            }

            .notification-actions {
                width: 100%;
                gap: 0.75rem;
            }

            .notification-actions .btn-view-details,
            .notification-actions .btn-add-cart,
            .notification-actions .btn-outline-rounded {
                flex: 1;
                min-width: 140px;
                padding: 0.625rem 1.25rem;
                font-size: 0.85rem;
            }

            .header-actions {
                flex-direction: column;
                width: 100%;
                margin-top: 1.25rem;
                gap: 0.75rem;
            }

            .header-actions .btn-light-rounded {
                width: 100%;
                padding: 0.75rem 1.5rem;
            }

            .empty-state {
                padding: 3rem 1.5rem;
            }

            .empty-state i {
                font-size: 4rem;
                margin-bottom: 1.5rem;
            }

            .empty-state h3 {
                font-size: 1.5rem;
            }

            .empty-state p {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 576px) {
            .notifications-header {
                padding: 1.5rem 1.25rem;
            }

            .notifications-body {
                padding: 1.25rem 0.875rem;
            }

            .notification-card {
                padding: 1.25rem 1rem;
            }

            .notification-actions .btn-view-details,
            .notification-actions .btn-add-cart,
            .notification-actions .btn-outline-rounded {
                min-width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>

    <!-- Full-width header at the top -->
    <div class="notifications-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div class="flex-grow-1">
                    <h2 class="mb-2">
                        <i class="fas fa-bell me-2"></i>Notifications
                    </h2>
                    <p class="mb-0">All recent updates about your orders and account activity</p>
                </div>
                <div class="header-actions">
                    @if (auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                        <form method="POST" action="{{ route('notifications.markAllAsRead') }}" class="d-inline m-0">
                            @csrf
                            <button type="submit" class="btn btn-light-rounded">
                                <i class="fas fa-check-double"></i>
                                <span>Mark all as read</span>
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('orders.index') }}" class="btn btn-light-rounded">
                        <i class="fas fa-shopping-bag"></i>
                        <span>My Orders</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="container-fluid px-xxl-5 py-4"> <!-- Changed to container-fluid for more width -->
        <div class="notifications-container">
            <!-- Notifications Body -->
            <div class="notifications-body">
                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = $notification->read_at === null;
                        // Determine receipt availability from payload (order_id)
                        $hasReceipt = isset($data['order_id']) && !empty($data['order_id']);
                        if ($hasReceipt) {
                            $receiptViewUrl = route('notifications.receipt.view', $notification->id);
                            $receiptDownloadUrl = route('notifications.receipt.download', $notification->id);
                            $receiptPreviewUrl = route('notifications.receipt.preview', $notification->id);
                        }
                    @endphp
                    <div class="notification-card {{ $isUnread ? 'unread' : 'read' }}">
                        <div class="d-flex align-items-start">
                            <!-- Icon -->
                            <div class="notification-icon-wrapper">
                                <i class="{{ $data['icon'] ?? 'fas fa-bell' }}"></i>
                            </div>

                            <!-- Content -->
                            <div class="notification-content">
                                <div class="notification-title">
                                    @if (isset($data['order_number']))
                                        Order #{{ $data['order_number'] }}
                                    @else
                                        Notification
                                    @endif
                                </div>

                                <div class="notification-message">
                                    {{ $data['message'] ?? 'Update available.' }}
                                </div>

                                <div class="notification-meta">
                                    <span class="notification-time">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                                    </span>
                                    @if ($hasReceipt)
                                        <span class="badge bg-success notification-badge">
                                            <i class="fas fa-receipt"></i>
                                            <span>Receipt ready</span>
                                        </span>
                                    @endif
                                    @if (isset($data['status_display']))
                                        <span class="badge bg-{{ $data['color'] ?? 'secondary' }} notification-badge">
                                            {{ $data['status_display'] }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="notification-actions">
                                    @if ($hasReceipt)
                                        <a href="{{ $receiptViewUrl }}" class="btn btn-add-cart">
                                            <i class="fas fa-eye"></i>
                                            <span>View Receipt</span>
                                        </a>
                                        <a href="{{ $receiptDownloadUrl }}" class="btn btn-outline-rounded">
                                            <i class="fas fa-download"></i>
                                            <span>Download</span>
                                        </a>
                                    @endif
                                    @if (isset($data['order_url']))
                                        <a href="{{ $data['order_url'] }}" class="btn btn-view-details">
                                            <i class="fas fa-shopping-bag"></i>
                                            <span>View Order</span>
                                        </a>
                                    @elseif(isset($data['url']) && $data['url'] !== '#')
                                        <a href="{{ $data['url'] }}" class="btn btn-success-hover">
                                            <i class="fas fa-external-link-alt"></i>
                                            <span>Open</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-bell-slash"></i>
                        <h3>No Notifications</h3>
                        <p>You don't have any notifications yet. We'll notify you when there are updates about your orders.</p>
                        <a href="{{ route('orders.index') }}" class="btn btn-view-details">
                            <i class="fas fa-shopping-bag"></i>
                            <span>View My Orders</span>
                        </a>
                    </div>
                @endforelse

                <!-- Pagination -->
                @if ($notifications->hasPages())
                    <div class="pagination-wrapper">
                        <div class="d-flex justify-content-center">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Toast notification function matching products page
        function showToast(message, type = 'success') {
            // Remove existing toasts
            document.querySelectorAll('.upper-middle-toast').forEach(toast => toast.remove());

            const bgColors = {
                'success': '#2C8F0C',
                'error': '#dc3545',
                'warning': '#ffc107',
                'info': '#17a2b8'
            };

            const icons = {
                'success': 'fa-check-circle',
                'error': 'fa-exclamation-triangle',
                'warning': 'fa-exclamation-circle',
                'info': 'fa-info-circle'
            };

            const bgColor = bgColors[type] || bgColors.success;
            const icon = icons[type] || icons.success;
            const textColor = type === 'warning' ? 'text-dark' : 'text-white';

            const toast = document.createElement('div');
            toast.className = 'upper-middle-toast position-fixed start-50 translate-middle-x p-3';
            toast.style.cssText = `
                top: 100px;
                z-index: 9999;
                min-width: 300px;
                text-align: center;
            `;

            toast.innerHTML = `
                <div class="toast align-items-center border-0 show shadow-lg" role="alert" style="background-color: ${bgColor}; border-radius: 10px;">
                    <div class="d-flex justify-content-center align-items-center p-3">
                        <div class="toast-body ${textColor} d-flex align-items-center">
                            <i class="fas ${icon} me-2 fs-5"></i>
                            <span class="fw-semibold">${message}</span>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(toast);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    // Add fade out animation
                    toast.style.transition = 'all 0.3s ease';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(-50%) translateY(-20px)';
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 300);
                }
            }, 3000);
        }

        // Mark as read functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Handle mark as read actions if needed
            const notificationCards = document.querySelectorAll('.notification-card.unread');
            
            notificationCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    // Don't trigger if clicking on a button
                    if (!e.target.closest('a') && !e.target.closest('button')) {
                        const notificationId = this.dataset.id;
                        if (notificationId) {
                            // You can add AJAX to mark as read on click
                            fetch(`/notifications/${notificationId}/read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    this.classList.remove('unread');
                                    this.classList.add('read');
                                }
                            });
                        }
                    }
                });
            });

            // Mark all as read form submission feedback
            const markAllForm = document.querySelector('form[action*="markAllAsRead"]');
            if (markAllForm) {
                markAllForm.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Processing...</span>';
                    
                    // Optional: Add a delay to show processing
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        
                        // Show success toast
                        showToast('All notifications marked as read!', 'success');
                        
                        // Update UI
                        document.querySelectorAll('.notification-card.unread').forEach(card => {
                            card.classList.remove('unread');
                            card.classList.add('read');
                        });
                    }, 1500);
                });
            }
        });
    </script>
@endsection