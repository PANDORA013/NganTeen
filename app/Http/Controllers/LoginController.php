<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected array $validRoles = ['penjual', 'pembeli'];

    public function login()
    {
        if (Auth::check()) {
            return $this->redirectTo();
        }
        return view('login');
    }

    public function actionlogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email:rfc,dns'],
            'password' => ['required', 'string']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (!in_array($user->role, $this->validRoles)) {
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['role' => 'Role tidak dikenali.']);
            }

            return $this->redirectTo();
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    protected function redirectTo()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        return match($user->role) {
            'penjual' => redirect()->route('penjual.home'),
            'pembeli' => redirect()->route('pembeli.home'),
            default => redirect()->route('login')
        };
    }

    public function actionlogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('status', 'Berhasil logout.');
    }
}