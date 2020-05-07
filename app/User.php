<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Plank\Metable\Metable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
  use Notifiable, Metable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'username', 'name', 'email', 'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function oauths(): HasMany
  {
    return $this->hasMany(SocialAccount::class);
  }

  public function roles(): BelongsToMany
  {
    return $this->belongsToMany(Role::class);
  }

  // public function streams(): HasMany
  // {
  //     return $this->hasMany(Stream::class);
  // }

  public function authorizeRoles($roles)
  {
    if (is_array($roles)) {
      return $this->hasAnyRole($roles) ||
        abort(401, 'This action is unauthorized.');
    }
    return $this->hasRole($roles) ||
      abort(401, 'This action is unauthorized.');
  }

  public function hasAnyRole($roles)
  {
    return null !== $this->roles()->whereIn('name', $roles)->first();
  }

  public function hasRole($role)
  {
    return null !== $this->roles()->where('name', $role)->first();
  }

  public function leaderboards(): HasMany
  {
    return $this->hasMany(Leaderboard::class);
  }

  public function betalist(): HasOne
  {
    return $this->hasOne(BetaList::class, 'user_id');
  }

  public function invited_beta_users(): HasMany
  {
    return $this->hasMany(BetaList::class, 'created_by');
  }

  // Logic based Accessores
  public function getTwitchIDAttribute()
  {
    $rVal = null;
    $twitch = Platform::where('name', 'twitch')->first();
    if ($twitch) {
      $social_account = $this->oauths()->where('platform_id', $twitch->id)->first();
      if ($social_account) {
        $rVal = $social_account->platform_user_id;
      }
    }
    return $rVal;
  }

  public function getRefreshTokenAttribute()
  {
    $rVal = null;
    $twitch = Platform::where('name', 'twitch')->first();
    if ($twitch) {
      $social_account = $this->oauths()->where('platform_id', $twitch->id)->first();
      if ($social_account) {
        $rVal = $social_account->refreshToken;
      }
    }
    return $rVal;
  }

  public function addRole($role)
  {
    $promoted = false;

    if ($this->hasRole($role)) {
      $promoted = true;
    } else {
      $role_obj = Role::where('name', $role)->first();
      if ($role_obj) {
        $this->roles()->attach($role_obj);
        if ($this->hasRole($role)) {
          $promoted = true;
        }
      }
    }

    return $promoted;
  }

  public function removeRole($role)
  {
    $demoted = false;

    if (!$this->hasRole($role)) {
      $demoted = true;
    } else {
      $role_obj = Role::where('name', $role)->first();
      if ($role_obj) {
        $this->roles()->detach($role_obj);
        if (!$this->hasRole($role)) {
          $demoted = true;
        }
      }
    }

    return $demoted;
  }

  public function addLoginAction($action)
  {
    $action = is_string($action) && str_len($action) > 0 ? $action : false;
    $added = false;

    if ($action) {
      $this->login_action = null;
      $this->save();

      if ($this->login_action === null) $added = true;
    }

    return $added;
  }

  public function removeLoginAction()
  {
    $removed = false;

    $this->login_action = null;
    $this->save();

    if ($this->login_action === null) $removed = true;

    return $removed;
  }

  public function processLoginAction() {
    if ($this->login_action) {
      switch($this->login_action) {
        case "promote-admin":
          $this->addRole('admin');
          break;
        case "demote-admin":
          $this->removeRole('admin');
          break;
        case "promote-streamer":
          $this->addRole('streamer');
          break;
        case "demote-streamer":
          $this->removeRole('streamer');
          break;
      }
      $this->removeLoginAction();
    }
  }


}
