@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded overflow-hidden">
  <div class="md:flex">
    <div class="md:w-2/3">
      <div class="relative">
        <img src="{{ asset($event->poster ?? 'images/hero-events.svg') }}" alt="{{ $event->title }} poster" class="w-full h-80 md:h-96 object-cover">
        <div class="absolute left-6 bottom-6 bg-black/50 backdrop-blur-sm text-white p-4 rounded">
          <h1 class="text-2xl md:text-4xl font-bold">{{ $event->title }}</h1>
          <p class="mt-1 text-sm text-gray-200">{{ $event->category }} • {{ $event->location }} • {{ $event->event_date }} {{ $event->event_time }}</p>
        </div>
      </div>

      <div class="p-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gray-100 overflow-hidden">
              <img src="{{ $event->user->profile_picture_url }}" alt="Organizer" class="w-full h-full object-cover">
            </div>
            <div>
              <div class="text-sm text-gray-500">Organizer</div>
              <div class="font-semibold">{{ $event->user->name }}</div>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <div class="text-sm text-gray-500 text-right">
              <div>Capacity</div>
              <div class="font-semibold">{{ $event->capacity }}</div>
            </div>

            @auth
              <a href="{{ route('tickets.book', $event) }}" class="px-4 py-2 bg-[#F53003] text-white rounded shadow hover:opacity-95">
                Beli Tiket
              </a>
            @else
              <a href="{{ route('login') }}" class="px-4 py-2 bg-[#F53003] text-white rounded shadow hover:opacity-95">
                Login untuk Beli Tiket
              </a>
            @endauth
          </div>
        </div>

        <div class="mt-6">
          <h3 class="text-lg font-semibold">Tentang Event</h3>
          <div class="mt-2 text-gray-700 leading-relaxed">{!! nl2br(e($event->description ?? 'Deskripsi belum tersedia.')) !!}</div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="p-4 border rounded">
            <h4 class="text-sm text-gray-500">Lokasi</h4>
            <div class="font-medium">{{ $event->location }}</div>
            <div class="text-sm text-gray-500 mt-1">{{ $event->address }}</div>
          </div>
          <div class="p-4 border rounded">
            <h4 class="text-sm text-gray-500">Tanggal & Waktu</h4>
            <div class="font-medium">{{ $event->event_date }} • {{ $event->event_time }}</div>
          </div>
          <div class="p-4 border rounded">
            <h4 class="text-sm text-gray-500">Status</h4>
            <div class="font-medium">{{ $event->status }}</div>
            @if($event->isPublished())
              <div class="text-xs text-gray-400">Dipublikasikan {{ optional($event->published_at)->format('d M Y') ?? '' }}</div>
            @endif
          </div>
        </div>

      </div>
    </div>

    <aside class="md:w-1/3 bg-gray-50 p-6">
      <div class="sticky top-6 space-y-4">
        <!-- Ticket Listing -->
        @php
          $tickets = $event->tickets()->where('is_active', true)->get();
        @endphp
        
        @if($tickets->isNotEmpty())
          <div class="rounded border p-4 bg-white">
            <div class="text-sm text-gray-500 mb-3">Tiket Tersedia</div>
            <div class="space-y-3">
              @foreach($tickets->take(3) as $ticket)
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <div class="font-semibold text-sm">{{ $ticket->name }}</div>
                    <div class="text-xs text-gray-500">Rp {{ number_format($ticket->price, 0, ',', '.') }}</div>
                    <div class="text-xs text-gray-400">{{ $ticket->available }} tersedia</div>
                  </div>
                </div>
              @endforeach
            </div>
            @auth
              <a href="{{ route('tickets.book', $event) }}" class="mt-4 block w-full text-center px-3 py-2 bg-[#F53003] text-white rounded text-sm hover:opacity-90">
                Pesan Sekarang
              </a>
            @else
              <a href="{{ route('login') }}" class="mt-4 block w-full text-center px-3 py-2 bg-gray-600 text-white rounded text-sm hover:opacity-90">
                Login untuk Pesan
              </a>
            @endauth
          </div>
        @else
          <div class="rounded border p-4 bg-white text-center text-sm text-gray-500">
            Tiket belum tersedia
          </div>
        @endif

        <!-- Additional Info -->
        <div class="rounded border p-4 bg-white">
          <div class="text-sm text-gray-500">Informasi Tambahan</div>
          <ul class="mt-2 text-sm text-gray-600 space-y-1">
            <li><strong>Kategori:</strong> {{ $event->category }}</li>
            <li><strong>Kapasitas:</strong> {{ $event->capacity }}</li>
            <li><strong>Organizer:</strong> {{ $event->user->name }}</li>
          </ul>
        </div>

        <!-- Organizer Actions -->
        @auth
          @if(auth()->id() === $event->user_id)
            <div class="rounded border p-4 bg-white">
              <div class="text-sm text-gray-500 mb-3">Kelola Event</div>
              <div class="space-y-2">
                <a href="{{ route('tickets.index', $event) }}" class="block w-full text-center px-3 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                  Kelola Tiket
                </a>
                <a href="{{ route('events.transactions', $event) }}" class="block w-full text-center px-3 py-2 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                  Lihat Transaksi
                </a>
                <a href="{{ route('events.edit', $event) }}" class="block w-full text-center px-3 py-2 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">
                  Edit Event
                </a>
              </div>
            </div>
          @endif
        @endauth
      </div>
    </aside>
  </div>
</div>

@endsection