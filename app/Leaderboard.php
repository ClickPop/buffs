<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use stdClass;

class Leaderboard extends Model
{
    use SoftDeletes;
    
    // protected $fillable = [ 'stream_id', 'name' ];
    protected $fillable = [ 'user_id', 'name' ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function stream(): BelongsTo
    // {
    //     return $this->belongsTo(Stream::class);
    // }

    public function referrals(): HasMany
    {
        return $this->hasMany(LeaderboardReferral::class);
    }

    public function referralCounts() {
        $referrals = $this->referrals->groupBy('referrer')->map->count()->toArray();
        $referralCounts = [];

        if (count($referrals) > 0) {
            arsort($referrals);
            foreach ($referrals as $referrer => $count) {
                $tempItem = (object)[
                    "referrer" => $referrer,
                    "count" => $count
                ];
                array_push($referralCounts, $tempItem);
            }
        }
        
        return $referralCounts;
    }
}
