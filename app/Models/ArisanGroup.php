<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArisanGroup extends Model
{
    protected $fillable = [
        'name',
        'amount',
        'owner_id',      // ID pemilik grup
        'schedule',      // Jadwal arisan (contoh: 'setiap Sabtu')
        'location',      // Lokasi pertemuan
        'notes',         // Catatan tambahan
        'status',        // Status grup: active, completed, pending
    ];

    /**
     * Relasi ke pembayaran dalam grup arisan
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relasi ke pemilik grup (user yang membuat grup)
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Relasi ke anggota grup (many-to-many)
     * Try different possible column names based on your actual table structure
     */
    public function members(): BelongsToMany
    {
        // Option 1: If column is named 'group_id'
        return $this->belongsToMany(
            User::class,
            'arisan_group_members',
            'group_id',             // Try this if column is 'group_id'
            'user_id'
        )->withTimestamps();
        
        // Option 2: If you're using Laravel's naming convention
        // return $this->belongsToMany(User::class, 'arisan_group_members')
        //     ->withTimestamps();
    }

    /**
     * Cek apakah grup aktif
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Cek apakah grup sudah selesai
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Cek apakah user adalah anggota grup
     */
    public function hasMember(int $userId): bool
    {
        return $this->members()->where('user_id', $userId)->exists();
    }

    /**
     * Cek apakah user adalah pemilik grup
     */
    public function isOwner(int $userId): bool
    {
        return $this->owner_id === $userId;
    }

    /**
     * Scope untuk grup aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope untuk grup selesai
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Hitung jumlah anggota grup
     */
    public function membersCount(): int
    {
        return $this->members()->count();
    }

    /**
     * Hitung total pembayaran dalam grup
     */
    public function totalPayments(): float
    {
        return $this->payments()->sum('amount');
    }

    /**
     * Fixed: Menggunakan field yang benar dari tabel payments
     */
    public function paymentStatus(): array
    {
        return $this->payments()
            ->select('user_id', 'status')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('user_id')
            ->map(function ($payments) {
                $latest = $payments->first();
                // Fix: menggunakan 'status' bukan 'payment_status'
                return match($latest->status) {
                    'success', 'settlement' => 'paid',
                    'pending' => 'pending',
                    default => 'unpaid'
                };
            })
            ->toArray();
    }
}