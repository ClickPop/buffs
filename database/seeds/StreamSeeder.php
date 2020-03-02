<?php

use Illuminate\Database\Seeder;
use App\Stream;
use App\User;
use App\Platform;

class StreamSeeder extends Seeder
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
            $tempLeaderboard = new Stream();
            $tempLeaderboard->channel_name = $user->username;
            $tempLeaderboard->user()->associate($user);
            $tempLeaderboard->platform()->associate($platform_twitch);
            $tempLeaderboard->save();
        }
    }
}
