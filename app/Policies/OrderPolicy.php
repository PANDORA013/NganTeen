<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Order $order)
    {
        // Pembeli can only view their own orders
        if ($user->role === 'pembeli') {
            return $user->id === $order->user_id;
        }

        // Penjual can view orders containing their menu items
        if ($user->role === 'penjual') {
            return $order->items()->whereHas('menu', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->exists();
        }

        return false;
    }

    public function cancel(User $user, Order $order)
    {
        // Only pembeli can cancel their own pending/processing orders
        if ($user->role === 'pembeli') {
            return $user->id === $order->user_id && 
                   in_array($order->status, ['pending', 'processing']);
        }

        return false;
    }

    public function updateStatus(User $user, Order $order)
    {
        // Only penjual can update status of orders containing their menu items
        if ($user->role === 'penjual') {
            return $order->items()->whereHas('menu', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->exists();
        }

        return false;
    }
}