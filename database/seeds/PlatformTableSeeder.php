<?php

use Illuminate\Database\Seeder;
use App\Platform;

class PlatformTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $platform_twitch = new Platform();
        $platform_twitch->name = 'twitch';
        $platform_twitch->description = 'Twitch';
        $platform_twitch->socialite_driver = 'twitch';
        $platform_twitch->channel_url_structure = 'https://www.twitch.tv/%%CHANNEL_NAME%%';
        $platform_twitch->url = 'https://twitch.tv';
        $platform_twitch->save();
    }
}
