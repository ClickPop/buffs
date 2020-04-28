<?php

namespace App\Http\Controllers;

use Auth;
use App\Leaderboard, App\BetaList, App\SocialAccount, App\User;

use Illuminate\Http\Request;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException as ExceptionRequestException;
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
    } catch (ExceptionRequestException $e) {
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

    $aweber_client = new Client();

    $access_token = $aweber_client->post('https://auth.aweber.com/oauth2/token', [
      'form_params' => [
        'grant_type' => 'refresh_token',
        'refresh_token' => env('AWEBER_REFRESH_TOKEN')
      ],
      'auth' => [
        env('AWEBER_CLIENT_ID'),
        env('AWEBER_CLIENT_SECRET')
      ]
    ]);

    $access_token = json_decode($access_token->getBody())->access_token;

    $hashids = new Hashids(env('API_KEY_SALT'), 32);
    $api_key = $hashids->encode($user->twitch_id);
    $buffsbot_client = new Client([
      'headers' => [
        'Authorization' => $api_key
      ]
    ]);
    $twitch_userId =  $user->twitch_id;
    try {
      $response = $buffsbot_client->get("https://buffsbot.herokuapp.com/api/admin/status/");
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      $chatbots = json_decode($data)->message->data->bots;
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

    $account = env('AWEBER_ACCOUNT');
    $list = env('AWEBER_LIST');

    try {
      $data = $aweber_client->get("https://api.aweber.com/1.0/accounts/$account/lists/$list/subscribers", [
        'headers' => [
          "Authorization" => "Bearer $access_token"
        ]
      ]);

      $entries = json_decode($data->getBody())->entries;
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
    } catch (\Throwable $th) {
      dd($th);
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
    $client = new Client([
      'headers' => [
        'Authorization' => $api_key
      ]
    ]);

    try {
      $response = $client->get("https://buffsbot.herokuapp.com/api/admin/status/");
      $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
      $chatbots = json_decode($data)->message->data->bots;
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

    $aweber_client = new Client();

    $access_token = $aweber_client->post('https://auth.aweber.com/oauth2/token', [
      'form_params' => [
        'grant_type' => 'refresh_token',
        'refresh_token' => env('AWEBER_REFRESH_TOKEN')
      ],
      'auth' => [
        env('AWEBER_CLIENT_ID'),
        env('AWEBER_CLIENT_SECRET')
      ]
    ]);

    $access_token = json_decode($access_token->getBody())->access_token;

    $account = env('AWEBER_ACCOUNT');
    $list = env('AWEBER_LIST');

    try {
      $data = $aweber_client->get("https://api.aweber.com/1.0/accounts/$account/lists/$list/subscribers", [
        'headers' => [
          "Authorization" => "Bearer $access_token"
        ]
      ]);

      $entries = json_decode($data->getBody())->entries;
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
    } catch (\Throwable $th) {
      dd($th);
    }

    // $betalist = BetaList::all();
    return view('betalist.admin', ['betalist' => $beta_list]);
  }
}
