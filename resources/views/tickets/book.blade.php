@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Event Information -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="md:flex">
            @if($event->poster)
                <div class="md:w-1/3">
                    <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->title }}" class="w-full h-64 md:h-full object-cover">
                </div>
            @endif
            <div class="p-6 {{ $event->poster ? 'md:w-2/3' : 'w-full' }}">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $event->title }}</h1>
                <div class="flex items-center text-gray-600 mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>
                        @if($event->event_date instanceof \Carbon\Carbon)
                            {{ $event->event_date->format('d F Y') }}
                        @else
                            {{ $event->event_date }}
                        @endif
                        - 
                        @if($event->event_time)
                            {{ is_string($event->event_time) ? $event->event_time : $event->event_time->format('H:i') }}
                        @endif
                    </span>
                </div>
                <div class="flex items-center text-gray-600 mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ $event->location }}</span>
                </div>
                <p class="text-gray-700">{{ Str::limit($event->description, 200) }}</p>
            </div>
        </div>
    </div>

    <!-- Ticket Selection -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Pilih Tiket</h2>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if($tickets->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                <p class="mt-4 text-gray-500">Tidak ada tiket yang tersedia untuk event ini.</p>
            </div>
        @else
            <form method="POST" action="{{ route('tickets.process-booking', $event) }}">
                @csrf

                <div class="space-y-4">
                    @foreach($tickets as $ticket)
                        <div class="border border-gray-200 rounded-lg p-6 hover:border-blue-500 transition-colors {{ $ticket->isAvailable() ? '' : 'opacity-50' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <input 
                                            type="radio" 
                                            name="ticket_id" 
                                            value="{{ $ticket->id }}" 
                                            id="ticket_{{ $ticket->id }}"
                                            class="w-4 h-4 text-blue-600"
                                            {{ $ticket->isAvailable() ? '' : 'disabled' }}
                                            required
                                        >
                                        <label for="ticket_{{ $ticket->id }}" class="text-xl font-semibold text-gray-900 cursor-pointer">
                                            {{ $ticket->name }}
                                        </label>
                                        @if(!$ticket->isAvailable())
                                            <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                                                Tidak Tersedia
                                            </span>
                                        @elseif($ticket->available < 10)
                                            <span class="px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">
                                                Hanya {{ $ticket->available }} tersisa
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($ticket->description)
                                        <p class="text-gray-600 mb-3">{{ $ticket->description }}</p>
                                    @endif

                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span>Kuota: {{ $ticket->quota }}</span>
                                        <span>Terjual: {{ $ticket->sold }}</span>
                                        <span>Tersedia: {{ $ticket->available }}</span>
                                    </div>

                                    @if($ticket->sale_start || $ticket->sale_end)
                                        <div class="mt-2 text-sm text-gray-500">
                                            @if($ticket->sale_start)
                                                <span>Mulai: {{ $ticket->sale_start->format('d M Y H:i') }}</span>
                                            @endif
                                            @if($ticket->sale_end)
                                                <span class="ml-3">Berakhir: {{ $ticket->sale_end->format('d M Y H:i') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="text-right ml-6">
                                    <div class="text-2xl font-bold text-blue-600">
                                        Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 border-t pt-6">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Tiket
                    </label>
                    <div class="flex items-center gap-4">
                        <input 
                            type="number" 
                            name="quantity" 
                            id="quantity" 
                            min="1" 
                            max="10" 
                            value="1"
                            class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                        <span class="text-sm text-gray-500">Maksimal 10 tiket per transaksi</span>
                    </div>
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex gap-4">
                    <button 
                        type="submit"
                        class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Pesan Tiket
                    </button>
                    <a 
                        href="{{ route('events.show', $event) }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition-colors"
                    >
                        Kembali
                    </a>
                </div>

                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>Catatan:</strong> Setelah memesan, Anda memiliki waktu 24 jam untuk menyelesaikan pembayaran. Biaya admin sebesar 5% akan ditambahkan ke total harga.
                    </p>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
