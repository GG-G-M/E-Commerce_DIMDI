<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Save to database and send email
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'message' => "Payment received for Order #{$this->order->order_number}. Your order is now being processed.",
            'icon' => 'fas fa-credit-card',
            'color' => 'success',
            'url' => route('orders.show', $this->order),
            'receipt_view_url' => route('orders.receipt.preview', $this->order),
            'receipt_download_url' => route('orders.receipt.download', $this->order)
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Payment Received - Order #' . $this->order->order_number)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Your payment has been received successfully.')
                    ->line('Order Number: ' . $this->order->order_number)
                    ->line('Amount Paid: ₱' . number_format($this->order->total_amount, 2))
                    ->action('View Order', route('orders.show', $this->order))
                    ->line('Thank you for your purchase!');
    }
}