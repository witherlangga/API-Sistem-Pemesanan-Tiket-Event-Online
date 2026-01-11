<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RefreshToken;
use App\Services\JwtService;
use App\Models\User;

class TokenController extends Controller
{
    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string'
        ]);

        $rt = RefreshToken::findByRawToken($request->input('refresh_token'));
        if (!$rt || !$rt->isValid()) {
            return response()->json(['success' => false, 'message' => 'Invalid refresh token'], 401);
        }

        $user = User::find($rt->user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 401);
        }

        // Rotate refresh token: revoke old, create new
        $rt->revoke();
        $newRefreshRaw = JwtService::generateRefreshToken();
        RefreshToken::createForUser($user, $newRefreshRaw, $request);

        $accessToken = JwtService::generateAccessToken($user);

        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
                'expires_in' => config('jwt.ttl') * 60,
                'refresh_token' => $newRefreshRaw,
            ],
        ]);
    }
}
