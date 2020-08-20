<?php

use Jaybizzle\CrawlerDetect\CrawlerDetect;

function validateReferral(App\Leaderboard $leaderboard, $ip_address, $user_agent) {
    $rVal = true;

    if ($leaderboard->referrals
        ->where('user_agent', $user_agent)
        ->where('ip_address', $ip_address)->all()) { $rVal = false; }
    
    return $rVal;
}

function isUserAgentValid($user_agent) {
    $rVal = true;

    $CrawlerDetect = new CrawlerDetect;
    $rVal = (!$CrawlerDetect->isCrawler($user_agent)) ? true : false;

    return $rVal;
}

