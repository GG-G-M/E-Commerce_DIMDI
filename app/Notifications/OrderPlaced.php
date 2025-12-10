<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Confirmation - ' . $this->order->order_number)
            ->greeting('Hello ' . $this->order->customer_name . '!')
            ->line('Thank you for your order!')
            ->line('Order Number: ' . $this->order->order_number)
            ->line('Total Amount: ₱' . number_format((float) $this->order->total_amount, 2))
            ->action('View Order', route('orders.show', $this->order))
            ->line('You can download your receipt from your order details page.')
            ->salutation('Thank you for shopping with us!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'order_placed',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'title' => 'Order Placed Successfully!',
            'message' => "Your order #{$this->order->order_number} has been placed successfully.",
            'total_amount' => $this->order->total_amount,
            'status' => $this->order->order_status,
            // Do not reference $this->id here — the notification id isn't available during serialization.
            // Build receipt links using the notification id when rendering the notification list instead.
            'order_url' => route('orders.show', $this->order),
            'timestamp' => now()->toDateTimeString()
        ];
    }
}