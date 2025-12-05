<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $status;
    public $deliveryPerson;
    public $timestamp;

    public function __construct($order, $status, $deliveryPerson = null)
    {
        $this->order = $order;
        $this->status = $status;
        $this->deliveryPerson = $deliveryPerson;
        $this->timestamp = now();
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->status,
            'status_display' => ucfirst($this->status),
            'delivery_person' => $this->deliveryPerson,
            'message' => $this->getMessage(),
            'time_ago' => $this->getTimeAgo(),
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
            'url' => route('orders.show', $this->order->id),
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->status,
            'message' => $this->getMessage(),
            'time_ago' => $this->getTimeAgo(),
            'url' => route('orders.show', $this->order->id),
        ];
    }

    private function getMessage()
    {
        $messages = [
            'pending' => 'Your order is being processed',
            'confirmed' => 'Your order has been confirmed',
            'processing' => 'Your order is being prepared',
            'shipped' => 'Your order has been shipped',
            'out_for_delivery' => 'Your order is out for delivery',
            'delivered' => 'Your order has been delivered',
            'cancelled' => 'Your order has been cancelled',
        ];

        $message = $messages[$this->status] ?? 'Order status updated';

        if ($this->deliveryPerson && in_array($this->status, ['shipped', 'out_for_delivery', 'delivered'])) {
            $message .= ' by ' . $this->deliveryPerson;
        }

        return $message;
    }

    private function getTimeAgo()
    {
        return $this->timestamp->diffForHumans();
    }

    private function getIcon()
    {
        $icons = [
            'pending' => 'fas fa-clock',
            'confirmed' => 'fas fa-check-circle',
            'processing' => 'fas fa-cogs',
            'shipped' => 'fas fa-shipping-fast',
            'out_for_delivery' => 'fas fa-truck',
            'delivered' => 'fas fa-box-open',
            'cancelled' => 'fas fa-times-circle',
        ];

        return $icons[$this->status] ?? 'fas fa-bell';
    }

    private function getColor()
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'shipped' => 'primary',
            'out_for_delivery' => 'info',
            'delivered' => 'success',
            'cancelled' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }
}