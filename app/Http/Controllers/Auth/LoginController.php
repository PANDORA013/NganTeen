<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | Controller ini menangani autentikasi pengguna untuk aplikasi dan
    | mengarahkan mereka ke halaman beranda. Controller menggunakan trait
    | untuk menyediakan fungsionalitasnya ke aplikasi Anda.
    |
    */

    use AuthenticatesUsers;

    /**
     * Membuat instance controller baru.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Menentukan redirect pengguna setelah login berdasarkan role.
     * 
     * @return string
     */
    protected function redirectTo(): string
    {
        if (Auth::user()->role === 'penjual') {
            return '/penjual/dashboard';
        }
        return '/home';
    }
}
