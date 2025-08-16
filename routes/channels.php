<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private channel for user-specific notifications
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Public channel for order updates (anyone can listen)
Broadcast::channel('orders.{orderId}', function ($user, $orderId) {
    // You can add authorization logic here if needed
    return true;
});

// Public channel for menu updates
Broadcast::channel('menu.updates', function ($user) {
    return true;
});

// Public channel for area-specific updates
Broadcast::channel('area.{areaName}', function ($user, $areaName) {
    return true;
});
