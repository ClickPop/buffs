<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FirstReferral extends Model
{
  protected $fillable = ['referrer', 'acknowledged', 'leaderboard_id'];

  public function leaderboard(): BelongsTo
  {
    return $this->belongsTo(Leaderboard::class);
  }
}
