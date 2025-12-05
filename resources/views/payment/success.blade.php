@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="text-success mb-4">
                        <i class="fas fa-check-circle" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="text-success mb-3">Payment Successful!</h2>
                    <p class="text-muted mb-4">Thank you for your order. Your payment has been processed successfully.</p>
                    
                    <!-- Auto-check for notifications -->
                    <div class="alert alert-info mb-4" id="notificationAlert" style="display: none;">
                        <i class="fas fa-bell me-2"></i>
                        <span id="notificationMessage">A notification has been added to your notifications panel.</span>
                        <a href="{{ route('notifications.index') }}" class="alert-link ms-1">View Notifications</a>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-block">
                        <a href="{{ route('orders.index') }}" class="btn btn-success px-4">
                            <i class="fas fa-receipt me-2"></i>View Orders
                        </a>
                        <a href="{{ route('notifications.index') }}" class="btn btn-info px-4">
                            <i class="fas fa-bell me-2"></i>View Notifications
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-success px-4">
                            <i class="fas fa-home me-2"></i>Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // If we just completed a payment, show notification alert
    @if(session('notification'))
        const notification = @json(session('notification'));
        const alertDiv = document.getElementById('notificationAlert');
        const messageSpan = document.getElementById('notificationMessage');
        
        if (notification.message) {
            messageSpan.textContent = notification.message;
            alertDiv.style.display = 'block';
        }
        
        // Show toast notification
        if (window.notificationSystem) {
            window.notificationSystem.showToast(notification.message, notification.type || 'success');
        }
        
        // Force refresh notification counts
        if (window.notificationSystem) {
            window.notificationSystem.updateNotificationCounts();
            window.notificationSystem.loadNotifications();
        }
    @endif
    
    // Auto-update notification count after payment
    setTimeout(() => {
        if (window.notificationSystem) {
            window.notificationSystem.checkForNewNotifications();
        }
    }, 1000);
});
</script>
@endsection