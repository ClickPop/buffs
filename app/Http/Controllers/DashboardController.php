<?php

namespace App\Http\Controllers;

use App\Leaderboard;
use Illuminate\Http\Request;
use Auth;
use Facade\FlareClient\Http\Response;

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
        return view('dashboard', ['user' => $user, 'leaderboard' => $leaderboard, 'referrals' => $referrals]);
    }
    public function updateSettings(Request $req)
    {
        $user = Auth::user();
        $leaderboard =  Leaderboard::find($user->leaderboards->first()->id);
        $theme = $req->all()['theme-selector'];
        $length = $req->all()['leaderboard-length-slider'];
        $leaderboard->theme = $theme;
        $leaderboard->length = $length;
        $leaderboard->save();
        return redirect('/');
    }
}
