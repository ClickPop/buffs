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
Route::domain(env('DOMAIN', 'buffs.pro'))->middleware('guest')->group(function() {
    Route::get('/', function () {
        return view('index');
    })->name('home');
    Route::get('sorry', function () {
        return view('sorry');
    })->name('sorry');
});

Route::domain(env('OAUTH_SUBDOMAIN', 'oauth.buffs.pro'))->group(function() {
    /** START: OAuth Routes **/
    Route::get('{provider}/login', 'Auth\LoginController@redirectToProvider')
        ->where('provider', 'twitch|wow')->name('oauth.login');
    Route::get('{provider}/callback', 'Auth\LoginController@handleProviderCallback')
        ->where('provider', 'twitch|wow')->name('oauth.callback');
    /** END: OAuth Routes */
});

Route::domain(env('APP_SUBDOMAIN'), 'app.buffs.pro')->group(function() {
    Auth::routes([
        'register' => false,
        'reset' => false,
        'verify' => false,
        'confirm' => false
    ]);

    Route::get('/', 'DashboardController@index')->name('app.dashboard');
    Route::get('/{provider}/leaderboard/{username}', function () {
        return view('leaderboard');
    })->name('app.leaderboard');
    Route::get('{provider}/leaderboard/{username}/settings', function () {
        if (Auth::check()) {
            return view('leaderboardSettings');
        } else {
            return redirect('/');
        }
    })->name('app.leaderboardSettings');
});





