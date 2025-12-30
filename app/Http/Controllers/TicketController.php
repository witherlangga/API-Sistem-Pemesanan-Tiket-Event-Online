<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Tampilkan form pemesanan tiket
     */
    public function book(Event $event)
    {
        $tickets = $event->tickets()
            ->where('is_active', true)
            ->get();

        return view('tickets.book', compact('event', 'tickets'));
    }

    /**
     * Proses pemesanan tiket
     */
    public function processBooking(Request $request, Event $event)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);

        // Validasi tiket
        if ($ticket->event_id !== $event->id) {
            return back()->with('error', 'Tiket tidak valid untuk event ini.');
        }

        if (!$ticket->isAvailable($request->quantity)) {
            return back()->with('error', 'Tiket tidak tersedia atau kuota tidak mencukupi.');
        }

        try {
            DB::beginTransaction();

            // Hitung total
            $subtotal = $ticket->price * $request->quantity;
            $adminFee = $subtotal * 0.05; // 5% biaya admin
            $totalAmount = $subtotal + $adminFee;

            // Buat transaksi
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'ticket_id' => $ticket->id,
                'quantity' => $request->quantity,
                'price_per_ticket' => $ticket->price,
                'subtotal' => $subtotal,
                'admin_fee' => $adminFee,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Kurangi kuota tiket
            $ticket->decreaseQuota($request->quantity);

            DB::commit();

            return redirect()
                ->route('transactions.show', $transaction)
                ->with('success', 'Pemesanan tiket berhasil! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pemesanan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan daftar tiket untuk event (untuk organizer)
     */
    public function index(Event $event)
    {
        // Pastikan user adalah organizer event
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $tickets = $event->tickets()->withTrashed()->get();

        return view('tickets.index', compact('event', 'tickets'));
    }

    /**
     * Tampilkan form tambah tiket
     */
    public function create(Event $event)
    {
        // Pastikan user adalah organizer event
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('tickets.create', compact('event'));
    }

    /**
     * Simpan tiket baru
     */
    public function store(Request $request, Event $event)
    {
        // Pastikan user adalah organizer event
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
            'sale_start' => 'nullable|date',
            'sale_end' => 'nullable|date|after:sale_start',
            'is_active' => 'boolean',
        ]);

        $event->tickets()->create($request->all());

        return redirect()
            ->route('tickets.index', $event)
            ->with('success', 'Tiket berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit tiket
     */
    public function edit(Event $event, Ticket $ticket)
    {
        // Pastikan user adalah organizer event
        if ($event->user_id !== Auth::id() || $ticket->event_id !== $event->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('tickets.edit', compact('event', 'ticket'));
    }

    /**
     * Update tiket
     */
    public function update(Request $request, Event $event, Ticket $ticket)
    {
        // Pastikan user adalah organizer event
        if ($event->user_id !== Auth::id() || $ticket->event_id !== $event->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:' . $ticket->sold,
            'sale_start' => 'nullable|date',
            'sale_end' => 'nullable|date|after:sale_start',
            'is_active' => 'boolean',
        ]);

        $ticket->update($request->all());

        return redirect()
            ->route('tickets.index', $event)
            ->with('success', 'Tiket berhasil diperbarui.');
    }

    /**
     * Hapus tiket
     */
    public function destroy(Event $event, Ticket $ticket)
    {
        // Pastikan user adalah organizer event
        if ($event->user_id !== Auth::id() || $ticket->event_id !== $event->id) {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah ada transaksi yang terkait
        if ($ticket->transactions()->where('status', 'paid')->exists()) {
            return back()->with('error', 'Tidak dapat menghapus tiket yang sudah memiliki transaksi.');
        }

        $ticket->delete();

        return redirect()
            ->route('tickets.index', $event)
            ->with('success', 'Tiket berhasil dihapus.');
    }
}
