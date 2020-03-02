<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\LeaderboardReferral;
use Faker\Generator as Faker;
use App\Leaderboard;
use App\Stream;
use App\User;

$factory->define(LeaderboardReferral::class, function (Faker $faker) {
    $usernames = [
        'datboi_fourtwenty',
        'Gelatinous3',
        'shorkattack',
        'GV14982',
        'Bruack',
        'clickpop',
        'NIS_Killbot',
        'NIS_Reidbot',
        'lordjack',
        'Rick_Spaniel',
        'Tom_Malufe',
        'dogwash7'
    ];


    $leaderboard = Leaderboard::all()->random();
    $username = $usernames[rand(0, count($usernames) - 1)];

    while ($username === User::find($leaderboard->user_id)->username) {
        $username = $usernames[rand(0, count($usernames) - 1)];
    }

    return [
        'leaderboard_id' => $leaderboard->id,
        'stream_id' => $leaderboard->stream_id,
        'user_id' => $leaderboard->user_id,
        'referrer' => $username,
        'ip_address' => $faker->ipv4,
        'userAgent' => $faker->userAgent
    ];
});
