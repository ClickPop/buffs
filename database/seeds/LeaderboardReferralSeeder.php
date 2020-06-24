<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\LeaderboardReferral;
use App\Leaderboard;
use App\Stream;
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
        factory(\App\LeaderboardReferral::class, 100)->create();
    }
}
