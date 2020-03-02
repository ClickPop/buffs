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
        $platform_twitch = Platform::create([
            'name' => 'twitch',
            'description' => 'Twitch',
            'socialite_driver' => 'twitch',
            'channel_url_structure' => 'https://www.twitch.tv/%%CHANNEL_NAME%%',
            'url' => 'https://twitch.tv'
        ]);
    }
}
