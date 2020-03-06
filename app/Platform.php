<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Platform extends Model
{
    protected $fillable = [ 'name', 'socialite_driver', 'channel_url_structure', 'description', 'url' ];
    
    public function oauths(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function streams(): HasMany
    {
        return $this->hasMany(Stream::class);
    }

    public function leaderboards(): HasMany
    {
        return $this->hasMany(Leaderboard::class);
    }
}
