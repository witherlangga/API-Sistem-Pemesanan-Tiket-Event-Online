<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Tampilkan daftar transaksi user
     */
    public function index()
    {
        $transactions = Transaction::with(['event', 'ticket'])
            ->forUser(Auth::id())
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Tampilkan detail transaksi
     */
    public function show(Transaction $transaction)
    {
        // Pastikan user yang mengakses adalah pemilik transaksi atau organizer event
        if ($transaction->user_id !== Auth::id() && $transaction->event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->load(['event', 'ticket', 'user']);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Tampilkan form upload bukti pembayaran
     */
    public function payment(Transaction $transaction)
    {
        // Pastikan user yang mengakses adalah pemilik transaksi
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Cek status transaksi
        if ($transaction->status !== 'pending') {
            return redirect()
                ->route('transactions.show', $transaction)
                ->with('error', 'Transaksi ini tidak dapat diproses.');
        }

        // Cek apakah transaksi expired
        if ($transaction->isExpired()) {
            $transaction->update(['status' => 'expired']);
            $transaction->ticket->increaseQuota($transaction->quantity);
            
            return redirect()
                ->route('transactions.show', $transaction)
                ->with('error', 'Transaksi telah kadaluarsa.');
        }

        return view('transactions.payment', compact('transaction'));
    }

    /**
     * Upload bukti pembayaran
     */
    public function uploadProof(Request $request, Transaction $transaction)
    {
        // Pastikan user yang mengakses adalah pemilik transaksi
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'payment_method' => 'required|in:bank_transfer,e_wallet,credit_card',
            'payment_proof' => 'required|image|max:2048', // Max 2MB
        ]);

        // Cek status transaksi
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini tidak dapat diproses.');
        }

        try {
            // Upload file
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');

            // Hapus file lama jika ada
            if ($transaction->payment_proof) {
                Storage::disk('public')->delete($transaction->payment_proof);
            }

            // Update transaksi
            $transaction->update([
                'payment_method' => $request->payment_method,
                'payment_proof' => $path,
            ]);

            return redirect()
                ->route('transactions.show', $transaction)
                ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi dari organizer.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengunggah bukti pembayaran.');
        }
    }

    /**
     * Batalkan transaksi
     */
    public function cancel(Transaction $transaction)
    {
        // Pastikan user yang mengakses adalah pemilik transaksi
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini tidak dapat dibatalkan.');
        }

        $transaction->cancel();

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil dibatalkan.');
    }

    /**
     * Verifikasi pembayaran (untuk organizer)
     */
    public function verify(Transaction $transaction)
    {
        // Pastikan user adalah organizer event
        if ($transaction->event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini tidak dapat diverifikasi.');
        }

        if (!$transaction->payment_proof) {
            return back()->with('error', 'Bukti pembayaran belum diunggah.');
        }

        $transaction->markAsPaid();

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Daftar transaksi event (untuk organizer)
     */
    public function eventTransactions($eventId)
    {
        $event = \App\Models\Event::findOrFail($eventId);

        // Pastikan user adalah organizer event
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $transactions = Transaction::with(['user', 'ticket'])
            ->where('event_id', $eventId)
            ->latest()
            ->paginate(15);

        return view('transactions.event-transactions', compact('event', 'transactions'));
    }
}
