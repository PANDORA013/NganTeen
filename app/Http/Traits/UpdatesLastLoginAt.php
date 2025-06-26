<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Auth;

trait UpdatesLastLoginAt
{
    /**
     * Update the last login timestamp for the authenticated user.
     *
     * @return void
     */
    protected function updateLastLoginTimestamp(): void
    {
        if ($user = Auth::user()) {
            $user->update(['last_login_at' => now()]);
        }
    }
}
