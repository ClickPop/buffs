<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function platform()
    {
        $this->belongsTo(Platform::class);
    }
}
