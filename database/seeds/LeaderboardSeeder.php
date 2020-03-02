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
            $streams = $user->streams;
            foreach ($streams as $stream) {
                $temp_leaderboard = Leaderboard::create([ 'name' => 'Referral Leaderboard' ]);
                $temp_leaderboard->stream()->associate($stream);
                $temp_leaderboard->save();
                $temp_leaderboard = null;
            }
        }
    }
}
