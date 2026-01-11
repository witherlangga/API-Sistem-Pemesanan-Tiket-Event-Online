@extends('layouts.app')

@section('content')
<div class="w-full">
  <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-white">
    @if($events->isNotEmpty())
      @php $featured = $events->first(); @endphp
      <div class="md:flex md:items-center md:justify-between p-8 md:p-12">
        <div class="md:w-2/3">
          <h1 class="text-3xl md:text-4xl font-bold">{{ $featured->title }}</h1>
          <p class="mt-3 text-indigo-100 max-w-xl">{{ \Illuminate\Support\Str::limit($featured->description ?? 'Event terbaik untuk Anda', 180) }}</p>
          <div class="mt-6 flex gap-3">
            <a href="{{ url('/events/' . ($featured->id ?? '#')) }}" class="px-4 py-2 border border-white/30 text-white rounded">Lihat Event</a>
            <a href="#featured-list" class="bg-transparent text-white border-white/30 px-4 py-2 rounded">Lihat Lainnya</a>
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
          <div class="min-w-[260px] bg-white border rounded-lg p-4 shadow-sm snap-start flex flex-col min-h-[320px]">
            <div class="h-36 bg-gray-100 rounded mb-3 flex items-center justify-center overflow-hidden">
              <img src="{{ asset($event->poster ?? 'images/hero-events.svg') }}" alt="" class="h-full w-full object-cover rounded">
            </div>
            <h3 class="font-semibold">{{ $event->title }}</h3>
            <div class="text-sm text-gray-500 mt-1 mb-3 text-left">{{ $event->event_date }} • {{ $event->location }}</div>
            <div class="mt-auto flex justify-end">
              <a href="{{ url('/events/' . $event->id) }}" class="px-3 py-1 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">Lihat</a>
            </div>
          </div>
        @empty
          <div class="text-gray-500">Belum ada event untuk ditampilkan.</div>
        @endforelse
      </div>
      <button id="nextBtn" class="absolute right-0 top-1/2 -translate-y-1/2 bg-white/80 text-gray-700 p-2 rounded-full shadow z-10">›</button>
    </div>
  </div>
        <div class="mt-6 w-full">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($events as $event)
                    <div class="bg-white rounded-lg shadow p-4 flex flex-col">
                        <div class="h-44 bg-gray-100 rounded overflow-hidden mb-3">
                            <img src="{{ asset($event->poster ?? 'images/hero-events.svg') }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $event->event_date }} • {{ $event->location }}</p>
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
                                <a href="{{ url('/events/' . $event->id) }}" class="px-3 py-1 bg-indigo-600 text-white rounded text-sm">Lihat</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-500">Belum ada event untuk ditampilkan.</div>
                @endforelse
            </div>
        </div>
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
