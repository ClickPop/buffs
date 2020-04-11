<?php

namespace App\Http\Controllers;

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
        if (isset($leaderboard) && $leaderboard->count() > 0) {
            $leaderboard = $leaderboard->first();
            $referrals = $leaderboard->referralCounts();
        } else { $leaderboard = null; }
        return view('dashboard', ['user' => $user, 'leaderboard' => $leaderboard, 'referrals' => $referrals]);
    }
}
