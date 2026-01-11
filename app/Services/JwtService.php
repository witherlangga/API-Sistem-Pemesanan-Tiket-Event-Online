<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Carbon\Carbon;

class JwtService
{
    public static function generateAccessToken($user)
    {
        $now = Carbon::now()->timestamp;
        $ttl = config('jwt.ttl', 60) * 60; // minutes -> seconds
        $payload = [
            'iss' => config('jwt.issuer'),
            'iat' => $now,
            'exp' => $now + $ttl,
            'sub' => $user->id,
            'scopes' => [$user->role ?? 'user'],
        ];

        $secret = config('jwt.secret');
        return JWT::encode($payload, $secret, 'HS256');
    }

    public static function decodeAccessToken($token)
    {
        $secret = config('jwt.secret');
        return JWT::decode($token, new Key($secret, 'HS256'));
    }

    public static function generateRefreshToken()
    {
        // random string; will be stored hashed in DB
        return bin2hex(random_bytes(64));
    }

    public static function refreshTtlSeconds()
    {
        return config('jwt.refresh_ttl', 30) * 24 * 60 * 60;
    }
}
