<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be accessible while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',          // Allow API endpoints
        'login',          // Allow login page
        'register',       // Allow registration
        'admin/*',        // Allow admin access
        'health-check',   // Allow health checks
    ];
}
