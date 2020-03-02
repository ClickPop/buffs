<?php

namespace App\Http\Controllers;

use App\LeaderboardReferral;
use App\Stream;
use App\Platform;
use App\Leaderboard;
use Illuminate\Http\Request;

class LeaderboardReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($provider, $channel_name)
    {
        $stream_id = Stream::where('channel_name', $channel_name)->get()->first()->id;
        $provider_id = Platform::where('name', $provider)->get()->first()->id;
        if ($stream_id && $provider_id) {
            return response()->json(LeaderboardReferral::where('stream_id', $stream_id)->get());
        }
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
    public function store(Request $request, $provider, $channel_name, $referrer)
    {
        $stream_id = Stream::where('channel_name', $channel_name)->get()->first()->id;
        $provider_id = Platform::where('name', $provider)->get()->first()->id;
        if ($stream_id && $provider_id) {
            $referral = new LeaderboardReferral();
            $referral->leaderboard_id = Leaderboard::where('stream_id', $stream_id)->get()->first()->id;
            $referral->stream_id = $stream_id;
            $referral->user_id = Leaderboard::where('stream_id', $stream_id)->get()->first()->user_id;
            $referral->referrer = $referrer;
            $referral->ip_address = $request->ip();
            $referral->userAgent = $request->userAgent();
            $referral->save();
            return redirect('https://twitch.tv/' . $channel_name);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LeaderboardReferral  $leaderboardReferral
     * @return \Illuminate\Http\Response
     */
    public function show(LeaderboardReferral $leaderboardReferral)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LeaderboardReferral  $leaderboardReferral
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaderboardReferral $leaderboardReferral)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LeaderboardReferral  $leaderboardReferral
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaderboardReferral $leaderboardReferral)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LeaderboardReferral  $leaderboardReferral
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaderboardReferral $leaderboardReferral)
    {
        //
    }
}
