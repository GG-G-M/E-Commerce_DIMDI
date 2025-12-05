<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Order $order)
    {
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payment received - ' . $this->order->order_number)
            ->greeting('Hello ' . $this->order->customer_name . '!')
            ->line('We received your payment.')
            ->line('Order Number: ' . $this->order->order_number)
            ->line('Total Amount: ₱' . number_format($this->order->total_amount, 2))
            ->action('View receipt', route('notifications.receipt.view', ['notification' => $this->id]))
            ->line('You can also view this order anytime from your account.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'payment_received',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'amount' => $this->order->total_amount,
            'message' => "Payment received for order #{$this->order->order_number}.",
            'status_display' => 'Payment received',
            'icon' => 'fas fa-receipt',
            'color' => 'success',
            'url' => route('notifications.receipt.view', ['notification' => $this->id]),
            'receipt_view_url' => route('notifications.receipt.view', ['notification' => $this->id]),
            'receipt_download_url' => route('notifications.receipt.download', ['notification' => $this->id]),
            'order_url' => route('orders.show', $this->order),
            'time_ago' => now()->diffForHumans(),
        ];
    }
}

