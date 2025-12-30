<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Field yang boleh diisi secara mass-assignment
     */
    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quota',
        'sold',
        'sale_start',
        'sale_end',
        'is_active',
    ];

    /**
     * Cast field ke tipe data yang sesuai
     */
    protected $casts = [
        'price' => 'decimal:2',
        'quota' => 'integer',
        'sold' => 'integer',
        'sale_start' => 'datetime',
        'sale_end' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relasi ke transaksi
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Accessor untuk mendapatkan jumlah tiket yang tersedia
     */
    public function getAvailableAttribute()
    {
        return $this->quota - $this->sold;
    }

    /**
     * Cek apakah tiket masih tersedia
     */
    public function isAvailable($quantity = 1)
    {
        return $this->is_active 
            && $this->available >= $quantity
            && $this->isSalePeriodActive();
    }

    /**
     * Cek apakah periode penjualan aktif
     */
    public function isSalePeriodActive()
    {
        $now = now();
        
        if ($this->sale_start && $now->lessThan($this->sale_start)) {
            return false;
        }
        
        if ($this->sale_end && $now->greaterThan($this->sale_end)) {
            return false;
        }
        
        return true;
    }

    /**
     * Kurangi kuota tiket
     */
    public function decreaseQuota($quantity)
    {
        $this->increment('sold', $quantity);
    }

    /**
     * Tambah kuota tiket (untuk pembatalan)
     */
    public function increaseQuota($quantity)
    {
        $this->decrement('sold', $quantity);
    }
}
