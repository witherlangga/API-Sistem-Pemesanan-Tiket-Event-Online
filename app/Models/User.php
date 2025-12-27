<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Event boot untuk validasi (dari poin 1, tetap dipertahankan)
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            $query = static::where('email', $user->email);
            if ($user->id) {
                $query->where('id', '!=', $user->id);
            }
            if ($query->exists()) {
                throw new \Exception('Email sudah digunakan.');
            }

            if (!in_array($user->role, ['organizer', 'customer'])) {
                throw new \Exception('Role tidak valid. Pilih organizer atau customer.');
            }
        });
    }

    // === BARU: Helper Methods untuk Cek Role ===
    /**
     * Cek apakah user adalah organizer.
     */
    public function isOrganizer(): bool
    {
        return $this->role === 'organizer';
    }

    /**
     * Cek apakah user adalah customer.
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // Common profile fields used by both customer and organizer
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_picture',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'string',
        'profile_picture' => 'string',
    ];

    // Keep profile picture url accessor
    protected $appends = [
        'profile_picture_url',
    ];

    public function getProfilePictureUrlAttribute(): ?string
    {
        if (! $this->profile_picture) {
            return null;
        }

        return asset('storage/' . $this->profile_picture);
    }

    /**
     * Cek apakah user memiliki role tertentu.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // === BARU: Scopes untuk Query Berdasarkan Role ===
    /**
     * Scope untuk mendapatkan hanya organizer.
     */
    public function scopeOrganizers($query)
    {
        return $query->where('role', 'organizer');
    }

    /**
     * Scope untuk mendapatkan hanya customer.
     */
    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    /**
     * Scope untuk mendapatkan user berdasarkan role tertentu.
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // === BARU: Methods Tambahan untuk Pengelolaan Data ===
    /**
     * Assign role ke user (dengan validasi).
     */
    public function assignRole(string $role): bool
    {
        if (!in_array($role, ['organizer', 'customer'])) {
            return false;
        }
        $this->role = $role;
        return $this->save();
    }

    /**
     * Get jumlah user per role (untuk dashboard admin nanti).
     */
    public static function countByRole(): array
    {
        return [
            'organizer' => self::organizers()->count(),
            'customer' => self::customers()->count(),
        ];
    }

    /**
     * Get list user berdasarkan role (dengan pagination untuk performa).
     */
    public static function getUsersByRole(string $role, int $perPage = 10)
    {
        return self::byRole($role)->paginate($perPage);
    }
}