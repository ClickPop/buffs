<?php

namespace App\Http\Controllers;

use App\Leaderboard;
use Illuminate\Http\Request;
use Auth;

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
            $referrals = $leaderboard->referralCounts();
            $theme = $leaderboard->theme;
        } else { $leaderboard = null; }
        return view('dashboard', ['user' => $user, 'leaderboard' => $leaderboard, 'referrals' => $referrals, 'theme' => $theme]);
    }
    public function changeTheme(Request $req, $themeName)
    {
        $user = Auth::user();
        $leaderboard =  Leaderboard::find($user->leaderboards->first()->id);
        $leaderboard->theme = $themeName;
        $leaderboard->save();
        return response()->json(['status' => 'success', ['leaderboard' => $leaderboard->id, 'theme' => $themeName]]);
    }
}
