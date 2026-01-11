<?php

return [
    // Secret used to sign tokens. Set via .env JWT_SECRET
    'secret' => env('JWT_SECRET', env('APP_KEY')),

    // Access token TTL in minutes
    'ttl' => env('JWT_TTL', 60),

    // Refresh token TTL in days
    'refresh_ttl' => env('JWT_REFRESH_TTL', 30),

    // Issuer
    'issuer' => env('APP_URL', 'http://localhost'),
];
