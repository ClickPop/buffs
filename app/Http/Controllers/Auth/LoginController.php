<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Session, Auth, Socialite;
use App\SocialAccount, App\User, App\Platform;


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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

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
    public function redirectToProvider(Request $request, $provider) {
        $remember = ($request->query('remember') === 'true') ? true : false;
        Session::put('login-remember', $remember);
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the specified Socialite Provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($platform_driver)
    {
        $platform = Platform::where('socialite_driver', $platform_driver)->first();
        $user = $this->createOrGetUser(Socialite::driver($platform->socialite_driver)->user(), $platform);
        $remember = Session::get('login-remember');

        Auth::login($user, $remember);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Create or get a user based on provider id.
     *
     * @return Object $user
     */
    private function createOrGetUser($platformUser, Platform $platform)
    {
        $account = SocialAccount::where('platform_id', $platform->id)
            ->where('platform_user_id', $platformUser->getId())
            ->first();

        if ($account) {
            //Return account if found
            $user = $account->user;
            if ($user->email == $user->username) {
                $user->username = getUserNameFromSocialAccount($platformUser, $platform);
                $user->save();
            }
            return $user;
        } else {
            //Check if user with same email address exist
            $user = User::where('email', $platformUser->getEmail())->first();

            //Create user if dont'exist
            if (!$user) {
                $username = getUserNameFromSocialAccount($platformUser, $platform);
                $user = User::create([
                    'email' => $platformUser->getEmail(),
                    'name' => $platformUser->getName(),
                    'username' => $username,
                    'password' => Str::random(24)
                ]);
            }

            //Create social account
            $user->oauths()->create([
                'platform_user_id' => $platformUser->getId(),
                'platform_id' => $platform->id
            ]);

            return $user;
        }
    }
}