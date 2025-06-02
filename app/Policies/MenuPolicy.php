<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability)
    {
        if ($user->role !== 'penjual') {
            return false;
        }
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Menu $menu)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->role === 'penjual';
    }

    public function update(User $user, Menu $menu)
    {
        return $user->id === $menu->user_id;
    }

    public function delete(User $user, Menu $menu)
    {
        return $user->id === $menu->user_id;
    }

    public function restore(User $user, Menu $menu)
    {
        return $user->id === $menu->user_id;
    }

    public function forceDelete(User $user, Menu $menu)
    {
        return $user->id === $menu->user_id;
    }
}
