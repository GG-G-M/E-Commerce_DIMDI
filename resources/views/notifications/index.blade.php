@extends('layouts.Nofooter')

@section('content')
<style>
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
        max-width: 950px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .notifications-header {
        background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 2.5rem 2rem;
        margin-bottom: 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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
        border-radius: 0 0 16px 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        min-height: 400px;
    }

    .notification-card {
        background: white;
        border: 1px solid var(--medium-gray);
        border-radius: 16px;
        padding: 1.75rem;
        margin-bottom: 1.25rem;
        transition: all 0.3s ease;
        position: relative;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .notification-card:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        transform: translateY(-3px);
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

    .notification-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--light-green);
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(44, 143, 12, 0.15);
    }

    .notification-icon-wrapper i {
        font-size: 1.4rem;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
        padding-left: 1rem;
    }

    .notification-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    .notification-message {
        font-size: 0.95rem;
        color: var(--dark-gray);
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
        padding-top: 0.75rem;
        border-top: 1px solid var(--medium-gray);
    }

    .notification-time {
        font-size: 0.875rem;
        color: var(--dark-gray);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notification-badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.9rem;
        border-radius: 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .notification-actions {
        display: flex;
        gap: 0.875rem;
        flex-wrap: wrap;
        margin-top: 0.5rem;
    }

    .btn-rounded {
        border-radius: 25px;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-rounded:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .btn-rounded:active {
        transform: translateY(0);
    }

    .btn-primary-rounded {
        background: linear-gradient(135deg, var(--primary-green), var(--accent-green));
        color: white;
    }

    .btn-primary-rounded:hover {
        background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
        color: white;
    }

    .btn-success-rounded {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .btn-success-rounded:hover {
        background: linear-gradient(135deg, #218838, #28a745);
        color: white;
    }

    .btn-outline-rounded {
        border: 2px solid var(--medium-gray);
        background: white;
        color: var(--text-dark);
    }

    .btn-outline-rounded:hover {
        border-color: var(--primary-green);
        background: var(--light-green);
        color: var(--primary-green);
    }

    .btn-light-rounded {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-light-rounded:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        border-color: rgba(255, 255, 255, 0.5);
    }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        color: var(--dark-gray);
    }

    .empty-state i {
        font-size: 5rem;
        color: var(--medium-gray);
        margin-bottom: 2rem;
        opacity: 0.6;
    }

    .empty-state h3 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-dark);
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

    @media (max-width: 768px) {
        .notifications-container {
            padding: 0 0.5rem;
        }

        .notifications-header {
            padding: 1.75rem 1.5rem;
            border-radius: 12px 12px 0 0;
        }

        .notifications-header h2 {
            font-size: 1.5rem;
        }

        .notifications-body {
            padding: 1.5rem 1rem;
            border-radius: 0 0 12px 12px;
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

        .notification-actions .btn-rounded {
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

        .header-actions .btn-rounded {
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

        .notification-actions .btn-rounded {
            min-width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>

<div class="container py-5">
    <div class="notifications-container">
        <!-- Header -->
        <div class="notifications-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div class="flex-grow-1">
                    <h2 class="mb-2">
                        <i class="fas fa-bell me-2"></i>Notifications
                    </h2>
                    <p class="mb-0">All recent updates about your orders and account activity</p>
                </div>
                <div class="header-actions">
                    @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                        <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="d-inline m-0">
                            @csrf
                            <button type="submit" class="btn btn-light-rounded btn-rounded">
                                <i class="fas fa-check-double"></i>
                                <span>Mark all as read</span>
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('orders.index') }}" class="btn btn-light-rounded btn-rounded">
                        <i class="fas fa-shopping-bag"></i>
                        <span>My Orders</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Notifications Body -->
        <div class="notifications-body">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = $notification->read_at === null;
                    $hasReceipt = isset($data['receipt_view_url']) || isset($data['receipt_download_url']);
                @endphp
                <div class="notification-card {{ $isUnread ? 'unread' : 'read' }}">
                    <div class="d-flex align-items-start">
                        <!-- Icon -->
                        <div class="notification-icon-wrapper">
                            <i class="{{ $data['icon'] ?? 'fas fa-bell' }} text-{{ $data['color'] ?? 'primary' }}"></i>
                        </div>

                        <!-- Content -->
                        <div class="notification-content">
                            <div class="notification-title">
                                @if(isset($data['order_number']))
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
                                @if($hasReceipt)
                                    <span class="badge bg-success notification-badge">
                                        <i class="fas fa-receipt"></i>
                                        <span>Receipt ready</span>
                                    </span>
                                @endif
                                @if(isset($data['status_display']))
                                    <span class="badge bg-{{ $data['color'] ?? 'secondary' }} notification-badge">
                                        {{ $data['status_display'] }}
                                    </span>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="notification-actions">
                                @if($hasReceipt)
                                    @if(isset($data['receipt_view_url']))
                                        <a href="{{ $data['receipt_view_url'] }}" class="btn btn-success-rounded btn-rounded">
                                            <i class="fas fa-eye"></i>
                                            <span>View Receipt</span>
                                        </a>
                                    @endif
                                    @if(isset($data['receipt_download_url']))
                                        <a href="{{ $data['receipt_download_url'] }}" class="btn btn-outline-rounded btn-rounded">
                                            <i class="fas fa-download"></i>
                                            <span>Download</span>
                                        </a>
                                    @endif
                                @endif
                                @if(isset($data['order_url']))
                                    <a href="{{ $data['order_url'] }}" class="btn btn-primary-rounded btn-rounded">
                                        <i class="fas fa-shopping-bag"></i>
                                        <span>View Order</span>
                                    </a>
                                @elseif(isset($data['url']) && $data['url'] !== '#')
                                    <a href="{{ $data['url'] }}" class="btn btn-primary-rounded btn-rounded">
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
                    <a href="{{ route('orders.index') }}" class="btn btn-primary-rounded btn-rounded">
                        <i class="fas fa-shopping-bag"></i>
                        <span>View My Orders</span>
                    </a>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="pagination-wrapper">
                    <div class="d-flex justify-content-center">
                        {{ $notifications->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

