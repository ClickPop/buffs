<?php

use App\Leaderboard;
use GuzzleHttp\Client;

function checkLeaderboard(App\User $user) {
  if ($user) {
    if ($user->leaderboards->count() === 0) {
      $leaderboard = new Leaderboard;
      $leaderboard->user()->associate($user);
      $leaderboard->save();
    }
  }
}

function checkChatbot(App\User $user) {
  if ($user) {
    $twitch_userId = $user->twitch_id;
    $client = new Client(['/'], array(
      'request.options' => array(
        'exceptions' => false,
      )
    ));
    try {
      $chatbot = $client->post('http://ec2-3-90-25-66.compute-1.amazonaws.com:5000/status', ['json' => ['twitch_userId' => $twitch_userId]]);      
    } catch (\Throwable $th) {
      $client->post('http://ec2-3-90-25-66.compute-1.amazonaws.com:5000/', ['json' => ['twitch_username' => $user->username, 'twitch_userId' => $twitch_userId]]);
    }
  }
}