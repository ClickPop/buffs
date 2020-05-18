<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leaderboard extends Model
{
  use SoftDeletes;

  protected $fillable = ['user_id', 'name'];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function referrals(): HasMany
  {
    return $this->hasMany(LeaderboardReferral::class);
  }

  public function firstReferrals(): HasMany
  {
    return $this->hasMany(FirstReferral::class);
  }

  public function referralCounts()
  {
    if (isset($this->reset_timestamp)) {
      $referrals = $this->referrals->where('created_at', '>=', $this->reset_timestamp)->groupBy('referrer')->map->count()->toArray();
    } else {
      $referrals = $this->referrals->groupBy('referrer')->map->count()->toArray();
    }
    $referralCounts = [];

    if (count($referrals) > 0) {
      arsort($referrals);
      foreach ($referrals as $referrer => $count) {
        $tempItem = (object) [
          "referrer" => $referrer,
          "count" => $count
        ];
        array_push($referralCounts, $tempItem);
      }
    }

    while (count($referralCounts) > 10) {
      array_pop($referralCounts);
    }

    return $referralCounts;
  }

  public function firstReferralsData()
  {
    if (isset($this->reset_timestamp)) {
      $firstReferrals = $this->firstReferrals()->where('updated_at', '>=', $this->reset_timestamp)->get();
    } else {
      $firstReferrals = $this->firstReferrals();
    }
    $firsts = [];

    if ($firstReferrals) {
      foreach ($firstReferrals as $firstReferral) {
        $tempItem = (object) [
          'id' => $firstReferral->id,
          'referrer' => $firstReferral->referrer,
          'acknowledged' => $firstReferral->acknowledged ? true : false
        ];
        array_push($firsts, $tempItem);
      }
    }

    return $firsts;
  }
}
