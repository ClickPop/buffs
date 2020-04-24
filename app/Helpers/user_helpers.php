<?php

use App\Leaderboard;
use GuzzleHttp\Client;

function checkLeaderboard(App\User $user)
{
  if ($user) {
    if ($user->leaderboards->count() === 0) {
      $leaderboard = new Leaderboard;
      $leaderboard->user()->associate($user);
      $leaderboard->save();
    }
  }
}

function checkChatbot(App\User $user)
{
  if ($user) {
    $user = Auth::user();
    $hashids = new Hashids(env('API_KEY_SALT'), 32);
    $api_key = $hashids->encode($user->twitch_id);
    $client = new Client([
      'headers' => [
        'Authorization' => $api_key
      ]
    ]);
    try {
      $chatbot = $client->get("https://buffsbot.herokuapp.com/status/$user->twitch_id");
    } catch (\Throwable $th) {
      $client->post('https://buffsbot.herokuapp.com/create', ['json' => ['twitch_username' => $user->username, 'twitch_userId' => $user->twitch_id]]);
    }
  }
}
