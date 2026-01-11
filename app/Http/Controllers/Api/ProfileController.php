<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\ApiResponse;

class ProfileController extends Controller
{
    use ApiResponse;
    /**
     * Get the authenticated user's profile.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $data = new \App\Http\Resources\UserResource($user);
        return $this->success('Profil berhasil diambil.', ['user' => $data]);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required_with:password|string',
            'password' => 'sometimes|required|string|min:8|confirmed',
            'profile_picture' => 'sometimes|nullable|image|max:2048', // max 2MB
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            // Jika ada password baru, verifikasi current password
            if ($request->has('password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password saat ini salah.',
                    ], 422);
                }
                $user->password = Hash::make($request->password);
            }

            // Update data lainnya
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            if ($request->has('email')) {
                $user->email = $request->email;
            }

            // Handle profile picture if uploaded
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $path = $file->store('profile_pictures', 'public');

                // Hapus yang lama jika ada
                if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                    Storage::disk('public')->delete($user->profile_picture);
                }

                $user->profile_picture = $path;
            }

            $user->save();

            try {
                \App\Models\ActivityLog::record($user->id, 'profile:update', $user, $request->only(['name','email']), $request);
            } catch (\Exception $e) {
            }

            return $this->success('Profil berhasil diperbarui.', [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'profile_picture' => $user->profile_picture,
                    'profile_picture_url' => $user->profile_picture_url,
                ],
            ]);
        } catch (\Exception $e) {
            return $this->error('Pembaruan profil gagal: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Delete (deactivate) the authenticated user's account.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        try {
            // Hapus file profile picture jika ada
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Hapus token access untuk keamanan
            if (method_exists($user, 'tokens')) {
                $user->tokens()->delete();
            }

            $user->delete();

            try {
                \App\Models\ActivityLog::record($user->id, 'profile:delete', $user, [], $request);
            } catch (\Exception $e) {
            }

            return $this->success('Akun berhasil dihapus.');
        } catch (\Exception $e) {
            return $this->error('Gagal menghapus akun: ' . $e->getMessage(), null, 500);
        }
    }
}
