<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderPlaced extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Confirmation')
            ->greeting('Hello ' . ($notifiable->name ?? 'Customer') . ',')
            ->line('Your order #' . $this->order->id . ' has been placed successfully!')
            ->line('Order Total: $' . number_format($this->order->total, 2))
            ->action('View Order', url(route('retail.orders.show', $this->order->id)))
            ->line('Thank you for your order!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'A new order #' . $this->order->id . ' has been placed.',
            'total' => $this->order->total,
            'customer' => $this->order->customer_name,
            'url' => url(route('retail.orders.show', $this->order->id)),
        ];
    }
} 