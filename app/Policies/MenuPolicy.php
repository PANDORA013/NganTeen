<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // Anyone can view the menu list
    }

    public function view(User $user, Menu $menu)
    {
        return true; // Anyone can view individual menus
    }

    public function create(User $user)
    {
        return $user->role === 'penjual';
    }

    public function update(User $user, Menu $menu)
    {
        return $user->id === $menu->user_id && $user->role === 'penjual';
    }

    public function delete(User $user, Menu $menu)
    {
        return $user->id === $menu->user_id && $user->role === 'penjual';
    }

    public function manageStock(User $user, Menu $menu)
    {
        return $user->id === $menu->user_id && $user->role === 'penjual';
    }
}
