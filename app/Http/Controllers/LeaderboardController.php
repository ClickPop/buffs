<?php

namespace App\Http\Controllers;

use App\Leaderboard;
use Illuminate\Http\Request;
use Auth;
use App\User;

class LeaderboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "index";
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminIndex()
    {
        $leaderboards = Leaderboard::withTrashed()->get();
        return view('leaderboards.admin')->with('leaderboards', $leaderboards);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Leaderboard::find(auth()->user()->id)) {
            $leaderboard = new Leaderboard;
            $leaderboard->user_id = auth()->user()->id;
            $leaderboard->platform = $request->input('platform');
            $leaderboard->save();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function quickStart(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->leaderboards->count() === 0) {
                $leaderboard = new Leaderboard;
                $leaderboard->user()->associate($user);
                $leaderboard->save();
            }
            return redirect()->route('dashboard');
        } else {
            return abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leaderboard  $leaderboard
     * @return \Illuminate\Http\Response
     */
    public function show(Leaderboard $leaderboard)
    {
        return view('leaderboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leaderboard  $leaderboard
     * @return \Illuminate\Http\Response
     */
    public function embed(Request $request, $channel_name)
    {
        $user = User::where('username', $channel_name)->first();

        if ($user) {
            $leaderboard = $user->leaderboards;
            $referrals = null;
            if (isset($leaderboard) && $leaderboard->count() > 0) {
                $leaderboard = $leaderboard->first();
                $referrals = $leaderboard->referralCounts();
            } else { $leaderboard = null; }

            return view('embeds.leaderboard', ['leaderboard' => $leaderboard, 'referrals' => $referrals]);
        } else {
            return abort(404);
        }
    }

    public function referrals(Request $req, $channel_name)
    {
        $user = User::where('username', $channel_name)->first();

        if ($user) {
            $leaderboard = $user->leaderboards;
            $referrals = null;
            if (isset($leaderboard) && $leaderboard->count() > 0) {
                $leaderboard = $leaderboard->first();
                $referrals = $leaderboard->referralCounts();
            } else { $leaderboard = null; }

            return response()->json(['referrals' => $referrals]);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Leaderboard  $leaderboard
     * @return \Illuminate\Http\Response
     */
    public function edit(Leaderboard $leaderboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Leaderboard  $leaderboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leaderboard $leaderboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leaderboard  $leaderboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leaderboard $leaderboard)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        return view('leaderboardSettings');
    }
}
