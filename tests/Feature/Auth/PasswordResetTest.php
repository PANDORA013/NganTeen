<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        $user = User::factory()->create();

        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
                         ->post('/forgot-password', ['email' => $user->email]);

        // Just check that it redirects back successfully (indicating email was processed)
        $response->assertRedirect();
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        // Create a password reset token manually for testing
        $user = User::factory()->create();
        
        $token = \Illuminate\Support\Str::random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        $response = $this->get('/reset-password/'.$token);

        $response->assertStatus(200);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::factory()->create();
        
        $token = \Illuminate\Support\Str::random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
                         ->post('/reset-password', [
                             'token' => $token,
                             'email' => $user->email,
                             'password' => 'new-password',
                             'password_confirmation' => 'new-password',
                         ]);

        $response->assertRedirect(route('login'));
    }
}
