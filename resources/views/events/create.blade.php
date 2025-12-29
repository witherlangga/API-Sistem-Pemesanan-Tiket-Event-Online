@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto bg-white p-8 rounded shadow mt-6">
    <h1 class="text-2xl font-semibold mb-4">Tambah Event</h1>

    <p class="text-sm text-gray-500 mb-4">Event akan disimpan sebagai <b>Draft</b> dan perlu dipublish agar tampil ke publik.</p>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <ul class="list-disc ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/events" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Judul Event</label>
            <input type="text" name="title" placeholder="Judul Event" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Kategori</label>
            <input type="text" name="category" placeholder="Kategori" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Lokasi</label>
            <input type="text" name="location" placeholder="Lokasi" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Tanggal</label>
            <input type="date" name="event_date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Waktu</label>
            <input type="time" name="event_time" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Kapasitas</label>
            <input type="number" name="capacity" placeholder="Kapasitas" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="flex gap-2 pt-4">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Simpan sebagai Draft
            </button>
            <a href="/events" class="border border-gray-300 px-4 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </form>
</div>

@endsection
