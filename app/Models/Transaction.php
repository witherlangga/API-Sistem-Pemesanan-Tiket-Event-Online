<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Field yang boleh diisi secara mass-assignment
     */
    protected $fillable = [
        'transaction_code',
        'user_id',
        'event_id',
        'ticket_id',
        'quantity',
        'price_per_ticket',
        'subtotal',
        'admin_fee',
        'total_amount',
        'status',
        'payment_method',
        'payment_proof',
        'paid_at',
        'expired_at',
        'notes',
    ];

    /**
     * Cast field ke tipe data yang sesuai
     */
    protected $casts = [
        'quantity' => 'integer',
        'price_per_ticket' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Boot model
     */
    protected static function boot()
    {
        parent::boot();

        // Generate transaction code otomatis
        static::creating(function ($transaction) {
            if (empty($transaction->transaction_code)) {
                $transaction->transaction_code = static::generateTransactionCode();
            }

            // Set expired_at 24 jam dari sekarang jika belum diset
            if (empty($transaction->expired_at)) {
                $transaction->expired_at = now()->addHours(24);
            }
        });
    }

    /**
     * Generate kode transaksi unik
     */
    public static function generateTransactionCode()
    {
        do {
            $code = 'TRX-' . strtoupper(Str::random(10));
        } while (static::where('transaction_code', $code)->exists());

        return $code;
    }

    /**
     * Relasi ke user (customer)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relasi ke ticket
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Cek apakah transaksi sudah expired
     */
    public function isExpired()
    {
        return $this->status === 'pending' 
            && $this->expired_at 
            && now()->greaterThan($this->expired_at);
    }

    /**
     * Tandai transaksi sebagai dibayar
     */
    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Batalkan transaksi
     */
    public function cancel()
    {
        // Kembalikan kuota tiket
        if ($this->status === 'pending') {
            $this->ticket->increaseQuota($this->quantity);
        }

        $this->update(['status' => 'cancelled']);
    }

    /**
     * Scope untuk transaksi yang pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk transaksi yang sudah dibayar
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope untuk transaksi user tertentu
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
