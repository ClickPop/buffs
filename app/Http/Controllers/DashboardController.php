<?php

namespace App\Http\Controllers;

use Auth;
use App\Leaderboard;
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
        } else { $leaderboard = null; }
        $client = new Client(['/'], array(
            'request.options' => array(
               'exceptions' => false,
            )
        ));
        $twitch_userId =  DB::table('social_accounts')->where('id', $user->id)->first()->platform_user_id;
        try {
            $response = $client->post('http://ec2-3-90-25-66.compute-1.amazonaws.com:5000/status', ['json' => ['twitch_userId' => $twitch_userId]]);
            $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
            return view('dashboard', ['chatbot' => json_decode($data), 'user' => $user, 'leaderboard' => $leaderboard, 'referrals' => $referrals]);
        } catch (ExceptionRequestException $e) {
            return view('dashboard', ['user' => $user, 'leaderboard' => $leaderboard, 'referrals' => $referrals]);
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
    //     $twitch_userId =  DB::table('social_accounts')->where('id', $user->id)->first()->platform_user_id;
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
        $leaderboard->theme = $req->all()['theme-selector'];
        $leaderboard->length = $req->all()['leaderboard-length-slider'];
        if (isset($req->all()['leaderboard-reset']) && isset($req->all()['leaderboard-reset-confirm-checkbox'])) {
            $leaderboard->reset_timestamp = Carbon::now()->toDateTimeString();
        }
        $leaderboard->save();
        return redirect()->route('dashboard');
    }
}
