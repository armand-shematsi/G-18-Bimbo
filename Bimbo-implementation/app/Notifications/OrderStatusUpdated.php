<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Status Updated')
            ->greeting('Hello ' . ($notifiable->name ?? 'Customer') . ',')
            ->line('The status of your order #' . $this->order->id . ' has changed to: ' . ucfirst($this->order->status))
            ->action('View Order', url(route('retail.orders.show', $this->order->id)))
            ->line('Thank you for shopping with us!');
    }
} 