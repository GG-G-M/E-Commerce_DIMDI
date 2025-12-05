@php
    $notifications = $notifications ?? collect();
@endphp

@if($notifications->count() > 0)
    @foreach($notifications as $notification)
        @php
            $data = $notification->data;
            $isUnread = $notification->read_at === null;
            $targetUrl = $data['receipt_view_url'] ?? $data['url'] ?? '#';
            $hasReceipt = isset($data['receipt_view_url']);
        @endphp
        <a href="#" class="notification-item d-block p-3 border-bottom text-decoration-none {{ $isUnread ? 'unread' : 'read' }}"
           data-id="{{ $notification->id }}"
           data-url="{{ $targetUrl }}">
            <div class="d-flex align-items-start">
                <div class="notification-icon me-3">
                    <i class="{{ $data['icon'] ?? 'fas fa-bell' }} text-{{ $data['color'] ?? 'primary' }}"></i>
                </div>
                <div class="notification-content flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <h6 class="mb-1" style="font-size: 0.9rem;">
                            @if(isset($data['order_number']))
                                Order #{{ $data['order_number'] }}
                            @else
                                Notification
                            @endif
                            @if($hasReceipt)
                                <span class="badge bg-success ms-1">Receipt</span>
                            @endif
                        </h6>
                        <small class="text-muted notification-time">{{ $data['time_ago'] ?? $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1" style="font-size: 0.85rem;">
                        {{ $data['message'] ?? 'New notification' }}
                    </p>
                    @if(isset($data['status_display']))
                        <small class="text-muted">
                            Status: <span class="badge bg-{{ $data['color'] ?? 'secondary' }}">
                                {{ $data['status_display'] }}
                            </span>
                        </small>
                    @endif
                </div>
            </div>
        </a>
    @endforeach
@else
    <div class="text-center py-4">
        <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
        <p class="text-muted mb-0">No notifications</p>
    </div>
@endif

