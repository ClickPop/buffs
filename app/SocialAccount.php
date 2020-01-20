<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'platform_user_id', 'platform_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function platform() {
        return $this->belongsTo(Platform::class);
    }
}
