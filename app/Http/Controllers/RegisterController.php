<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    protected array $validRoles = ['penjual', 'pembeli'];

    public function register()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('register');
    }

    public function actionregister(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
            'email' => ['required', 'string', 'email:rfc,dns', 'unique:users', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->uncompromised()
            ],
            'role' => ['required', 'string', 'in:' . implode(',', $this->validRoles)],
        ]);

        try {
            $user = User::create([
                'name' => trim($validated['name']),
                'email' => strtolower($validated['email']),
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.']);
        }
    }

    protected function redirectBasedOnRole($user)
    {
        return match ($user->role) {
            'penjual' => redirect()->route('penjual.home'),
            'pembeli' => redirect()->route('pembeli.home'),
            default => redirect()->route('login')
        };
    }
}
