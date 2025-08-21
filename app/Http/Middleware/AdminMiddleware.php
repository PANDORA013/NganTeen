<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah user memiliki role admin
        if (Auth::user()->role !== 'admin') {
            // Log attempt untuk security
            \Log::warning('Unauthorized admin access attempt', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'user_role' => Auth::user()->role,
                'requested_url' => $request->url(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);

            // Redirect dengan pesan error
            abort(403, 'Akses ditolak. Anda tidak memiliki permission untuk mengakses halaman admin.');
        }

        return $next($request);
    }
}
