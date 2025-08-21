<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class UpdateLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            
            // Only update if it's been more than 5 minutes since last update
            // to avoid too many database writes
            if (!$user->last_activity || $user->last_activity->diffInMinutes(now()) >= 5) {
                $user->last_activity = now();
                $user->save();
            }
        }

        return $next($request);
    }
}
