<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stream extends Model
{
    protected $fillable = [ 'user_id', 'platform_id', 'channel_name' ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
