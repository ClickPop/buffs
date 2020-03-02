<?php

namespace App\Http\Controllers;

use App\Leaderboard;
use Illuminate\Http\Request;

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
    public function index($platform, $channel_name)
    {
        //
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
