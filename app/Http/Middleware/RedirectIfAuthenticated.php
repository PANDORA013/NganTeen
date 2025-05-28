<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string|null  ...$guards
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirect based on user role with better error handling
                try {
                    return match($user->role) {
                        'penjual' => redirect()->route('penjual.home'),
                        'pembeli' => redirect()->route('pembeli.home'),
                        default => redirect()->route('login')
                            ->with('error', 'Invalid user role')
                    };
                } catch (\Exception $e) {
                    Auth::logout();
                    return redirect()->route('login')
                        ->with('error', 'Authentication error occurred');
                }
            }
        }

        return $next($request);
    }
}
