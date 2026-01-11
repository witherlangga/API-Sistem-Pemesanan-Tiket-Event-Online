<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RefreshToken extends Model
{
    protected $fillable = ['user_id', 'token_hash', 'expires_at', 'revoked', 'ip_address', 'user_agent'];

    protected $casts = [
        'revoked' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public static function createForUser($user, $rawToken, $request = null)
    {
        return static::create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $rawToken),
            'expires_at' => Carbon::now()->addSeconds(\App\Services\JwtService::refreshTtlSeconds()),
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }

    public static function findByRawToken($rawToken)
    {
        return static::where('token_hash', hash('sha256', $rawToken))->first();
    }

    public function revoke()
    {
        $this->revoked = true;
        $this->save();
    }

    public function isValid()
    {
        return !$this->revoked && ($this->expires_at === null || $this->expires_at->isFuture());
    }
}
