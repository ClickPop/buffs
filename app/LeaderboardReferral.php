<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaderboardReferral extends Model
{
    public function leaderboard()
    {
        return $this->belongsTo(Leaderboard::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }
}
