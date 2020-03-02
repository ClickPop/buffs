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
            if ($user->username !== null) {
                $temp_stream = new Stream();
                $temp_stream->channel_name = $user->username;
                $temp_stream->user()->associate($user);
                $temp_stream->platform()->associate($platform_twitch);
                $temp_stream->save();
                $temp_stream = null;
            }
        }
    }
}
