<?php

namespace App\Http\Controllers;

use Auth;
use App\Leaderboard, App\BetaList, App\SocialAccount;

use Illuminate\Http\Request;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException as ExceptionRequestException;

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
        $client = new Client(['/'], [
            'headers' => [
                'Authorization' => $user->refreshToken
            ],
            'request.options' => [
                'exceptions' => false
            ]
        ]);
        $twitch_userId =  $user->twitch_id;
        try {
            $response = $client->get("https://buffsbot.herokuapp.com/status/$twitch_userId");
            $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
            return view('dashboard.index', ['chatbot' => json_decode($data), 'user' => $user, 'leaderboard' => $leaderboard, 'referrals' => $referrals]);
        } catch (ExceptionRequestException $e) {
            return view('dashboard.index', ['user' => $user, 'leaderboard' => $leaderboard, 'referrals' => $referrals]);
        }
    }

    // public function chatbot(Request $req)
    // {
    //     $user = Auth::user();
    //     $client = new Client(['/'], array(
    //         'request.options' => array(
    //            'exceptions' => false,
    //          )
    //       ));
    //     $twitch_userId =  $user->twitch_id;
    //     try {
    //         $response = $client->post('http://ec2-3-90-25-66.compute-1.amazonaws.com:5000/status', ['json' => ['twitch_userId' => $twitch_userId]]);
    //         $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
    //         return view('dashboardChatbot', ['chatbot' => json_decode($data)]);
    //     } catch (ExceptionRequestException $e) {
    //         return view('dashboardChatbot');
    //     }
    // }

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
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return view('dashboard.admin');
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function adminChatbot()
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            $chatbots = null;
            return view('chatbot.admin');
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function adminBetaList()
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            $betalist = BetaList::all();
            return view('betalist.admin');
        } else {
            return redirect()->route('dashboard');
        }
    }
}
