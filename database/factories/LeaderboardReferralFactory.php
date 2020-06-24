<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\LeaderboardReferral;
use Faker\Generator as Faker;
use App\Leaderboard;
// use App\Stream;
use App\User;

$factory->define(LeaderboardReferral::class, function (Faker $faker) {
    $usernames = [
        'datboi_fourtwenty',
        'gelatinous3',
        'shorkattack',
        'gv14982',
        'bruack',
        'clickpop',
        'nis_killbot',
        'nis_reidbot',
        'lordjack',
        'ricky_spaniel',
        'tom_malufe',
        'dogwash7'
    ];


    $leaderboard = Leaderboard::all()->random();
    $username = $usernames[rand(0, count($usernames) - 1)];

    // while ($username === $leaderboard->stream->user->username) {
    while ($username === $leaderboard->user->username) {
        $username = $usernames[rand(0, count($usernames) - 1)];
    }

    return [
        'leaderboard_id' => $leaderboard->id,
        'referrer' => $username,
        'ip_address' => $faker->ipv4,
        'user_agent' => $faker->userAgent
    ];
});
