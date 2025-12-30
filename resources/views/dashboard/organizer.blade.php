@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8">
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

    <!-- Events Table -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-xl font-bold text-gray-900">Event Anda</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Judul Event</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($events as $event)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border font-medium">{{ $event->title }}</td>
                        <td class="p-2 border">{{ $event->location }}</td>
                        <td class="p-2 border">{{ $event->event_date }}</td>
                        <td class="p-2 border">
                            @if ($event->status === 'draft')
                                <span class="px-2 py-1 bg-gray-300 rounded text-xs">Draft</span>
                            @elseif ($event->status === 'published')
                                <span class="px-2 py-1 bg-green-500 text-white rounded text-xs">Published</span>
                            @else
                                <span class="px-2 py-1 bg-red-500 text-white rounded text-xs">Cancelled</span>
                            @endif
                        </td>
                        <td class="p-2 border">
                            <div class="flex flex-wrap gap-2 items-center">
                                {{-- Kelola Tiket - TOMBOL BARU --}}
                                <a href="/events/{{ $event->id }}/tickets" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-semibold">
                                    🎫 Kelola Tiket
                                </a>

                                {{-- Lihat Transaksi - TOMBOL BARU --}}
                                <a href="/events/{{ $event->id }}/transactions" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-xs">
                                    💰 Transaksi
                                </a>

                                {{-- Edit (hanya draft) --}}
                                @if ($event->status === 'draft')
                                    <a href="/events/{{ $event->id }}/edit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">Edit</a>
                                @endif

                                {{-- Lihat Detail --}}
                                <a href="{{ url('/events/' . $event->id) }}" class="text-sm text-blue-600 hover:underline">Lihat</a>

                                {{-- Publish --}}
                                @if ($event->status === 'draft')
                                    <form action="/events/{{ $event->id }}/publish" method="POST" class="inline">
                                        @csrf
                                        <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">Terbitkan</button>
                                    </form>
                                @endif

                                {{-- Cancel --}}
                                @if ($event->status === 'published')
                                    <form action="/events/{{ $event->id }}/cancel" method="POST" class="inline">
                                        @csrf
                                        <button class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-xs">Batalkan</button>
                                    </form>
                                @endif

                                {{-- Delete --}}
                                <form action="/events/{{ $event->id }}" method="POST" onsubmit="return confirm('Yakin hapus event ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12">
                            <div class="text-center">
                                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada event</h3>
                                <p class="mt-2 text-sm text-gray-500">Mulai dengan membuat event pertama Anda!</p>
                                <div class="mt-6">
                                    <a href="/events/create" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg transition-all">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Buat Event Pertama
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
