<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{

    public function platform() {
        return $this->belongsTo(Platform::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
