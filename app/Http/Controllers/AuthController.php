<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLoginForm()
    {
        return view('login'); // Cocok dengan file: resources/views/login.blade.php
    }

    // Fungsi untuk proses login
    public function actionlogin(Request $request)
    {
        // Validasi input login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required|in:penjual,pembeli',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');
        $inputRole = $request->input('role');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->role === $inputRole) {
                // Redirect berdasarkan role
                if ($inputRole === 'penjual') {
                    return redirect()->intended('/penjual/dashboard');
                } else {
                    return redirect()->intended('/pembeli/dashboard');
                }
            } else {
                Auth::logout();
                return back()->with('error', 'Role yang dipilih tidak sesuai dengan akun Anda.');
            }
        }

        return back()->with('error', 'Email atau kata sandi salah.');
    }

    // Fungsi logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // Cocok dengan route login
    }
}
