<?php

namespace App\Http\Controllers;

use Auth;
use App\Leaderboard, App\BetaList, App\SocialAccount, App\User;

use Illuminate\Http\Request;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;
use Hashids\Hashids;

use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class DashboardController extends Controller
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

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    $user = Auth::user();
    $leaderboard = $user->leaderboards;
    $referrals = null;
    if (isset($leaderboard) && $leaderboard->count() > 0) {
      $leaderboard = $leaderboard->first();
      $referrals = $leaderboard->referralCounts(true);
    } else {
      $leaderboard = null;
    }
    $hashids = new Hashids(env('API_KEY_SALT'), 32);
    $api_key = $hashids->encode($user->twitch_id);
    $client = new Client([
      'headers' => [
        'Authorization' => $api_key
      ]
    ]);
    $twitch_userId =  $user->twitch_id;
    try {
      $response = $client->get("https://buffsbot.herokuapp.com/api/status/");
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      return view('dashboard.index', ['chatbot' => json_decode($data), 'user' => $user, 'leaderboard' => $leaderboard, 'referrals' => $referrals]);
    } catch (RequestException $e) {
      return view('dashboard.index', ['user' => $user, 'leaderboard' => $leaderboard, 'referrals' => $referrals]);
    }
  }

  public function updateSettings(Request $req)
  {
    $user = Auth::user();
    $leaderboard =  Leaderboard::find($user->leaderboards->first()->id);
    $data = json_decode($req->getContent(), true);
    $leaderboard->theme = $data['theme-selector'];
    $leaderboard->length = $data['leaderboard-length-slider'];
    $leaderboard->save();
    return response()->json($data);
  }

  public function resetLeaderboard(Request $req)
  {
    $user = Auth::user();
    $leaderboard =  Leaderboard::find($user->leaderboards->first()->id);
    $leaderboard->reset_timestamp = Carbon::now()->toDateTimeString();
    $leaderboard->save();
    return response()->json(['status' => 'success', 'data' => ['reset-date' => $leaderboard->reset_timestamp]]);
  }

  public function adminIndex()
  {
    $user = Auth::user();
    if (!Auth::check() && !$user->hasRole('admin')) {
      return redirect()->route('dashboard');
    }

    $client = new Client();

    $access_token = getAccessKey($client);

    $hashids = new Hashids(env('API_KEY_SALT'), 32);
    $api_key = $hashids->encode($user->twitch_id);
    $twitch_userId =  $user->twitch_id;
    try {
      $chatbots = getBots($client, $api_key);
      $totalChatbots = count($chatbots);
      $joinedChatbots = [];
      $partedChatbots = [];
      foreach ($chatbots as $bot) {
        if ($bot->joined) {
          array_push($joinedChatbots, $bot);
        } else {
          array_push($partedChatbots, $bot);
        }
      }
    } catch (\Throwable $th) {
      $chatbots = null;
    }

    $entries = getBetaList($client, $access_token);

    $beta_list = [];
    $approved = [];
    $pending = [];
    $denied = [];
    foreach ($entries as $entry) {
      if (in_array('beta-interest', $entry->tags)) {
        $user = BetaList::where('email', $entry->email)->get()->first();

        if (!$user) {
          $user = BetaList::create([
            'email' => $entry->email
          ]);
        }

        switch ($user->current_status) {
          case 'approved':
            array_push($approved, $user);
            break;
          case 'pending':
            array_push($pending, $user);
            break;
          case 'denied':
            array_push($denied, $user);
            break;
        }

        if ($entry->unsubscribed_at) {
          $user->current_status = 'denied';
        }

        array_push($beta_list, $user);
      }
    }

    return view('dashboard.admin', [
      'API_KEY' => $api_key,
      'total' => $totalChatbots,
      'joined' => count($joinedChatbots),
      'parted' => count($partedChatbots),
      'betalist' => count($beta_list),
      'approved' => count($approved),
      'pending' => count($pending),
      'denied' => count($denied)
    ]);
  }

  public function adminChatbot()
  {
    $user = Auth::user();
    if (!Auth::check() && $user->hasRole('admin')) {
      return redirect()->route('dashboard');
    }
    $hashids = new Hashids(env('API_KEY_SALT'), 32);
    $api_key = $hashids->encode($user->twitch_id);
    $client = new Client();

    try {
      $chatbots = getBots($client, $api_key);
    } catch (\Throwable $th) {
      $chatbots = null;
    }

    $user_bots = createUserBots($chatbots);

    return view('chatbot.admin', ['user_bots' => $user_bots]);
  }

  public function adminBetaList()
  {
    if (!Auth::check() && !Auth::user()->hasRole('admin')) {
      return redirect()->route('dashboard');
    }

    $client = new Client();

    $access_token = getAccessKey($client);

    $account = env('AWEBER_ACCOUNT');
    $list = env('AWEBER_LIST');

    try {
      $entries = getBetaList($client, $access_token);
      $beta_list = [];
      $approved = [];
      $pending = [];
      $denied = [];
      foreach ($entries as $entry) {
        if (in_array('beta-interest', $entry->tags)) {
          $user = BetaList::where('email', $entry->email)->get()->first();

          if (!$user) {
            $user = BetaList::create([
              'email' => $entry->email
            ]);
          }

          $userData = json_decode(json_encode(['id' => $entry->id, 'email' => $user->email, 'username' => $entry->custom_fields->twitch, 'current_status' => $user->current_status]));

          switch ($user->current_status) {
            case 'approved':
              array_push($approved, $userData);
              break;
            case 'pending':
              array_push($pending, $userData);
              break;
            case 'denied':
              array_push($denied, $userData);
              break;
          }

          if ($entry->unsubscribed_at) {
            $user->current_status = 'denied';
            $user->save();
          }

          array_push($beta_list, $userData);
        }
      }
    } catch (\Throwable $th) {
      dd($th);
    }


    return view('betalist.admin', ['betalist' => $beta_list]);
  }

  public function addOrUpdateSubscriber(Request $req)
  {
    if (!Auth::check() && !Auth::user()->hasRole('admin')) {
      return redirect()->route('dashboard');
    }

    $action = json_decode($req->getContent())->action;
    $email = json_decode($req->getContent())->email;
    $username = json_decode($req->getContent())->username;

    $client = new Client();

    $access_token = getAccessKey($client);

    $account = env('AWEBER_ACCOUNT');
    $list = env('AWEBER_LIST');

    if ($action === 'deny') {
      $user = BetaList::where('email', $email)->get()->first();
      if ($user && $user->current_status === 'approved') {
        $id = json_decode($req->getContent())->id;
        $response = $client->patch("https://api.aweber.com/1.0/accounts/$account/lists/$list/subscribers/$id", [
          'headers' => [
            'Authorization' => "Bearer $access_token"
          ],
          'json' => [
            'email' => $email,
            'custom_fields' => [
              'twitch' => $username
            ],
            'tags' => [
              'remove' => [
                'beta_enrolled'
              ]
            ],
          ]
        ]);
      }

      if (!$user) {
        BetaList::create([
          'email' => $email
        ]);
      }

      $user->current_status = 'denied';
      $user->save();

      return response()->json(['User Denied']);
    }

    $tags = json_decode($req->getContent())->tags;

    try {
      $response = $client->post("https://api.aweber.com/1.0/accounts/$account/lists/$list/subscribers", [
        'headers' => [
          'Authorization' => "Bearer $access_token"
        ],
        'json' => [
          'email' => $email,
          'custom_fields' => [
            'twitch' => $username
          ],
          'tags' => $tags,
          'update_existing' => 'true'
        ]
      ]);

      if ($response->getStatusCode() === 201 && in_array('beta_enrolled', $tags)) {
        $user = BetaList::where('email', $email)->get()->first();
        if (!$user) {
          BetaList::create([
            'email' => $email
          ]);
        }
        $user->current_status = 'approved';
        $user->save();
      }

      return response()->json(['User Approved']);
    } catch (\Throwable $th) {
      dd($th);
    }
  }
}
