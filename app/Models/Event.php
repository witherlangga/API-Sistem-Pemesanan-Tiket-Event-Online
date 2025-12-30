<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Ticket;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Field yang boleh diisi secara mass-assignment
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'category',
        'location',
        'address',
        'poster',
        'event_date',
        'event_time',
        'capacity',
        'status',
        'published_at',
    ];

    /**
     * Cast field ke tipe data yang sesuai
     */
    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime:H:i',
        'published_at' => 'datetime',
    ];

    /**
     * Relasi ke user sebagai penyelenggara event
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tiket
     * (digunakan oleh modul manajemen tiket)
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Relasi ke transaksi
     */
    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }

    /**
     * Scope untuk event yang sudah dipublish
     * Digunakan untuk tampilan publik
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Label status event
     * Berguna untuk API response dan tampilan web
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'published' => 'Dipublikasikan',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak diketahui',
        };
    }

    /**
     * Mengecek apakah event sudah dipublish
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
