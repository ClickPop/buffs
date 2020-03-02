<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    protected $fillable = [ 'stream_id', 'name' ];

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function referrals()
    {
        return $this->hasMany(LeaderboardReferral::class);
    }
}
