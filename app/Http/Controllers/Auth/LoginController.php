<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Session, Auth, Socialite;
use App\SocialAccount, App\User, App\Platform, App\BetaList;
use GuzzleHttp\Client;

class LoginController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

  use AuthenticatesUsers;

  protected function redirectTo()
  {
    return redirect()->route(RouteServiceProvider::HOME);
  }

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }

  /**
   * Redirect the user to the specified Socialite Provider authentication page.
   *
   * @return \Illuminate\Http\Response
   */
  public function redirectToProvider(Request $request, $provider)
  {
    Auth::logout();
    $remember = ($request->query('remember') === 'true') ? true : false;
    Session::put('login-remember', $remember);
    return Socialite::driver($provider)->redirect();
  }

  /**
   * Obtain the user information from the specified Socialite Provider.
   *
   * @return \Illuminate\Http\Response
   */
  public function handleProviderCallback($platform_driver, Request $req)
  {
    $platform = Platform::where('socialite_driver', $platform_driver)->first();
    $code = $req->query->get('code');
    $client_id = env('OAUTH_TWITCH_CLIENT_ID');
    $client_secret = env('OAUTH_TWITCH_CLIENT_SECRET');
    $callback = env('OAUTH_TWITCH_CALLBACK');
    $client = new Client();
    try {
      $response = $client->post("https://id.twitch.tv/oauth2/token?client_id=$client_id&client_secret=$client_secret&code=$code&redirect_uri=$callback&grant_type=authorization_code");
      $access_token = json_decode($response->getBody())->access_token;
    } catch (\Throwable $th) {
      dd($th);
    }
    try {
      $response = $client->get('https://api.twitch.tv/helix/users', [
        'headers' => [
          'Authorization' => "Bearer $access_token",
          'Client-ID' => $client_id
        ]
      ]);
      $twitch_data = json_decode($response->getBody()->getContents())->data[0];
    } catch (\Throwable $th) {
      dd($th);
    }
    $user = $this->createOrGetUser($twitch_data, $platform);
    // $user = $this->createOrGetUser(Socialite::driver($platform->socialite_driver)->user(), $platform);
    $remember = Session::get('login-remember');

    if ($user === false) {
      return redirect()->route('sorry');
    } else {
      Auth::login($user, $remember);
    }


    return redirect()->route(RouteServiceProvider::HOME);
  }

  /**
   * Create or get a user based on provider id.
   *
   * @return Object $user
   */
  private function createOrGetUser($platformUser, Platform $platform)
  {
    $account = SocialAccount::where('platform_id', $platform->id)
      // ->where('platform_user_id', $platformUser->getId())
      ->where('platform_user_id', $platformUser->id)
      ->first();

    // $betaListUser = BetaList::where('email', $platformUser->getEmail())->first();
    $betaListUser = BetaList::where('email', $platformUser->email)->first();

    if ($account) {
      //Return account if found
      $user = $account->user;
      //Check for update username
      $tempUsername = getUserNameFromSocialAccount($platformUser, $platform);
      if ($tempUsername !== $user->username) {
        $user->username = $tempUsername;
        $user->save();
      }
      if ($betaListUser && !$betaListUser->user) {
        $betaListUser->user()->associate($user);
        $betaListUser->save();
      }
      checkLeaderboard($user);
      checkChatbot($user);
      return $user;
    } else {
      //Check if user with same email address exist
      // $user = User::where('email', $platformUser->getEmail())->first();
      $user = User::where('email', $platformUser->email)->first();

      //Create user if dont'exist
      if (!$user) {
        if (env('USER_REGISTRATION_ENABLED', false) === true || $betaListUser) {
          $username = getUserNameFromSocialAccount($platformUser, $platform);
          $user = User::create([
            // 'email' => $platformUser->getEmail(),
            // 'name' => $platformUser->getName(),
            'email' => $platformUser->email,
            'name' => $platformUser->display_name,
            'username' => $username,
            'password' => Str::random(24)
          ]);
          if ($betaListUser) {
            $betaListUser->user()->associate($user);
            $betaListUser->save();
          }
        } else {
          return false;
        }
      } else {
        $tempUsername = getUserNameFromSocialAccount($platformUser, $platform);
        if ($tempUsername !== $user->username) {
          $user->username = $tempUsername;
          $user->save();
        }
        if ($betaListUser && !$betaListUser->user) {
          $betaListUser->user()->associate($user);
          $betaListUser->save();
        }
      }

      //Create social account
      $now = Carbon::now();
      $expiresIn = property_exists($platformUser, 'expiresIn') ? $platformUser->expiresIn : null;
      if (is_numeric($expiresIn)) {
        $expires = $now->copy()->addSeconds($expiresIn - 20);
      } else {
        $expires = null;
      }

      $token = property_exists($platformUser, 'token') ? $platformUser->token : null;
      $tokenSecret = property_exists($platformUser, 'tokenSecret') ? $platformUser->tokenSecret : null;
      $refreshToken = property_exists($platformUser, 'refreshToken') ? $platformUser->refreshToken : null;

      $user->oauths()->create([
        // 'platform_user_id' => $platformUser->getID(),
        'platform_user_id' => $platformUser->id,
        'platform_id' => $platform->id,
        'token' => $token,
        'tokenSecret' => $tokenSecret,
        'refreshToken' => $refreshToken,
        'expires' => $expires,
      ]);
      checkLeaderboard($user);
      checkChatbot($user);
      return $user;
    }
  }
}
