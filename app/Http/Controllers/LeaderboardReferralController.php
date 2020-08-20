<?php

namespace App\Http\Controllers;

use App\LeaderboardReferral;
use App\User;
use App\Stream;
use App\Platform;
use App\Leaderboard;
use Illuminate\Http\Request;
use Auth;

class LeaderboardReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $preview = true;
        $user = Auth::user();
        if ($user) {
            $leaderboard = $user->leaderboards;
            $referrals = null;
            if (isset($leaderboard) && $leaderboard->count() > 0) {
                $leaderboard = $leaderboard->first();
                $referrals = $leaderboard->referralCounts($preview);
            } else {
                $leaderboard = null;
            }

            return response()->json(['leaderboard' => ['theme' => $leaderboard->theme, 'length' => $leaderboard->length], 'referrals' => $referrals]);
        } else {
            return abort(404);
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

    public function referral(Request $request, $channel_name, $referrer)
    {
        if (isset($channel_name) && isset($referrer)) {
            if (
                is_string($channel_name) && strlen($channel_name) > 0
                && is_string($referrer) && strlen($referrer) > 0
            ) {

                $channel_name = strtolower($channel_name);
                $referrer = strtolower($referrer);
                $ip_address = $request->ip();
                $user_agent = $request->userAgent();

                if ($channel_name !== $referrer) {
                    $user = User::where('username', $channel_name)->first();
                    if ($user && $user->leaderboards) {
                        $leaderboard = $user->leaderboards->first();
                        $addIt = (\App::environment() === 'production') ? validateReferral($leaderboard, $ip_address, $user_agent) : true;
                        $addIt = ($addIt && isUserAgentValid($user_agent)) ? true : false;
                        if ($addIt) {
                            $newReferral = LeaderboardReferral::create([
                                'leaderboard_id' => $leaderboard->id,
                                'referrer' => $referrer,
                                'ip_address' => $ip_address,
                                'user_agent' => $user_agent
                            ]);
                        }
                        return redirect('https://twitch.tv/' . $channel_name);
                    }
                } else {
                    abort(403, "No gaming the system!");
                }
            } else {
                return abort(403, "Invalid url parameters.");
            }
        } else {
            return abort(403, "Missing url parameters.");
        }
    }
}
