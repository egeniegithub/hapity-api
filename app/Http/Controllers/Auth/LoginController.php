<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserProfile;
use App\UserSocial;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Socialite;

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
    protected $redirectTo = '/home';

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        $this->username = $this->findUsername();
    }

     /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    public function findUsername()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {

        switch ($provider) {
            case 'facebook':
                try {
                    $user = Socialite::driver($provider)->user();

                    if (!is_null($user)) {
                        $fb_user = $user->user;

                        $user_name_info = explode(' ', $fb_user['name']);

                        $first_name = '';
                        $last_name = '';

                        if (!empty($user_name_info)) {
                            $first_name = count($user_name_info) > 0 ? $user_name_info[0] : "";
                            $last_name = count($user_name_info) > 1 ? $user_name_info[1] : "";
                        }

                        if (!empty($fb_user)) {
                            $local_user = User::where('email', $fb_user['email'])->with(['social'])->first();

                            if (is_null($local_user)) {
                                $new_user = new User();
                                $new_user->email = $fb_user['email'];
                                $new_user->username = strtolower(str_replace(' ', '_', $fb_user['name'])) . '_' . time();
                                $new_user->password = bcrypt('h@p!ty_soc!@l_signup');
                                $new_user->save();

                                $new_user->roles()->attach(HAPITY_USER_ROLE_ID);

                                $new_user_profile = new UserProfile();
                                $new_user_profile->user_id = $new_user->id;
                                $new_user_profile->first_name = $first_name;
                                $new_user_profile->last_name = $last_name;
                                $new_user_profile->full_name = $fb_user['name'];
                                $new_user_profile->screen_name = $fb_user['name'];
                                $new_user_profile->email = $new_user->email;
                                $new_user_profile->screen_name = $new_user->email;
                                $new_user_profile->auth_key = bcrypt($new_user->username);
                                $new_user_profile->save();

                                $new_user_social = new UserSocial();
                                $new_user_social->user_id = $new_user->id;
                                $new_user_social->social_id = $fb_user['id'];
                                $new_user_social->email = $new_user->email;
                                $new_user_social->platform = "facebook";
                                $new_user_social->save();

                                Auth::login($new_user);

                                return redirect()->route('user_home');

                            } else {
                                $user_existing_social = UserSocial::where('social_id', $fb_user['id'])->where('user_id', $local_user->id)->first();

                                if (is_null($user_existing_social)) {
                                    $new_user_social = new UserSocial();
                                    $new_user_social->user_id = $new_user->id;
                                    $new_user_social->social_id = $fb_user['id'];
                                    $new_user_social->email = $new_user->email;
                                    $new_user_social->platform = "facebook";
                                    $new_user_social->save();
                                }

                                Auth::login($local_user);

                                return redirect()->route('user_home');

                            }

                        }
                    }
                } catch (Exception $e) {
                    Log::debug('facebook login failed: ' . $e->getMessage());
                }
                break;
            case 'twitter':
                break;
        }

        // $user->token;
    }
}
