<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaderboardReferral extends Model
{
    protected $fillable = [ 'leaderboard_id', 'referrer', 'ip_address', 'user_agent' ];

    public function leaderboard(): BelongsTo
    {
        return $this->belongsTo(Leaderboard::class);
    }

}
