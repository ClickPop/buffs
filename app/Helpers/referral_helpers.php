<?php

function validateReferral(App\Leaderboard $leaderboard, $ip_address, $user_agent) {
    $rVal = true;

    if ($leaderboard->referrals
        ->where('user_agent', $user_agent)
        ->where('ip_address', $ip_address)->all()) { $rVal = false; }
    
    return $rVal;
}

function firstReferral(App\Leaderboard $leaderboard, $referrer) {
  $rVal = false;
  $count = 0;

  if (isset($leaderboard->reset_timestamp)) {
    $count = $leaderboard->referrals->where('created_at', '>=', $leaderboard->reset_timestamp)->where('referrer', $referrer)->count();
  } else {
    $count = $leaderboard->referrals->where('referrer', $referrer)->count();
  }
  $count = (is_numeric($count) && $count === 0) ? true : false;

  return $count;
}