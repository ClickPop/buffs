<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Platform extends Model
{
    protected $fillable = [ 'name', 'socialite_driver', 'channel_url_structure', 'description', 'url' ];
    protected $appends = [ 'icon_class' ];
    
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

    public function getIconClassAttribute()
    {
        switch ($this->name) {
            case "twitch":
                $rVal = "fab fa-twitch";
                break;
            case "mixer":
                $rVal = "fab fa-mixer";
                break;
            default:
                $rVal = "fa fa-controller";
        }
        return $rVal;
    }
}
