@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto bg-white p-8 rounded shadow mt-6">
    <h1 class="text-2xl font-semibold mb-4">Edit Event</h1>

    {{-- Peringatan jika bukan draft --}}
    @if ($event->status !== 'draft')
        <div class="bg-yellow-100 text-yellow-800 p-3 mb-4 rounded">
            Event ini sudah <b>{{ ucfirst($event->status) }}</b> dan tidak dapat diedit.
        </div>
    @endif

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <ul class="list-disc ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/events/{{ $event->id }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Judul --}}
        <div>
            <label class="block text-sm font-medium mb-1">Judul Event</label>
            <input type="text" name="title" value="{{ old('title', $event->title) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" {{ $event->status !== 'draft' ? 'disabled' : '' }}>
        </div>

        {{-- Kategori --}}
        <div>
            <label class="block text-sm font-medium mb-1">Kategori</label>
            <input type="text" name="category" value="{{ old('category', $event->category) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" {{ $event->status !== 'draft' ? 'disabled' : '' }}>
        </div>

        {{-- Lokasi --}}
        <div>
            <label class="block text-sm font-medium mb-1">Lokasi</label>
            <input type="text" name="location" value="{{ old('location', $event->location) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" {{ $event->status !== 'draft' ? 'disabled' : '' }}>
        </div>

        {{-- Tanggal --}}
        <div>
            <label class="block text-sm font-medium mb-1">Tanggal Event</label>
            <input type="date" name="event_date" value="{{ old('event_date', $event->event_date) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" {{ $event->status !== 'draft' ? 'disabled' : '' }}>
        </div>

        {{-- Waktu --}}
        <div>
            <label class="block text-sm font-medium mb-1">Waktu Event</label>
            <input type="time" name="event_time" value="{{ old('event_time', $event->event_time) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" {{ $event->status !== 'draft' ? 'disabled' : '' }}>
        </div>

        {{-- Kapasitas --}}
        <div>
            <label class="block text-sm font-medium mb-1">Kapasitas</label>
            <input type="number" name="capacity" value="{{ old('capacity', $event->capacity) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" {{ $event->status !== 'draft' ? 'disabled' : '' }}>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-2 pt-4">
            @if ($event->status === 'draft')
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Update
                </button>
            @else
                <button disabled
                        class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed">
                    Terkunci
                </button>
            @endif

            <a href="/events" class="border border-gray-300 px-4 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </form>
</div>

@endsection
