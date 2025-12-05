@extends('layouts.Nofooter')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Notifications</h4>
            <small class="text-muted">All recent updates about your orders</small>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-shopping-bag me-1"></i>My Orders
        </a>
    </div>

    <div class="list-group shadow-sm">
        @forelse($notifications as $notification)
            @php
                $data = $notification->data;
                $hasReceipt = isset($data['receipt_view_url']) || isset($data['receipt_download_url']);
            @endphp
            <div class="list-group-item list-group-item-action">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="{{ $data['icon'] ?? 'fas fa-bell' }} text-{{ $data['color'] ?? 'primary' }}"></i>
                            <strong>{{ $data['order_number'] ?? 'Notification' }}</strong>
                            @if($hasReceipt)
                                <span class="badge bg-success">Receipt ready</span>
                            @endif
                        </div>
                        <div class="text-muted small mb-1">{{ $notification->created_at->diffForHumans() }}</div>
                        <p class="mb-0">{{ $data['message'] ?? 'Update available.' }}</p>
                    </div>
                    <div class="text-end">
                        @if($hasReceipt)
                            <a href="{{ $data['receipt_view_url'] ?? '#' }}" class="btn btn-success btn-sm mb-1">
                                <i class="fas fa-eye me-1"></i>View receipt
                            </a>
                            <a href="{{ $data['receipt_download_url'] ?? '#' }}" class="btn btn-outline-success btn-sm mb-1">
                                <i class="fas fa-download me-1"></i>Download
                            </a>
                        @endif
                        @if(isset($data['order_url']))
                            <a href="{{ $data['order_url'] }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-shopping-bag me-1"></i>Order
                            </a>
                        @elseif(isset($data['url']))
                            <a href="{{ $data['url'] }}" class="btn btn-outline-secondary btn-sm">
                                Open
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="list-group-item text-center text-muted py-4">
                <i class="fas fa-bell-slash fa-2x mb-2"></i>
                <p class="mb-0">You have no notifications yet.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</div>
@endsection

