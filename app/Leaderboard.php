<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leaderboard extends Model
{
    use SoftDeletes;
    
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
