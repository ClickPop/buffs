<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [ 'name', 'description' ];
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
