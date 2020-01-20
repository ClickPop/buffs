<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{

    public function oauths() {
        return $this->hasMany(SocialAccount::class);
    }

    public function streams() {
        return $this->hasMany(Stream::class);
    }
}
