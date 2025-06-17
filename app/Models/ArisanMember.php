<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArisanMember extends Model
{
    protected $fillable = ['user_id', 'arisan_group_id', 'join_date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(ArisanGroup::class, 'arisan_group_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
