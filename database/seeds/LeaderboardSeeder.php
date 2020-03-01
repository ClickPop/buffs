<?php

use Illuminate\Database\Seeder;
use App\Leaderboard;
use App\Platform;
use App\User;

class LeaderboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $platform_twitch = Platform::where('name', 'twitch')->first();
        $users = User::all();

        foreach ($users as $user) {
            $tempLeaderboard = new Leaderboard();
            $tempLeaderboard->name = 'My Leaderboard';
            $tempLeaderboard->user()->associate($user);
            $tempLeaderboard->platform()->associate($platform_twitch);
            $tempLeaderboard->save();
        }
    }
}
