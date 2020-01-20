<?php

function getUserNameFromSocialAccount($platfomUser, App\Platform $platform) {
    $username = false;
    if (is_object($platfomUser) && is_object($platform)) {
        switch($platform->socialite_driver) {
            case "twitch":
                $username = $platfomUser->user['display_name'];
                break;
            default:
                $username = $platfomUser->getEmail();
        }
    }

    return $username;
}
