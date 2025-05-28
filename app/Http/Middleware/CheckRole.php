<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== $role) {
            return redirect()->route('login')
                ->withErrors(['role' => 'Anda tidak memiliki akses ke halaman tersebut.']);
        }

        return $next($request);
    }
}
