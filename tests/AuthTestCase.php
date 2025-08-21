<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class AuthTestCase extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Call the given URI with a POST request, disabling CSRF.
     */
    public function postWithoutCsrf($uri, array $data = [], array $headers = [])
    {
        return $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
                    ->post($uri, $data, $headers);
    }
}
