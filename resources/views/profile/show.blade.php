@extends('layouts.app')

@section('content')
<div class="w-full px-6 lg:px-12">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-8 bg-white">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                <div class="flex flex-col items-center md:items-start">
                    <div class="w-36 h-36 rounded-full overflow-hidden border-2 border-gray-100 shadow-sm bg-white flex items-center justify-center">
                        <img id="profile-avatar" src="{{ $user->profile_picture_url }}" alt="{{ $user->name }} avatar" class="object-cover w-full h-full" loading="lazy" data-placeholder-src="{{ asset('images/avatar-placeholder.svg') }}" onerror="this.onerror=null;this.src=this.getAttribute('data-placeholder-src');">
                    </div>

                    <div class="mt-4 text-center md:text-left">
                        <h1 class="text-2xl md:text-3xl font-semibold text-gray-900">{{ $user->name }}</h1>
                        <div class="text-sm text-gray-600">{{ $user->email }}</div>
                        <div class="mt-3 inline-flex items-center gap-2">
                            <span class="px-2 py-1 rounded-full bg-gray-100 text-sm text-gray-700">{{ ucfirst($user->role) }}</span>
                            @if($user->company_name)
                                <span class="text-sm text-gray-600">· {{ $user->company_name }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('profile.edit') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md font-medium shadow-sm">Edit Profile</a>
                        <a href="/dashboard" class="px-4 py-2 text-gray-600 rounded-md">Dashboard</a>
                    </div>

                    @if($user->profile_picture)
                        @if(! ($pictureExists ?? true))
                            <div class="mt-4 p-3 rounded-md bg-red-50 border border-red-100 text-red-700 text-sm flex items-start gap-2">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94A2 2 0 0023 18L14.5 3.86a2 2 0 00-3.5 0zM12 9v4m0 4h.01" /></svg>
                                <div>
                                    <div class="font-medium">Foto profil tersimpan tidak ditemukan</div>
                                    <div class="text-xs">File <span class="font-mono">{{ $user->profile_picture }}</span> tidak ada di disk publik. Coba re-upload foto atau jalankan <code>php artisan storage:link</code>.</div>
                                    <div class="mt-2"><a href="{{ route('profile.edit') }}" class="text-gray-700 underline">Unggah ulang foto</a></div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="md:col-span-2">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded shadow-sm">
                            <h2 class="text-lg font-semibold mb-3">About</h2>

                            @if($user->bio)
                                <p class="text-gray-700">{{ $user->bio }}</p>
                            @else
                                <p class="text-gray-500">Belum ada bio. Tambahkan sedikit cerita tentang diri Anda.</p>
                            @endif

                            <div class="mt-4 text-sm text-gray-600 space-y-2">
                                <div><strong class="text-gray-800">Joined:</strong> {{ $user->created_at ? $user->created_at->toFormattedDateString() : '-' }}</div>
                                <div><strong class="text-gray-800">Email:</strong> {{ $user->email }}</div>
                                @if($user->phone)
                                    <div><strong class="text-gray-800">Phone:</strong> {{ $user->phone }}</div>
                                @endif
                                @if($user->website)
                                    <div><strong class="text-gray-800">Website:</strong> <a href="{{ $user->website }}" class="text-indigo-600 hover:underline">{{ $user->website }}</a></div>
                                @endif
                                @if($user->address)
                                    <div><strong class="text-gray-800">Address:</strong> {{ $user->address }}</div>
                                @endif
                            </div>
                        </div>


                    </div>

            </div>
        </div>
    </div>

    <section class="w-full py-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-lg font-semibold mb-4">Events yang sudah Anda ikuti</h2>

            @if(isset($joinedEvents) && $joinedEvents->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
                    @foreach($joinedEvents as $jevent)
                        @if($jevent)
                            <a href="{{ url('/events/' . $jevent->id) }}" class="bg-white rounded border border-gray-100 p-4 flex gap-4 items-center hover:shadow-sm transition">
                                <img src="{{ asset($jevent->poster ?? 'images/hero-events.svg') }}" class="h-28 w-44 object-cover rounded" alt="">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $jevent->title }}</div>
                                    <div class="text-sm text-gray-500 mt-1">{{ $jevent->event_date }}</div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">Belum ada event yang Anda ikuti.</p>
            @endif
        </div>
    </section>
</div>
@endsection
