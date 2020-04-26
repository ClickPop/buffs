<?php

use App\User;

function adminUserBotStatus($user_bot) {
  $status = '<span class="badge badge-danger">ERROR</span>';
  if ($user_bot instanceof stdClass) {
    if ($user_bot->twitch_id) {
      if ($user_bot->has_bot === true && 
        property_exists($user_bot, 'bot') && 
        $user_bot->bot instanceof stdClass) {
          if ($user_bot->bot->joined === true) {
            $status = '<span class="badge badge-success">Joined</span>';
          } elseif ($user_bot->bot->joined === false) {
            $status = '<span class="badge badge-warning">Parted</span>';
          } else {
            $status = '<span class="badge badge-danger">Uknown Status</span>';
          }
      } else {
        $status = '<span class="badge badge-danger">Not Created</span>';
      }
    } else {
      $status = '<span class="badge badge-danger">Must Login</span>';
    }
  }

  return $status;  
}

function createUserBots($bots) {
  $users = User::all();
  $user_bots = [];
  $exploded_bots = [];
  foreach ($bots as $bot) {
    $exploded_bots[$bot->twitch_userId] = $bot;
  }
  foreach($users as $user) {
    $temp_user_bot = (object)[
      'id' => $user->id,
      'email' => $user->email,
      'twitch_id' => $user->twitch_id,
      'username' => $user->username,
      'has_bot' => false
    ];
    if (array_key_exists($user->twitch_id, $exploded_bots)) {
      $temp_user_bot->has_bot = true;
      $temp_user_bot->bot = $exploded_bots[$user->twitch_id];
    }

    array_push($user_bots, $temp_user_bot);
  }
  return $user_bots;
}