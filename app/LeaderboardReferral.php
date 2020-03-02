<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaderboardReferral extends Model
{
    protected $fillable = [ 'leaderboard_id', 'referrer', 'ip_address', 'user_agent' ];

    public function leaderboard()
    {
        return $this->belongsTo(Leaderboard::class);
    }

}
