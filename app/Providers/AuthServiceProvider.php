<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Menu;
use App\Policies\MenuPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Menu::class => MenuPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-menu', function ($user) {
            return $user->role === 'penjual';
        });

        Gate::define('access-cart', function ($user) {
            return $user->role === 'pembeli';
        });
    }
}
