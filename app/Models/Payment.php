<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'arisan_group_id',
        'period',
        'amount',
        'payment_status',       // pending, success, failed, expired
        'payment_type', // bank_transfer, e-wallet, etc.
        'midtrans_order_id',
        'snap_token',
        'metadata'      // JSON data tambahan
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function arisanGroup(): BelongsTo
    {
        return $this->belongsTo(ArisanGroup::class);
    }

    public function isPaid(): bool
    {
        return in_array($this->payment_status, ['success', 'settlement']);
    }

        public function group(): BelongsTo
    {
        return $this->arisanGroup();
    }
}