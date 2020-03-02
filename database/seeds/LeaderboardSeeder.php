<?php

use Illuminate\Database\Seeder;
use App\Leaderboard;
use App\Stream;
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
        $users = User::all();

        foreach ($users as $user) {
            $stream = Stream::where('user_id', $user->id)->first();
            $tempLeaderboard = new Leaderboard();
            $tempLeaderboard->name = 'My Leaderboard';
            $tempLeaderboard->user()->associate($user);
            $tempLeaderboard->stream()->associate($stream);
            $tempLeaderboard->save();
        }
    }
}
