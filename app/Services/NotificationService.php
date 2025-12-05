<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Notification;

class NotificationService
{
   // app/Services/NotificationService.php
public static function createPaymentNotification(User $user, Order $order)
{
    $notificationData = [
        'order_id' => $order->id,
        'order_number' => $order->order_number,
        'message' => "Payment successful for Order #{$order->order_number}. Amount: ₱" . number_format($order->total_amount, 2),
        'icon' => 'fas fa-credit-card',
        'color' => 'success',
        'order_url' => route('orders.show', $order),
        // CHANGE THESE ROUTES TO NOTIFICATIONS ROUTES
        'receipt_view_url' => route('notifications.receipt.view', $order->id), // This will be handled by NotificationController
        'receipt_download_url' => route('notifications.receipt.download', $order->id),
        'receipt_preview_url' => route('notifications.receipt.preview', $order->id),
        'amount' => number_format($order->total_amount, 2),
        'timestamp' => now()->toDateTimeString()
    ];

    return $user->notifications()->create([
        'type' => 'payment.success',
        'data' => $notificationData,
        'read_at' => null
    ]);
}
    
    public static function createOrderNotification(User $user, Order $order, $status)
    {
        $messages = [
            'confirmed' => "Order #{$order->order_number} has been confirmed and is being processed.",
            'processing' => "Order #{$order->order_number} is now being processed.",
            'shipped' => "Order #{$order->order_number} has been shipped.",
            'delivered' => "Order #{$order->order_number} has been delivered.",
            'cancelled' => "Order #{$order->order_number} has been cancelled."
        ];
        
        $icons = [
            'confirmed' => 'fas fa-check-circle',
            'processing' => 'fas fa-cogs',
            'shipped' => 'fas fa-shipping-fast',
            'delivered' => 'fas fa-box-open',
            'cancelled' => 'fas fa-times-circle'
        ];
        
        $colors = [
            'confirmed' => 'success',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger'
        ];
        
        $notificationData = [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'message' => $messages[$status] ?? "Order #{$order->order_number} status updated.",
            'icon' => $icons[$status] ?? 'fas fa-bell',
            'color' => $colors[$status] ?? 'info',
            'order_url' => route('orders.show', $order),
            'timestamp' => now()->toDateTimeString()
        ];

        return $user->notifications()->create([
            'type' => 'order.status.' . $status,
            'data' => $notificationData,
            'read_at' => null
        ]);
    }
}