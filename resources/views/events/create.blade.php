@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Tambah Event Baru</h1>
        <p class="text-gray-600">Event akan disimpan sebagai <span class="font-semibold text-blue-600">Draft</span> dan perlu dipublish agar tampil ke publik.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-800 mb-2">Terdapat beberapa kesalahan:</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 py-6 bg-gradient-to-r from-blue-600 to-blue-700">
            <h2 class="text-2xl font-bold text-white">Informasi Event</h2>
            <p class="text-blue-100 text-sm mt-1">Lengkapi detail event yang akan Anda buat</p>
        </div>

        <form action="/events" method="POST" class="p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul Event -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Judul Event <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        placeholder="Masukkan judul event yang menarik" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        value="{{ old('title') }}"
                        required
                    >
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="category" 
                        placeholder="Contoh: Musik, Olahraga, Seminar" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        value="{{ old('category') }}"
                        required
                    >
                </div>

                <!-- Lokasi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Lokasi <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="location" 
                        placeholder="Nama lokasi atau venue" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        value="{{ old('location') }}"
                        required
                    >
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="date" 
                            name="event_date" 
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            value="{{ old('event_date') }}"
                            required
                        >
                    </div>
                </div>

                <!-- Waktu -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Waktu <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="time" 
                            name="event_time" 
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            value="{{ old('event_time') }}"
                            required
                        >
                    </div>
                </div>

                <!-- Kapasitas -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Kapasitas <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="capacity" 
                        placeholder="Jumlah maksimal peserta" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        value="{{ old('capacity') }}"
                        min="1"
                        required
                    >
                    <p class="mt-2 text-sm text-gray-500">Tentukan jumlah maksimal peserta yang dapat menghadiri event</p>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-blue-900 mb-1">Tips Membuat Event</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Gunakan judul yang menarik dan deskriptif</li>
                            <li>• Pastikan tanggal dan waktu sudah benar</li>
                            <li>• Setelah disimpan, Anda dapat menambahkan detail lainnya seperti deskripsi dan poster</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4 mt-8 pt-6 border-t">
                <button type="submit" class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    Simpan sebagai Draft
                </button>
                <a href="/dashboard" class="flex-1 flex items-center justify-center gap-2 border-2 border-gray-300 px-6 py-3 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
