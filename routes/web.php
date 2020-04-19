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
});

Route::domain(env('DOMAIN', 'buffs.app'))->group(function () {
    Route::get('/embed/leaderboard/{channel_name}', 'LeaderboardController@embed')->name('leaderboard.embed');
    Route::get('/r/{channel_name}/{referrer}', 'LeaderboardReferralController@referral');
    Route::get('/referrals/{channel_name}', 'LeaderboardController@referrals')->name('referrals');
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

    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/chatbot', 'DashboardController@chatbot')->name('dashboard-chatbot');
    Route::post('/', 'DashboardController@updateSettings')->name('change-theme');
    // Route::prefix('/admin')->group(function() {
    //     Route::get('/leaderboards', 'LeaderboardController@adminIndex')->name('leaderboards.admin');
    //     Route::get('/referrals', 'LeaderboardReferralController@adminIndex')->name('leaderboardReferrals.admin');
    // });

    Route::prefix('/admin')->group(function() {
        Route::get('/', 'DashboardController@adminIndex')->name('admin.dashboard');
        Route::get('/chatbot', 'DashboardController@adminChatbot')->name('admin.chatbots');
        Route::get('/betalist', 'DashboardController@adminBetaList')->name('admin.betalist');
        // Route::get('/leaderboards', 'LeaderboardController@adminIndex')->name('admin.leaderboards');
    });
    Route::prefix('/leaderboards')->group(function() {
        Route::get('/', 'LeaderboardController@index')->name('leaderboards.index');
        Route::get('/quick-start', 'LeaderboardController@quickStart')->name('leaderboards.quickStart');
        Route::get('/reset', 'DashboardController@resetLeaderboard')->name('leaderboards.reset');
    });
    Route::prefix('/chatbot')->group(function() {
        Route::get('/', 'DashboardController@chatbot')->name('dashboard-chatbot');
        Route::get('/quick-start', 'Chatbot@quickStart')->name('chatbot.quickStart');
        Route::get('/join', 'Chatbot@join')->name('chatbot.join');
        Route::get('/part', 'Chatbot@part')->name('chatbot.part');
        Route::get('/updateUsername', 'Chatbot@updateUsername')->name('chatbot.updateUsername');
        Route::get('/delete', 'Chatbot@delete')->name('chatbot.delete');
        Route::get('/status', 'Chatbot@status')->name('chatbot.status');
    });
    Route::prefix('/referrals')->group(function() {
        Route::get('/', 'LeaderboardReferralController@index')->name('leaderboardReferrals.index');
    });
});
