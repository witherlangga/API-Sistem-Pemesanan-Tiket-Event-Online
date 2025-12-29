@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
  <div class="relative overflow-hidden rounded-lg bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-white">
    @if($events->isNotEmpty())
      @php $featured = $events->first(); @endphp
      <div class="md:flex md:items-center md:justify-between p-8 md:p-12">
        <div class="md:w-2/3">
          <h1 class="text-3xl md:text-4xl font-bold">{{ $featured->title }}</h1>
          <p class="mt-3 text-indigo-100 max-w-xl">{{ \Illuminate\Support\Str::limit($featured->description ?? 'Event terbaik untuk Anda', 180) }}</p>
          <div class="mt-6 flex gap-3">
            <a href="{{ url('/events/' . ($featured->id ?? '#')) }}" class="px-4 py-2 bg-white text-indigo-700 rounded font-medium">Lihat Event</a>
            <a href="#featured-list" class="px-4 py-2 bg-transparent border border-white/30 rounded text-white">Lihat Lainnya</a>
          </div>
        </div>
        <div class="hidden md:block md:w-1/3">
          <img src="{{ asset($featured->poster ?? 'images/hero-events.svg') }}" alt="Hero" class="w-full h-48 object-cover opacity-80 rounded">
        </div>
      </div>
    @else
      <div class="p-8 md:p-12">
        <h1 class="text-3xl md:text-4xl font-bold">Selamat datang, {{ $user->name }} 👋</h1>
        <p class="mt-3 text-indigo-100 max-w-xl">Belum ada event dipublikasikan saat ini. Coba kembali nanti.</p>
      </div>
    @endif
  </div>

  <div id="featured-list" class="mt-6">
    <h2 class="text-lg font-semibold mb-3">Featured Events</h2>
    <div class="relative">
      <button id="prevBtn" class="absolute left-0 top-1/2 -translate-y-1/2 bg-white/80 text-gray-700 p-2 rounded-full shadow z-10">‹</button>
      <div id="carousel" class="flex gap-4 overflow-x-auto px-8 py-4 snap-x snap-mandatory">
        @forelse($events as $event)
          <div class="min-w-[260px] bg-white border rounded-lg p-4 shadow-sm snap-start">
            <div class="h-36 bg-gray-100 rounded mb-3 flex items-center justify-center">
              <img src="{{ asset($event->poster ?? 'images/hero-events.svg') }}" alt="" class="h-full w-full object-cover rounded">
            </div>
            <h3 class="font-semibold">{{ $event->title }}</h3>
            <p class="text-sm text-gray-500 mt-1">{{ $event->event_date }} • {{ $event->location }}</p>
            <div class="mt-3">
              <a href="{{ url('/events/' . $event->id) }}" class="text-sm text-indigo-600 hover:underline">Lihat</a>
            </div>
          </div>
        @empty
          <div class="text-gray-500">Belum ada event untuk ditampilkan.</div>
        @endforelse
      </div>
      <button id="nextBtn" class="absolute right-0 top-1/2 -translate-y-1/2 bg-white/80 text-gray-700 p-2 rounded-full shadow z-10">›</button>
    </div>
  </div>
        <div class="p-4 border rounded">
            <h2 class="font-semibold mb-2">Upcoming Events</h2>

            {{-- Flash message --}}
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <table class="w-full border text-sm">
                <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border">Judul</th>
                    <th class="p-2 border">Lokasi</th>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border w-48">Aksi</th>
                </tr>
                </thead>
                <tbody>
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
                            {{-- Customers/guests can only view details --}}
                            <a href="#" class="text-sm text-blue-600 hover:underline">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center p-4 text-gray-500">Belum ada event</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border rounded">Recommendations: (placeholder)</div>
    </div>
</div>

<script>
  (function(){
    const carousel = document.getElementById('carousel');
    const prev = document.getElementById('prevBtn');
    const next = document.getElementById('nextBtn');
    if(!carousel) return;
    const scrollAmount = Math.max(carousel.clientWidth * 0.7, 260);
    prev.addEventListener('click', ()=> { carousel.scrollBy({left: -scrollAmount, behavior: 'smooth'}); });
    next.addEventListener('click', ()=> { carousel.scrollBy({left: scrollAmount, behavior: 'smooth'}); });
  })();
</script>
@endsection
