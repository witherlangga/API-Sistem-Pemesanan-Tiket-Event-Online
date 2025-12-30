@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Upload Bukti Pembayaran</h1>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Transaction Summary -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h2 class="font-semibold text-gray-900 mb-2">Ringkasan Transaksi</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Kode Transaksi:</span>
                    <span class="font-semibold">{{ $transaction->transaction_code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Event:</span>
                    <span class="font-semibold">{{ $transaction->event->title }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tiket:</span>
                    <span class="font-semibold">{{ $transaction->ticket->name }} x{{ $transaction->quantity }}</span>
                </div>
                <div class="flex justify-between text-lg pt-2 border-t">
                    <span class="text-gray-900 font-bold">Total:</span>
                    <span class="text-blue-600 font-bold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="font-semibold text-blue-900 mb-3">Instruksi Pembayaran</h3>
            <div class="space-y-4 text-sm text-blue-800">
                <div>
                    <p class="font-semibold mb-1">Transfer Bank:</p>
                    <p>Bank BCA: 1234567890</p>
                    <p>a.n. PT Event Organizer</p>
                </div>
                <div>
                    <p class="font-semibold mb-1">E-Wallet:</p>
                    <p>GoPay/OVO: 081234567890</p>
                </div>
                <div class="pt-2 border-t border-blue-200">
                    <p class="font-semibold">Catatan:</p>
                    <ul class="list-disc list-inside space-y-1 mt-1">
                        <li>Transfer sesuai nominal yang tertera</li>
                        <li>Upload bukti pembayaran setelah transfer</li>
                        <li>Pembayaran akan diverifikasi dalam 1x24 jam</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <form method="POST" action="{{ route('transactions.upload-proof', $transaction) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                    Metode Pembayaran <span class="text-red-500">*</span>
                </label>
                <select 
                    name="payment_method" 
                    id="payment_method" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                >
                    <option value="">Pilih metode pembayaran</option>
                    <option value="bank_transfer">Transfer Bank</option>
                    <option value="e_wallet">E-Wallet (GoPay/OVO/Dana)</option>
                    <option value="credit_card">Kartu Kredit</option>
                </select>
                @error('payment_method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                    Bukti Pembayaran <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="payment_proof" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Upload file</span>
                                <input 
                                    id="payment_proof" 
                                    name="payment_proof" 
                                    type="file" 
                                    class="sr-only"
                                    accept="image/*"
                                    required
                                    onchange="previewImage(this)"
                                >
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                    </div>
                </div>
                <div id="preview-container" class="mt-4 hidden">
                    <img id="preview-image" src="" alt="Preview" class="max-w-full h-auto rounded-lg border">
                </div>
                @error('payment_proof')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>Batas Waktu:</strong> {{ $transaction->expired_at->format('d M Y H:i') }}
                    ({{ $transaction->expired_at->diffForHumans() }})
                </p>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('transactions.show', $transaction) }}" class="flex-1 text-center border border-gray-300 px-6 py-3 rounded-lg font-semibold text-gray-700 hover:bg-gray-50">
                    Kembali
                </a>
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Upload Bukti
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('preview-image');
    const container = document.getElementById('preview-container');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
