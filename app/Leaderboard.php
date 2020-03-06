<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leaderboard extends Model
{
    use SoftDeletes;
    
    protected $fillable = [ 'stream_id', 'name' ];

    public function stream(): BelongsTo
    {
        return $this->belongsTo(Stream::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(LeaderboardReferral::class);
    }
}
