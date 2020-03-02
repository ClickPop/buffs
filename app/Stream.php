<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    protected $fillable = [ 'user_id', 'platform_id', 'channel_name' ];

    public function platform() {
        return $this->belongsTo(Platform::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
