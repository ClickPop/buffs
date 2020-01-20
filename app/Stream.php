<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{

    public function platform() {
        return $this->belongsTo(Platform::class);
    }
}
