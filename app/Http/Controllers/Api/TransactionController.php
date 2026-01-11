<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiResponse;

class TransactionController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return $this->error('Unauthorized', null, 401);
        }

        $query = Transaction::forUser($user->id);

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->get('event_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $transactions = $query->latest()->paginate(10);

        $data = \App\Http\Resources\TransactionResource::collection($transactions)->response()->getData(true);
        return $this->success('Daftar transaksi user', $data);
    }

    public function show(Request $request, Transaction $transaction)
    {
        $user = $request->user();
        if (!$user) {
            return $this->error('Unauthorized', null, 401);
        }

        if ($transaction->user_id !== $user->id && $user->role !== 'organizer') {
            return $this->error('Unauthorized', null, 403);
        }

        $transaction->loadMissing(['user','event','ticket']);
        $data = new \App\Http\Resources\TransactionResource($transaction);
        return $this->success('Detail transaksi', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        if (!$user) {
            return $this->error('Unauthorized', null, 401);
        }

        $ticket = Ticket::findOrFail($validated['ticket_id']);

        if (!$ticket->isAvailable($validated['quantity'])) {
            return response()->json(['status' => false, 'message' => 'Tiket tidak tersedia atau kuota tidak mencukupi'], 409);
        }

        $price = $ticket->price;
        $subtotal = $price * $validated['quantity'];
        $adminFee = round($subtotal * 0.05, 2);
        $total = $subtotal + $adminFee;

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'event_id' => $ticket->event_id,
                'ticket_id' => $ticket->id,
                'quantity' => $validated['quantity'],
                'price_per_ticket' => $price,
                'subtotal' => $subtotal,
                'admin_fee' => $adminFee,
                'total_amount' => $total,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Kurangi kuota
            $ticket->decreaseQuota($validated['quantity']);

            DB::commit();

            try {
                \App\Models\ActivityLog::record($user->id, 'transaction:create', $transaction, [], $request);
            } catch (\Exception $e) {
            }

            return $this->success('Transaksi dibuat', $transaction, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Gagal membuat transaksi: ' . $e->getMessage(), null, 500);
        }
    }

    public function update(Request $request, Transaction $transaction)
    {
        $user = $request->user();
        if (!$user) {
            return $this->error('Unauthorized', null, 401);
        }

        if ($user->role === 'organizer' && $request->get('action') === 'verify') {
            $transaction->markAsPaid();
            try {
                \App\Models\ActivityLog::record($user->id, 'transaction:verify', $transaction, [], $request);
            } catch (\Exception $e) {
            }
            return $this->success('Transaksi diverifikasi', $transaction);
        }

        if ($transaction->user_id === $user->id && $request->get('action') === 'cancel') {
            $transaction->cancel();
            try {
                \App\Models\ActivityLog::record($user->id, 'transaction:cancel', $transaction, [], $request);
            } catch (\Exception $e) {
            }
            return $this->success('Transaksi dibatalkan', $transaction);
        }

        return $this->error('Unauthorized atau action tidak dikenal', null, 403);
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        $user = $request->user();
        if (!$user) {
            return $this->error('Unauthorized', null, 401);
        }

        if ($transaction->user_id !== $user->id && $user->role !== 'organizer') {
            return $this->error('Unauthorized', null, 403);
        }

        $transaction->delete();
        try {
            \App\Models\ActivityLog::record($user->id, 'transaction:delete', $transaction, [], $request);
        } catch (\Exception $e) {
        }

        return $this->success('Transaksi dihapus');
    }
}
