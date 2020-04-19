<?php
use App\Leaderboard;

function checkLeaderboard(App\User $user) {
    if ($user) {
        if ($user->leaderboards->count() === 0) {
            $leaderboard = new Leaderboard;
            $leaderboard->user()->associate($user);
            $leaderboard->save();
        }
    }
}