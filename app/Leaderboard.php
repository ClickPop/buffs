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

    public function referralCounts($preview = false) {
        if (isset($this->reset_timestamp)) {
            $referrals = $this->referrals->where('created_at', '>=', $this->reset_timestamp)->groupBy('referrer')->map->count()->toArray();
        } else {
            $referrals = $this->referrals->groupBy('referrer')->map->count()->toArray();
        }
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

        while (count($referralCounts) > 10) {
            array_pop($referralCounts);
        }
         
        if($preview === true && count($referralCounts) < 10) {
            $wizards = [
                'Gandalf',
                'Merlin',
                'Dumbledore',
                'Hermione Granger',
                'Sabrina',
                'Prospero',
                'Saruman',
                'Voldemort',
                'Elminster',
                'Tim',
                'Harry Dresden',
                'Orwen',
                'Glinda',
                'Morgana Le Fay',
                'Kiki'
            ];
            shuffle($wizards);
            
            $wizard_num = 0;

            while(count($referralCounts) < 10) {
                $tempItem = (object)[
                    "referrer" => $wizards[$wizard_num],
                    "count" => 1
                ];
                array_push($referralCounts, $tempItem);
                $wizard_num++;
            }
        }
        
        return $referralCounts;
    }
}
