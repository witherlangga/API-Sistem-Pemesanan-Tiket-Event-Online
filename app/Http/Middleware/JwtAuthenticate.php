<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\JwtService;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class JwtAuthenticate
{
    public function handle($request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized (missing token)'], 401);
        }

        $token = substr($authHeader, 7);

        try {
            $payload = JwtService::decodeAccessToken($token);
            $userId = $payload->sub ?? null;
            if (!$userId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized (invalid token)'], 401);
            }

            $user = User::find($userId);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized (user not found)'], 401);
            }

            // Authenticate for this request
            Auth::setUser($user);

            return $next($request);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Unauthorized (token error)'], 401);
        }
    }
}
