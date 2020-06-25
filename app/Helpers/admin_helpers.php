<?php

use App\User;

function adminUserBotStatus($bot, $assigned = true)
{
  $assigned = (isset($assigned) && is_bool($assigned)) ? $assigned : true;
  $status = '<span class="badge badge-danger">ERROR</span>';

  if ($bot instanceof stdClass) {
    if ($assigned === true && $bot->twitch_id) {
      if (
        $bot->has_bot === true &&
        property_exists($bot, 'bot') &&
        $bot->bot instanceof stdClass
      ) {
        if ($bot->bot->joined === true) {
          $status = '<span class="badge badge-success">Joined</span>';
        } elseif ($bot->bot->joined === false) {
          $status = '<span class="badge badge-warning">Parted</span>';
        } else {
          $status = '<span class="badge badge-danger">Uknown Status</span>';
        }
      } else {
        $status = '<span class="badge badge-danger">Not Created</span>';
      }
    } elseif ($assigned === false) {
      if (property_exists($bot, 'joined') && $bot->joined === true) {
        $status = '<span class="badge badge-success">Joined</span>';
      } elseif (property_exists($bot, 'joined') && $bot->joined === false) {
        $status = '<span class="badge badge-warning">Parted</span>';
      } else {
        $status = '<span class="badge badge-danger">Uknown Status</span>';
      }
    } else {
      $status = '<span class="badge badge-danger">Must Login</span>';
    }
  }

  return $status;
}

function createUserBots($bots)
{
  $users = User::all();
  $user_bots = (object) [
    'assigned' => [],
    'unassigned' => []
  ];

  // Explode bots array to include numeric twitch user id as index
  $exploded_bots = [];
  foreach ($bots as $bot) {
    $bot->assigned = false;
    $exploded_bots[$bot->twitch_userId] = $bot;
  }

  // Find & push assigned bots to user_bots object
  foreach ($users as $user) {
    $temp_user_bot = (object) [
      'id' => $user->id,
      'email' => $user->email,
      'twitch_id' => $user->twitch_id,
      'username' => $user->username,
      'has_bot' => false
    ];
    if (array_key_exists($user->twitch_id, $exploded_bots)) {
      $temp_user_bot->has_bot = true;
      $exploded_bots[$user->twitch_id]->assigned = true;
      $temp_user_bot->bot = clone $exploded_bots[$user->twitch_id];

      unset($temp_user_bot->bot->assigned);
    }

    array_push($user_bots->assigned, $temp_user_bot);
  }

  // push unassigned bots to user_bots object
  foreach ($exploded_bots as $bot) {
    if ($bot->assigned === false) {
      unset($bot->assigned);
      array_push($user_bots->unassigned, $bot);
    }
  }

  return $user_bots;
}

function getAccessKey($client)
{
  $data = $client->post('https://auth.aweber.com/oauth2/token', [
    'form_params' => [
      'grant_type' => 'refresh_token',
      'refresh_token' => env('AWEBER_REFRESH_TOKEN')
    ],
    'auth' => [
      env('AWEBER_CLIENT_ID'),
      env('AWEBER_CLIENT_SECRET')
    ]
  ]);

  return json_decode($data->getBody())->access_token;
}

function getBetaList($client, $access_token)
{
  $account = env('AWEBER_ACCOUNT');
  $list = env('AWEBER_LIST');

  $data = $client->get("https://api.aweber.com/1.0/accounts/$account/lists/$list/subscribers", [
    'headers' => [
      "Authorization" => "Bearer $access_token"
    ]
  ]);

  return json_decode($data->getBody())->entries;
}

function getBots($client, $api_key)
{
  $response = $client->get("https://buffsbot.herokuapp.com/api/admin/status/", [
    'headers' => [
      'Authorization' => $api_key
    ]
  ]);
  $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
  return json_decode($data)->message->data->bots;
}
