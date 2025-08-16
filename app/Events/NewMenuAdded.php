<?php

namespace App\Events;

use App\Models\Menu;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMenuAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $menuId;
    public $menuNama;
    public $menuHarga;
    public $menuAreaKampus;
    public $menuGambar;
    public $userName;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Menu $menu)
    {
        $this->menuId = $menu->id;
        $this->menuNama = $menu->nama;
        $this->menuHarga = $menu->harga;
        $this->menuAreaKampus = $menu->area_kampus ?? 'Unknown';
        $this->menuGambar = $menu->gambar;
        $this->userName = $menu->user->name ?? 'Unknown';
        $this->message = "Menu baru '{$menu->nama}' telah ditambahkan di {$this->menuAreaKampus}!";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('menu.updates'),
            new Channel('area.' . str_replace(' ', '', strtolower($this->menuAreaKampus))),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'menu.added';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'menu' => [
                'id' => $this->menuId,
                'nama' => $this->menuNama,
                'harga' => $this->menuHarga,
                'area_kampus' => $this->menuAreaKampus,
                'gambar' => $this->menuGambar,
                'user_name' => $this->userName,
            ],
            'message' => $this->message,
            'timestamp' => now()->toISOString(),
        ];
    }
}
