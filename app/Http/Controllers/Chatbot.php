<?php

namespace App\Http\Controllers;

use Auth, DB;
use App\Leaderboard, App\SocialAccount;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException as ExceptionRequestException;
use Illuminate\Support\Facades\Hash;
use Hashids\Hashids;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class Chatbot extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  private function getData()
  {
    $user = Auth::user();
    $hashids = new Hashids(env('API_KEY_SALT'), 32);
    $api_key = $hashids->encode($user->twitch_id);
    $client = new Client([
      'headers' => [
        'Authorization' => $api_key
      ]
    ]);
    return [$user, $client, $user->twitch_id];
  }

  public function quickStart()
  {
    [$user, $client, $twitch_userId] = Chatbot::getData();
    try {
      $response = $client->post('https://buffsbot.herokuapp.com/api/create', ['json' => ['twitch_username' => $user->username, 'twitch_userId' => $twitch_userId]]);
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return redirect()->route('dashboard');
    } catch (ExceptionRequestException $e) {
      return response()->json(json_decode($e->getResponse()->getBody()));
    }
  }

  public function join()
  {
    [$user, $client, $twitch_userId] = Chatbot::getData();

    try {
      $response = $client->put('https://buffsbot.herokuapp.com/api/action', ['json' => ['twitch_username' => $user->username, 'twitch_userId' => $twitch_userId, 'action' => 'join']]);
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return response()->json(json_decode($data));
    } catch (ExceptionRequestException $e) {
      return response()->json(json_decode($e->getResponse()->getBody()));
    }
  }

  public function part()
  {
    [$user, $client, $twitch_userId] = Chatbot::getData();

    try {
      $response = $client->put('https://buffsbot.herokuapp.com/api/action', ['json' => ['twitch_username' => $user->username, 'twitch_userId' => $twitch_userId, 'action' => 'part']]);
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return response()->json(json_decode($data));
    } catch (ExceptionRequestException $e) {
      return response()->json(json_decode($e->getResponse()->getBody()));
    }
  }

  public function updateUsername()
  {
    [$user, $client, $twitch_userId] = Chatbot::getData();

    try {
      $response = $client->put('https://buffsbot.herokuapp.com/api/action', ['json' => ['twitch_username' => $user->username, 'twitch_userId' => $twitch_userId, 'action' => 'updateUsername']]);
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return response()->json(json_decode($data));
    } catch (ExceptionRequestException $e) {
      return response()->json(json_decode($e->getResponse()->getBody()));
    }
  }

  public function delete()
  {
    [$user, $client, $twitch_userId] = Chatbot::getData();

    try {
      $response = $client->delete('https://buffsbot.herokuapp.com/api/delete', ['json' => ['twitch_userId' => $twitch_userId]]);
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return response()->json(json_decode($data));
    } catch (ExceptionRequestException $e) {
      return response()->json(json_decode($e->getResponse()->getBody()));
    }
  }

  public function status()
  {
    [$user, $client, $twitch_userId] = Chatbot::getData();

    try {
      $response = $client->get("https://buffsbot.herokuapp.com/api/status/");
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return response()->json(json_decode($data));
    } catch (ExceptionRequestException $e) {
      return response()->json(json_decode($e->getResponse()->getBody()));
    }
  }

  public function adminStatusAll()
  {
    [$user, $client, $twitch_userId] = Chatbot::getData();

    try {
      $response = $client->get("https://buffsbot.herokuapp.com/api/admin/status");
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return response()->json(json_decode($data));
    } catch (ExceptionRequestException $e) {
      return response()->json(json_decode($e->getResponse()->getBody()));
    }
  }

  public function adminStatus($twitch_id)
  {
    [$user, $client, $twitch_userId] = Chatbot::getData();

    try {
      $response = $client->get("https://buffsbot.herokuapp.com/api/admin/status/$twitch_id");
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return response()->json(json_decode($data));
    } catch (ExceptionRequestException $e) {
      return response()->json(json_decode($e->getResponse()->getBody()));
    }
  }

  public function adminCreate(Request $req)
  {
    [$user, $client, $twitch_userId] = Chatbot::getData();
    $twitch_id = json_decode($req->getContent())->twitch_userId;
    $twitch_username = json_decode($req->getContent())->twitch_username;

    try {
      $response = $client->post('https://buffsbot.herokuapp.com/api/create', ['json' => ['twitch_username' => $twitch_username, 'twitch_userId' => $twitch_id]]);
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return redirect()->route('dashboard');
    } catch (ExceptionRequestException $e) {
      return response()->json(json_decode($e->getResponse()->getBody()));
    }
  }


  public function adminJoin(Request $req)
  {
    [$user, $client, $twitch_userId] = Chatbot::getData();

    $twitch_id = json_decode($req->getContent())->twitch_userId;

    try {
      $response = $client->put('https://buffsbot.herokuapp.com/api/action', ['json' => ['twitch_userId' => $twitch_id, 'action' => 'join']]);
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return response()->json(json_decode($data));
    } catch (ExceptionRequestException $e) {
      return response()->json(json_decode($e->getResponse()->getBody()));
    }
  }

  public function adminPart(Request $req)
  {
    [$user, $client, $twitch_userId] = Chatbot::getData();

    $twitch_id = json_decode($req->getContent())->twitch_userId;

    try {
      $response = $client->put('https://buffsbot.herokuapp.com/api/action', ['json' => ['twitch_userId' => $twitch_id, 'action' => 'part']]);
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return response()->json(json_decode($data));
    } catch (ExceptionRequestException $e) {
      return response()->json(json_decode($e->getResponse()->getBody()));
    }
  }

  public function adminUpdateUsername(Request $req)
  {
    [$user, $client, $twitch_userId] = Chatbot::getData();

    $twitch_id = json_decode($req->getContent())->twitch_userId;
    $newUsername = json_decode($req->getContent())->newUsername;

    try {
      $response = $client->put('https://buffsbot.herokuapp.com/api/action', ['json' => ['twitch_username' => $newUsername, 'twitch_userId' => $twitch_id, 'action' => 'updateUsername']]);
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return response()->json(json_decode($data));
    } catch (ExceptionRequestException $e) {
      return response()->json(json_decode($e->getResponse()->getBody()));
    }
  }

  public function adminDelete(Request $req)
  {
    [$user, $client, $twitch_userId] = Chatbot::getData();

    $twitch_id = json_decode($req->getContent())->twitch_userId;

    try {
      $response = $client->delete('https://buffsbot.herokuapp.com/api/delete', ['json' => ['twitch_userId' => $twitch_id]]);
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return response()->json(json_decode($data));
    } catch (ExceptionRequestException $e) {
      return response()->json(json_decode($e->getResponse()->getBody()));
    }
  }
}
