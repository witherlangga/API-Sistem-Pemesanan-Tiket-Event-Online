@extends('layouts.app')

@section('content')
<div class="w-full py-8 px-6 lg:px-12">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Dashboard Organizer</h1>
                <p class="text-gray-600 text-lg">Selamat datang, <span class="font-semibold text-blue-600">{{ $user->name }}</span> 👋</p>
            </div>
            <a href="/events/create" class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Event
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Event</p>
                    <p class="text-4xl font-bold">{{ $events->count() }}</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Event Published</p>
                    <p class="text-4xl font-bold">{{ $events->where('status', 'published')->count() }}</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Event Draft</p>
                    <p class="text-4xl font-bold">{{ $events->where('status', 'draft')->count() }}</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash message --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg shadow">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg shadow">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Events Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($events as $event)
            <div class="bg-white rounded-2xl shadow p-4 flex flex-col">
                <div class="h-40 overflow-hidden rounded mb-3">
                    <img src="{{ asset($event->poster ?? 'images/hero-events.svg') }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold">{{ $event->title }}</h3>
                    <p class="text-sm text-gray-500">{{ $event->event_date }} • {{ $event->location }}</p>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <div>
                        @if ($event->status === 'draft')
                            <span class="px-2 py-1 bg-gray-200 rounded text-xs">Draft</span>
                        @elseif ($event->status === 'published')
                            <span class="px-2 py-1 bg-green-500 text-white rounded text-xs">Published</span>
                        @else
                            <span class="px-2 py-1 bg-red-500 text-white rounded text-xs">Cancelled</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="/events/{{ $event->id }}/tickets" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">🎫 Kelola Tiket</a>
                        <a href="/events/{{ $event->id }}/transactions" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm">💰 Transaksi</a>
                    </div>
                </div>

                <div class="mt-3 flex gap-2">
                    @if ($event->status === 'draft')
                        <a href="/events/{{ $event->id }}/edit" class="flex-1 inline-flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded">Edit</a>
                        <form action="/events/{{ $event->id }}/publish" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded">Terbitkan</button>
                        </form>
                    @elseif ($event->status === 'published')
                        <form action="/events/{{ $event->id }}/cancel" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded">Batalkan</button>
                        </form>
                    @endif

                    <form action="/events/{{ $event->id }}" method="POST" onsubmit="return confirm('Yakin hapus event ini?')" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center p-6 bg-white rounded-2xl">
                <h3 class="text-lg font-medium">Belum ada event</h3>
                <p class="text-sm text-gray-500">Mulai dengan membuat event pertama Anda!</p>
                <div class="mt-4">
                    <a href="/events/create" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold">Buat Event Pertama</a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
