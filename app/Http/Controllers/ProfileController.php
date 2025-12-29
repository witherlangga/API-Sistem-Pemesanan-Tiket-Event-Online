<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        // check if stored profile picture actually exists on disk
        $pictureExists = $user->profile_picture && Storage::disk('public')->exists($user->profile_picture);

        // Prepare attributes for display (exclude sensitive fields)
        $sensitive = ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'];
        $raw = $user->getAttributes();
        $attributes = [];
        foreach ($raw as $k => $v) {
            if (in_array($k, $sensitive)) {
                continue;
            }

            // Format dates for readability
            if (in_array($k, ['created_at', 'updated_at', 'email_verified_at']) && $v) {
                $v = \Illuminate\Support\Carbon::parse($v)->toDayDateTimeString();
            }

            // For profile_picture show either public url or path (so user/developer can see it)
            if ($k === 'profile_picture') {
                if ($v && $pictureExists) {
                    $v = asset('storage/' . $v);
                } elseif ($v && ! $pictureExists) {
                    $v = "(file missing) " . $v;
                } else {
                    $v = null;
                }
            }

            $attributes[$k] = $v;
        }

        return view('profile.show', [
            'user' => $user,
            'pictureExists' => $pictureExists,
            'attributes' => $attributes,
        ]);
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        return view('profile.edit', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'bio' => 'nullable|string|max:2000',
            'address' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');

            // delete old picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $data['profile_picture'] = $path;
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
