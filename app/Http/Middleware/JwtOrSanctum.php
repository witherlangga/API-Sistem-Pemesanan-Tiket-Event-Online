<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class JwtOrSanctum
{
    public function handle($request, Closure $next)
    {
        // If already authenticated (e.g., Sanctum session), continue
        if ($request->user()) {
            return $next($request);
        }

        // Try to detect the Authorization header
        $authHeader = $request->header('Authorization');
        $token = null;
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);
        }

        // If token seems like a JWT (has dots), use JwtAuthenticate
        if ($token && str_contains($token, '.')) {
            $jwt = new JwtAuthenticate();
            return $jwt->handle($request, $next);
        }

        // Otherwise attempt to resolve a Sanctum personal access token
        if ($token) {
            try {
                $pat = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
                if ($pat && $pat->tokenable) {
                    \Illuminate\Support\Facades\Auth::setUser($pat->tokenable);
                    return $next($request);
                }
            } catch (\Exception $e) {
                // ignore and fallthrough to JWT try/fail
            }
        }

        // Fallback: try JWT authenticate (will return 401 if invalid)
        $jwt = new JwtAuthenticate();
        return $jwt->handle($request, $next);
    }
}
