<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::domain(env('DOMAIN', 'buffs.app'))->middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('index');
    })->name('home');
    Route::get('sorry', function () {
        return view('sorry');
    })->name('sorry');
    Route::get('/api/referrals/{provider}/{channel_name}', 'LeaderboardReferralController@index');
    
});

Route::domain(env('OAUTH_SUBDOMAIN', 'oauth.buffs.app'))->group(function () {
    /** START: OAuth Routes **/
    Route::get('{provider}/login', 'Auth\LoginController@redirectToProvider')
        ->where('provider', 'twitch|wow')->name('oauth.login');
    Route::get('{provider}/callback', 'Auth\LoginController@handleProviderCallback')
        ->where('provider', 'twitch|wow')->name('oauth.callback');
    /** END: OAuth Routes */
});

Route::domain(env('APP_SUBDOMAIN'), 'cauldron.buffs.app')->group(function () {
    Auth::routes([
        'register' => false,
        'reset' => false,
        'verify' => false,
        'confirm' => false
    ]);

    Route::get('/', 'DashboardController@index')->name('app.dashboard');
    Route::prefix('/admin')->group(function() {
        Route::get('/leaderboards', 'LeaderboardController@adminIndex')->name('leaderboards.admin');
        Route::get('/referrals', 'LeaderboardReferralController@adminIndex')->name('leaderboardReferrals.admin');
    });

    Route::prefix('/leaderboards')->group(function() {
        Route::get('/', 'LeaderboardController@index')->name('leaderboards.index');
    });

    Route::prefix('/referrals')->group(function() {
        Route::get('/', 'LeaderboardReferralController@index')->name('leaderboardReferrals.index');
    });
    
    // Route::get('/leaderboard/{provider}/{channel_name}', 'LeaderboardController@index')->name('app.leaderboard');
    // Route::get('/leaderboard/{provider}/{channel_name}/settings', 'LeaderboardController@settings')->name('app.leaderboardSettings');
    // Route::get('/referral/{provider}/{channel_name}/{referrer}', 'LeaderboardReferralController@store')->name('app.referral');
});
