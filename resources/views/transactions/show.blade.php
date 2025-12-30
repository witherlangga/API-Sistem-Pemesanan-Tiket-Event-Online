@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="border-b pb-6 mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Transaksi</h1>
                    <p class="text-lg text-gray-600">{{ $transaction->transaction_code }}</p>
                </div>
                <span class="px-4 py-2 text-sm font-semibold rounded-full
                    @if($transaction->status === 'paid') bg-green-100 text-green-800
                    @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($transaction->status === 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ strtoupper($transaction->status) }}
                </span>
            </div>
        </div>

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

        <!-- Event Info -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informasi Event</h2>
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $transaction->event->title }}</h3>
                <div class="space-y-2 text-gray-600">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $transaction->event->event_date->format('d F Y') }} - {{ $transaction->event->event_time->format('H:i') }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        {{ $transaction->event->location }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Info -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Detail Tiket</h2>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold text-gray-900">{{ $transaction->ticket->name }}</span>
                    <span class="text-gray-600">x{{ $transaction->quantity }}</span>
                </div>
                <div class="text-sm text-gray-600">
                    Rp {{ number_format($transaction->price_per_ticket, 0, ',', '.') }} per tiket
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Rincian Pembayaran</h2>
            <div class="space-y-3">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal ({{ $transaction->quantity }} tiket)</span>
                    <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Biaya Admin</span>
                    <span>Rp {{ number_format($transaction->admin_fee, 0, ',', '.') }}</span>
                </div>
                <div class="border-t pt-3 flex justify-between text-lg font-bold text-gray-900">
                    <span>Total Pembayaran</span>
                    <span class="text-blue-600">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        @if($transaction->payment_method)
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Informasi Pembayaran</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="mb-2">
                        <span class="text-sm text-gray-500">Metode Pembayaran:</span>
                        <span class="font-semibold text-gray-900 ml-2">
                            @if($transaction->payment_method === 'bank_transfer') Transfer Bank
                            @elseif($transaction->payment_method === 'e_wallet') E-Wallet
                            @else Kartu Kredit
                            @endif
                        </span>
                    </div>
                    @if($transaction->paid_at)
                        <div>
                            <span class="text-sm text-gray-500">Tanggal Pembayaran:</span>
                            <span class="font-semibold text-gray-900 ml-2">{{ $transaction->paid_at->format('d M Y H:i') }}</span>
                        </div>
                    @endif
                    @if($transaction->payment_proof)
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-2">Bukti Pembayaran:</p>
                            <img src="{{ asset('storage/' . $transaction->payment_proof) }}" alt="Bukti Pembayaran" class="max-w-md rounded-lg border">
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Status Messages -->
        @if($transaction->status === 'pending')
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="font-semibold text-yellow-800 mb-1">Menunggu Pembayaran</p>
                <p class="text-sm text-yellow-700">
                    Silakan selesaikan pembayaran sebelum {{ $transaction->expired_at->format('d M Y H:i') }}
                </p>
            </div>
        @elseif($transaction->status === 'paid')
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="font-semibold text-green-800 mb-1">Pembayaran Berhasil</p>
                <p class="text-sm text-green-700">
                    Terima kasih! Tiket Anda telah dikonfirmasi.
                </p>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex gap-3">
            <a href="{{ route('transactions.index') }}" class="flex-1 text-center border border-gray-300 px-6 py-3 rounded-lg font-semibold text-gray-700 hover:bg-gray-50">
                Kembali
            </a>
            
            @if($transaction->status === 'pending' && !$transaction->isExpired())
                @if(!$transaction->payment_proof)
                    <a href="{{ route('transactions.payment', $transaction) }}" class="flex-1 text-center bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
                        Upload Bukti Pembayaran
                    </a>
                @endif
                
                <form method="POST" action="{{ route('transactions.cancel', $transaction) }}" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700">
                        Batalkan
                    </button>
                </form>
            @endif

            @if($transaction->status === 'paid')
                <a href="#" class="flex-1 text-center bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Download Tiket
                </a>
            @endif
        </div>

        <!-- Organizer Actions -->
        @if(Auth::id() === $transaction->event->user_id && $transaction->status === 'pending' && $transaction->payment_proof)
            <div class="mt-6 pt-6 border-t">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Organizer</h3>
                <form method="POST" action="{{ route('transactions.verify', $transaction) }}" onsubmit="return confirm('Verifikasi pembayaran ini?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
                        Verifikasi Pembayaran
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
