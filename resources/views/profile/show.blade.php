@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
        <div class="max-w-6xl mx-auto flex items-center gap-6">
            <div class="w-28 h-28 rounded-full overflow-hidden ring-4 ring-white shadow-lg bg-white flex items-center justify-center">
                <img id="profile-avatar" src="{{ $user->profile_picture_url }}" alt="{{ $user->name }} avatar" class="object-cover w-full h-full" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/avatar-placeholder.svg') }}'">
            </div>

            @if($user->profile_picture)
                @if(! ($pictureExists ?? true))
                    <div class="mt-3 p-3 rounded-md bg-red-50 border border-red-100 text-red-700 text-sm flex items-start gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94A2 2 0 0023 18L14.5 3.86a2 2 0 00-3.5 0zM12 9v4m0 4h.01" /></svg>
                        <div>
                            <div class="font-medium">Foto profil tersimpan tidak ditemukan</div>
                            <div class="text-xs">File <span class="font-mono">{{ $user->profile_picture }}</span> tidak ada di disk publik. Coba re-upload foto atau jalankan <code>php artisan storage:link</code>.</div>
                            <div class="mt-2"><a href="{{ route('profile.edit') }}" class="text-blue-600 underline">Unggah ulang foto</a></div>
                        </div>
                    </div>
                @endif
            @endif

            <div class="flex-1 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold">{{ $user->name }}</h1>
                        <div class="text-sm opacity-90">{{ $user->email }}</div>
                        <div class="mt-2 inline-flex items-center gap-2">
                            <span class="px-2 py-1 rounded-full bg-white/20 text-sm">{{ ucfirst($user->role) }}</span>
                            @if($user->company_name)
                                <span class="text-sm opacity-90">· {{ $user->company_name }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="text-right">
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 bg-white text-blue-600 hover:bg-white/90 px-4 py-2 rounded-md font-medium shadow">Edit Profile</a>
                    </div>
                </div>

                @if($user->bio)
                    <p class="mt-4 text-sm max-w-2xl opacity-90">{{ $user->bio }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="p-6 max-w-6xl mx-auto">
        <div class="bg-white p-6 rounded shadow-sm">
            <h2 class="text-lg font-semibold mb-3">About</h2>
            <div class="mt-2 text-gray-700">
                @if($user->bio)
                    <p class="mb-2">{{ $user->bio }}</p>
                @else
                    <p class="mb-2 text-gray-400">No bio provided.</p>
                @endif

                @if($user->company_name)
                    <p class="text-sm text-gray-500">Company: <span class="font-medium text-gray-800">{{ $user->company_name }}</span></p>
                @endif
            </div>
        </div>

        <div class="mt-4 flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Edit Profile</a>
            <a href="/dashboard" class="px-4 py-2 border rounded">Dashboard</a>
        </div>
    </div>
</div>
@endsection
