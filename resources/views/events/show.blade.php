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

            <button id="buyBtn" class="px-4 py-2 bg-[#F53003] text-white rounded shadow hover:opacity-95">Beli Tiket</button>
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
        <div class="rounded border p-4 bg-white">
          <div class="text-sm text-gray-500">Tickets</div>
          <div class="mt-2 flex items-center justify-between">
            <div>
              <div class="font-semibold">General</div>
              <div class="text-sm text-gray-500">Free / Placeholder</div>
            </div>
            <button id="buyBtnSide" class="px-3 py-1 bg-[#F53003] text-white rounded text-sm">Beli</button>
          </div>
        </div>

        <div class="rounded border p-4 bg-white">
          <div class="text-sm text-gray-500">Additional</div>
          <ul class="mt-2 text-sm text-gray-600 space-y-1">
            <li><strong>Category:</strong> {{ $event->category }}</li>
            <li><strong>Capacity:</strong> {{ $event->capacity }}</li>
            <li><strong>Organizer:</strong> {{ $event->user->name }}</li>
          </ul>
        </div>

        <div class="rounded border p-4 bg-white text-sm text-gray-600">
          <h4 class="font-semibold mb-2">Perhatian</h4>
          <p>Fitur pembelian tiket belum diimplementasikan. Tombol hanya menunjukkan UI untuk flow pembelian.</p>
        </div>
      </div>
    </aside>
  </div>
</div>

<!-- Modal -->
<div id="buyModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
  <div class="bg-white rounded w-[90%] md:w-1/2 p-6">
    <div class="flex justify-between items-center">
      <h3 class="text-lg font-semibold">Beli Tiket — {{ $event->title }}</h3>
      <button id="closeModal" class="text-gray-500">✕</button>
    </div>

    <div class="mt-4">
      <p class="text-gray-700">Ini hanya demo UI. Integrasi pembayaran dan pembuatan tiket belum diimplementasikan.</p>

      <div class="mt-6 flex justify-end gap-2">
        <button id="closeModal2" class="px-4 py-2 bg-gray-200 rounded">Tutup</button>
        <button id="proceedBtn" class="px-4 py-2 bg-[#F53003] text-white rounded">Lanjut (demo)</button>
      </div>
    </div>
  </div>
</div>

<script>
  (function(){
    const buy = document.getElementById('buyBtn');
    const buySide = document.getElementById('buyBtnSide');
    const modal = document.getElementById('buyModal');
    const close = document.getElementById('closeModal');
    const close2 = document.getElementById('closeModal2');
    const proceed = document.getElementById('proceedBtn');

    function open(){ modal.classList.remove('hidden'); modal.classList.add('flex'); }
    function closeModal(){ modal.classList.add('hidden'); modal.classList.remove('flex'); }

    buy?.addEventListener('click', open);
    buySide?.addEventListener('click', open);
    close?.addEventListener('click', closeModal);
    close2?.addEventListener('click', closeModal);

    proceed?.addEventListener('click', function(){
      alert('Checkout belum diimplementasikan — ini hanya demo UI');
    });
  })();
</script>

@endsection