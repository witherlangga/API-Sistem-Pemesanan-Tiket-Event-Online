<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' untuk password_confirmation
            'role' => 'nullable|in:organizer,customer',
            'company_name' => 'nullable|required_if:role,organizer|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'data' => $validator->errors(),
            ], 422);
        }

        try {
            // Tentukan role berdasarkan input, default 'customer'
            $role = $request->input('role', 'customer');

            // Buat user baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $role,
                'company_name' => $request->input('company_name'),
            ]);

            try {
                \App\Models\ActivityLog::record($user->id, 'auth:register', $user, [], $request);
            } catch (\Exception $e) {
            }

            $data = new \App\Http\Resources\UserResource($user);

            // Also issue JWT tokens upon registration
            $accessToken = \App\Services\JwtService::generateAccessToken($user);
            $refreshTokenRaw = \App\Services\JwtService::generateRefreshToken();
            \App\Models\RefreshToken::createForUser($user, $refreshTokenRaw, $request);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil.',
                'data' => [
                    'user' => $data,
                    'access_token' => $accessToken,
                    'token_type' => 'Bearer',
                    'expires_in' => config('jwt.ttl') * 60,
                    'refresh_token' => $refreshTokenRaw,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registrasi gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'data' => $validator->errors(),
            ], 422);
        }

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Verifikasi password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // Generate JWT access token and refresh token
        $accessToken = \App\Services\JwtService::generateAccessToken($user);
        $refreshTokenRaw = \App\Services\JwtService::generateRefreshToken();
        \App\Models\RefreshToken::createForUser($user, $refreshTokenRaw, $request);

        // Update last_activity and record login
        try {
            $user->last_activity = time();
            $user->save();
            ActivityLog::record($user->id, 'auth:login', null, [], $request);
        } catch (\Exception $e) {
            // ignore logging failures
        }

        $data = new \App\Http\Resources\UserResource($user);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'user' => $data,
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
                'expires_in' => config('jwt.ttl') * 60,
                'refresh_token' => $refreshTokenRaw,
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            try {
                ActivityLog::record($user->id, 'auth:logout', null, [], $request);
            } catch (\Exception $e) {
            }
        }

        // revoke refresh token if provided
        $refresh = $request->input('refresh_token');
        if ($refresh) {
            $rt = \App\Models\RefreshToken::findByRawToken($refresh);
            if ($rt) {
                $rt->revoke();
            }
        }

        // also attempt to delete sanctum token if present
        if ($request->user() && method_exists($request->user(), 'currentAccessToken')) {
            $request->user()->currentAccessToken()?->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ], 200);
    }
}
