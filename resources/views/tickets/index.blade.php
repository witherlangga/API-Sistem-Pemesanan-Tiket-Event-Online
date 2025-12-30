@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Tiket: {{ $event->title }}</h1>
            <p class="text-gray-600 mt-2">Atur jenis tiket dan kuota untuk event Anda</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tickets.create', $event) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                Tambah Tiket
            </a>
            <a href="{{ route('events.show', $event) }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-700">
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if($tickets->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada tiket</h3>
            <p class="mt-2 text-gray-500">Mulai tambahkan jenis tiket untuk event Anda</p>
            <a href="{{ route('tickets.create', $event) }}" class="mt-6 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                Tambah Tiket Pertama
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tiket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terjual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tersedia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tickets as $ticket)
                        <tr class="hover:bg-gray-50 {{ $ticket->trashed() ? 'opacity-50' : '' }}">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $ticket->name }}</div>
                                @if($ticket->description)
                                    <div class="text-xs text-gray-500">{{ Str::limit($ticket->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                Rp {{ number_format($ticket->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $ticket->quota }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $ticket->sold }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold 
                                    {{ $ticket->available > 10 ? 'text-green-600' : ($ticket->available > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $ticket->available }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ticket->trashed())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Dihapus
                                    </span>
                                @elseif($ticket->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @unless($ticket->trashed())
                                    <div class="flex gap-2">
                                        <a href="{{ route('tickets.edit', [$event, $ticket]) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('tickets.destroy', [$event, $ticket]) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus tiket ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Total Tiket</p>
                <p class="text-2xl font-bold text-gray-900">{{ $tickets->sum('quota') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Tiket Terjual</p>
                <p class="text-2xl font-bold text-green-600">{{ $tickets->sum('sold') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Tiket Tersedia</p>
                <p class="text-2xl font-bold text-blue-600">{{ $tickets->sum('quota') - $tickets->sum('sold') }}</p>
            </div>
        </div>
    @endif
</div>
@endsection
