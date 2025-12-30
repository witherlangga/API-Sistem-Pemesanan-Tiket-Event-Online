@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Transaksi Saya</h1>
        <p class="text-gray-600 mt-2">Kelola dan pantau status pemesanan tiket Anda</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if($transactions->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada transaksi</h3>
            <p class="mt-2 text-gray-500">Mulai pesan tiket untuk event favorit Anda!</p>
            <a href="/events" class="mt-6 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                Jelajahi Event
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($transactions as $transaction)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $transaction->event->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $transaction->transaction_code }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($transaction->status === 'paid') bg-green-100 text-green-800
                                @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($transaction->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ strtoupper($transaction->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Jenis Tiket</p>
                                <p class="font-semibold text-gray-900">{{ $transaction->ticket->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Jumlah</p>
                                <p class="font-semibold text-gray-900">{{ $transaction->quantity }} tiket</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Pembayaran</p>
                                <p class="font-semibold text-blue-600 text-lg">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Pemesanan</p>
                                <p class="font-semibold text-gray-900">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        @if($transaction->status === 'pending')
                            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800">
                                    <strong>Batas Pembayaran:</strong> {{ $transaction->expired_at->format('d M Y H:i') }}
                                    ({{ $transaction->expired_at->diffForHumans() }})
                                </p>
                            </div>
                        @endif

                        <div class="flex gap-2">
                            <a href="{{ route('transactions.show', $transaction) }}" 
                               class="flex-1 text-center bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                Lihat Detail
                            </a>
                            @if($transaction->status === 'pending' && !$transaction->isExpired())
                                <a href="{{ route('transactions.payment', $transaction) }}" 
                                   class="flex-1 text-center bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                                    Bayar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
@endsection
