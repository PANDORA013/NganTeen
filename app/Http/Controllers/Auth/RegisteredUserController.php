<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:penjual,pembeli'],
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'registration_date' => now(),
                'last_activity' => now(),
                'last_login_at' => now(),
            ]);

            event(new Registered($user));

            Auth::login($user);
            
            Log::info('User berhasil registrasi', ['user_id' => $user->id]);

            // Redirect based on role to the proper named route
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->role === 'penjual') {
                return redirect()->intended(route('penjual.dashboard'));
            } else {
                return redirect()->intended(route('pembeli.dashboard'));
            }
        } catch (\Exception $e) {
            Log::error('Error saat registrasi: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'input' => $request->except('password')
            ]);
            
            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat melakukan registrasi. Silakan coba lagi.'
            ])->withInput();
        }
    }
}
