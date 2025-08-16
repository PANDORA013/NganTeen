<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $orderId;
    public $userId;
    public $message;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, string $oldStatus, string $newStatus)
    {
        $this->orderId = $order->id;
        $this->userId = $order->user_id;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->message = $this->generateMessage();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
            new Channel('orders.' . $this->orderId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'order.status.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->orderId,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => $this->message,
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Generate notification message based on status change.
     */
    private function generateMessage(): string
    {
        return match ($this->newStatus) {
            'ready' => 'Pesanan Anda sudah siap! Silakan ambil di lokasi penjual.',
            'paid' => 'Terima kasih! Pembayaran pesanan Anda sudah dikonfirmasi.',
            'cancelled' => 'Maaf, pesanan Anda dibatalkan. Silakan hubungi penjual untuk info lebih lanjut.',
            default => 'Status pesanan Anda telah diperbarui.',
        };
    }
}
