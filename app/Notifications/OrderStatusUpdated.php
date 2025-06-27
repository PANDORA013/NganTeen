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

    /**
     * Create a new notification instance.
     */
    public function __construct($order, $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Status Pesanan Diperbarui')
            ->line('Status pesanan Anda untuk ' . $this->order->menu->nama . ' telah diperbarui.')
            ->line('Status baru: ' . $this->status)
            ->action('Lihat Pesanan', url('/orders'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'menu_name' => $this->order->menu->nama,
            'status' => $this->status,
            'message' => 'Status pesanan ' . $this->order->menu->nama . ' berubah menjadi ' . $this->status
        ];
    }
}
