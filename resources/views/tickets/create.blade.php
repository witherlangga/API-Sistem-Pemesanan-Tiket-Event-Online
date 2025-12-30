@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Tambah Tiket Baru</h1>

        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong>Event:</strong> {{ $event->title }}
            </p>
        </div>

        <form method="POST" action="{{ route('tickets.store', $event) }}">
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Tiket <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    placeholder="Contoh: VIP, Regular, VVIP"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                    placeholder="Deskripsi tiket (opsional)"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                        Harga <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                        <input 
                            type="number" 
                            name="price" 
                            id="price" 
                            value="{{ old('price') }}"
                            min="0"
                            step="1000"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror"
                            placeholder="0"
                            required
                        >
                    </div>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quota" class="block text-sm font-medium text-gray-700 mb-2">
                        Kuota <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="quota" 
                        id="quota" 
                        value="{{ old('quota') }}"
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quota') border-red-500 @enderror"
                        placeholder="Jumlah tiket"
                        required
                    >
                    @error('quota')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="sale_start" class="block text-sm font-medium text-gray-700 mb-2">
                        Mulai Penjualan
                    </label>
                    <input 
                        type="datetime-local" 
                        name="sale_start" 
                        id="sale_start" 
                        value="{{ old('sale_start') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sale_start') border-red-500 @enderror"
                    >
                    @error('sale_start')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sale_end" class="block text-sm font-medium text-gray-700 mb-2">
                        Berakhir Penjualan
                    </label>
                    <input 
                        type="datetime-local" 
                        name="sale_end" 
                        id="sale_end" 
                        value="{{ old('sale_end') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sale_end') border-red-500 @enderror"
                    >
                    @error('sale_end')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="ml-2 text-sm text-gray-700">Aktifkan tiket (tiket dapat dibeli)</span>
                </label>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('tickets.index', $event) }}" class="flex-1 text-center border border-gray-300 px-6 py-3 rounded-lg font-semibold text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Simpan Tiket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
