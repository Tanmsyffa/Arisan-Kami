<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArisanDraw extends Model
{
    protected $fillable = ['arisan_group_id', 'winner_id', 'period', 'draw_date'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(ArisanGroup::class, 'arisan_group_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }
}
