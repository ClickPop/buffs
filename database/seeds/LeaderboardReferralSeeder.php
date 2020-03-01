<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\LeaderboardReferral;
use App\Leaderboard;
use App\Platform;
use App\User;

class LeaderboardReferralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $leaderboards = Leaderboard::all();
        $platform = Platform::where('name', 'twitch')->first();

        foreach ($leaderboards as $leaderboard) {
            $user = User::find($leaderboard->user_id);
            for ($i = 0; $i < 20; $i++) {
                $referral = new LeaderboardReferral;
                $referral->leaderboard()->associate($leaderboard);
                $referral->user()->associate($user);
                $referral->platform()->associate($platform);
                $referral->referrer = $faker->userName;
                $referral->ip_address = $faker->ipv4;
                $referral->userAgent = $faker->userAgent;
                $referral->save();
            }
        }
    }
}
